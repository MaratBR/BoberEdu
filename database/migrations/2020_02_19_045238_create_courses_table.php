<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->timestamps();
            $table->string('name')->index();
            $table->decimal('price', 19, 2)->nullable(false)->default(0);
            $table->text('about');
            $table->date('sign_up_beg')->nullable();
            $table->date('sign_up_end')->nullable();
            $table->boolean('available')->default(false);
            $table->smallInteger('trial_length')->default(0);
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
        Schema::dropIfExists('courses');
    }
}
