<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStandartExamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standart_exam', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('name');
            $table->string('version');
            $table->text('description')->nullable();
            $table->integer('category_exam_id');
            $table->integer('time_limit')->nullable();
            $table->integer('point_value')->nullable();
            $table->integer('author_id');
            $table->text('result_actions')->nullable();
            $table->string('preview_img')->nullable();
            $table->string('detail_img')->nullable();
            $table->timestamps();
        });

        Schema::table('standart_exam', function (Blueprint $table) {
            $table->index('version');
            $table->index('category_exam_id');
            $table->index('point_value');
            $table->index('time_limit');
            $table->index('author_id');
        });

        Schema::table('standart_exam', function (Blueprint $table) {
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
        Schema::table('standart_exam', function (Blueprint $table) {
            $table->dropForeign('standart_exam_author_id_foreign');
        });

        Schema::table('standart_exam', function (Blueprint $table) {
            $table->dropIndex('standart_exam_version_index');
            $table->dropIndex('standart_exam_category_exam_id_index');
            $table->dropIndex('standart_exam_point_value_index');
            $table->dropIndex('standart_exam_time_limit_index');
            $table->dropIndex('standart_exam_author_id_index');
        });

        Schema::dropIfExists('standart_exam');
    }
}
