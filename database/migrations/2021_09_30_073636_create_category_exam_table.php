<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryExamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_exam', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->string('preview_img')->nullable();
            $table->string('detail_img')->nullable();
            $table->timestamps();
        });

        Schema::table('category_exam', function (Blueprint $table) {
            $table->index('url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_exam', function (Blueprint $table) {
            $table->dropIndex('category_exam_url_index');
        });

        Schema::dropIfExists('category_exam');
    }
}
