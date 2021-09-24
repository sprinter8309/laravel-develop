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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('standart_question');
    }
}
