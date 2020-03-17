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

        if (config('database.default') !== 'sqlite') {
            \Illuminate\Support\Facades\DB::statement('
            ALTER TABLE courses
                ADD CONSTRAINT chk_signup_period CHECK (
                    (sign_up_beg IS NULL AND sign_up_end IS NULL) OR
                    (sign_up_beg IS NOT NULL AND sign_up_end IS NOT NULL AND sign_up_beg < sign_up_end)
            );
            ');
        }
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
