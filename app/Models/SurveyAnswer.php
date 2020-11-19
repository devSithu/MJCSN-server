<?php

namespace App\Models;

class SurveyAnswer extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'survey_question_id',
        'survey_answer_id',
        'content',
        'is_other',
        'is_exclusion',
    ];

    public function survey_visitor_question_answers()
    {
        return $this->hasMany('App\Models\SurveyVisitorQuestionAnswer');
    }

    public function survey_question()
    {
        return $this->belongsTo('App\Models\SurveyQuestion', 'survey_question_id', 'survey_question_id');
    }

}
