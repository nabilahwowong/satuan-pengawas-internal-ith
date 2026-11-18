<?php

use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WorkUnitController;
use App\Http\Controllers\AuditAssignmentController;
use App\Http\Controllers\AuditFindingController;
use App\Http\Controllers\AuditReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContentManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\MonitoringExportController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuditeeWorkspaceController;
use App\Models\DocumentRequest;
use App\Models\AuditFinding;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Dapat Diakses Bebas Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'index'])->name('public.home');
Route::get('/visi-misi', [PublicController::class, 'visionMission'])->name('public.vision-mission');
Route::get('/program-kerja', [PublicController::class, 'workPrograms'])->name('public.work-programs');
Route::get('/berita/{post}', [PublicController::class, 'showPost'])->name('public.post');

// Survei Persepsi Publik Kampus ITH
Route::get('/survei-kepuasan', [SurveyController::class, 'index'])->name('surveys.index');
Route::post('/survei-kepuasan/{survey}', [SurveyController::class, 'store'])->name('surveys.store');

/*
|--------------------------------------------------------------------------
| Guest Routes (Hanya Untuk Pengguna yang Belum Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Logout (Wajib Login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated Core System (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // ==========================================
    // 1. DASHBOARD & MASTER DATA ADMIN
    // ==========================================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        // Master Data
        Route::resource('units', WorkUnitController::class)->except(['create', 'edit', 'show']);
        Route::resource('users', UserController::class)->only(['index', 'store', 'destroy']);

        // CMS Publik & Agenda
        Route::get('/cms', [CmsController::class, 'index'])->name('cms.index');
        Route::post('/cms/posts', [CmsController::class, 'storePost'])->name('cms.posts.store');
        Route::post('/cms/schedules', [CmsController::class, 'storeSchedule'])->name('cms.schedules.store');
    });

    // ==========================================
    // 2. DASHBOARD PERAN LAIN & RUANG KERJA AUDITEE
    // ==========================================
    Route::middleware('role:auditor')->prefix('auditor')->name('auditor.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'auditor'])->name('dashboard');
    });

    Route::middleware('role:auditee')->prefix('auditee')->name('auditee.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'auditee'])->name('dashboard');

        // 3 Menu Terpisah Khusus Ruang Kerja Auditee
        Route::get('/ruang-kerja/dokumen', [AuditeeWorkspaceController::class, 'documents'])->name('documents');
        Route::get('/ruang-kerja/temuan', [AuditeeWorkspaceController::class, 'findings'])->name('findings');
        Route::get('/ruang-kerja/tindak-lanjut', [AuditeeWorkspaceController::class, 'followups'])->name('followups');
    });

    Route::middleware('role:approver')->prefix('approver')->name('approver.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'approver'])->name('dashboard');
    });

    // ==========================================
    // 3. SIKLUS PENGAWASAN INTERNAL (AUDIT LIFECYCLE)
    // ==========================================

    // A. Penugasan Audit
    Route::get('/assignments', [AuditAssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/{assignment}', [AuditAssignmentController::class, 'show'])->name('assignments.show');

    Route::middleware('role:admin,auditor')->group(function () {
        Route::get('/assignment/create', [AuditAssignmentController::class, 'create'])->name('assignments.create');
        Route::post('/assignment', [AuditAssignmentController::class, 'store'])->name('assignments.store');
    });

    // B. Dokumen Pemeriksaan
    Route::get('/assignments/{assignment}/documents', [DocumentRequestController::class, 'index'])->name('assignments.documents');
    Route::get('/assignments/{assignment}/documents', [DocumentRequestController::class, 'index'])->name('documents.index');

    Route::middleware('role:admin,auditor')->group(function () {
        Route::post('/assignments/{assignment}/documents', [DocumentRequestController::class, 'storeRequest'])->name('documents.store');
        Route::post('/submissions/{submission}/verify', [DocumentRequestController::class, 'verifySubmission'])->name('submissions.verify');
    });

    Route::middleware('role:auditee,admin')->group(function () {
        Route::post('/documents/{documentRequest}/submit', [DocumentRequestController::class, 'uploadSubmission'])->name('documents.submit');

        // Rute pengaman GET untuk mencegah MethodNotAllowed / ReflectionException saat validasi unggahan gagal
        Route::get('/documents/{documentRequest}/submit', function (DocumentRequest $documentRequest) {
            return redirect()->route('documents.index', $documentRequest->assignment_id);
        });
    });

    // C. Kertas Kerja Temuan & Rekomendasi
    Route::get('/assignments/{assignment}/findings', [AuditFindingController::class, 'index'])->name('assignments.findings');
    Route::get('/assignments/{assignment}/findings', [AuditFindingController::class, 'index'])->name('findings.index');

    Route::middleware('role:admin,auditor')->group(function () {
        Route::post('/assignments/{assignment}/findings', [AuditFindingController::class, 'store'])->name('findings.store');
    });

    Route::middleware('role:auditee,admin')->group(function () {
        Route::post('/findings/{finding}/response', [AuditFindingController::class, 'storeResponse'])->name('findings.response');

        // Rute pengaman GET yang aman (tidak akan loop)
        Route::get('/findings/{finding}/response', function (AuditFinding $finding) {
            return redirect()->route('assignments.show', $finding->assignment_id);
        });
    });

    // D. Laporan Hasil Pengawasan (LHP) & Persetujuan
    Route::get('/assignments/{assignment}/report', [AuditReportController::class, 'show'])->name('assignments.report');
    Route::get('/assignments/{assignment}/report/pdf', [AuditReportController::class, 'downloadPdf'])->name('reports.pdf');

    Route::middleware('role:admin,auditor')->group(function () {
        Route::post('/assignments/{assignment}/report', [AuditReportController::class, 'storeOrUpdate'])->name('reports.store');
        Route::post('/reports/{report}/submit', [AuditReportController::class, 'submitForApproval'])->name('reports.submit');
    });

    Route::middleware('role:approver,admin')->group(function () {
        Route::post('/reports/{report}/decide', [AuditReportController::class, 'decideApproval'])->name('reports.decide');
    });

    // E. Pemantauan Tindak Lanjut Rekomendasi (Follow-Up)
    Route::get('/assignments/{assignment}/followups', [FollowUpController::class, 'index'])->name('assignments.followups');

    Route::middleware('role:auditee,admin')->group(function () {
        Route::post('/recommendations/{recommendation}/followup', [FollowUpController::class, 'storeSubmission'])->name('followups.store');
    });

    Route::middleware('role:auditor,admin')->group(function () {
        Route::post('/followups/{submission}/verify', [FollowUpController::class, 'verifySubmission'])->name('followups.verify');
    });

    // ==========================================
    // 4. KELOLA PROFIL & KONTEN (ADMIN, AUDITOR, APPROVER)
    // ==========================================
    Route::middleware('role:admin,auditor')->prefix('portal-konten')->name('content.')->group(function () {
        Route::get('/', [ContentManagementController::class, 'index'])->name('index');
        Route::put('/profile', [ContentManagementController::class, 'updateProfile'])->name('profile.update');
        Route::post('/programs', [ContentManagementController::class, 'storeProgram'])->name('programs.store');
        Route::put('/programs/{program}', [ContentManagementController::class, 'updateProgram'])->name('programs.update');
        Route::delete('/programs/{program}', [ContentManagementController::class, 'destroyProgram'])->name('programs.destroy');
    });

    Route::middleware('role:admin,auditor,approver')->group(function () {
        Route::get('/monitoring/rekapitulasi', [MonitoringExportController::class, 'index'])->name('monitoring.index');
        Route::get('/monitoring/export-csv', [MonitoringExportController::class, 'exportCsv'])->name('monitoring.export');
    });

    Route::middleware('role:admin,approver')->group(function () {
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('logs.index');
    });

    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
});
