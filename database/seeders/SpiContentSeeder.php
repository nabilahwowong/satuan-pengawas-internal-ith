<?php

namespace Database\Seeders;

use App\Models\SpiProfile;
use App\Models\WorkProgram;
use App\Models\User;
use Illuminate\Database\Seeder;

class SpiContentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();

        // 1. Profil Default Visi Misi SPI
        SpiProfile::updateOrCreate(
            ['id' => 1],
            [
                'about' => 'Satuan Pengawasan Intern (SPI) Institut Teknologi Bacharuddin Jusuf Habibie (ITH) adalah unit kerja pengawasan yang berada di bawah dan bertanggung jawab langsung kepada Rektor untuk memastikan penyelenggaraan tata kelola kampus yang transparan, akuntabel, dan berintegritas tinggi.',
                'vision' => 'Menjadi unit pengawas internal yang profesional, independen, dan terpercaya dalam mewujudkan tata kelola perguruan tinggi yang unggul berbasis teknologi di tingkat nasional.',
                'mission' => "1. Melaksanakan pengawasan internal bidang non-akademik secara berkala dan terprogram.\n2. Mendorong kepatuhan terhadap regulasi, efisiensi anggaran, dan pengamanan aset institusi.\n3. Memberikan konsultasi dan rekomendasi konstruktif bagi perbaikan manajemen unit kerja kampus.\n4. Membangun budaya anti-kecurangan (anti-fraud) dan penguatan zona integritas.",
                'updated_by' => $admin?->id,
            ]
        );

        // 2. 10 Program Kerja Standar Pengawasan SPI
        $defaultPrograms = [
            ['title' => 'Penyusunan Program Kerja Pengawasan Tahunan (PKPT)', 'desc' => 'Perencanaan audit berbasis risiko untuk memetakan prioritas pemeriksaan unit kerja sepanjang tahun akademik.', 'status' => 'terealisasi'],
            ['title' => 'Audit Kepatuhan Pengelolaan Keuangan & Anggaran', 'desc' => 'Evaluasi kepatuhan tata kelola anggaran DIPA dan penerimaan kampus terhadap peraturan perundang-undangan.', 'status' => 'berjalan'],
            ['title' => 'Audit Pengadaan Barang dan Jasa (PBJ)', 'desc' => 'Pemeriksaan kepatuhan prosedur pengadaan fasilitas fisik, laboratorium, dan teknologi informasi kampus.', 'status' => 'terencana'],
            ['title' => 'Pemeriksaan Aset, Sarana & Prasarana Kampus', 'desc' => 'Inventarisasi fisik dan evaluasi tertib administrasi penatausahaan aset BMN di lingkungan ITH.', 'status' => 'terencana'],
            ['title' => 'Review Laporan Keuangan Berkala', 'desc' => 'Pemberian keyakinan memadai atas keandalan penyajian laporan keuangan sebelum disahkan pimpinan.', 'status' => 'berjalan'],
            ['title' => 'Pemantauan Tindak Lanjut Rekomendasi Temuan Audit', 'desc' => 'Monitoring berkala terhadap implementasi perbaikan atas saran auditor internal oleh para auditee.', 'status' => 'berjalan'],
            ['title' => 'Pendampingan Penguatan Zona Integritas (ZI-WBK)', 'desc' => 'Sosialisasi tata kelola bersih, pengawasan gratifikasi, dan budaya anti-fraud di civitas akademika.', 'status' => 'terealisasi'],
            ['title' => 'Pemberian Layanan Konsultasi Pengendalian Internal', 'desc' => 'Asistensi teknis bagi unit kerja/fakultas/jurusan terkait kepatuhan SOP administrasi.', 'status' => 'berjalan'],
            ['title' => 'Audit Kinerja Sumber Daya Manusia & Tata Kelola', 'desc' => 'Penilaian efektivitas pelaksanaan tupoksi tenaga kependidikan dan struktur operasional biro.', 'status' => 'terencana'],
            ['title' => 'Penyusunan Laporan Tahunan Pengawasan SPI ke Rektor', 'desc' => 'Rekapitulasi komprehensif seluruh pengawasan dan kepatuhan unit kerja untuk diserahkan ke Rektor.', 'status' => 'terencana'],
        ];

        WorkProgram::truncate();

        foreach ($defaultPrograms as $index => $prog) {
            WorkProgram::create([
                'order_num' => $index + 1,
                'title' => $prog['title'],
                'description' => $prog['desc'],
                'status' => $prog['status'],
                'created_by' => $admin?->id,
            ]);
        }
    }
}
