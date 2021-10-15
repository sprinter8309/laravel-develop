<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('preview');
            $table->text('content');
            $table->string('status');
            $table->string('image');
            $table->integer('category_id');
            $table->integer('author_id');
            $table->boolean('is_delete')->default(false)->nullable();
            $table->timestamps();
        });

        Schema::table('post', function (Blueprint $table) {
            $table->index('status');
            $table->index('category_id');
            $table->index('author_id');
        });

        Schema::table('post', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post', function (Blueprint $table) {
            $table->dropForeign('post_category_id_foreign');
        });

        Schema::table('post', function (Blueprint $table) {
            $table->dropIndex('post_status_index');
            $table->dropIndex('post_category_id_index');
            $table->dropIndex('post_author_id_index');
        });

        Schema::dropIfExists('post');
    }
}
