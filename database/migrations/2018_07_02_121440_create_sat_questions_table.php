<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSatQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sat_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->integer('section_id')->unsigned();
            $table->timestamps();

            $table->foreign('section_id')
                ->references('id')->on('sat_sections')
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
        Schema::dropIfExists('sat_questions');
    }
}
