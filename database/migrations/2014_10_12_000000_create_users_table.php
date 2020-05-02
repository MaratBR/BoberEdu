<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique()->nullable(false);
            $table->string('normalized_name')->unique()->nullable(false);
            $table->string('status')->nullable();
            $table->text('about')->nullable();
            $table->string('email')->unique();
            $table->string('normalized_email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(false);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->enum('sex', ['u', 'f', 'm'])->nullable(false)->default('u');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
