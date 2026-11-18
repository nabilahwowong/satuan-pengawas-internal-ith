<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\AuditAssignment;
use App\Models\User;
use App\Models\WorkUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditAssignmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Jika role-nya auditee, tampilkan penugasan aktif (bukan draft) khusus unit kerjanya
        if ($user->role === 'auditee') {
            $assignments = AuditAssignment::with(['workUnit', 'leader'])
                ->where('work_unit_id', $user->work_unit_id)
                ->where('status', '!=', 'draft')
                ->latest()
                ->paginate(10);
        } else {
            // Admin, Auditor, dan Approver melihat seluruh penugasan dengan paginasi
            $assignments = AuditAssignment::with(['workUnit', 'leader'])
                ->latest()
                ->paginate(10);
        }

        return view('assignments.index', compact('assignments'));
    }

    public function create()
    {
        $units = WorkUnit::where('is_active', true)->get();
        $auditors = User::where('role', 'auditor')->where('is_active', true)->get();

        return view('assignments.create', compact('units', 'auditors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'assignment_number' => ['required', 'string', 'max:100', 'unique:audit_assignments,assignment_number'],
            'title' => ['required', 'string', 'max:255'],
            'work_unit_id' => ['required', 'exists:work_units,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'scope' => ['nullable', 'string'],
            'leader_id' => ['required', 'exists:users,id'],
            'member_ids' => ['nullable', 'array'],
            'member_ids.*' => ['exists:users,id'],
        ]);

        DB::transaction(function () use ($validated) {
            // 1. Buat Header Penugasan Audit
            $assignment = AuditAssignment::create([
                'assignment_number' => $validated['assignment_number'],
                'title' => $validated['title'],
                'work_unit_id' => $validated['work_unit_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'scope' => $validated['scope'] ?? null,
                'status' => 'draft',
                'created_by' => Auth::id(),
            ]);

            // 2. Kaitkan Ketua Tim
            $assignment->auditors()->attach($validated['leader_id'], [
                'role_in_team' => 'ketua_tim',
            ]);

            // 3. Kaitkan Anggota Tim (jika ada)
            if (!empty($validated['member_ids'])) {
                foreach ($validated['member_ids'] as $memberId) {
                    if ($memberId != $validated['leader_id']) {
                        $assignment->auditors()->attach($memberId, [
                            'role_in_team' => 'anggota',
                        ]);
                    }
                }
            }

            // 4. Catat ke Jejak Audit Sistem (Activity Log)
            $assignment->load('workUnit');
            ActivityLog::record(
                'CREATE',
                'Penugasan Audit',
                "Membuat surat tugas baru nomor {$assignment->assignment_number} untuk unit {$assignment->workUnit->name}."
            );
        });

        return redirect()->route('assignments.index')->with('success', 'Penugasan audit berhasil dibuat.');
    }

    public function show(AuditAssignment $assignment)
    {
        $user = Auth::user();

        // Pengecekan akses dilonggarkan (hanya mencocokkan unit jika rolenya auditee, status draft diabaikan agar tidak 403)
        if ($user->role === 'auditee' && $assignment->work_unit_id !== $user->work_unit_id) {
            abort(403, 'Anda tidak memiliki akses ke penugasan audit ini.');
        }

        // Memuat relasi lengkap termasuk documentRequests, submissions, dan submitter-nya
        $assignment->load([
            'workUnit',
            'auditors',
            'documentRequests.submissions.submitter',
            'findings.recommendations'
        ]);

        return view('assignments.show', compact('assignment'));
    }
}
