<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyVisitorsTable extends Migration
{
    protected $table = "survey_visitors";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            //id
            $table->increments('survey_visitor_id');
            //foreign key
            $table->integer('survey_id');
            //community user
            $table->integer('user_number');
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
