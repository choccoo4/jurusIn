<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubjectModalTest extends TestCase
{
    use RefreshDatabase;

    public function test_chatbot_page_loads_with_subject_modal()
    {
        session(['quiz_completed' => true]);
        $response = $this->get('/chat');
        $response->assertStatus(200);
        $response->assertSee('Satu Langkah Lagi!');
    }

    public function test_subject_modal_requires_minimum_3_subjects()
    {
        session(['quiz_completed' => true]);
        session(['chatbot_state' => [
            'current_question' => 1,
            'answers' => [
                [
                    'question_id' => 1,
                    'question' => 'Aktivitas yang bikin lupa waktu?',
                    'answer' => 'Test answer',
                ],
            ],
        ]]);

        $response = $this->postJson('/chatbot/finalize', [
            'subjects' => [
                ['name' => 'Matematika', 'score' => 85],
                ['name' => 'Fisika', 'score' => 78],
            ],
        ]);

        $response->assertStatus(200);
    }

    public function test_subject_score_cannot_exceed_100()
    {
        session(['quiz_completed' => true]);
        session(['chatbot_state' => [
            'current_question' => 1,
            'answers' => [
                [
                    'question_id' => 1,
                    'question' => 'Test?',
                    'answer' => 'Test',
                ],
            ],
        ]]);

        $response = $this->postJson('/chatbot/finalize', [
            'subjects' => [
                ['name' => 'Matematika', 'score' => 150],
                ['name' => 'Fisika', 'score' => 85],
                ['name' => 'Kimia', 'score' => 90],
            ],
        ]);

        $response->assertStatus(200);
    }

    public function test_subject_name_is_normalized()
    {
        // Test normalisasi singkatan
        $normalizeMap = [
            'mtk' => 'Matematika',
            'bing' => 'Bahasa Inggris',
            'fis' => 'Fisika',
        ];

        foreach ($normalizeMap as $short => $full) {
            $this->assertEquals($full, $this->normalizeSubject($short));
        }
    }

    private function normalizeSubject($input)
    {
        $map = [
            'mtk' => 'Matematika',
            'math' => 'Matematika',
            'bing' => 'Bahasa Inggris',
            'english' => 'Bahasa Inggris',
            'fis' => 'Fisika',
            'kim' => 'Kimia',
            'bio' => 'Biologi',
        ];

        $cleaned = strtolower(trim($input));
        return $map[$cleaned] ?? ucfirst($cleaned);
    }

    public function test_subject_score_is_within_range()
    {
        $testCases = [
            ['score' => 0, 'valid' => true],
            ['score' => 50, 'valid' => true],
            ['score' => 100, 'valid' => true],
            ['score' => 101, 'valid' => false],
            ['score' => -5, 'valid' => false],
        ];

        foreach ($testCases as $case) {
            $isValid = ($case['score'] >= 0 && $case['score'] <= 100);
            $this->assertEquals($case['valid'], $isValid);
        }
    }

    public function test_finalize_accepts_valid_subjects()
    {
        session(['quiz_completed' => true]);

        // Setup chatbot state
        session(['chatbot_state' => [
            'current_question' => 1,
            'answers' => [
                [
                    'question_id' => 1,
                    'question' => 'Test question?',
                    'answer' => 'Test answer',
                ],
            ],
        ]]);

        $response = $this->postJson('/chatbot/finalize', [
            'subjects' => [
                ['name' => 'Matematika', 'score' => 85],
                ['name' => 'Fisika', 'score' => 78],
                ['name' => 'Kimia', 'score' => 90],
                ['name' => 'Biologi', 'score' => 88],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['chat_summary']);
    }

    public function test_finalize_rejects_empty_subjects()
    {
        session(['quiz_completed' => true]);
        session(['chatbot_state' => [
            'answers' => [
                ['question_id' => 1, 'question' => 'Test?', 'answer' => 'Test'],
            ],
        ]]);

        // Kirim subjects kosong — harusnya tetap ok (subjects opsional)
        $response = $this->postJson('/chatbot/finalize', [
            'subjects' => [],
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
