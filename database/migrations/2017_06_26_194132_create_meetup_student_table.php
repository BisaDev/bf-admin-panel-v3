<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetupStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetup_student', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meetup_id')->unsigned();
            $table->integer('student_id')->unsigned();

            $table->foreign('meetup_id')
                ->references('id')->on('meetups')
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
        Schema::dropIfExists('meetup_student');
    }
}
