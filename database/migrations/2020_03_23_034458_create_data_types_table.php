<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_types', function (Blueprint $table) {
            $table->increments('data_type_id');
            $table->string('name',50);
            $table->string('input_type',50);
            $table->boolean('is_freeanswer')->nullable()->default(false);
            $table->integer('order')->nullable();
            $table->boolean('is_view')->default(true);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('data_types');
    }
}
