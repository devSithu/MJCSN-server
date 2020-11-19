<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyQuestionValidationRulesTable extends Migration
{
    protected $table = "survey_question_validation_rules";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            //id
            $table->increments('survey_question_validation_rule_id');
            //foreign key
            $table->integer('survey_question_id');
            $table->integer('validation_rule_id');
            $table->string('parameter', 255)->nullable();
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
        Schema::dropIfExists($this->table);
    }
}
