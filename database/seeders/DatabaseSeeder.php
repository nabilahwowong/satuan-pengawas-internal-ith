<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Unit Kerja Awal
        $unitKeuangan = WorkUnit::create([
            'unit_code' => 'REK-KEU',
            'name' => 'Bagian Keuangan & Perencanaan',
            'leader_name' => 'Dr. H. Ahmad, M.T.',
            'leader_nip' => '197501012000031001',
            'is_active' => true,
        ]);

        $unitFakultas = WorkUnit::create([
            'unit_code' => 'FTII-01',
            'name' => 'Fakultas Teknologi Informasi dan Industri',
            'leader_name' => 'Dr. Eng. Ir. Imran, M.Eng.',
            'leader_nip' => '198202022005011002',
            'is_active' => true,
        ]);

        $defaultPassword = Hash::make('password123');

        // 2. Akun Administrator Sistem SPI
        User::create([
            'name' => 'Administrator SPI',
            'nip' => '199001012015011001',
            'email' => 'admin@spi.ith.ac.id',
            'password' => $defaultPassword,
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 3. Akun Pejabat Penyetuju / Kepala SPI
        User::create([
            'name' => 'Kepala SPI ITH',
            'nip' => '197005051995031002',
            'email' => 'kepala@spi.ith.ac.id',
            'password' => $defaultPassword,
            'role' => 'approver',
            'is_active' => true,
        ]);

        // 4. Akun Anggota Auditor SPI
        User::create([
            'name' => 'Auditor SPI Utama',
            'nip' => '198804122014021003',
            'email' => 'auditor@spi.ith.ac.id',
            'password' => $defaultPassword,
            'role' => 'auditor',
            'is_active' => true,
        ]);

        // 5. Akun Auditee (Kepala / Staf Unit Kerja)
        User::create([
            'name' => 'PIC Bagian Keuangan',
            'nip' => '199208152019032004',
            'email' => 'keuangan@ith.ac.id',
            'password' => $defaultPassword,
            'role' => 'auditee',
            'work_unit_id' => $unitKeuangan->id,
            'is_active' => true,
        ]);
    }
}
