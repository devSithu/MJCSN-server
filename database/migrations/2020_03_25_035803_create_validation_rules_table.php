<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValidationRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('validation_rules', function (Blueprint $table) {
            $table->increments('validation_rule_id');
            $table->string('view_name', 50);
            $table->string('rule_name', 50);
            $table->boolean('is_data_format')->nullable()->default(false);
            $table->integer('order')->nullable();
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
        Schema::dropIfExists('validation_rules');
    }
}
