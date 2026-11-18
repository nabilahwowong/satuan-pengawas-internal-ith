<?php

namespace App\Http\Controllers;

use App\Models\AuditAssignment;
use App\Models\Recommendation;
use App\Models\WorkUnit;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MonitoringExportController extends Controller
{
    /**
     * Tampilan Rekapitulasi Pemantauan Seluruh Unit Kerja
     */
    public function index()
    {
        $units = WorkUnit::with(['assignments.findings.recommendations'])->get()->map(function ($unit) {
            $recommendations = $unit->assignments->flatMap->findings->flatMap->recommendations;

            return [
                'unit_id' => $unit->id,
                'unit_name' => $unit->name,
                'total_recs' => $recommendations->count(),
                'closed' => $recommendations->where('status', 'closed')->count(),
                'in_progress' => $recommendations->where('status', 'in_progress')->count(),
                'under_review' => $recommendations->where('status', 'under_review')->count(),
                'open' => $recommendations->where('status', 'open')->count(),
                'percentage' => $recommendations->count() > 0
                    ? round(($recommendations->where('status', 'closed')->count() / $recommendations->count()) * 100, 1)
                    : 0,
            ];
        });

        return view('monitoring.index', compact('units'));
    }

    /**
     * Ekspor Matriks Rekomendasi & Tindak Lanjut ke Format CSV / Excel
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $fileName = 'Matriks_Tindak_Lanjut_SPI_ITH_' . date('Ymd_His') . '.csv';

        $recommendations = Recommendation::with(['finding.assignment.workUnit', 'finding.assignment.leader.user'])
            ->latest()
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($recommendations) {
            $handle = fopen('php://output', 'w');

            // Tambahkan BOM agar karakter terbaca rapi di Microsoft Excel Windows
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header Kolom
            fputcsv($handle, [
                'No',
                'Nomor Penugasan PKPT',
                'Unit Kerja (Auditee)',
                'Tahun Audit',
                'Judul Temuan',
                'Kondisi Temuan',
                'Rekomendasi SPI',
                'Status Rekomendasi',
                'Batas Waktu',
            ], ';');

            foreach ($recommendations as $index => $rec) {
                $assignment = $rec->finding?->assignment;

                fputcsv($handle, [
                    $index + 1,
                    $assignment?->assignment_number ?? '-',
                    $assignment?->workUnit?->name ?? '-',
                    $assignment ? \Carbon\Carbon::parse($assignment->start_date)->format('Y') : '-',
                    $rec->finding?->title ?? '-',
                    strip_tags($rec->finding?->condition ?? '-'),
                    $rec->description,
                    strtoupper($rec->status),
                    $rec->deadline ? \Carbon\Carbon::parse($rec->deadline)->format('d/m/Y') : '-',
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
