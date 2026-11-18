<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FindingResponse extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'finding_responses'; // Sesuaikan jika nama tabel Anda berbeda

    protected $fillable = [
        'audit_finding_id', // atau finding_id, sesuaikan dengan foreign key di database Anda
        'user_id',
        'response',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function finding()
    {
        return $this->belongsTo(AuditFinding::class, 'audit_finding_id');
    }
}
