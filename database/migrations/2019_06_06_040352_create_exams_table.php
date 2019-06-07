<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->string('room_number');
            $table->string('start_at');
            $table->string('end_at');
            $table->unsignedBigInteger('course_id');
            $table->boolean('status')->default(false);
            $table->boolean('teacher_signed')->default(false);
            $table->string('signer_id')->nullable();
            $table->primary('id');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams');
    }
}
