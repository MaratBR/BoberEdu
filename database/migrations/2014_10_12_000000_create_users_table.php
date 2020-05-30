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
            $table->id();
            $table->string('name')->unique()->nullable(false);
            $table->string('normalized_name')->unique()->nullable(false);
            $table->string('status')->nullable();
            $table->text('about')->nullable();
            $table->string('email')->unique();
            $table->string('normalized_email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(false);
            $table->string('display_name')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->boolean('activated')->default(true);
            $table->boolean('is_admin')->default(false);
            $table->timestamp('blocked_until')->nullable();

            $table->rememberToken();
            $table->timestamps();
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
        Schema::dropIfExists('users');
    }
}
