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
        Schema::table('users', function (Blueprint $table) {
            // Add company_name column
            if (!Schema::hasColumn('users', 'company_name')) {
                $table->string('company_name')->nullable();
            }
            
            // Add settings column
            $table->json('settings')->nullable();
            
            // Add is_admin column
            $table->boolean('is_admin')->default(false);
            
            // Add stripe_connect_id column
            $table->string('stripe_connect_id')->nullable();
            
            // Add company_id column (foreign key will be added later after companies table exists)
            $table->unsignedBigInteger('company_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop company_id (foreign key will be dropped separately if it exists)
            $table->dropColumn('company_id');
            
            // Drop stripe_connect_id
            $table->dropColumn('stripe_connect_id');
            
            // Drop is_admin
            $table->dropColumn('is_admin');
            
            // Drop settings
            $table->dropColumn('settings');
            
            // Drop company_name if it exists
            if (Schema::hasColumn('users', 'company_name')) {
                $table->dropColumn('company_name');
            }
        });
    }
};
