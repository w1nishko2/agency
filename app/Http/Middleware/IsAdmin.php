<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function __invoke(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Необходима авторизация');
        }

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }

        return $next($request);
    }
}
