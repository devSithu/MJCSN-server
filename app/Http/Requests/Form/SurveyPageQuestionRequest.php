<?php

namespace App\Http\Requests\Form;

use App\Models\CommunityUser;
use Illuminate\Foundation\Http\FormRequest;

class SurveyPageQuestionRequest extends FormRequest
{
    protected $visitor;

    public function __construct()
    {
        $this->visitor = CommunityUser::where([
            ['user_number', \Request::input('user_number')],
        ])->first();
    }

    public function authorize()
    {
        return true;
    }

    public function withValidator($validator)
    {
        $validator->addExtension('exist_user_number', function ($attribute, $value) {
            if (!empty($this->visitor)) {
                return true;
            }
            return false;
        });
        $validator->addExtension('is_full_name_user_number', function ($attribute, $value) {
            if (!empty($this->visitor) && ($this->visitor['user_name'] == \Request::input('user_name'))) {
                return true;
            }
            return false;
        });
    }

    public function rules()
    {
        $rules = [];
        $rules['user_name'] = '';

        if (!empty($this->visitor) && ($this->visitor['user_name'] && $this->visitor['user_name'] != \Request::input('user_name'))) {
            $rules['user_name'] = 'required|';
        }
        $rules['user_name'] = $rules['user_name'] . 'is_full_name_user_number';
        $rules['user_number'] = 'required|numeric|exist_user_number';
        return $rules;
    }

    public function attributes()
    {
        return [
            'user_number' => '来場証No',
            'user_name' => '氏名',
        ];
    }

    public function messages()
    {
        return [
            'user_number.required' => ':attributeを入力してください。',
            'user_name.required' => ':attributeを入力してください。',
            'user_number.exist_user_number' => ':attributeは正確に入力してください。',
            'user_name.is_full_name_user_number' => ':attributeは正確に入力してください。',
        ];
    }
}
