<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\AuditAssignment;
use App\Models\AuditReport;
use App\Models\ReportApproval;
use App\Models\User;
use App\Notifications\SpiActivityNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AuditReportController extends Controller
{
    // Menampilkan halaman LHP per penugasan
    public function show(AuditAssignment $assignment)
    {
        $assignment->load(['workUnit', 'auditors', 'findings.recommendations', 'report.approvals.approver']);
        $report = $assignment->report;

        return view('reports.show', compact('assignment', 'report'));
    }

    // Ekspor / Cetak LHP Resmi dalam Format PDF
    public function downloadPdf(AuditAssignment $assignment)
    {
        $assignment->load([
            'workUnit',
            'leader.user',
            'members.user',
            'report.creator',
            'report.approver',
            'findings.recommendations.followUps'
        ]);

        $report = $assignment->report;

        if (!$report) {
            return back()->withErrors(['error' => 'Laporan Hasil Pengawasan (LHP) belum dibuat.']);
        }

        $pdf = Pdf::loadView('reports.pdf_template', compact('assignment', 'report'))
                  ->setPaper('a4', 'portrait');

        $fileName = 'LHP-' . str_replace(['/', ' '], '-', $report->report_number) . '.pdf';

        return $pdf->download($fileName);
    }

    // Auditor/Admin membuat draf LHP atau memperbarui berkas LHP
    public function storeOrUpdate(Request $request, AuditAssignment $assignment)
    {
        $request->validate([
            'report_number' => ['required', 'string', 'max:100'],
            'report_date' => ['required', 'date'],
            'report_file' => ['nullable', 'file', 'mimes:pdf', 'max:25600'], // maks 25MB PDF
        ]);

        $filePath = null;
        if ($request->hasFile('report_file')) {
            $filePath = $request->file('report_file')->store('audit_reports/' . $assignment->id, 'public');
        }

        $report = $assignment->report;

        if ($report) {
            $report->update([
                'report_number' => $request->input('report_number'),
                'report_date' => $request->input('report_date'),
                'file_path' => $filePath ?? $report->file_path,
                'status' => 'draft',
            ]);
        } else {
            $report = $assignment->report()->create([
                'report_number' => $request->input('report_number'),
                'report_date' => $request->input('report_date'),
                'file_path' => $filePath,
                'status' => 'draft',
            ]);
        }

        $assignment->update(['status' => 'draft_lhp']);

        return back()->with('success', 'Draf Laporan Hasil Pengawasan (LHP) berhasil disimpan.');
    }

    // Mengajukan LHP ke Kepala SPI
    public function submitForApproval(AuditReport $report)
    {
        if (!$report->file_path) {
            return back()->withErrors(['error' => 'Harap unggah berkas draf LHP sebelum mengajukan pengesahan.']);
        }

        $report->update(['status' => 'submitted_for_approval']);
        $report->assignment->update(['status' => 'lhp_review']);

        // Catat ke Jejak Audit Sistem
        ActivityLog::record(
            'SUBMIT',
            'Laporan (LHP)',
            "Mengajukan draf LHP nomor {$report->report_number} kepada Kepala SPI."
        );

        // Kirim notifikasi ke seluruh akun Approver (Kepala SPI)
        $approvers = User::where('role', 'approver')->where('is_active', true)->get();
        Notification::send($approvers, new SpiActivityNotification(
            'Pengajuan Telaah LHP Baru',
            "Tim Auditor mengajukan draf LHP {$report->report_number} untuk ditelaah.",
            route('assignments.report', $report->assignment_id),
            'warning'
        ));

        return back()->with('success', 'LHP berhasil diajukan ke Kepala SPI untuk ditelaah dan disahkan.');
    }

    // Tindakan Kepala SPI (Setujui / Tolak)
    public function decideApproval(Request $request, AuditReport $report)
    {
        $validated = $request->validate([
            'decision' => ['required', 'in:approved,rejected'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $report) {
            // Catat riwayat persetujuan
            ReportApproval::create([
                'report_id' => $report->id,
                'approver_id' => Auth::id(),
                'decision' => $validated['decision'],
                'notes' => $validated['notes'] ?? null,
                'decided_at' => now(),
            ]);

            if ($validated['decision'] === 'approved') {
                $report->update(['status' => 'approved']);
                // Ubah status penugasan menjadi pemantauan tindak lanjut
                $report->assignment->update(['status' => 'in_monitoring']);
            } else {
                $report->update(['status' => 'rejected']);
                $report->assignment->update(['status' => 'draft_lhp']);
            }

            // Catat ke Jejak Audit Sistem
            $statusText = $validated['decision'] === 'approved' ? 'Menyetujui' : 'Menolak (Revisi)';
            ActivityLog::record(
                strtoupper($validated['decision']),
                'Laporan (LHP)',
                "{$statusText} naskah LHP nomor {$report->report_number}."
            );

            // Kirim notifikasi ke anggota tim auditor pada penugasan ini
            $auditors = $report->assignment->auditors;
            $statusMsg = $validated['decision'] === 'approved' ? 'telah DISAHKAN oleh Kepala SPI.' : 'DIKEMBALIKAN untuk revisi.';
            Notification::send($auditors, new SpiActivityNotification(
                'Keputusan Pengesahan LHP',
                "LHP {$report->report_number} {$statusMsg}",
                route('assignments.report', $report->assignment_id),
                $validated['decision'] === 'approved' ? 'success' : 'warning'
            ));
        });

        return back()->with('success', 'Keputusan pengesahan LHP berhasil dicatat.');
    }
}
