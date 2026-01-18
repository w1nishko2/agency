<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TelegramBotSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TelegramBotController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Отображение страницы настроек Telegram бота
     */
    public function index()
    {
        $settings = TelegramBotSettings::current();
        
        // Получить информацию о webhook, если бот настроен
        $webhookInfo = null;
        if ($settings->isConfigured()) {
            $result = $settings->getWebhookInfo();
            if ($result['success']) {
                $webhookInfo = $result['webhook_info'];
            }
        }

        return view('admin.telegram-bot.index', compact('settings', 'webhookInfo'));
    }

    /**
     * Обновление настроек бота
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bot_token' => 'required|string',
            'bot_username' => 'nullable|string',
            'admin_telegram_id' => 'nullable|string',
            'welcome_message' => 'nullable|string|max:4096',
            'is_active' => 'boolean',
        ], [
            'bot_token.required' => 'Токен бота обязателен для заполнения',
            'welcome_message.max' => 'Приветственное сообщение не должно превышать 4096 символов',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.telegram-bot.index')
                ->withErrors($validator)
                ->withInput();
        }

        $settings = TelegramBotSettings::current();
        $settings->fill($request->only([
            'bot_token',
            'bot_username',
            'admin_telegram_id',
            'welcome_message',
        ]));
        
        $settings->is_active = $request->has('is_active');
        $settings->save();

        return redirect()
            ->route('admin.telegram-bot.index')
            ->with('success', 'Настройки бота успешно обновлены');
    }

    /**
     * Тестирование подключения к боту
     */
    public function testConnection()
    {
        $settings = TelegramBotSettings::current();
        $result = $settings->testConnection();

        if ($result['success']) {
            // Обновляем username бота, если он был получен
            if (isset($result['bot_info']['username'])) {
                $settings->bot_username = $result['bot_info']['username'];
                $settings->save();
            }

            return redirect()
                ->route('admin.telegram-bot.index')
                ->with('success', $result['message'] . ' Бот: @' . ($result['bot_info']['username'] ?? 'unknown'));
        }

        return redirect()
            ->route('admin.telegram-bot.index')
            ->with('error', $result['message']);
    }

    /**
     * Установка webhook
     */
    public function setWebhook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'webhook_url' => 'required|url',
        ], [
            'webhook_url.required' => 'URL для webhook обязателен',
            'webhook_url.url' => 'Некорректный URL',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.telegram-bot.index')
                ->withErrors($validator)
                ->withInput();
        }

        $settings = TelegramBotSettings::current();
        $result = $settings->setWebhook($request->webhook_url);

        if ($result['success']) {
            return redirect()
                ->route('admin.telegram-bot.index')
                ->with('success', $result['message']);
        }

        return redirect()
            ->route('admin.telegram-bot.index')
            ->with('error', $result['message']);
    }

    /**
     * Удаление webhook
     */
    public function deleteWebhook()
    {
        $settings = TelegramBotSettings::current();
        $result = $settings->deleteWebhook();

        if ($result['success']) {
            return redirect()
                ->route('admin.telegram-bot.index')
                ->with('success', $result['message']);
        }

        return redirect()
            ->route('admin.telegram-bot.index')
            ->with('error', $result['message']);
    }

    /**
     * Получение информации о webhook
     */
    public function getWebhookInfo()
    {
        $settings = TelegramBotSettings::current();
        $result = $settings->getWebhookInfo();

        if ($result['success']) {
            return response()->json($result['webhook_info']);
        }

        return response()->json(['error' => $result['message']], 400);
    }
}
