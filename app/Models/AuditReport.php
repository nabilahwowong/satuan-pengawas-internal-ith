<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditReport extends Model
{
    use HasFactory;

    // Mengizinkan semua atribut selain id diisi secara massal
    protected $guarded = ['id'];

    /**
     * Relasi balik ke penugasan audit utama
     */
    public function assignment()
    {
        return $this->belongsTo(AuditAssignment::class, 'audit_assignment_id');
    }

    /**
     * Relasi ke auditor/admin yang membuat/menyusun draft LHP
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke approver/pimpinan SPI yang mengesahkan LHP
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
