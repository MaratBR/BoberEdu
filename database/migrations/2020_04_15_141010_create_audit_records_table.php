<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_records', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->ipAddress('ip');
            $table->unsignedInteger('action');
            $table->string('user_agent');
            $table->json('extra')->nullable();
            $table->string('comment')->nullable();
            $table->nullableMorphs('subject');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_records');
    }
}
