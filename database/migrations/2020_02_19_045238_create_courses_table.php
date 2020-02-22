<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name')->index()->nullable(false);
            $table->decimal('price', 19, 2)->nullable(false)->default(0);
            $table->text('about')->nullable(false)->default('');
            $table->date('sign_up_beg');
            $table->date('sign_up_end');
            $table->boolean('available')->nullable(false)->default(false);
            $table->softDeletes();
        });

        \Illuminate\Support\Facades\DB::statement('
        ALTER TABLE courses
            ADD CONSTRAINT chk_signup_period CHECK (
                (sign_up_beg IS NULL AND sign_up_end IS NULL) OR
                (sign_up_beg IS NOT NULL AND sign_up_end IS NOT NULL AND sign_up_beg < sign_up_end)
            )
        ');
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
