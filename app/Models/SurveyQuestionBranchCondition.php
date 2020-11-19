<?php

namespace App\Models;

class SurveyQuestionBranchCondition extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'survey_question_id',
        'branch_question_id',
        'branch_answer_id',
    ];

    public function survey_question()
    {
        return $this->belongsTo('App\Models\SurveyQuestion', 'survey_question_id', 'survey_question_id');
    }

    public function survey_branch_question()
    {
        return $this->belongsTo('App\Models\SurveyQuestion', 'branch_question_id', 'survey_question_id');
    }

    public function survey_answer()
    {
        return $this->belongsTo('App\Models\SurveyAnswer', 'branch_answer_id', 'survey_answer_id');
    }

}
