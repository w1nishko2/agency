<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('casting_applications', function (Blueprint $table) {
            $table->id();
            
            // Личная информация
            $table->string('first_name');
            $table->string('last_name');
            $table->string('patronymic')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->string('city');
            
            // Контакты
            $table->string('phone');
            $table->string('email');
            $table->string('telegram')->nullable();
            $table->string('instagram')->nullable();
            
            // Параметры
            $table->integer('height');
            $table->integer('weight');
            $table->integer('bust')->nullable();
            $table->integer('waist')->nullable();
            $table->integer('hips')->nullable();
            $table->integer('shoe_size');
            $table->string('clothing_size');
            
            // Внешность
            $table->string('eye_color');
            $table->string('hair_color');
            $table->string('skin_tone');
            
            // Опыт и навыки
            $table->boolean('has_experience')->default(false);
            $table->text('experience_description')->nullable();
            $table->boolean('has_modeling_school')->default(false);
            $table->string('school_name')->nullable();
            $table->json('languages')->nullable();
            $table->json('special_skills')->nullable();
            
            // Фотографии
            $table->string('photo_portrait')->nullable();
            $table->string('photo_full_body')->nullable();
            $table->string('photo_profile')->nullable();
            $table->string('photo_additional_1')->nullable();
            $table->string('photo_additional_2')->nullable();
            
            // Дополнительно
            $table->text('about')->nullable();
            $table->text('motivation')->nullable();
            $table->json('categories_interest')->nullable(); // Интересующие категории
            
            // Статус
            $table->enum('status', ['new', 'review', 'approved', 'rejected', 'contacted'])->default('new');
            $table->text('admin_notes')->nullable();
            
            // Согласия
            $table->boolean('terms_accepted')->default(false);
            $table->boolean('personal_data_accepted')->default(false);
            $table->boolean('photo_usage_accepted')->default(false);
            
            $table->timestamps();
            
            // Индексы
            $table->index('status');
            $table->index('city');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('casting_applications');
    }
};
