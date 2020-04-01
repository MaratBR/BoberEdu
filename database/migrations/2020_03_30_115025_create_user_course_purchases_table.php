<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCoursePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_course_purchases', function (Blueprint $table) {
            $table->unsignedBigInteger('user_course_id');
            $table->unsignedBigInteger('purchase_id');

            $table->foreign('purchase_id')->references('id')->on('purchases');
            $table->foreign('user_course_id')->references('id')->on('user_courses');

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
        Schema::dropIfExists('user_course_purchases');
    }
}
