<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('price', 19, 4)->default(0);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->on('users')->references('id');
            $table->string('payment_external_id')->nullable();
            $table->enum('status', ['success', 'failed', 'pending', 'cancelled'])->default('pending');
            $table->boolean('preview')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_purchases');
    }
}
