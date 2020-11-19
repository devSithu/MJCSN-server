<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyAnswersTable extends Migration
{
    protected $table = "survey_answers";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            //id
            $table->increments('survey_answer_id');
            //foreign key
            $table->integer('survey_question_id');
            $table->text('content');
            $table->boolean('is_other')->default(false);
            $table->boolean('is_exclusion')->default(false);
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
