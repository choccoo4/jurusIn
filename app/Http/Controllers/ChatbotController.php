<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Services\ChatbotValidationService;

class ChatbotController extends Controller
{
    private ChatbotValidationService $validator;

    public function __construct(ChatbotValidationService $validator)
    {
        $this->validator = $validator;
    }

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
            'question_id' => 'required|integer|min:1|max:5',
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
            $updatedAnswers = $result['answers'] ?? $sessionState['answers'];

            session(['chatbot_state' => [
                'current_question' => $result['current_question'] ?? $request->question_id + 1,
                'answers' => $updatedAnswers,
            ]]);

            if ($result['completed']) {
                $chatProfileText = $this->validator->generateChatProfileText($updatedAnswers);
                $result['chat_profile_text'] = $chatProfileText;
                $result['answers'] = $updatedAnswers;
            }
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
        $profileText = $request->profile_text ?? '';

        if (empty($answers)) {
            return response()->json(['error' => 'No answers found'], 400);
        }

        // Pakai jawaban mentah (nanti pakai cleaned chat)
        $rawAnswers = array_column($answers, 'answer');
        $chatSummary = implode("\n", array_map(function ($a) {
            return "- {$a}";
        }, $rawAnswers));

        $inputProfileText = $profileText . "\n\nDari percakapan chatbot:\n" . $chatSummary;

        session()->forget('chatbot_state');

        return response()->json([
            'success' => true,
            'input_profile_text' => $inputProfileText,
            'chat_summary' => $chatSummary,
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

        $session = DB::table('questionnaire_sessions')
            ->where('session_id', $request->session_id)
            ->first();

        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        DB::table('recommendations')->insert([
            'questionnaire_session_id' => $session->id,
            'input_profile_text' => $request->input_profile_text,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        session(['chatbot_completed' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Input profile text saved to database',
        ]);
    }
}