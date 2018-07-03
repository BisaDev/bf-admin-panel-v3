<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSatAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sat_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->text('text');
            $table->tinyInteger('is_correct')->default(0);
            $table->integer('question_id')->unsigned();
            $table->timestamps();

            $table->foreign('question_id')
                ->references('id')->on('sat_questions')
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
        Schema::dropIfExists('sat_answers');
    }
}
