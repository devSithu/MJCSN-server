<?php

namespace App\Models;

class SurveyQuestion extends BaseModel
{
    const COLOR = ['#EA5532', '#00AFEC', '#F6AD3C', '#00A95F', '#FFF33F', '#4D4398', '#00ADA9', '#E85298', '#187FC4', '#AACF52',
        '#CF7250', '#49AAD2', '#D7A861', '#5AA572', '#DED46E', '#695C98', '#53A8A6', '#CE749C', '#5D87B7', '#A7BE70',
        '#EF845C', '#54C3F1', '#F9C270', '#69BD83', '#FFF67F', '#796BAF', '#61C1BE', '#EE87B4', '#6C9BD2', '#C1DB81',
        '#BD6748', '#419CC0', '#C49958', '#519768', '#CAC264', '#60538C', '#4A9A98', '#BC698F', '#547BA8', '#98AE66',
        '#F5B090', '#9FD9F6', '#FCD7A1', '#A5D4AD', '#FFF9B1', '#A59ACA', '#A2D7D4', '#F4B4D0', '#A3BCE2', '#D7E7AF'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'survey_id',
        'content',
        'data_type_id',
        'order',
        'page',
    ];

    public function survey()
    {
        return $this->belongsTo('App\Models\Survey');
    }

    public function survey_visitor_question_answers()
    {
        return $this->hasMany('App\Models\SurveyVisitorQuestionAnswer');
    }

    public function survey_answers()
    {
        return $this->hasMany('App\Models\SurveyAnswer');
    }

    public function survey_question_branch_conditions()
    {
        return $this->hasMany('App\Models\SurveyQuestionBranchCondition');
    }

    public function survey_question_validation_rules()
    {
        return $this->hasMany('App\Models\SurveyQuestionValidationRule');
    }

    public function data_type()
    {
        return $this->belongsTo('App\Models\DataType');
    }

    public function validation_rules()
    {
        return $this->belongsToMany('App\Models\ValidationRule', 'survey_question_validation_rules', 'survey_question_id', 'validation_rule_id')
            ->withPivot("parameter");
    }

    public function isRequired()
    {
        $item = $this->validation_rules->filter(function ($item, $key) {
            return $item->isRequired();
        });

        return $item->count() > 0;
    }

}
