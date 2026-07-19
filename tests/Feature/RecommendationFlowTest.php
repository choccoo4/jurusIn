<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class RecommendationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_fastapi_recommend_endpoint_returns_valid_response()
    {
        // Mock FastAPI response
        Http::fake([
            'localhost:8001/api/health' => Http::response([
                'status' => 'ok',
                'model_loaded' => true,
            ], 200),
            'localhost:8001/api/recommend' => Http::response([
                'success' => true,
                'recommendations' => [
                    [
                        'rank' => 1,
                        'jurusan' => 'Teknik Informatika',
                        'bidang' => 'Komputer dan Teknologi',
                        'final_score' => 0.85,
                        'chatbot_similarity' => 0.82,
                        'riasec_similarity' => 0.90,
                        'confidence' => 'Sangat Cocok',
                        'matched_keywords' => 'coding, algoritma',
                        'reasoning' => 'Cocok karena minat kamu...',
                    ],
                ],
            ], 200),
        ]);

        $health = Http::get('http://localhost:8001/api/health');
        $this->assertEquals('ok', $health['status']);

        $response = Http::post('http://localhost:8001/api/recommend', [
            'riasec_scores' => ['R' => 70, 'I' => 80, 'A' => 50, 'S' => 60, 'E' => 40, 'C' => 30],
            'chatbot_answers' => ['saya suka coding'],
            'top_k' => 3,
        ]);

        $this->assertTrue($response['success']);
        $this->assertCount(1, $response['recommendations']);
    }

    public function test_results_page_requires_session()
    {
        $response = $this->get('/hasil');
        $response->assertRedirect('/mulai');
    }
}
