<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('models', function (Blueprint $table) {
            // Типаж внешности
            $table->string('appearance_type')->nullable()->after('hair_color'); // славянский/афро/азиатский/европейский/латино
            
            // Цвет кожи
            $table->string('skin_color')->nullable()->after('appearance_type'); // светлая/средняя/смуглая/темная
            
            // Дополнительные поля
            $table->boolean('has_snaps')->default(false)->after('portfolio_photos'); // Есть снепы
            $table->boolean('has_video_presentation')->default(false)->after('has_snaps'); // Есть видео представление
            $table->boolean('has_video_walk')->default(false)->after('has_video_presentation'); // Есть видео походка
            $table->boolean('has_passport')->default(false)->after('has_video_walk'); // Загранпаспорт
            $table->boolean('has_professional_experience')->default(false)->after('has_passport'); // Большой опыт работы на съёмках
            $table->boolean('has_tattoos')->default(false)->after('has_professional_experience'); // Татуировки
            $table->boolean('has_piercings')->default(false)->after('has_tattoos'); // Пирсинг
            
            // Диапазоны размеров для фильтров
            $table->integer('clothing_size_numeric')->nullable()->after('clothing_size'); // Размер одежды в числовом формате для фильтрации
        });
    }

    public function down(): void
    {
        Schema::table('models', function (Blueprint $table) {
            $table->dropColumn([
                'appearance_type',
                'skin_color',
                'has_snaps',
                'has_video_presentation',
                'has_video_walk',
                'has_passport',
                'has_professional_experience',
                'has_tattoos',
                'has_piercings',
                'clothing_size_numeric',
            ]);
        });
    }
};
