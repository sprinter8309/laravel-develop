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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('standart_exam');
    }
}
