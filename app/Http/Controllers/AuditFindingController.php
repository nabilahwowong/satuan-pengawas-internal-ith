<?php

namespace App\Http\Controllers;

use App\Models\AuditAssignment;
use App\Models\AuditFinding;
use App\Models\FindingResponse;
use App\Models\Recommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditFindingController extends Controller
{
    // Menampilkan daftar temuan pada penugasan tertentu
    public function index(AuditAssignment $assignment)
    {
        $assignment->load(['workUnit', 'auditors']);
        $findings = AuditFinding::with(['recommendations', 'responses.user'])
            ->where('assignment_id', $assignment->id)
            ->latest()
            ->get();

        return view('findings.index', compact('assignment', 'findings'));
    }

    // Auditor menerbitkan temuan baru beserta rekomendasinya
    public function store(Request $request, AuditAssignment $assignment)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'condition' => ['required', 'string'],
            'criteria' => ['required', 'string'],
            'cause' => ['required', 'string'],
            'impact' => ['required', 'string'], // Disesuaikan dari 'effect' ke 'impact'
            'recommendation' => ['required', 'string'], // Disesuaikan dengan input tunggal dari form modal
        ]);

        DB::transaction(function () use ($validated, $assignment) {
            // Generate otomatis nomor temuan jika belum ada kolomnya di form
            $findingNumber = 'TMP-' . date('Ymd') . '-' . rand(100, 999);

            // 1. Simpan Temuan Audit
            $finding = $assignment->findings()->create([
                'finding_number' => $findingNumber,
                'title' => $validated['title'],
                'condition' => $validated['condition'],
                'criteria' => $validated['criteria'],
                'cause' => $validated['cause'],
                'effect' => $validated['impact'], // Masukkan ke kolom effect di database
            ]);

            // 2. Simpan Rekomendasi Tindak Lanjut
            if (!empty(trim($validated['recommendation']))) {
                $finding->recommendations()->create([
                    'recommendation_text' => $validated['recommendation'], // Sesuaikan dengan kolom tabel recommendations Anda jika pakai recommendation_text/content
                    'status' => 'open',
                ]);
            }
        });

        return back()->with('success', 'Lembar temuan audit dan rekomendasi berhasil disimpan.');
    }

    // Auditee memberikan tanggapan/klarifikasi atas temuan
    public function storeResponse(Request $request, AuditFinding $finding)
    {
        $validated = $request->validate([
            'response' => ['required', 'string'],
        ]);

        // Jika tabel findings Anda memiliki kolom 'auditee_response', simpan langsung ke finding:
        // $finding->update(['auditee_response' => $validated['response']]);

        // Atau jika menggunakan relasi responses:
        $finding->responses()->create([
            'user_id' => Auth::id(),
            'response' => $validated['response'],
            'status' => 'submitted',
        ]);

        return back()->with('success', 'Tanggapan auditee berhasil dikirimkan ke tim auditor.');
    }
}
