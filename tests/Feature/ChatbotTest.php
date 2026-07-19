<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatbotTest extends TestCase
{
    use RefreshDatabase;

    public function test_chatbot_start_resets_session()
    {
        //Set session biar lolos middleware
        session(['quiz_completed' => true]);

        $response = $this->get('/chatbot/start');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'first_question' => ['id', 'text']
            ]);
    }

    public function test_chatbot_validates_answer()
    {
        session(['quiz_completed' => true]);
        $this->get('/chatbot/start');

        $response = $this->postJson('/chatbot/process', [
            'question_id' => 1,
            'answer' => 'saya suka coding dan membuat aplikasi',
        ]);

        $response->assertStatus(200)
            ->assertJson(['valid' => true]);
    }

    public function test_chatbot_rejects_short_answer()
    {
        session(['quiz_completed' => true]);
        $this->get('/chatbot/start');

        $response = $this->postJson('/chatbot/process', [
            'question_id' => 1,
            'answer' => 'ok',
        ]);

        $response->assertStatus(200)
            ->assertJson(['valid' => false]);
    }

    public function test_chatbot_rejects_blacklist_words()
    {
        session(['quiz_completed' => true]);
        $this->get('/chatbot/start');

        $response = $this->postJson('/chatbot/process', [
            'question_id' => 1,
            'answer' => 'gatau',
        ]);

        $response->assertStatus(200)
            ->assertJson(['valid' => false]);
    }
}
