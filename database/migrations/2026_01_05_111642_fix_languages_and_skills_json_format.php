<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Исправление формата languages и skills - конвертация строк в JSON массивы
     * Связано с ошибкой: count(): Argument #1 ($value) must be of type Countable|array
     */
    public function up(): void
    {
        // Получаем все записи где languages или skills не являются валидным JSON
        $models = DB::table('models')->get();

        foreach ($models as $model) {
            $updates = [];

            // Исправляем languages
            if (!empty($model->languages)) {
                // Проверяем, является ли это уже валидным JSON
                $decoded = json_decode($model->languages, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Это строка, конвертируем в массив
                    $updates['languages'] = json_encode([$model->languages]);
                } elseif (!is_array($decoded)) {
                    // JSON, но не массив
                    $updates['languages'] = json_encode([]);
                }
            } else {
                // Пустое значение -> пустой массив
                $updates['languages'] = json_encode([]);
            }

            // Исправляем skills
            if (!empty($model->skills)) {
                $decoded = json_decode($model->skills, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $updates['skills'] = json_encode([$model->skills]);
                } elseif (!is_array($decoded)) {
                    $updates['skills'] = json_encode([]);
                }
            } else {
                $updates['skills'] = json_encode([]);
            }

            // Применяем обновления если есть изменения
            if (!empty($updates)) {
                DB::table('models')
                    ->where('id', $model->id)
                    ->update($updates);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Откат не требуется, т.к. мы исправляем данные
        // Возврат к строкам может привести к ошибкам
    }
};
