<?php

namespace App\Models;

class SurveyQuestionValidationRule extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'servey_question_id',
        'validation_rule_id',
        'parameter',
    ];

    public function survey_question()
    {
        return $this->belongsTo('App\Models\SurveyQuestion');
    }

    public function validation_rule()
    {
        return $this->belongsTo('App\Models\ValidationRule');
    }
}
