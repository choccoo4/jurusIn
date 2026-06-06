<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckQuestionnaire
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!session('quiz_completed')) {
            return redirect()->route('questionnaire')
                ->with('message', 'Silahkan isi kuisioner terlebih dahulu!');
        }

        return $next($request);
    }
}
