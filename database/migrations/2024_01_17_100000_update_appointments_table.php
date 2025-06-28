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
            // Add status column with all possible values including 'pending'
            $table->enum('status', ['open', 'cancelled', 'closed', 'pending'])->default('open');
            
            // Add company_id with foreign key
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
            
            // Make user_id nullable
            $table->unsignedBigInteger('user_id')->nullable()->change();
            
            // Add customer fields
            $table->string('customer_name')->nullable()->after('user_id');
            $table->string('customer_email')->nullable()->after('customer_name');
            $table->string('customer_phone')->nullable()->after('customer_email');
            $table->text('customer_message')->nullable()->after('customer_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Drop customer fields
            $table->dropColumn(['customer_name', 'customer_email', 'customer_phone', 'customer_message']);
            
            // Make user_id not nullable again
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            
            // Drop company foreign key and column
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
            
            // Drop status column
            $table->dropColumn('status');
        });
    }
};
