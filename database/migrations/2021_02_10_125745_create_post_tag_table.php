<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_tag', function (Blueprint $table) {
            $table->id();
            $table->integer('post_id');
            $table->integer('tag_id');
            $table->string('status');
            $table->timestamps();
        });

        Schema::table('post_tag', function (Blueprint $table) {
            $table->index('post_id');
            $table->index('tag_id');
            $table->index('status');
        });

        Schema::table('post_tag', function (Blueprint $table) {
            $table->foreign('post_id')->references('id')->on('post');
            $table->foreign('tag_id')->references('id')->on('tag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_tag', function (Blueprint $table) {
            $table->dropForeign('post_tag_post_id_foreign');
            $table->dropForeign('post_tag_tag_id_foreign');
        });

        Schema::table('post_tag', function (Blueprint $table) {
            $table->dropIndex('post_tag_post_id_index');
            $table->dropIndex('post_tag_tag_id_index');
            $table->dropIndex('post_tag_status_index');
        });

        Schema::dropIfExists('post_tag');
    }
}
