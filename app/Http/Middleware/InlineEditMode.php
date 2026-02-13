<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware для включения режима inline-редактирования контента
 */
class InlineEditMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Проверяем, авторизован ли пользователь и является ли он администратором
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }

        // Устанавливаем флаг режима редактирования в request
        $request->attributes->set('inline_edit_mode', true);
        
        // Передаем флаг в view для всех дальнейших отображений
        view()->share('inlineEditMode', true);

        return $next($request);
    }
}
