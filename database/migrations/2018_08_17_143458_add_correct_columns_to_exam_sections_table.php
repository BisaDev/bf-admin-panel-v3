<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCorrectColumnsToExamSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exam_sections', function (Blueprint $table) {
            $table->string('correct_6', 4)->nullable()->after('correct_5');
            $table->string('correct_7', 4)->nullable()->after('correct_6');
            $table->string('correct_8', 4)->nullable()->after('correct_7');
            $table->string('correct_9', 4)->nullable()->after('correct_8');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_sections', function (Blueprint $table) {
            $table->dropColumn('correct_6');
            $table->dropColumn('correct_7');
            $table->dropColumn('correct_8');
            $table->dropColumn('correct_9');
        });
    }
}
