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
        Schema::table('users', function (Blueprint $table) {
            $table->string('telegram_id')->nullable()->unique()->comment('Telegram ID пользователя');
            $table->string('telegram_username')->nullable()->comment('Telegram username');
            $table->string('telegram_bind_key')->nullable()->comment('Временный ключ для привязки');
            $table->timestamp('telegram_bind_key_expires_at')->nullable()->comment('Срок действия ключа');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telegram_id', 'telegram_username', 'telegram_bind_key', 'telegram_bind_key_expires_at']);
        });
    }
};
