<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Services\ChatbotValidationService;
use App\Services\ChatbotService;

class ChatbotController extends Controller
{
    public function __construct(
        private readonly ChatbotValidationService $validator,
        private readonly ChatbotService $chatbotService,
    ) {}

    public function index(): View
    {
        return view('pages.chatbot');
    }

    /**
     * Process single answer — stateful.
     */
    public function processAnswer(Request $request)
    {
        $request->validate([
            'question_id' => 'required|integer|min:1|max:8',
            'answer' => 'required|string',
        ]);

        $sessionState = session('chatbot_state', [
            'current_question' => 1,
            'answers' => [],
        ]);

        $result = $this->validator->processAnswer(
            $request->question_id,
            $request->answer,
            $sessionState
        );

        if ($result['valid']) {
            session(['chatbot_state' => [
                'current_question' => $result['current_question'] ?? $request->question_id + 1,
                'answers' => $result['answers'] ?? $sessionState['answers'],
            ]]);
        }

        return response()->json($result);
    }

    /**
     * Finalize — combine RIASEC + Chatbot.
     */
    public function finalize(Request $request)
    {
        $sessionState = session('chatbot_state', []);
        $answers = $sessionState['answers'] ?? [];
        $subjects = $request->subjects ?? [];  

        if (empty($answers)) {
            return response()->json(['error' => 'No answers found'], 400);
        }

        // menggabungkan jawaban chatbot + mapel
        $chatProfileText = $this->validator->generateChatProfileText($answers);
        if (!empty($subjects)) {
            $subjectText = implode(', ', array_map(function ($s) {
                return "{$s['name']} (nilai: {$s['score']})";
            }, $subjects));
            $chatProfileText .= "\n\nMata pelajaran favorit: " . $subjectText . '.';
        }

        session()->forget('chatbot_state');

        return response()->json([
            'success' => true,
            'chat_summary' => $chatProfileText,
            'total_answers' => count($answers),
        ]);
    }

    /**
     * Get first question.
     */
    public function startChat()
    {
        session()->forget('chatbot_state');
        session(['chatbot_state' => [
            'current_question' => 1,
            'answers' => [],
        ]]);

        return response()->json([
            'first_question' => $this->validator->getFirstQuestion(),
        ]);
    }

    public function saveToDatabase(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'input_profile_text' => 'required|string',
        ]);

        try {
            $recommendation = $this->chatbotService->saveRecommendation(
                $request->session_id,
                $request->input_profile_text,
            );

            session(['chatbot_completed' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil disimpan',
                'recommendation_id' => $recommendation->id,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal menyimpan profil: ' . $e->getMessage(),
            ], 500);
        }
    }
}
