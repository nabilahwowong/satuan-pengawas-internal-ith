<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function workUnit()
    {
        return $this->belongsTo(WorkUnit::class, 'work_unit_id');
    }

    public function auditors()
    {
        return $this->belongsToMany(User::class, 'audit_assignment_members', 'assignment_id', 'user_id')
            ->withPivot('role_in_team')
            ->withTimestamps();
    }

    public function members()
    {
        return $this->hasMany(AuditAssignmentMember::class, 'assignment_id');
    }

    public function leader()
    {
        return $this->hasOne(AuditAssignmentMember::class, 'assignment_id')
            ->where('role_in_team', 'ketua_tim');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Ubah foreign key menjadi 'assignment_id'
    public function documentRequests()
    {
        return $this->hasMany(DocumentRequest::class, 'assignment_id');
    }

    public function findings()
    {
        return $this->hasMany(AuditFinding::class, 'assignment_id');
    }

    public function report()
    {
        return $this->hasOne(AuditReport::class, 'assignment_id');
    }
}
