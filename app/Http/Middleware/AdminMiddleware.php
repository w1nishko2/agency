<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Проверка роли администратора
        // Вариант 1: Проверка по email
        $adminEmails = ['admin@goldenmodels.ru', 'info@goldenmodels.ru'];
        if (!in_array(Auth::user()->email, $adminEmails)) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }

        // Вариант 2: Если будет добавлена колонка is_admin в таблицу users
        // if (!Auth::user()->is_admin) {
        //     abort(403, 'Доступ запрещен.');
        // }

        return $next($request);
    }
}
