<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TelegramBotSettings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $update = $request->all();
        
        Log::info('Telegram webhook received', ['update' => $update]);
        
        // Проверяем наличие сообщения
        if (!isset($update['message'])) {
            return response()->json(['ok' => true]);
        }
        
        $message = $update['message'];
        $chatId = $message['chat']['id'] ?? null;
        $text = $message['text'] ?? '';
        $username = $message['from']['username'] ?? null;
        
        if (!$chatId) {
            return response()->json(['ok' => true]);
        }
        
        $botSettings = TelegramBotSettings::current();
        
        if (!$botSettings->isConfigured() || !$botSettings->is_active) {
            Log::warning('Bot not configured or inactive');
            return response()->json(['ok' => true]);
        }
        
        // Обработка команд
        if (strpos($text, '/start') === 0) {
            $this->handleStart($botSettings, $chatId, $username);
        } elseif (strpos($text, '/bind') === 0) {
            $this->handleBind($botSettings, $chatId, $text, $username);
        } elseif (strpos($text, '/help') === 0) {
            $this->handleHelp($botSettings, $chatId);
        } else {
            // Неизвестная команда
            $this->sendMessage($botSettings, $chatId, 
                "Неизвестная команда. Используйте /help для списка команд.");
        }
        
        return response()->json(['ok' => true]);
    }
    
    /**
     * Команда /start
     */
    private function handleStart($botSettings, $chatId, $username)
    {
        $message = $botSettings->welcome_message ?: 
            "👋 Добро пожаловать в Golden Models!\n\n" .
            "Этот бот позволяет получать уведомления о новых кастингах.\n\n" .
            "Доступные команды:\n" .
            "/bind КЛЮЧ - привязать аккаунт\n" .
            "/help - помощь";
        
        $this->sendMessage($botSettings, $chatId, $message);
    }
    
    /**
     * Команда /bind КЛЮЧ
     */
    private function handleBind($botSettings, $chatId, $text, $username)
    {
        // Извлекаем ключ из команды
        $parts = explode(' ', trim($text));
        
        if (count($parts) < 2) {
            $this->sendMessage($botSettings, $chatId, 
                "❌ Неверный формат команды.\n\nИспользуйте: /bind ВАШ_КЛЮЧ\n\nПолучите ключ в личном кабинете на сайте.");
            return;
        }
        
        $key = strtoupper(trim($parts[1]));
        
        // Ищем пользователя с таким ключом
        $user = User::where('telegram_bind_key', $key)
            ->where('telegram_bind_key_expires_at', '>', now())
            ->whereNull('telegram_id')
            ->first();
        
        if (!$user) {
            $this->sendMessage($botSettings, $chatId, 
                "❌ Ключ недействителен или истек.\n\nПолучите новый ключ в личном кабинете на сайте.");
            
            Log::warning('Invalid or expired bind key', [
                'key' => $key,
                'chat_id' => $chatId
            ]);
            return;
        }
        
        // Привязываем аккаунт
        $user->telegram_id = $chatId;
        $user->telegram_username = $username;
        $user->telegram_bind_key = null;
        $user->telegram_bind_key_expires_at = null;
        $user->save();
        
        $this->sendMessage($botSettings, $chatId, 
            "✅ Аккаунт успешно привязан!\n\n" .
            "Теперь вы будете получать уведомления о новых кастингах и важных событиях.");
        
        Log::info('Telegram account bound', [
            'user_id' => $user->id,
            'telegram_id' => $chatId,
            'username' => $username
        ]);
    }
    
    /**
     * Команда /help
     */
    private function handleHelp($botSettings, $chatId)
    {
        $message = "ℹ️ <b>Помощь по боту Golden Models</b>\n\n" .
            "<b>Доступные команды:</b>\n\n" .
            "/start - Начать работу с ботом\n" .
            "/bind КЛЮЧ - Привязать ваш аккаунт с сайта\n" .
            "/help - Показать эту справку\n\n" .
            "<b>Как привязать аккаунт:</b>\n" .
            "1. Зайдите в личный кабинет на сайте\n" .
            "2. Нажмите \"Получить ключ для привязки\"\n" .
            "3. Отправьте команду: /bind ВАШ_КЛЮЧ\n\n" .
            "После привязки вы будете получать уведомления о кастингах!";
        
        $this->sendMessage($botSettings, $chatId, $message);
    }
    
    /**
     * Отправка сообщения через Telegram API
     */
    private function sendMessage($botSettings, $chatId, $text)
    {
        try {
            $response = \Illuminate\Support\Facades\Http::post(
                "https://api.telegram.org/bot{$botSettings->bot_token}/sendMessage",
                [
                    'chat_id' => $chatId,
                    'text' => $text,
                    'parse_mode' => 'HTML'
                ]
            );
            
            if (!$response->successful() || !$response->json('ok')) {
                Log::error('Failed to send Telegram message', [
                    'chat_id' => $chatId,
                    'error' => $response->json('description')
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception sending Telegram message', [
                'chat_id' => $chatId,
                'error' => $e->getMessage()
            ]);
        }
    }
}
