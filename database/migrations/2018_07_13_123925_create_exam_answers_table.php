<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_exam_section_id')->unsigned();
            $table->integer('question_number');
            $table->string('answer');
            $table->string('guessed');
            $table->timestamps();

            $table->foreign('student_exam_section_id')
                ->references('id')->on('student_exam_sections')
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
        Schema::dropIfExists('exam_answers');
    }
}
