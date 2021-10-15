<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('status');
            $table->string('user_type');
            $table->text('social_networks');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('email');
            $table->index('status');
            $table->index('user_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_email_index');
            $table->dropIndex('users_status_index');
            $table->dropIndex('users_user_type_index');
        });

        Schema::dropIfExists('users');
    }
}
