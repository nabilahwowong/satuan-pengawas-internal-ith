<?php

namespace Database\Seeders;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;

class CampusPublicSurveySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $survey = Survey::create([
            'created_by' => $admin ? $admin->id : 1,
            'title' => 'Survei Persepsi Publik & Transparansi Tata Kelola Kampus ITH',
            'description' => 'Kuesioner ini mengukur pandangan masyarakat, sivitas akademika, dan mitra terhadap transparansi, integritas, serta mutu lingkungan akademik kampus ITH.',
            'is_active' => true,
        ]);

        $questions = [
            [
                'question_text' => 'Bagaimana penilaian Anda terhadap kemudahan akses informasi publik dan keterbukaan layanan di kampus ITH?',
                'question_type' => 'rating_5',
                'order_num' => 1,
            ],
            [
                'question_text' => 'Seberapa baik komitmen kampus ITH dalam menegakkan zona integritas, transparansi, dan pencegahan kecurangan (anti-fraud)?',
                'question_type' => 'rating_5',
                'order_num' => 2,
            ],
            [
                'question_text' => 'Bagaimana mutu fasilitas akademik, riset teknologi, dan sarana umum di kampus menurut Anda?',
                'question_type' => 'rating_5',
                'order_num' => 3,
            ],
            [
                'question_text' => 'Apakah proses birokrasi dan layanan administrasi kampus berlangsung cepat dan tidak berbelit-belit?',
                'question_type' => 'rating_5',
                'order_num' => 4,
            ],
            [
                'question_text' => 'Saran atau masukan konstruktif untuk kemajuan dan integritas kampus ITH ke depan:',
                'question_type' => 'text',
                'order_num' => 5,
            ],
        ];

        foreach ($questions as $q) {
            $survey->questions()->create($q);
        }
    }
}
