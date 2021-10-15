<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post', function (Blueprint $table) {
            $table->foreign('author_id')->references('id')->on('author');
        });

        Schema::table('standart_exam', function (Blueprint $table) {
            $table->foreign('category_exam_id')->references('id')->on('category_exam');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('standart_exam', function (Blueprint $table) {
            $table->dropForeign('standart_exam_category_exam_id_foreign');
        });

        Schema::table('post', function (Blueprint $table) {
            $table->dropForeign('post_author_id_foreign');
        });
    }
}
