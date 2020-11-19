<?php

namespace App\Models;

class SurveyVisitorQuestionAnswer extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'survey_visitor_id',
        'survey_question_id',
        'content',
        'survey_answer_id',
    ];

    public function survey_visitor()
    {
        return $this->belongsTo('App\Models\SurveyVisitor');
    }

    public function survey_question()
    {
        return $this->belongsTo('App\Models\SurveyQuestion');
    }

    public function survey_answer()
    {
        return $this->belongsTo('App\Models\SurveyAnswer');
    }
}
