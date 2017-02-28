<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeeklyRepetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekly_repetitions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('task_id');
            $table->integer('week_day');
            $table->timestamps();

            $table->unique(['task_id', 'week_day']);
            $table->foreign('task_id')->references('task_id')->on('weekly_tasks')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weekly_repetitions');
    }
}
