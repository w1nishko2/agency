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
        return Socialite::driver('vkid')->redirect();
    }

    /**
     * Обработка callback от ВКонтакте
     */
    public function handleVkCallback()
    {
        try {
            // Логируем входящие параметры
            \Log::info('VK Callback received', [
                'params' => request()->all(),
                'method' => request()->method()
            ]);
            
            // Проверяем, есть ли access_token от VK ID SDK
            if (!request()->has('access_token')) {
                \Log::error('VK OAuth Error: No access_token in callback');
                return redirect('/login')->withErrors(['error' => 'Не получен токен авторизации от VK ID.']);
            }
            
            $accessToken = request('access_token');
            
            // Получаем информацию о пользователе через API
            \Log::info('Getting VK user info with token');
            
            $response = \Http::post('https://id.vk.com/oauth2/user_info', [
                'access_token' => $accessToken,
                'client_id' => config('services.vkid.client_id'),
            ]);
            
            if (!$response->successful()) {
                \Log::error('VK API Error', ['response' => $response->json()]);
                return redirect('/login')->withErrors(['error' => 'Ошибка получения данных от VK ID.']);
            }
            
            $userData = $response->json();
            \Log::info('VK User data received', $userData);
            
            if (!isset($userData['user'])) {
                \Log::error('VK OAuth Error: Invalid user data structure');
                return redirect('/login')->withErrors(['error' => 'Некорректные данные от VK ID.']);
            }
            
            $vkUser = $userData['user'];
            
            // Генерируем email, если его нет (используем VK ID)
            $email = $vkUser['email'] ?? null;
            if (empty($email)) {
                // Создаем уникальный email на основе VK ID
                $email = 'vk_' . $vkUser['user_id'] . '@vkid.local';
                \Log::info('VK OAuth: Generated email for user without email', ['email' => $email]);
            }
            
            // Обрабатываем avatar - обрезаем до 255 символов или берем только базовый URL
            $avatar = null;
            if (!empty($vkUser['avatar'])) {
                // Убираем query параметры, оставляем только базовый URL
                $avatarUrl = $vkUser['avatar'];
                if (strpos($avatarUrl, '?') !== false) {
                    $avatarUrl = substr($avatarUrl, 0, strpos($avatarUrl, '?'));
                }
                // Обрезаем до 255 символов на всякий случай
                $avatar = substr($avatarUrl, 0, 255);
            }
            
            // Ищем пользователя по vk_id
            $user = User::where('vk_id', $vkUser['user_id'])->first();
            
            if ($user) {
                // Пользователь уже авторизовывался через VK
                // Обновляем аватар и имя, если изменились
                $updated = false;
                
                if ($avatar && $user->avatar !== $avatar) {
                    $user->avatar = $avatar;
                    $updated = true;
                }
                
                $fullName = trim(($vkUser['first_name'] ?? '') . ' ' . ($vkUser['last_name'] ?? ''));
                if ($fullName && $user->name !== $fullName) {
                    $user->name = $fullName;
                    $updated = true;
                }
                
                if ($updated) {
                    $user->save();
                }
            } else {
                // Проверяем, есть ли пользователь с таким email (только если это реальный email, не сгенерированный)
                if (!str_contains($email, '@vkid.local')) {
                    $user = User::where('email', $email)->first();
                } else {
                    $user = null;
                }
                
                if ($user) {
                    // Привязываем VK аккаунт к существующему пользователю
                    $user->vk_id = $vkUser['user_id'];
                    
                    if ($avatar) {
                        $user->avatar = $avatar;
                    }
                    
                    $user->save();
                } else {
                    // Создаём нового пользователя
                    $fullName = trim(($vkUser['first_name'] ?? '') . ' ' . ($vkUser['last_name'] ?? ''));
                    
                    $user = User::create([
                        'name' => $fullName ?: 'Пользователь ВКонтакте',
                        'email' => $email,
                        'vk_id' => $vkUser['user_id'],
                        'avatar' => $avatar,
                        'password' => null,
                        'role' => 'user',
                    ]);
                }
            }
            
            // Авторизуем пользователя
            Auth::login($user, true);
            
            \Log::info('VK OAuth Success: User logged in', ['user_id' => $user->id]);
            
            // Перенаправляем на главную
            return redirect('/');
            
        } catch (\Exception $e) {
            // Логируем детальную ошибку
            \Log::error('VK OAuth Error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
                'params' => request()->all()
            ]);
            
            return redirect('/login')->withErrors(['error' => 'Ошибка авторизации через ВКонтакте: ' . $e->getMessage()]);
        }
    }
}
