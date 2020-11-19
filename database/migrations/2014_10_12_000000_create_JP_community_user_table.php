<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJPCommunityUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_user', function (Blueprint $table) {
            $table->increments('user_number');
            $table->string('login_id', 50);
            $table->string('password', 50)->nullable(true);
            $table->tinyInteger('user_type');
            $table->string('user_name', 50);
            $table->string('gender', 1)->nullable(true);
            $table->date('date_of_birth');
            $table->string('nrc_number', 50)->nullable(true);
            $table->string('graduated_from', 100)->nullable(true);
            $table->string('graduated_dep', 100)->nullable(true);
            $table->string('graduated_year', 100)->nullable(true);
            $table->string('region', 50)->nullable(true);
            $table->string('address', 200)->nullable(true);
            $table->string('phone_number', 20)->nullable(true);
            $table->string('email', 100)->nullable(true);
            $table->string('connect_sns', 50)->nullable(true);
            $table->longText('nrc_image')->nullable(true);
            $table->string('career',50)->nullable(true);
            $table->integer('status');
            $table->string('answer_one', 256);
            $table->string('answer_two', 256);
            $table->string('answer_three', 256);
            $table->string('answer_four',256);
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
        Schema::dropIfExists('community_user');
    }
}
