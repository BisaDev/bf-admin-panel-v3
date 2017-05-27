<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('brithdate');
            $table->json('gender');
            $table->string('photo');
            $table->string('current_school');
            $table->string('teacher', 100);
            $table->string('former_school');
            $table->integer('location_id')->unsigned()->nullable();
            $table->integer('grade_level_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('location_id')
                ->references('id')->on('locations')
                ->onDelete('set null');
            $table->foreign('grade_level_id')
                ->references('id')->on('grade_levels')
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
        Schema::dropIfExists('students');
    }
}
