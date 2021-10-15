<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('link_name');
            $table->integer('parent_category_id')->nullable();
            $table->boolean('is_active');
        });

        Schema::table('category', function (Blueprint $table) {
            $table->index('name');
            $table->index('link_name');
            $table->index('is_active');
            $table->index('parent_category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category', function (Blueprint $table) {
            $table->dropIndex('category_name_index');
            $table->dropIndex('category_link_name_index');
            $table->dropIndex('category_is_active_index');
            $table->dropIndex('category_parent_category_id_index');
        });

        Schema::dropIfExists('category');
    }
}
