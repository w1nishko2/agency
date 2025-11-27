<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('models', function (Blueprint $table) {
            // Добавляем контактные данные
            $table->string('email')->nullable()->after('last_name');
            $table->string('phone')->nullable()->after('email');
            $table->date('birth_date')->nullable()->after('phone');
            $table->string('facebook')->nullable()->after('vk');
            
            // Делаем user_id необязательным (для незарегистрированных моделей)
            $table->foreignId('user_id')->nullable()->change();
            
            // Добавляем уникальный индекс на email
            $table->unique('email');
        });
    }

    public function down(): void
    {
        Schema::table('models', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->dropColumn(['email', 'phone', 'birth_date', 'facebook']);
        });
    }
};
