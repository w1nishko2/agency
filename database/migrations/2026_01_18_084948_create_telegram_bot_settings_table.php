<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('telegram_bot_settings', function (Blueprint $table) {
            $table->id();
            $table->string('bot_token')->nullable()->comment('Токен Telegram бота');
            $table->string('bot_username')->nullable()->comment('Username бота');
            $table->string('webhook_url')->nullable()->comment('URL для webhook');
            $table->boolean('is_active')->default(false)->comment('Активен ли бот');
            $table->text('welcome_message')->nullable()->comment('Приветственное сообщение');
            $table->json('settings')->nullable()->comment('Дополнительные настройки');
            $table->timestamp('last_webhook_check')->nullable()->comment('Последняя проверка webhook');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_bot_settings');
    }
};
