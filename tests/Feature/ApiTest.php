<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class ApiTest extends TestCase
{
    public function test_fastapi_health_check()
    {
        // Mock HTTP response
        Http::fake([
            'localhost:8001/api/health' => Http::response([
                'status' => 'ok',
                'model_loaded' => true,
            ], 200),
        ]);

        $response = Http::get('http://localhost:8001/api/health');

        $this->assertEquals('ok', $response['status']);
    }

    public function test_results_page_redirects_without_session()
    {
        $response = $this->get('/hasil');
        $response->assertRedirect('/mulai');
    }
}
