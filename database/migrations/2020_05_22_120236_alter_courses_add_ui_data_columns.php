<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCoursesAddUiDataColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->foreignId('uidata_image_id')->nullable();
            $table->foreign('uidata_image_id', 'fk_courses_image_id_to_file_infos_id')
                ->references('id')->on('file_infos');
            $table->char('uidata_color', 6)->default('ffffff');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign('fk_courses_image_id_to_file_infos_id');
            $table->dropColumn([
                'uidata_image_id',
                'uidata_color'
            ]);
        });
    }
}
