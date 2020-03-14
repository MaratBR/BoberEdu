<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('created_at')->useCurrent();
            $table->enum('status', ['preview', 'active', 'inactive']);

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('gifted_by_id')->nullable();
            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('gifted_by_id')->on('users')->references('id');

            $table->unsignedInteger('course_id');
            $table->foreign('course_id')->on('courses')->references('id');

            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->foreign('purchase_id')->on('purchases')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_attendances');
    }
}
