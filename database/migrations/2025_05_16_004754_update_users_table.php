<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rename 'tel_number' to 'telephone_number'
            $table->renameColumn('tel_number', 'telephone_number');

            // Add 'company_name' as a nullable (not required) column
            $table->string('company_name')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert 'telephone_number' back to 'tel_number'
            $table->renameColumn('telephone_number', 'tel_number');

            // Drop 'company_name'
            $table->dropColumn('company_name');
        });
    }
}