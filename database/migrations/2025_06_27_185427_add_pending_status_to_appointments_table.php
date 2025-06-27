<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Update the ENUM to include 'pending' status
            DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('open', 'cancelled', 'closed', 'pending') NOT NULL DEFAULT 'open'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Revert back to original ENUM values
            DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('open', 'cancelled', 'closed') NOT NULL DEFAULT 'open'");
        });
    }
};
