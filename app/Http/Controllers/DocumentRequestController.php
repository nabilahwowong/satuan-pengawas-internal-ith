<?php

namespace App\Http\Controllers;

use App\Models\AuditAssignment;
use App\Models\DocumentRequest;
use App\Models\DocumentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentRequestController extends Controller
{
    // Halaman daftar dokumen per penugasan audit
    public function index(AuditAssignment $assignment)
    {
        $assignment->load(['workUnit', 'auditors']);
        $requests = DocumentRequest::with(['submissions.submitter'])
            ->where('assignment_id', $assignment->id)
            ->latest()
            ->get();

        return view('documents.index', compact('assignment', 'requests'));
    }

    // Auditor membuat butir permintaan dokumen baru
    public function storeRequest(Request $request, AuditAssignment $assignment)
    {
        $validated = $request->validate([
            'request_title' => ['nullable', 'string', 'max:255'],
            'document_name' => ['nullable', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'deadline'      => ['required', 'date'],
        ]);

        $docName = $validated['document_name'] ?? $validated['request_title'] ?? 'Dokumen Pemeriksaan';

        $docRequest = $assignment->documentRequests()->create([
            'document_name' => $docName,
            'description'   => $validated['description'] ?? null,
            'deadline'      => $validated['deadline'],
            'status'        => 'requested',
        ]);

        \App\Models\ActivityLog::record(
            'CREATE',
            'Dokumen Pemeriksaan',
            "Meminta dokumen '{$docName}' pada penugasan {$assignment->assignment_number}."
        );

        $auditees = \App\Models\User::where('role', 'auditee')
            ->where('work_unit_id', $assignment->work_unit_id)
            ->where('is_active', true)
            ->get();

        \Illuminate\Support\Facades\Notification::send($auditees, new \App\Notifications\SpiActivityNotification(
            'Permintaan Dokumen Baru',
            "Tim Auditor meminta dokumen '{$docName}' (Batas waktu: {$validated['deadline']}).",
            route('documents.index', $assignment->id),
            'info'
        ));

        return back()->with('success', 'Permintaan dokumen pemeriksaan berhasil ditambahkan.');
    }

    // Auditee mengunggah berkas tanggapan
    // Auditee mengunggah berkas tanggapan
    public function uploadSubmission(Request $request, DocumentRequest $documentRequest)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,docx,xlsx,xls,zip,rar', 'max:20480'], // maks 20MB
            'notes' => ['nullable', 'string'],
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $storedPath = $file->store('audit_documents/' . $documentRequest->assignment_id, 'public');

        DocumentSubmission::create([
            'document_request_id' => $documentRequest->id,
            'uploaded_by'         => Auth::id(),
            'file_name'           => $originalName,
            'file_path'           => $storedPath,
            'notes'               => $request->input('notes'),
            'verification_status' => 'pending',
        ]);

        $documentRequest->update(['status' => 'fulfilled']);

        // ==========================================
        // KIRIM NOTIFIKASI KE TIM AUDITOR
        // ==========================================
        $assignment = $documentRequest->assignment;
        $auditors = $assignment->auditors()->where('is_active', true)->get();

        if ($auditors->isNotEmpty()) {
            $auditeeUser = Auth::user();
            $workUnitName = $auditeeUser->workUnit->name ?? 'Unit Kerja';
            $docName = $documentRequest->document_name;

            \Illuminate\Support\Facades\Notification::send(
                $auditors,
                new \App\Notifications\SpiActivityNotification(
                    'Dokumen Ditanggapi Auditee',
                    "Unit kerja {$workUnitName} telah mengunggah berkas untuk permintaan '{$docName}'.",
                    route('documents.index', $assignment->id),
                    'success'
                )
            );
        }

        return redirect()->route('documents.index', $documentRequest->assignment_id)
            ->with('success', 'Berkas dokumen berhasil diunggah dan notifikasi terkirim ke Auditor.');
    }

    // Auditor memverifikasi berkas (Terima / Minta Perbaikan)
    public function verifySubmission(Request $request, DocumentSubmission $submission)
    {
        $validated = $request->validate([
            'verification_status' => ['required', 'in:accepted,rejected'],
            'notes' => ['nullable', 'string'],
        ]);

        $submission->update([
            'verification_status' => $validated['verification_status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $parentRequest = $submission->request;
        if ($validated['verification_status'] === 'rejected') {
            $parentRequest->update(['status' => 'revision_needed']);
        } elseif ($validated['verification_status'] === 'accepted') {
            $parentRequest->update(['status' => 'fulfilled']);
        }

        return back()->with('success', 'Status verifikasi berkas berhasil diperbarui.');
    }
}
