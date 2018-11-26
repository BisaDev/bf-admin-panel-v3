<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMiniExamsToExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->string('mini_exam_format')->nullable()->after('description');
            $table->integer('mini_exam_time')->nullable()->after('mini_exam_format');
            $table->integer('mini_exam_questions')->nullable()->after('mini_exam_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn('mini_exam_format');
            $table->dropColumn('mini_exam_time');
            $table->dropColumn('mini_exam_questions');
        });
    }
}
