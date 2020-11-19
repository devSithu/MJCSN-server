@extends('layouts.surveyanswer.main')

@section('content')
  <div class="page-header qb-page-header qb-sub-page-header survey-name">
    @if (!empty($communityuser))
      {{ $communityuser->user_name }}
    @endif
    <h1 class="text-center">{{ $h_survey->name }}</h1>
  </div>
  <div class="form-horizontal mb30">
    <form method="post" action="{{ route('validate_question', ['url' => \Route::input('url')]) }}" id="form">
      {{ csrf_field() }}
      <input type="hidden" name="user_number" value="{{ $communityuser ? $communityuser->user_number :  null}}">
      <input type="hidden" name="page_number" value="{{ $page_number }}">
      <input type="hidden" name="total_page" value="{{ $total_page }}">
      <input type="hidden" name="current_order" value="{{ isset($current_order) ? $current_order : '' }}">
      @php($order_question = (isset($current_order)) ? ($current_order+1) : 1)
      @php($old_value_arr = [])
      @foreach($questions as $key => $question)
        <input type="hidden" name="question_id[]" value="{{ $question['survey_question_id'] }}">
        <div class="form-group">
          <div class="_wrapper">
            <p class="{{ in_array(1, $question->survey_question_validation_rules->pluck("validation_rule_id")->toArray()) ? 'required' : '' }}"><span class="font-weight-bold">Q{{ $order_question++ }}.</span><span class="font-weight-bold">{{$question['content']}}</span></p>
          </div>
          <div class="col-sm-12 _wrapper">
            <div class="col-sm-12 _wrapper">
              @if($question['data_type_id'] == \App\Models\Config::QUESTION_TEXT)
                <input type="text" class="form-control name-control name-first md-input" placeholder="" name="answer_question_{{ $question['survey_question_id'] }}">
                @php($old_value_arr['answer_question_'.$question['survey_question_id']] = CpsForm::oldOrSession('answer_question_'.$question['survey_question_id']))
              @endif

              @if($question['data_type_id'] == \App\Models\Config::QUESTION_TEXTAREA)
                <textarea class="form-control" name="answer_question_{{ $question['survey_question_id'] }}" style="height: 150px">{{ CpsForm::oldOrSession('answer_question_'.$question['survey_question_id']) }}</textarea>
                @php($old_value_arr['answer_question_'.$question['survey_question_id']] = CpsForm::oldOrSession('answer_question_'.$question['survey_question_id']))
              @endif

              @if($question['data_type_id'] == \App\Models\Config::QUESTION_RADIO)
                @foreach($question->survey_answers as $answer)
                  <div class="radio" tappable>
                    <label><input type="radio" name="answer_question_{{ $question['survey_question_id'] }}" value="{{ $answer->survey_answer_id }}"
                      {{ old('answer_question_'.$question['survey_question_id']) == $answer->survey_answer_id ? 'checked' : '' }}>{{ $answer->content }}</label>
                    @if($answer['is_other'])
                      <input type="text" class="form-control qb-form-input-other mt10" name="answer_question_{{ $question['survey_question_id'] }}_other[{{ $answer->survey_answer_id }}]"
                      value="{{ old('answer_question_'.$question['survey_question_id'].'_other.'.$answer->survey_answer_id) }}">
                    @endif
                  </div>
                @endforeach
              @endif

              @if($question['data_type_id'] == \App\Models\Config::QUESTION_SELECT)
                <select class="form-control select-answer-question" name="answer_question_{{ $question['survey_question_id'] }}" >
                  <option value=""></option>
                  @foreach($question->survey_answers as $answer)
                    <option value="{{ $answer->survey_answer_id }}" {{ old('answer_question_'.$question['survey_question_id']) == $answer->survey_answer_id ? 'selected' : '' }}>{{ $answer->content }}</option>
                  @endforeach
                </select>
                @foreach($question->survey_answers as $answer)
                  @if($answer['is_other'])
                    <input type="text" class="form-control qb-form-input-other mt10" name="answer_question_{{ $question['survey_question_id'] }}_other[{{ $answer->survey_answer_id }}]"
                    value="{{ old('answer_question_'.$question['survey_question_id'].'_other.'.$answer->survey_answer_id) }}">
                  @endif
                @endforeach
              @endif

              @if($question['data_type_id'] == \App\Models\Config::QUESTION_CHECKBOX)
                @foreach($question->survey_answers as $answer)
                  <div class="checkbox" tappable>
                    <label>
                      <input type="checkbox" class="answer_question_{{ $question['survey_question_id'] }} @if($answer['is_exclusion'] == true) exclusion_answer @endif" name="answer_question_{{$question['survey_question_id']}}[]" value="{{$answer->survey_answer_id}}"
                        @if(old('answer_question_'.$question['survey_question_id']) && in_array($answer->survey_answer_id, old('answer_question_'.$question['survey_question_id']))) checked @endif>{{$answer->content}}</label>
                    @if($answer['is_other'])
                      <input type="text" class="form-control qb-form-input-other mt10" name="answer_question_{{$question['survey_question_id']}}_other[{{$answer->survey_answer_id}}]"
                      value="{{ old('answer_question_'.$question['survey_question_id'].'_other.'.$answer->survey_answer_id) }}">
                    @endif
                  </div>
                @endforeach
              @endif

              @if ($errors->has('answer_question_'.$question['survey_question_id']))
                {!! CpsForm::printErrorMessage($errors->first('answer_question_'.$question['survey_question_id'])) !!}
              @endif
            </div>
          </div>
        </div>
      @endforeach
      <div class="text-center mt50">
        <button type="submit" class="btn btn-danger" name="{{ ($page_number == $total_page) ? 'အဖြေကိုပေးပို့မည်' : 'နောက်တစ်ခု' }}">@if($page_number == $total_page) အဖြေကိုပေးပို့မည် @else နောက်တစ်ခု @endif
          <div class="ripple-container"></div>
        </button>
      </div>
    </form>
  </div>
@endsection
@section("js")
@token
@scope([
  'survey_id' => $h_survey->survey_id,
  'old_value_arr' => $old_value_arr
])
<script src="/js/form/survey/question_survey.js"></script>
@endsection