<?php
namespace App\Http\Requests\Form;

use App\Models\Survey;
use Illuminate\Foundation\Http\FormRequest;
use Route;

class SurveyNextPageQuestionRequest extends FormRequest
{

    protected $questions;

    public function __construct()
    {
        $survey = Survey::where('url', Route::input('url'))->first();
        $this->questions = $survey->survey_questions->where('page', \Request::input('page_number'));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];
        foreach ($this->questions as $question) {
            if (in_array($question->survey_question_id, \Request::input('question_id'))) {
                $rules['answer_question_' . $question->survey_question_id] = 'nullable';
                foreach ($question->survey_question_validation_rules as $question_validation_rule) {
                    $rules['answer_question_' . $question->survey_question_id] .= '|' . $question_validation_rule->validation_rule->rule_name;
                    if ($question_validation_rule->validation_rule_id == 13) {
                        $rules['answer_question_' . $question->survey_question_id] .= ':' . $question_validation_rule->parameter;
                    }
                }
            }
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [];
        foreach ($this->questions as $question) {
            foreach ($question->survey_question_validation_rules as $question_validation_rule) {
                switch ($question_validation_rule->validation_rule->rule_name) {
                    case 'max_length':
                        $messages['answer_question_' . $question->survey_question_id . '.' . $question_validation_rule->validation_rule->rule_name] = ':max文字以下で入力してください。';
                        break;

                    case 'required':
                        break;

                    default:
                        $messages['answer_question_' . $question->survey_question_id . '.' . $question_validation_rule->validation_rule->rule_name] = $question_validation_rule->validation_rule->view_name . 'で入力してください。';
                        break;
                }
            }
        }
        return $messages;
    }
}
