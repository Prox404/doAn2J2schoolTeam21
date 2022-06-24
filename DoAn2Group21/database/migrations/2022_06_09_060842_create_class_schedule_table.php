<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_schedule', function (Blueprint $table) {
            $table->bigInteger('class_id')->unsigned();
            $table->bigInteger('schedule_id')->unsigned();
            $table->tinyInteger('shift');
//            $table->foreign('class_id')->references('id')->on('classes');
//            $table->foreign('shedule_id')->references('id')->on('schedules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_schedule');
    }
}
