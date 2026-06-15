<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class recommendationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('recommendation_completed')) {
            return redirect('results')
                ->with('message', 'Kamu sudah mendapatkan rekomendasi. Untuk tes ulang, silahkan ulangi tes.');
        }
        return $next($request);
    }
}
