<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityBucketQuizTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_bucket_quiz', function (Blueprint $table) {
            $table->integer('activity_bucket_id')->unsigned();
            $table->integer('quiz_id')->unsigned();
            $table->integer('minigame_id')->unsigned()->nullable();

            $table->foreign('quiz_id')
                ->references('id')->on('quizzes')
                ->onDelete('cascade');

            $table->foreign('activity_bucket_id')
                ->references('id')->on('activity_buckets')
                ->onDelete('cascade');

            $table->foreign('minigame_id')
                ->references('id')->on('minigames')
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
        Schema::dropIfExists('quiz_activity_bucket');
    }
}
