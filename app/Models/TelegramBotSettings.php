<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBotSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_token',
        'bot_username',
        'admin_telegram_id',
        'webhook_url',
        'is_active',
        'welcome_message',
        'settings',
        'last_webhook_check',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
        'last_webhook_check' => 'datetime',
    ];

    /**
     * Получить текущие настройки бота
     */
    public static function current()
    {
        return static::first() ?? new static();
    }

    /**
     * Проверить, настроен ли бот
     */
    public function isConfigured(): bool
    {
        return !empty($this->bot_token) && !empty($this->bot_username);
    }

    /**
     * Проверить подключение к Telegram API
     */
    public function testConnection(): array
    {
        if (empty($this->bot_token)) {
            return [
                'success' => false,
                'message' => 'Токен бота не указан'
            ];
        }

        try {
            $response = \Illuminate\Support\Facades\Http::get("https://api.telegram.org/bot{$this->bot_token}/getMe");
            
            if ($response->successful() && $response->json('ok')) {
                $botInfo = $response->json('result');
                return [
                    'success' => true,
                    'message' => 'Подключение успешно!',
                    'bot_info' => $botInfo
                ];
            }

            return [
                'success' => false,
                'message' => 'Ошибка подключения: ' . ($response->json('description') ?? 'Неизвестная ошибка')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Ошибка: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Установить webhook
     */
    public function setWebhook(string $url): array
    {
        if (empty($this->bot_token)) {
            return [
                'success' => false,
                'message' => 'Токен бота не указан'
            ];
        }

        try {
            $response = \Illuminate\Support\Facades\Http::post("https://api.telegram.org/bot{$this->bot_token}/setWebhook", [
                'url' => $url
            ]);
            
            if ($response->successful() && $response->json('ok')) {
                $this->webhook_url = $url;
                $this->last_webhook_check = now();
                $this->save();

                return [
                    'success' => true,
                    'message' => 'Webhook установлен успешно!'
                ];
            }

            return [
                'success' => false,
                'message' => 'Ошибка установки webhook: ' . ($response->json('description') ?? 'Неизвестная ошибка')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Ошибка: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Получить информацию о webhook
     */
    public function getWebhookInfo(): array
    {
        if (empty($this->bot_token)) {
            return [
                'success' => false,
                'message' => 'Токен бота не указан'
            ];
        }

        try {
            $response = \Illuminate\Support\Facades\Http::get("https://api.telegram.org/bot{$this->bot_token}/getWebhookInfo");
            
            if ($response->successful() && $response->json('ok')) {
                $this->last_webhook_check = now();
                $this->save();

                return [
                    'success' => true,
                    'webhook_info' => $response->json('result')
                ];
            }

            return [
                'success' => false,
                'message' => 'Ошибка получения информации: ' . ($response->json('description') ?? 'Неизвестная ошибка')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Ошибка: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Удалить webhook
     */
    public function deleteWebhook(): array
    {
        if (empty($this->bot_token)) {
            return [
                'success' => false,
                'message' => 'Токен бота не указан'
            ];
        }

        try {
            $response = \Illuminate\Support\Facades\Http::post("https://api.telegram.org/bot{$this->bot_token}/deleteWebhook");
            
            if ($response->successful() && $response->json('ok')) {
                $this->webhook_url = null;
                $this->last_webhook_check = now();
                $this->save();

                return [
                    'success' => true,
                    'message' => 'Webhook удален успешно!'
                ];
            }

            return [
                'success' => false,
                'message' => 'Ошибка удаления webhook: ' . ($response->json('description') ?? 'Неизвестная ошибка')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Ошибка: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Отправить сообщение администратору
     */
    public function sendMessageToAdmin(string $message, array $options = []): array
    {
        if (empty($this->bot_token)) {
            return [
                'success' => false,
                'message' => 'Токен бота не указан'
            ];
        }

        if (empty($this->admin_telegram_id)) {
            return [
                'success' => false,
                'message' => 'ID администратора не указан'
            ];
        }

        try {
            $data = array_merge([
                'chat_id' => $this->admin_telegram_id,
                'text' => $message,
                'parse_mode' => 'HTML'
            ], $options);

            $response = \Illuminate\Support\Facades\Http::post(
                "https://api.telegram.org/bot{$this->bot_token}/sendMessage", 
                $data
            );
            
            if ($response->successful() && $response->json('ok')) {
                return [
                    'success' => true,
                    'message' => 'Сообщение отправлено администратору'
                ];
            }

            return [
                'success' => false,
                'message' => 'Ошибка отправки: ' . ($response->json('description') ?? 'Неизвестная ошибка')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Ошибка: ' . $e->getMessage()
            ];
        }
    }
}
