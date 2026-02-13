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
        Schema::table('models', function (Blueprint $table) {
            $table->string('model_number', 20)->nullable()->unique()->after('id');
        });
        
        // Автоматически заполняем номера для существующих моделей
        DB::statement("UPDATE models SET model_number = CONCAT('GM', LPAD(id, 5, '0')) WHERE model_number IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('models', function (Blueprint $table) {
            $table->dropColumn('model_number');
        });
    }
};
