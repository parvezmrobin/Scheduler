<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->dateTime('from');
            $table->dateTime('to');
            $table->unsignedInteger('availability_id');
            $table->unsignedInteger('privacy_id');
            $table->unsignedInteger('type_id');
            $table->string('location');
            $table->text('detail');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('availability_id')->references('id')->on('availabilities')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('privacy_id')->references('id')->on('privacies')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('type_id')->references('id')->on('types')
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
        Schema::dropIfExists('tasks');
    }
}
