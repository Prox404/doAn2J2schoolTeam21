<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->bigInteger('class_id');
            $table->float('diligence')->nullable();
            $table->float('quiz1')->nullable();
            $table->float('quiz2')->nullable();
            $table->float('homework')->nullable();
            $table->float('midterm')->nullable();
            $table->float('final')->nullable();
            $table->float('total')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scores');
    }
}
