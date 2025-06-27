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
        // Check if we're using MySQL or SQLite and handle accordingly
        $driver = Schema::getConnection()->getDriverName();
        
        if ($driver === 'mysql') {
            // MySQL supports MODIFY COLUMN with ENUM
            DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('open', 'cancelled', 'closed', 'pending') NOT NULL DEFAULT 'open'");
        } else {
            // For SQLite and other databases, we need to recreate the table
            // First, let's check if the 'pending' value is already allowed
            $hasConstraint = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='appointments'");
            
            if (!empty($hasConstraint) && !str_contains($hasConstraint[0]->sql, "'pending'")) {
                // Create a temporary table with the new structure (only existing columns at this point)
                Schema::create('appointments_temp', function (Blueprint $table) {
                    $table->id();
                    $table->timestamps();
                    $table->string('service');
                    $table->date('date');
                    $table->time('timeslot');
                    $table->enum('status', ['open', 'cancelled', 'closed', 'pending'])->default('open');
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->unsignedBigInteger('company_id')->nullable();
                    
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
                });
                
                // Copy data from old table to new table with explicit column mapping (only existing columns)
                DB::statement('
                    INSERT INTO appointments_temp 
                    (id, created_at, updated_at, service, date, timeslot, status, user_id, company_id)
                    SELECT id, created_at, updated_at, service, date, timeslot, status, user_id, company_id
                    FROM appointments
                ');
                
                // Drop old table and rename new table
                Schema::drop('appointments');
                Schema::rename('appointments_temp', 'appointments');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        
        if ($driver === 'mysql') {
            // MySQL supports MODIFY COLUMN with ENUM
            DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('open', 'cancelled', 'closed') NOT NULL DEFAULT 'open'");
        } else {
            // For SQLite, recreate table without 'pending' status (only existing columns)
            Schema::create('appointments_temp', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->string('service');
                $table->date('date');
                $table->time('timeslot');
                $table->enum('status', ['open', 'cancelled', 'closed'])->default('open');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('company_id')->nullable();
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            });
            
            // Copy data (excluding any records with 'pending' status)
            DB::statement('
                INSERT INTO appointments_temp 
                (id, created_at, updated_at, service, date, timeslot, status, user_id, company_id)
                SELECT id, created_at, updated_at, service, date, timeslot, status, user_id, company_id
                FROM appointments WHERE status != \'pending\'
            ');
            
            // Drop old table and rename new table
            Schema::drop('appointments');
            Schema::rename('appointments_temp', 'appointments');
        }
    }
};
