<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUserIdFromAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['user_id']);
            // Now drop the column
            $table->dropColumn('user_id');
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Re-add the column
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
}
