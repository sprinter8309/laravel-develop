<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('author', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lastname');
            $table->integer('age');
            $table->string('sex');
            $table->integer('user_id');
        });

        Schema::table('author', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('author', function (Blueprint $table) {
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
        Schema::table('author', function (Blueprint $table) {
            $table->dropForeign('author_user_id_foreign');
        });

        Schema::table('author', function (Blueprint $table) {
            $table->dropIndex('author_user_id_index');
        });

        Schema::dropIfExists('author');
    }
}
