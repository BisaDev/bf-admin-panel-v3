<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradedQuizQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('graded_quiz_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('graded_quiz_id')->unsigned();
            $table->integer('question_id')->unsigned()->nullable();
            $table->string('question_title')->nullable();
            $table->string('question_photo')->nullable();
            $table->string('question_topic')->nullable();
            $table->text('answers')->nullable();
            $table->timestamps();
    
            $table->foreign('graded_quiz_id')
                ->references('id')->on('graded_quizzes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('graded_quiz_questions');
    }
}
