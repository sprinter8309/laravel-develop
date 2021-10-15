<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamAttemptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_attempt', function (Blueprint $table) {
            $table->id();
            $table->integer('exam_id');
            $table->integer('user_id')->nullable();
            $table->string('exam_version')->nullable();
            $table->string('status');
            $table->text('user_answers');
            $table->timestamps();
            $table->dateTime('finish_at')->nullable();
        });

        Schema::table('exam_attempt', function (Blueprint $table) {
            $table->index('exam_id');
            $table->index('user_id');
        });

        Schema::table('exam_attempt', function (Blueprint $table) {
            $table->foreign('exam_id')->references('id')->on('standart_exam');
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
        Schema::table('exam_attempt', function (Blueprint $table) {
            $table->dropForeign('exam_attempt_exam_id_foreign');
            $table->dropForeign('exam_attempt_user_id_foreign');
        });

        Schema::table('exam_attempt', function (Blueprint $table) {
            $table->dropIndex('exam_attempt_exam_id_index');
            $table->dropIndex('exam_attempt_user_id_index');
        });

        Schema::dropIfExists('exam_attempt');
    }
}
