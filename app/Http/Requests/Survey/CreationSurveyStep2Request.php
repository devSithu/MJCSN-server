<?php

namespace App\Http\Requests\Survey;

use CpsForm;
use Illuminate\Foundation\Http\FormRequest;

class CreationSurveyStep2Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $errors = $validator->errors();

            $validation_data = parent::validationData();
            if (empty(CpsForm::input("survey_questions"))) {
                $errors->add('survey_questions', cps_trans('validation.survey_question_required'));
            }
        });
    }

    public function rules()
    {
        $rules = [];

        return $rules;
    }
}
