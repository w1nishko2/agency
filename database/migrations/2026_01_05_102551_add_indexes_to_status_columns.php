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
        $connection = Schema::getConnection();
        
        // Проверяем и добавляем индексы только если их нет
        if (!$this->indexExists('models', 'models_status_index')) {
            $connection->statement('ALTER TABLE `models` ADD INDEX `models_status_index` (`status`)');
        }

        if (!$this->indexExists('casting_applications', 'casting_applications_status_index')) {
            $connection->statement('ALTER TABLE `casting_applications` ADD INDEX `casting_applications_status_index` (`status`)');
        }

        if (!$this->indexExists('bookings', 'bookings_status_index')) {
            $connection->statement('ALTER TABLE `bookings` ADD INDEX `bookings_status_index` (`status`)');
        }
    }

    /**
     * Check if index exists
     */
    private function indexExists(string $table, string $index): bool
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();
        
        $result = $connection->select(
            "SELECT COUNT(*) as count FROM information_schema.statistics 
             WHERE table_schema = ? AND table_name = ? AND index_name = ?",
            [$database, $table, $index]
        );
        
        return $result[0]->count > 0;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('models', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('casting_applications', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};
