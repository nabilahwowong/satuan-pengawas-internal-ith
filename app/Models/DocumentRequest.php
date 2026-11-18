<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Agar pemanggilan $doc->request_title di Blade otomatis membaca kolom document_name
     */
    public function getRequestTitleAttribute()
    {
        return $this->attributes['document_name'] ?? null;
    }

    public function assignment()
    {
        return $this->belongsTo(AuditAssignment::class, 'assignment_id');
    }

    public function submissions()
    {
        return $this->hasMany(DocumentSubmission::class, 'document_request_id');
    }
}
