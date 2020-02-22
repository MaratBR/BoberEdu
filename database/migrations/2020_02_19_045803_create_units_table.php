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
            $table->string('name')->nullable(false);
            $table->boolean('is_preview')->nullable(false)->default(false);
            $table->text('about');
            $table->unsignedInteger('course_id')->nullable(false);
            $table->foreign('course_id')->on('courses')->references('id');
            $table->smallInteger('order_num')->nullable(false)->default(0);
            $table->softDeletes();
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
