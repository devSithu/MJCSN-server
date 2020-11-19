<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppAccessLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_access_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('host', 50)->unique();
            $table->string('request_method', 10);
            $table->text('request_route');
            $table->string('status', 3);
            $table->string('response_bytes', 20);
            $table->string('referer', 255);
            $table->string('user_agent', 255);
            $table->string('received_bytes', 20);
            $table->string('sent_bytes', 20);
            $table->string('x_user_id', 20)->nullable();
            $table->string('x_func', 100)->nullable();
            $table->string('x_action', 100)->nullable();
            $table->text('x_param')->nullable();
            $table->string('x_version', 10)->nullable();
            $table->string('x_os', 50)->nullable();
            $table->string('x_osversion', '10')->nullable();
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_access_log');
    }
}
