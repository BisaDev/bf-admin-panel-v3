<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradedQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('graded_quizzes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meetup_id')->unsigned()->nullable();
            $table->integer('quiz_id')->unsigned()->nullable();
            $table->string('quiz_name')->nullable();
            $table->string('quiz_description')->nullable();
            $table->string('quiz_type')->nullable();
            $table->string('quiz_grade_level')->nullable();
            $table->string('quiz_subject')->nullable();
            $table->timestamps();
            
            $table->foreign('meetup_id')
                ->references('id')->on('meetups')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('graded_quizzes');
    }
}
