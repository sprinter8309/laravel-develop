<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('status');
            $table->integer('tag_category_id');
            $table->timestamps();
        });

        Schema::table('tag', function (Blueprint $table) {
            $table->index('label');
            $table->index('status');
            $table->index('tag_category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tag', function (Blueprint $table) {
            $table->dropIndex('tag_label_index');
            $table->dropIndex('tag_status_index');
            $table->dropIndex('tag_tag_category_id_index');
        });

        Schema::dropIfExists('tag');
    }
}
