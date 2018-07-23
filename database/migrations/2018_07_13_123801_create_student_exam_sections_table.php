<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentExamSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_exam_sections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_exam_id')->unsigned();
            $table->integer('section_number');
            $table->integer('number_correct');
            $table->integer('score');
            $table->timestamps();

            $table->foreign('student_exam_id')
                ->references('id')->on('student_exams')
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
        Schema::dropIfExists('student_exam_sections');
    }
}
