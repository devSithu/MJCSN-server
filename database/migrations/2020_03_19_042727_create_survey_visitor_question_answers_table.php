<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyVisitorQuestionAnswersTable extends Migration
{
    protected $table = "survey_visitor_question_answers";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            //id
            $table->increments('survey_visitor_question_answer_id');
            //foreign key
            $table->integer('survey_visitor_id');
            $table->integer('survey_question_id');
            $table->integer('survey_answer_id')->nullable();
            $table->text('content')->nullable();
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
