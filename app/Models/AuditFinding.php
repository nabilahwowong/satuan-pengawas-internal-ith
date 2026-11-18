<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditFinding extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function assignment()
    {
        return $this->belongsTo(AuditAssignment::class, 'assignment_id');
    }

    public function responses()
    {
        return $this->hasMany(FindingResponse::class, 'finding_id');
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class, 'finding_id');
    }
}
