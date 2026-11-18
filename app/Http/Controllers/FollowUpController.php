<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\AuditAssignment;
use App\Models\FollowUpSubmission;
use App\Models\Recommendation;
use App\Notifications\SpiActivityNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class FollowUpController extends Controller
{
    // Halaman pemantauan matriks tindak lanjut rekomendasi
    public function index(AuditAssignment $assignment)
    {
        $assignment->load([
            'workUnit',
            'findings.recommendations.followUpSubmissions.submittedBy',
            'findings.recommendations.followUpSubmissions.verifiedBy'
        ]);

        return view('followups.index', compact('assignment'));
    }

    // Auditee mengunggah laporan tindakan & bukti tindak lanjut
    public function storeSubmission(Request $request, Recommendation $recommendation)
    {
        $request->validate([
            'action_taken' => ['required', 'string'],
            'evidence_file' => ['required', 'file', 'mimes:pdf,docx,xlsx,zip,jpg,png', 'max:20480'], // maks 20MB
        ]);

        $filePath = $request->file('evidence_file')->store('follow_up_evidences/' . $recommendation->finding->assignment_id, 'public');

        DB::transaction(function () use ($request, $recommendation, $filePath) {
            FollowUpSubmission::create([
                'recommendation_id' => $recommendation->id,
                'submitted_by' => Auth::id(),
                'action_taken' => $request->input('action_taken'),
                'evidence_path' => $filePath,
                'status' => 'submitted',
            ]);

            // Ubah status rekomendasi menjadi sedang diproses
            $recommendation->update(['status' => 'under_review']);

            // Catat ke Jejak Audit Sistem
            ActivityLog::record(
                'SUBMIT',
                'Tindak Lanjut',
                "Mengunggah bukti tindak lanjut untuk rekomendasi #{$recommendation->id}."
            );

            // Kirim notifikasi ke tim auditor penugasan terkait
            $assignment = $recommendation->finding->assignment;
            $assignment->loadMissing(['auditors', 'workUnit']);

            Notification::send($assignment->auditors, new SpiActivityNotification(
                'Bukti Tindak Lanjut Baru',
                "Unit {$assignment->workUnit->name} mengunggah bukti tindak lanjut rekomendasi #{$recommendation->id}.",
                route('assignments.followups', $assignment->id),
                'info'
            ));
        });

        return back()->with('success', 'Bukti tindak lanjut berhasil diserahkan ke tim Auditor.');
    }

    // Auditor memverifikasi bukti perbaikan
    public function verifySubmission(Request $request, FollowUpSubmission $submission)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:verified,revision_required'],
            'verification_notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $submission) {
            $submission->update([
                'status' => $validated['status'],
                'verified_by' => Auth::id(),
                'verification_notes' => $validated['verification_notes'] ?? null,
                'verified_at' => now(),
            ]);

            $recommendation = $submission->recommendation;

            if ($validated['status'] === 'verified') {
                $recommendation->update(['status' => 'closed']);
            } else {
                $recommendation->update(['status' => 'in_progress']);
            }

            // Catat ke Jejak Audit Sistem
            $statusLabel = $validated['status'] === 'verified' ? 'Memverifikasi (Sesuai)' : 'Meminta revisi';
            ActivityLog::record(
                'VERIFY',
                'Tindak Lanjut',
                "{$statusLabel} bukti tindak lanjut rekomendasi #{$submission->recommendation_id}."
            );

            // Kirim notifikasi hasil verifikasi kembali ke Auditee yang mengunggah bukti
            if ($submission->submittedBy) {
                $notifTitle = $validated['status'] === 'verified'
                    ? 'Tindak Lanjut Disetujui (Tuntas)'
                    : 'Revisi Bukti Tindak Lanjut';
                $notifMsg = $validated['status'] === 'verified'
                    ? "Bukti tindak lanjut rekomendasi #{$recommendation->id} telah diverifikasi dan dinyatakan tuntas."
                    : "Tim Auditor meminta revisi pada bukti tindak lanjut rekomendasi #{$recommendation->id}.";

                Notification::send($submission->submittedBy, new SpiActivityNotification(
                    $notifTitle,
                    $notifMsg,
                    route('assignments.followups', $recommendation->finding->assignment_id),
                    $validated['status'] === 'verified' ? 'success' : 'warning'
                ));
            }

            // Pengecekan otomatis: Jika seluruh rekomendasi pada audit ini sudah 'closed'
            $assignment = $recommendation->finding->assignment;
            $allRecommendations = Recommendation::whereHas('finding', function ($q) use ($assignment) {
                $q->where('assignment_id', $assignment->id);
            })->get();

            $allClosed = $allRecommendations->every(fn($r) => $r->status === 'closed');

            if ($allClosed && $allRecommendations->isNotEmpty()) {
                $assignment->update(['status' => 'completed']);
            }
        });

        return back()->with('success', 'Hasil telaah verifikasi bukti tindak lanjut berhasil diperbarui.');
    }
}
