<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_students', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('class_id')->unsigned();
            $table->primary(['user_id', 'class_id']);
//            $table->foreign('user_id')->references('id')->on('users');
//            $table->foreign('class_id')->references('id')->on('classes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_students');
    }
}
