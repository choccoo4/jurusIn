<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Recommendations;
use App\Models\RecommendationDetail;
use App\Models\QuestionnaireSession;
use App\Models\Major;
use Illuminate\Support\Facades\DB;

class RiasecResultTest extends TestCase
{
    use RefreshDatabase;

    public function test_result_page_shows_riasec_traits()
    {
        // Setup
        DB::table('questionnaires')->insert([
            'id' => 1,
            'title' => 'Test',
            'description' => 'Test',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $session = QuestionnaireSession::create([
            'session_id' => 'riasec-test',
            'questionnaire_id' => 1,
            'r_score' => 70, 'i_score' => 90, 'a_score' => 50,
            's_score' => 60, 'e_score' => 40, 'c_score' => 30,
            'status' => 'completed',
        ]);

        $major = Major::create([
            'major_name' => 'Test Major',
            'field' => 'Test',
            'description' => 'Test',
        ]);

        $rec = Recommendations::create([
            'input_profile_text' => 'test',
            'questionnaire_session_id' => $session->id,
        ]);

        RecommendationDetail::create([
            'recommendation_id' => $rec->id,
            'major_id' => $major->id,
            'similarity_score' => 0.85,
            'rank' => 1,
            'reasoning' => 'Test',
        ]);

        session([
            'quiz_completed' => true,
            'chatbot_completed' => true,
            'questionnaire_session_id' => $session->id,
        ]);

        $response = $this->get('/hasil');
        $response->assertStatus(200);
        $response->assertSee('Profil Kepribadianmu');
    }
}