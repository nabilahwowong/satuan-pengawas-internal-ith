<?php

namespace App\Http\Controllers;

use App\Models\AuditAssignment;
use App\Models\AuditFinding;
use App\Models\AuditReport;
use App\Models\Recommendation;
use App\Models\SurveyResponse;
use App\Models\User;
use App\Models\WorkUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        $stats = [
            'total_users' => User::count(),
            'total_units' => WorkUnit::count(),
            'total_audits' => AuditAssignment::count(),
            'total_surveys' => SurveyResponse::count(),
            'audit_statuses' => [
                'draft' => AuditAssignment::where('status', 'draft')->count(),
                'ongoing' => AuditAssignment::where('status', 'ongoing')->count(),
                'in_monitoring' => AuditAssignment::where('status', 'in_monitoring')->count(),
                'completed' => AuditAssignment::where('status', 'completed')->count(),
            ],
            'recent_audits' => AuditAssignment::with(['workUnit', 'leader.user'])->latest()->take(5)->get(),
        ];

        return view('dashboard.admin', compact('stats'));
    }

    public function auditor()
    {
        $userId = Auth::id();

        // Cari penugasan di mana user ini terdaftar sebagai anggota atau ketua
        $assignedAuditIds = AuditAssignment::whereHas('members', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->pluck('id');

        // Ambil semua ID temuan milik penugasan-penugasan tersebut (pakai kolom assignment_id)
        $findingIds = AuditFinding::whereIn('assignment_id', $assignedAuditIds)->pluck('id');

        $stats = [
            'my_audits_count' => $assignedAuditIds->count(),
            'my_active_audits' => AuditAssignment::whereIn('id', $assignedAuditIds)
                ->whereIn('status', ['draft', 'ongoing', 'draft_lhp', 'lhp_review'])
                ->count(),
            'findings_count' => $findingIds->count(),
            'pending_verifications' => Recommendation::whereIn('finding_id', $findingIds)
                ->where('status', 'under_review')
                ->count(),
            'active_assignments' => AuditAssignment::whereIn('id', $assignedAuditIds)
                ->with(['workUnit', 'leader.user'])
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('dashboard.auditor', compact('stats'));
    }

    public function auditee()
    {
        $unitId = Auth::user()->work_unit_id;

        // Ambil semua ID penugasan milik unit kerja yang sedang login
        $unitAssignmentIds = AuditAssignment::where('work_unit_id', $unitId)->pluck('id');

        // Ambil semua ID temuan milik penugasan tersebut (pakai kolom assignment_id)
        $unitFindingIds = AuditFinding::whereIn('assignment_id', $unitAssignmentIds)->pluck('id');

        $stats = [
            'unit_audits_count' => $unitAssignmentIds->count(),
            'open_recommendations' => Recommendation::whereIn('finding_id', $unitFindingIds)
                ->whereIn('status', ['open', 'in_progress'])
                ->count(),
            'submitted_followups' => Recommendation::whereIn('finding_id', $unitFindingIds)
                ->where('status', 'under_review')
                ->count(),
            'resolved_recommendations' => Recommendation::whereIn('finding_id', $unitFindingIds)
                ->where('status', 'closed')
                ->count(),
            'recent_assignments' => AuditAssignment::where('work_unit_id', $unitId)
                ->with('leader.user')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('dashboard.auditee', compact('stats'));
    }

    public function approver()
    {
        $stats = [
            'pending_approvals' => AuditReport::where('status', 'submitted')->count(),
            'approved_reports' => AuditReport::where('status', 'approved')->count(),
            'audits_in_monitoring' => AuditAssignment::where('status', 'in_monitoring')->count(),
            'total_completed_audits' => AuditAssignment::where('status', 'completed')->count(),
            'reports_to_review' => AuditReport::with(['assignment.workUnit', 'creator'])
                ->where('status', 'submitted')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('dashboard.approver', compact('stats'));
    }
}
