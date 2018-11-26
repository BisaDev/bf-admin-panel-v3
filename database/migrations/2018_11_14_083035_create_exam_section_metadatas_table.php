<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamSectionMetadatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_section_metadatas', function (Blueprint $table) {
            $table->string('exam_type');
            $table->integer('section_number')->unsigned();
            $table->string('section_name');
            $table->integer('questions')->unsigned();
            $table->integer('open_questions')->nullable();
            $table->integer('time_available')->unsigned();
            $table->string('table_score');
            $table->integer('max_score')->unsigned();
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
        Schema::dropIfExists('exam_section_metadatas');
    }
}
