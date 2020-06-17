<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->timestamps();
            $table->string('full_name');
            $table->text('about');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->softDeletes();

            $table->string('education')->nullable();
            $table->string('location')->nullable();
            $table->string('degree')->nullable();

            $table->string('link_web')->nullable();
            $table->string('link_yt')->nullable();
            $table->string('link_linked_in')->nullable();
            $table->string('link_vk')->nullable();
            $table->string('link_fb')->nullable();
            $table->string('link_twitter')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
