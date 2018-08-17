<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamScoreTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_score_tables', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_id');
            $table->string('score_table');
            $table->timestamps();

            $table->foreign('exam_id')
                ->references('id')->on('exams')
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
        Schema::dropIfExists('exam_score_tables');
    }
}
