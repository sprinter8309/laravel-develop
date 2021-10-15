<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_comment', function (Blueprint $table) {
            $table->id();
            $table->string('content');
            $table->string('status');
            $table->integer('post_id');
            $table->integer('user_id');
            $table->timestamps();
        });

        Schema::table('post_comment', function (Blueprint $table) {
            $table->index('status');
            $table->index('post_id');
            $table->index('user_id');
        });

        Schema::table('post_comment', function (Blueprint $table) {
            $table->foreign('post_id')->references('id')->on('post');
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
        Schema::table('post_comment', function (Blueprint $table) {
            $table->dropForeign('post_comment_post_id_foreign');
            $table->dropForeign('post_comment_user_id_foreign');
        });

        Schema::table('post_comment', function (Blueprint $table) {
            $table->dropIndex('post_comment_status_index');
            $table->dropIndex('post_comment_post_id_index');
            $table->dropIndex('post_comment_user_id_index');
        });

        Schema::dropIfExists('post_comment');
    }
}
