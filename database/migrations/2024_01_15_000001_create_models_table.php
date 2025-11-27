<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Основная информация
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('gender', ['male', 'female']);
            $table->integer('age');
            $table->string('city');
            
            // Параметры
            $table->integer('height'); // см
            $table->integer('weight'); // кг
            $table->integer('bust')->nullable(); // см
            $table->integer('waist')->nullable(); // см
            $table->integer('hips')->nullable(); // см
            
            // Внешность
            $table->string('eye_color');
            $table->string('hair_color');
            $table->integer('shoe_size')->nullable();
            $table->string('clothing_size')->nullable();
            
            // Дополнительно
            $table->text('bio')->nullable();
            $table->json('languages')->nullable(); // ['ru', 'en']
            $table->json('skills')->nullable(); // ['фотосессии', 'подиум']
            
            // Социальные сети
            $table->string('instagram')->nullable();
            $table->string('telegram')->nullable();
            $table->string('vk')->nullable();
            
            // Медиа
            $table->string('main_photo')->nullable();
            $table->json('portfolio_photos')->nullable();
            $table->string('video_url')->nullable();
            
            // Категории и статусы
            $table->json('categories')->nullable(); // ['fashion', 'commercial']
            $table->enum('status', ['pending', 'active', 'inactive', 'rejected'])->default('pending');
            $table->boolean('is_featured')->default(false);
            
            // Опыт и образование
            $table->integer('experience_years')->default(0);
            $table->text('experience_description')->nullable();
            $table->boolean('has_modeling_school')->default(false);
            
            // Статистика
            $table->integer('views_count')->default(0);
            $table->integer('bookings_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Индексы
            $table->index('status');
            $table->index('gender');
            $table->index('city');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('models');
    }
};
