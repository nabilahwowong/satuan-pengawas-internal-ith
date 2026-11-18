<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Pengawasan (LHP) - {{ $report->report_number }}</title>
    <style>
        @page {
            margin: 1.5cm 2cm 2cm 2cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
        }

        /* STYLING KOP SURAT */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            margin-bottom: 0;
        }
        .kop-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }
        .col-logo-ith {
            width: 15%;
            text-align: left;
        }
        .col-logo-ith img {
            max-height: 80px;
            width: auto;
        }
        .col-text {
            width: 70%;
            text-align: center;
            line-height: 1.15;
        }
        .col-logo-spi {
            width: 15%;
            text-align: right;
        }
        .col-logo-spi img {
            max-height: 75px;
            width: auto;
        }
        .kemen-title {
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .ith-title {
            font-size: 12.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0 0 0;
        }
        .spi-title {
            font-size: 12.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0 0 0;
        }
        .contact-info {
            font-size: 8.5pt;
            margin-top: 4px;
        }
        .kop-line {
            border: 0;
            border-top: 3px double #000;
            height: 0;
            margin: 6px 0 16px 0;
        }

        /* STYLING NASKAH LHP */
        .title-doc {
            text-align: center;
            margin-bottom: 20px;
        }
        .title-doc h1 {
            font-size: 13pt;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 0;
            font-weight: bold;
        }
        .title-doc span {
            font-size: 10.5pt;
            display: block;
            margin-top: 3px;
        }
        table.meta {
            width: 100%;
            margin-bottom: 18px;
            font-size: 10.5pt;
            border-collapse: collapse;
        }
        table.meta td {
            vertical-align: top;
            padding: 3px 0;
            border: none;
        }
        h4.section-title {
            font-size: 11pt;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-top: 16px;
            margin-bottom: 8px;
            text-transform: uppercase;
            font-weight: bold;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 9.5pt;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }
        table.data-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .signature-table {
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 10.5pt;
            border: none;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT RESMI SPI ITH -->
    <div class="letterhead">
        <table class="kop-table">
            <tr>
                <td class="col-logo-ith">
                    @php
                        $pathIth = public_path('images/logo_ith.png');
                        $logoIthData = file_exists($pathIth) ? 'data:image/png;base64,' . base64_encode(file_get_contents($pathIth)) : null;
                    @endphp
                    @if($logoIthData)
                        <img src="{{ $logoIthData }}" alt="Logo ITH">
                    @endif
                </td>
                <td class="col-text">
                    <div class="kemen-title">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</div>
                    <div class="ith-title">INSTITUT TEKNOLOGI BACHARUDDIN JUSUF HABIBIE</div>
                    <div class="spi-title">SATUAN PENGAWAS INTERNAL</div>
                    <div class="contact-info">
                        Jl. Balai Kota No. 1, Kota Parepare 91122, Sulawesi Selatan (0421)–2924000<br>
                        Laman: ith.ac.id | Pos-el: spi@ith.ac.id
                    </div>
                </td>
                <td class="col-logo-spi">
                    @php
                        $pathSpi = public_path('images/logo_spi.png');
                        $logoSpiData = file_exists($pathSpi) ? 'data:image/png;base64,' . base64_encode(file_get_contents($pathSpi)) : null;
                    @endphp
                    @if($logoSpiData)
                        <img src="{{ $logoSpiData }}" alt="Logo SPI ITH">
                    @endif
                </td>
            </tr>
        </table>
        <div class="kop-line"></div>
    </div>

    <!-- JUDUL DOKUMEN -->
    <div class="title-doc">
        <h1>LAPORAN HASIL PENGAWASAN (LHP)</h1>
        <span>Nomor: {{ $report->report_number }}</span>
    </div>

    <!-- METADATA AUDIT -->
    <table class="meta">
        <tr>
            <td style="width: 26%;"><strong>Unit Kerja (Auditee)</strong></td>
            <td style="width: 3%;">:</td>
            <td>{{ $assignment->workUnit?->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Surat Tugas Pengawasan</strong></td>
            <td>:</td>
            <td>{{ $assignment->assignment_number }}</td>
        </tr>
        <tr>
            <td><strong>Masa Pemeriksaan</strong></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($assignment->start_date)->translatedFormat('d F Y') }} s.d. {{ \Carbon\Carbon::parse($assignment->end_date)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td><strong>Ketua Tim Pengawas</strong></td>
            <td>:</td>
            <td>{{ $assignment->leader?->user?->name ?? 'Tim Auditor SPI ITH' }}</td>
        </tr>
    </table>

    <!-- RINGKASAN EKSEKUTIF -->
    <h4 class="section-title">I. Ringkasan Eksekutif</h4>
    <div style="text-align: justify; margin-bottom: 15px; font-size: 10.5pt;">
        {!! nl2br(e($report->executive_summary)) !!}
    </div>

    <!-- TABEL TEMUAN & REKOMENDASI -->
    <h4 class="section-title">II. Matriks Temuan & Rekomendasi Pengawasan</h4>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Kondisi Temuan</th>
                <th style="width: 20%;">Kriteria / SOP</th>
                <th style="width: 20%;">Akibat / Dampak</th>
                <th style="width: 30%;">Rekomendasi SPI</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($assignment->findings as $idx => $f)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td>
                        <strong>{{ $f->title }}</strong><br>
                        <small>{{ $f->condition }}</small>
                    </td>
                    <td>{{ $f->criteria ?? '-' }}</td>
                    <td>{{ $f->effect ?? '-' }}</td>
                    <td>
                        <ol style="margin: 0; padding-left: 14px;">
                            @foreach ($f->recommendations as $rec)
                                <li>{{ $rec->description }}</li>
                            @endforeach
                        </ol>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada catatan temuan ketidakpatuhan atau penyimpangan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- LEMBAR PENGESAHAN RESMI -->
    <table class="signature-table">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Kepala Satuan Pengawas Internal</strong><br><br><br><br><br>
                <u><strong>{{ $report->approver?->name ?? 'Kepala SPI ITH' }}</strong></u><br>
                NIP. {{ $report->approver?->nip ?? '197505102000031002' }}
            </td>
            <td>
                Parepare, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                <strong>Ketua Tim Pengawas</strong><br><br><br><br><br>
                <u><strong>{{ $assignment->leader?->user?->name ?? 'Auditor SPI ITH' }}</strong></u><br>
                NIP. {{ $assignment->leader?->user?->nip ?? '-' }}
            </td>
        </tr>
    </table>

</body>
</html>
