<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->timestamps();
            $table->string('name');
            $table->boolean('preview')->default(false);
            $table->text('about');
            $table->smallInteger('order_num')->default(0);
            $table->softDeletes();

            $table->unsignedInteger('course_id');
            $table->foreign('course_id')->on('courses')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('units');
    }
}
