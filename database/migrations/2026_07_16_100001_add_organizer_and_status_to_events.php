<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambah organizer_id (FK) dan status (enum) ke tabel events.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Foreign key ke user dengan role organizer/superadmin
            $table->foreignId('organizer_id')
                  ->nullable()
                  ->after('category_id')
                  ->constrained('users')
                  ->nullOnDelete();

            // Status kurasi event oleh superadmin
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('approved') // Legacy events default approved
                  ->after('poster_path');
        });

        // Set semua event lama ke 'approved' agar tidak hilang dari homepage
        DB::statement("UPDATE events SET status = 'approved' WHERE status IS NULL OR status = ''");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['organizer_id']);
            $table->dropColumn(['organizer_id', 'status']);
        });
    }
};
