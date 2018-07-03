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
            $table->date('birthdate')->nullable();
            $table->string('gender');
            $table->string('photo')->nullable();
            $table->string('current_school')->nullable();
            $table->string('school_year')->nullable();
            $table->string('teacher')->nullable();
            $table->string('former_school')->nullable();
            $table->integer('location_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('location_id')
                ->references('id')->on('locations')
                ->onDelete('set null');
        });

        Schema::table('students', function($table)
        {
            $table->integer('user_id')->unsigned()->nullable()->after('location_id');

            $table->foreign('user_id')
                ->references('id')->on('users')
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
