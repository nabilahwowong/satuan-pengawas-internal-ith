<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Relasi ke temuan audit (AuditFinding)
     */
    public function finding()
    {
        return $this->belongsTo(AuditFinding::class, 'finding_id');
    }

    /**
     * Relasi ke penugasan audit (melalui AuditFinding)
     */
    public function assignment()
    {
        return $this->hasOneThrough(
            AuditAssignment::class,
            AuditFinding::class,
            'id',                   // Foreign key di audit_findings (referensi ke rekomendasi)
            'id',                   // Foreign key di audit_assignments
            'audit_finding_id',     // Local key di recommendations
            'audit_assignment_id'   // Local key di audit_findings
        );
    }

    /**
     * Relasi ke berkas tindak lanjut auditee
     */
    public function followUps()
    {
        return $this->hasMany(FollowUpSubmission::class, 'recommendation_id');
    }
}
