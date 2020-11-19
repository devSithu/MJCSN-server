<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_log', function (Blueprint $table) {
            $table->bigIncrements('action_id');
            $table->integer('user_number');
            $table->string('action', 100);
            $table->timestamp('action_at');
            $table->string('parameter', 256);
            $table->integer('point');
            $table->string('os', 50);
            $table->string('os_version', 50);
            $table->string('app', 50);
            $table->string('app_version', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('action_log');
    }
}
