<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaggingQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagging_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tagging_subject_id');
            $table->unsignedInteger('tagging_topic_id')->nullable();
            $table->unsignedInteger('pdf_id')->nullable();
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
        Schema::dropIfExists('tagging_questions');
    }
}
