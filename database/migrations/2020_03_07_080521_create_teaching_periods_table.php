<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachingPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teaching_periods', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->date('since');
            $table->date('until')->nullable();
            $table->unsignedInteger('teacher_id');
            $table->unsignedInteger('course_id');
            $table->foreign('teacher_id')->on('teachers')->references('id');
            $table->foreign('course_id')->on('courses')->references('id');
        });

        // TODO Check constraint
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teaching_periods');
    }
}
