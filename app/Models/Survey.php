<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class, 'survey_id')->orderBy('order_num', 'asc');
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class, 'survey_id');
    }
}
