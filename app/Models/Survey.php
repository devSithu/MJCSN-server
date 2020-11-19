<?php

namespace App\Models;

class Survey extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'survey_id',
        'name',
        'url',
        'start_datetime',
        'end_datetime',
        'start_screen_message',
        'finish_screen_message',
        'created_at',
        'created_by',
        'updated_by',
    ];

    public function survey_questions()
    {
        return $this->hasMany('App\Models\SurveyQuestion');
    }

    public function survey_visitors()
    {
        return $this->hasMany('App\Models\SurveyVisitor');
    }

    public function survey_answers()
    {
        return $this->hasManyThrough('App\Models\SurveyAnswer', 'App\Models\SurveyQuestion');
    }

}
