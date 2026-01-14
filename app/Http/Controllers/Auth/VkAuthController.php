<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class VkAuthController extends Controller
{
    /**
     * Перенаправление на страницу авторизации ВКонтакте
     */
    public function redirectToVk()
    {
        return Socialite::driver('vkontakte')->redirect();
    }

    /**
     * Обработка callback от ВКонтакте
     */
    public function handleVkCallback()
    {
        try {
            $vkUser = Socialite::driver('vkontakte')->user();
            
            // Проверяем, что у пользователя есть email
            if (!$vkUser->email) {
                return redirect('/login')->withErrors(['error' => 'Не удалось получить email от ВКонтакте. Проверьте настройки аккаунта.']);
            }
            
            // Ищем пользователя по vk_id
            $user = User::where('vk_id', $vkUser->id)->first();
            
            if ($user) {
                // Пользователь уже авторизовывался через VK
                // Обновляем аватар и имя, если изменились
                $updated = false;
                
                if ($vkUser->avatar && $user->avatar !== $vkUser->avatar) {
                    $user->avatar = $vkUser->avatar;
                    $updated = true;
                }
                
                if ($vkUser->name && $user->name !== $vkUser->name) {
                    $user->name = $vkUser->name;
                    $updated = true;
                }
                
                if ($updated) {
                    $user->save();
                }
            } else {
                // Проверяем, есть ли пользователь с таким email
                $user = User::where('email', $vkUser->email)->first();
                
                if ($user) {
                    // Привязываем VK аккаунт к существующему пользователю
                    $user->vk_id = $vkUser->id;
                    
                    if ($vkUser->avatar) {
                        $user->avatar = $vkUser->avatar;
                    }
                    
                    $user->save();
                } else {
                    // Создаём нового пользователя
                    $user = User::create([
                        'name' => $vkUser->name ?? $vkUser->nickname ?? 'Пользователь ВКонтакте',
                        'email' => $vkUser->email,
                        'vk_id' => $vkUser->id,
                        'avatar' => $vkUser->avatar,
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
            \Log::error('VK OAuth Error: ' . $e->getMessage());
            
            return redirect('/login')->withErrors(['error' => 'Ошибка авторизации через ВКонтакте. Попробуйте снова.']);
        }
    }
}
