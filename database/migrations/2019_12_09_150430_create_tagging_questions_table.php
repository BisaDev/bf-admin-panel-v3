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
            $table->increments('tagging_question_id');
            $table->unsignedInteger('tagging_question_id');
            $table->unsignedInteger('tagging_topic_id')->nullable();
            $table->string('pdf_number');
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
