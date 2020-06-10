<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->timestamp('completed_at')->nullable();
            $table->enum('status', ['pending', 'cancelled', 'successful', 'prepared'])->default('pending');
            $table->string('uid')->nullable();
            $table->string('gateaway_name');
            $table->string('title');
            $table->string('user_agent');
            $table->string('redirect_url')->nullable();
            $table->ipAddress('ip_address');
            $table->decimal('amount', 19, 2);
            $table->foreignId('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
