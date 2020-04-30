<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachingAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teaching_assignments', function (Blueprint $table) {
            $table->unsignedInteger('teacher_id');
            $table->unsignedInteger('course_id');
            $table->foreign('teacher_id')->on('teachers')->references('id');
            $table->foreign('course_id')->on('courses')->references('id');
            $table->primary(['teacher_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teaching_assignments');
    }
}
