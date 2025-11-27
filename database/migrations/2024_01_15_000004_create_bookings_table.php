<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('model_id')->constrained()->onDelete('cascade');
            
            // Информация о клиенте
            $table->string('client_name');
            $table->string('client_phone');
            $table->string('client_email');
            $table->string('company_name')->nullable();
            
            // Детали бронирования
            $table->enum('event_type', ['фотосессия', 'показ', 'видеосъёмка', 'реклама', 'другое']);
            $table->text('event_description');
            $table->date('event_date')->nullable();
            $table->time('event_time')->nullable();
            $table->string('event_location')->nullable();
            $table->integer('duration_hours')->nullable();
            
            // Финансы
            $table->decimal('budget', 10, 2)->nullable();
            $table->decimal('final_price', 10, 2)->nullable();
            
            // Статус
            $table->enum('status', [
                'new',          // Новая заявка
                'contacted',    // Клиент связался
                'confirmed',    // Подтверждено
                'completed',    // Выполнено
                'cancelled'     // Отменено
            ])->default('new');
            
            $table->text('admin_notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            $table->timestamps();
            
            $table->index('status');
            $table->index('event_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
