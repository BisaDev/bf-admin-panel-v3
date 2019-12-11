<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaggingImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagging_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image_answer');
            $table->string('image_url');
            $table->unsignedInteger('tagging_question_id');
            $table->string('explanation_url');
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
        Schema::dropIfExists('tagging_images');
    }
}
