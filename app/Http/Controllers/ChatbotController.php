<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request; 
use App\Http\Traits\ApiResponse;
use App\Services\ChatbotValidationService;
use App\Http\Requests\SaveChatbotRequest;
use App\Services\ChatbotService;

class ChatbotController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ChatbotValidationService $validator,
        private readonly ChatbotService $chatbotService,
    ) {}


    public function index(): View
    {
        return view('pages.chatbot');
    }

    public function processAnswer(Request $request)
    {
        $result = $this->validator->processAnswer(
            $request->question_id,
            $request->answer,
            session('chatbot_state', [])
        );
        return response()->json($result);
    }

    public function startChat()
    {
        return response()->json([
            'first_question' => $this->validator->getFirstQuestion(),
        ]);
    }

    public function save(SaveChatbotRequest $request): JsonResponse
    {
        try {
            $recommendation = DB::transaction(
                fn() => $this->chatbotService->saveRecommendation(
                    $request->validated('session_id'),
                    $request->validated('chat_data'),
                )
            );

            return $this->successResponse([
                'session_id'         => $request->validated('session_id'),
                'recommendation_id'  => $recommendation->id,
                'input_profile_text' => $recommendation->input_profile_text,
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), 404);
        } catch (Throwable $e) {
            report($e);
            return $this->errorResponse('Terjadi kesalahan pada server. Silakan coba lagi.', 500);
        }
    }
}
