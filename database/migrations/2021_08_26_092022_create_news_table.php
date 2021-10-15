<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('preview_text');
            $table->text('detail_text');
            $table->string('preview_image');
            $table->string('main_image');
            $table->string('status');
            $table->integer('author_id');
            $table->timestamps();
        });

        Schema::table('news', function (Blueprint $table) {
            $table->index('status');
            $table->index('author_id');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->foreign('author_id')->references('id')->on('author');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign('news_author_id_foreign');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropIndex('news_status_index');
            $table->dropIndex('news_author_id_index');
        });

        Schema::dropIfExists('news');
    }
}
