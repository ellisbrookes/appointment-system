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
        Schema::table('company_members', function (Blueprint $table) {
            $table->string('email')->nullable()->after('user_id');
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_members', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
