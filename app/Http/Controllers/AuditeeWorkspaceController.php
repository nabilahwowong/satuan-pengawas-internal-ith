<?php

namespace App\Http\Controllers;

use App\Models\AuditAssignment;
use Illuminate\Support\Facades\Auth;

class AuditeeWorkspaceController extends Controller
{
    // 1. Halaman Permintaan Dokumen
    public function documents()
    {
        $unitId = Auth::user()->work_unit_id;
        $assignments = AuditAssignment::where('work_unit_id', $unitId)
            ->where('status', '!=', 'draft')
            ->with(['documentRequests.submissions'])
            ->latest()
            ->get();

        return view('auditee.documents', compact('assignments'));
    }

    // 2. Halaman Temuan & Tanggapan
    public function findings()
    {
        $unitId = Auth::user()->work_unit_id;
        $assignments = AuditAssignment::where('work_unit_id', $unitId)
            // ->where('status', '!=', 'draft') // <-- Komentari atau hapus baris ini jika ingin menampilkan semua penugasan unit
            ->with(['findings.recommendations'])
            ->latest()
            ->get();

        return view('auditee.findings', compact('assignments'));
    }

    // 3. Halaman Tindak Lanjut & Bukti Perbaikan
    public function followups()
    {
        $unitId = Auth::user()->work_unit_id;
        $assignments = AuditAssignment::where('work_unit_id', $unitId)
            ->where('status', '!=', 'draft')
            ->with(['findings.recommendations.followUps'])
            ->latest()
            ->get();

        return view('auditee.followups', compact('assignments'));
    }
}
