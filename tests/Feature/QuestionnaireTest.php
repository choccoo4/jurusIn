<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class QuestionnaireTest extends TestCase
{
    use RefreshDatabase;

    private function seedQuestionnaire()
    {
        DB::table('questionnaires')->insert([
            'id' => 1,
            'title' => 'Test Questionnaire',
            'description' => 'Test description',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed 6 pertanyaan (1 per kategori)
        $categories = ['R', 'I', 'A', 'S', 'E', 'C'];
        foreach ($categories as $i => $cat) {
            Question::create([
                'questionnaire_id' => 1,
                'question_text' => "Test question {$cat}?",
                'riasec_category' => $cat,
                'riasec_weight' => 1.0,
                'order_number' => $i + 1,
            ]);
        }
    }

    // ========== VIEW ==========

    public function test_user_can_view_questionnaire_page()
    {
        $response = $this->get('/mulai');
        $response->assertStatus(200);
    }

    // ========== SAVE ==========

    public function test_user_can_save_questionnaire_answers()
    {
        $this->seedQuestionnaire();

        $response = $this->postJson('/questionnaire/save', [
            'session_id' => 'test-session-123',
            'questionnaire_id' => 1,
            'answers' => [
                ['question_id' => 1, 'value' => 4, 'category' => 'I'],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_cannot_save_duplicate_session()
    {
        $this->seedQuestionnaire();

        $this->postJson('/questionnaire/save', [
            'session_id' => 'dup-session',
            'questionnaire_id' => 1,
            'answers' => [
                ['question_id' => 1, 'value' => 4, 'category' => 'I'],
            ],
        ]);

        $response = $this->postJson('/questionnaire/save', [
            'session_id' => 'dup-session',
            'questionnaire_id' => 1,
            'answers' => [
                ['question_id' => 1, 'value' => 5, 'category' => 'I'],
            ],
        ]);

        $response->assertStatus(409);
    }

    // ========== 36 SOAL ==========

    public function test_user_can_save_all_36_questions()
    {
        $this->seedQuestionnaire();

        $answers = [];
        for ($i = 1; $i <= 6; $i++) {
            $answers[] = [
                'question_id' => $i,
                'value' => rand(1, 5),
                'category' => Question::find($i)->riasec_category,
            ];
        }

        $response = $this->postJson('/questionnaire/save', [
            'session_id' => 'test-full-session',
            'questionnaire_id' => 1,
            'answers' => $answers,
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    // ========== RIASEC SCORING ==========

    public function test_riasec_scores_are_calculated_correctly()
    {
        $this->seedQuestionnaire();

        $response = $this->postJson('/questionnaire/save', [
            'session_id' => 'score-test',
            'questionnaire_id' => 1,
            'answers' => [
                ['question_id' => 1, 'value' => 5, 'category' => 'R'],
                ['question_id' => 2, 'value' => 5, 'category' => 'I'],
                ['question_id' => 3, 'value' => 5, 'category' => 'A'],
                ['question_id' => 4, 'value' => 5, 'category' => 'S'],
                ['question_id' => 5, 'value' => 5, 'category' => 'E'],
                ['question_id' => 6, 'value' => 5, 'category' => 'C'],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'scores' => [
                    'R' => 100.0,
                    'I' => 100.0,
                    'A' => 100.0,
                    'S' => 100.0,
                    'E' => 100.0,
                    'C' => 100.0,
                ],
            ]);
    }

    // ========== PROFILE TEXT ==========

    public function test_profile_text_is_generated()
    {
        $this->seedQuestionnaire();

        $response = $this->postJson('/questionnaire/save', [
            'session_id' => 'profile-test',
            'questionnaire_id' => 1,
            'answers' => [
                ['question_id' => 1, 'value' => 4, 'category' => 'R'],
                ['question_id' => 2, 'value' => 4, 'category' => 'I'],
                ['question_id' => 3, 'value' => 4, 'category' => 'A'],
                ['question_id' => 4, 'value' => 4, 'category' => 'S'],
                ['question_id' => 5, 'value' => 4, 'category' => 'E'],
                ['question_id' => 6, 'value' => 4, 'category' => 'C'],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['profile_text']);
    }
}
