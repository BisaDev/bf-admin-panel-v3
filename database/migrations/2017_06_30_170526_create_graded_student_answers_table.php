<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradedStudentAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('graded_quiz_question_id')->unsigned();
            $table->integer('student_id')->unsigned();
            $table->string('answer_text')->nullable();
            $table->string('answer_id')->nullable();
            $table->tinyInteger('is_correct')->default(0);
            $table->timestamps();
    
            $table->foreign('graded_quiz_question_id')
                ->references('id')->on('graded_quiz_questions')
                ->onDelete('cascade');
    
            $table->foreign('student_id')
                ->references('id')->on('students')
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
        Schema::dropIfExists('student_answers');
    }
}
