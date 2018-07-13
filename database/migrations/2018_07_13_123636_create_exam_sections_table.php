<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_sections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('section_number');
            $table->integer('question_number');
            $table->string('correct_1');
            $table->string('correct_2');
            $table->string('correct_3');
            $table->string('correct_4');
            $table->string('correct_5');
            $table->string('topic');
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
        Schema::dropIfExists('exam_sections');
    }
}
