<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ChatbotMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('ChatbotMiddleware CHECK', [
            'quiz_completed' => session('quiz_completed'),
            'chatbot_completed' => session('chatbot_completed'),
        ]);

        if (!session('quiz_completed')) {
            return redirect()->route('questionnaire')
                ->with('message', 'Silahkan isi kuisoner terlebih dahulu!');
        }

        if (!session('chatbot_completed')) {
            return redirect()->route('quiz.chat')
                ->with('message', 'Silahkan selesaikan chatbot terlebih dahulu!');
        }

        return $next($request);
    }
}
