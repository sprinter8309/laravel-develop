<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStandartQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standart_question', function (Blueprint $table) {
            $table->id();
            $table->integer('exam_id');
            $table->string('quest_type');
            $table->string('quest_text');
            $table->text('quest_extra_info')->nullable();
            $table->text('answers');
            $table->text('tags')->nullable();
            $table->timestamps();
        });

        Schema::table('standart_question', function (Blueprint $table) {
            $table->index('exam_id');
            $table->index('quest_type');
        });

        Schema::table('standart_question', function (Blueprint $table) {
            $table->foreign('exam_id')->references('id')->on('standart_exam');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('standart_question', function (Blueprint $table) {
            $table->dropForeign('standart_question_exam_id_foreign');
        });

        Schema::table('standart_question', function (Blueprint $table) {
            $table->dropIndex('standart_question_exam_id_index');
            $table->dropIndex('standart_question_quest_type_index');
        });

        Schema::dropIfExists('standart_question');
    }
}
