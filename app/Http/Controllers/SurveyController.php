<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    // Halaman pengisian survei publik
    public function index()
    {
        $surveys = Survey::where('is_active', true)->with('questions')->get();
        return view('surveys.index', compact('surveys'));
    }

    // Menyimpan respon pengunjung website
    public function store(Request $request, Survey $survey)
    {
        $request->validate([
            'respondent_name' => ['nullable', 'string', 'max:255'],
            'visitor_category' => ['required', 'string', 'max:100'], // Mahasiswa, Dosen, Mitra, Publik
            'answers' => ['required', 'array'],
            'answers.*' => ['required'],
        ]);

        DB::transaction(function () use ($request, $survey) {
            $response = SurveyResponse::create([
                'survey_id' => $survey->id,
                'respondent_name' => $request->input('respondent_name') ?: 'Anonim',
                'respondent_unit' => $request->input('visitor_category'), // Disimpan di kolom respondent_unit
            ]);

            foreach ($request->input('answers') as $questionId => $value) {
                SurveyAnswer::create([
                    'response_id' => $response->id,
                    'question_id' => $questionId,
                    'answer_value' => is_array($value) ? implode(', ', $value) : (string)$value,
                ]);
            }
        });

        return back()->with('success', 'Terima kasih atas aspirasi dan penilaian Anda terhadap tata kelola kampus ITH.');
    }
}
