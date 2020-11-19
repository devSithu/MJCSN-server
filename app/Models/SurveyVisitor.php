<?php

namespace App\Models;

class SurveyVisitor extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'survey_id',
        'user_number',
    ];

    public function survey()
    {
        return $this->belongsTo('App\Models\Survey');
    }

    public function survey_visitor_question_answers()
    {
        return $this->hasMany('App\Models\SurveyVisitorQuestionAnswer');
    }

    public function community_user()
    {
        return $this->belongsTo('App\Models\CommunityUser', 'user_number', 'user_number');
    }
}
