<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class CreationSurveyStep1Request extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|max:255',
            'url' => 'required|max:255|regex:/^[a-zA-Z0-9_\-.]+$/|unique:surveys,url,' . ($this->request->get('survey_id') ? $this->request->get('survey_id') : -1) . ',survey_id',
            'open_date' => 'required|date_format:Y/m/d',
            'open_hour' => ['required', 'regex:/^([0-9]|1[0-9]|2[0-3])$/'],
            'open_minute' => ['required', 'regex:/^[0-9]|[0-5][0-9]$/'],
            'end_date' => 'required|date_format:Y/m/d|after:open_date',
            'end_hour' => ['required', 'regex:/^([0-9]|1[0-9]|2[0-3])$/'],
            'end_minute' => ['required', 'regex:/^[0-9]|[0-5][0-9]$/'],
            'start_screen_message' => 'max:10000',
            'end_screen_message' => 'max:10000',
        ];

        return $rules;
    }

    public function messages()
    {
        return ['date_format' => ':attributeはYYYY/mm/dd HH:iiの形式で正確に入力してください。',
            'end_date.after' => '受付終了日は、受付開始日の翌日以降にしてください。',
            'url.regex' => ':attributeは英数字と使用可能な記号（- _ .）のみで設定してください。'];
    }

    public function attributes()
    {
        return [
            'name' => '名称',
            'open_date' => '開始日時',
            'open_hour' => '開始日時',
            'open_minute' => '開始日時',
            'end_date' => '終了日時',
            'end_hour' => '終了日時',
            'end_minute' => '終了日時',
            'start_screen_message' => '開始画面メッセージ',
            'end_screen_message' => '終了画面メッセージ',
        ];
    }
}
