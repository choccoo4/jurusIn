<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChatbotMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('chatbot_completed')) {
            return redirect()->route('quiz.chat')
                ->with('message', 'Silahkan selesaikan chatbot terlebih dahulu!');
        }

        return $next($request);
    }
}
