<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_admin', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('status');
            $table->timestamps();
        });

        Schema::table('user_admin', function (Blueprint $table) {
            $table->index('status');
            $table->index('user_id');
        });

        Schema::table('user_admin', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_admin', function (Blueprint $table) {
            $table->dropForeign('user_admin_user_id_foreign');
        });

        Schema::table('user_admin', function (Blueprint $table) {
            $table->dropIndex('user_admin_status_index');
            $table->dropIndex('user_admin_user_id_index');
        });

        Schema::dropIfExists('user_admin');
    }
}
