<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    /**
     * Relasi ke penugasan audit pada unit kerja ini
     */
    public function assignments()
    {
        return $this->hasMany(AuditAssignment::class, 'work_unit_id');
    }

    /**
     * Relasi ke pengguna (auditee) yang tergabung di unit kerja ini
     */
    public function users()
    {
        return $this->hasMany(User::class, 'work_unit_id');
    }
}
