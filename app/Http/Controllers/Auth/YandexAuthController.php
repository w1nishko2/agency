<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class YandexAuthController extends Controller
{
    /**
     * Перенаправление на страницу авторизации Яндекса
     */
    public function redirectToYandex()
    {
        return Socialite::driver('yandex')->redirect();
    }

    /**
     * Обработка callback от Яндекса
     */
    public function handleYandexCallback()
    {
        try {
            $yandexUser = Socialite::driver('yandex')->user();
            
            // Проверяем, что у пользователя есть email
            if (!$yandexUser->email) {
                return redirect('/login')->withErrors(['error' => 'Не удалось получить email от Яндекс. Проверьте настройки аккаунта.']);
            }
            
            // Ищем пользователя по yandex_id
            $user = User::where('yandex_id', $yandexUser->id)->first();
            
            if ($user) {
                // Пользователь уже авторизовывался через Яндекс
                // Обновляем аватар и имя, если изменились
                $updated = false;
                
                if ($yandexUser->avatar && $user->avatar !== $yandexUser->avatar) {
                    $user->avatar = $yandexUser->avatar;
                    $updated = true;
                }
                
                if ($yandexUser->name && $user->name !== $yandexUser->name) {
                    $user->name = $yandexUser->name;
                    $updated = true;
                }
                
                if ($updated) {
                    $user->save();
                }
            } else {
                // Проверяем, есть ли пользователь с таким email
                $user = User::where('email', $yandexUser->email)->first();
                
                if ($user) {
                    // Привязываем Яндекс аккаунт к существующему пользователю
                    $user->yandex_id = $yandexUser->id;
                    
                    if ($yandexUser->avatar) {
                        $user->avatar = $yandexUser->avatar;
                    }
                    
                    $user->save();
                } else {
                    // Создаём нового пользователя
                    $user = User::create([
                        'name' => $yandexUser->name ?? $yandexUser->nickname ?? 'Пользователь Яндекс',
                        'email' => $yandexUser->email,
                        'yandex_id' => $yandexUser->id,
                        'avatar' => $yandexUser->avatar,
                        'password' => null, // Пароль не нужен для OAuth пользователей
                        'role' => 'user', // Роль по умолчанию
                    ]);
                }
            }
            
            // Авторизуем пользователя (remember = true для длительной сессии)
            Auth::login($user, true);
            
            // Перенаправляем на страницу, куда пользователь пытался попасть, или на главную
            return redirect()->intended('/');
            
        } catch (\Exception $e) {
            // Логируем ошибку для отладки
            \Log::error('Yandex OAuth Error: ' . $e->getMessage());
            
            return redirect('/login')->withErrors(['error' => 'Ошибка авторизации через Яндекс. Попробуйте снова.']);
        }
    }
}
