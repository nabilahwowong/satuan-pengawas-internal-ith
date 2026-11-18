<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentSubmission extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Tambahkan relasi ini agar method 'submitter' dikenali
    public function submitter()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function request()
    {
        return $this->belongsTo(DocumentRequest::class, 'document_request_id');
    }
}
