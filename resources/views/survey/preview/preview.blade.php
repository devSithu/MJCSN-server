@extends('layouts.surveyanswer.main')

@section('css')
  <link href="/css/survey/preview/preview.css" rel="stylesheet">
@endsection
@section('content')
<div class="page-header qb-page-header qb-sub-page-header survey-name">
  <h1 class="text-center">{{ $survey_name }}</h1>
</div>
<div class="form-horizontal mb30">
  <form method="post"  @if ($page == $total_page) action="{{ route('preview_finish_page_survey_question') }}" @else action="{{ route('preview_next_page_survey_question', ['page' => ($page + \App\Models\Config::NEXT_PAGE_SURVEY_QUESTION)]) }}"  @endif id="form">
    {{ csrf_field() }}
    <input type="hidden" name="page" value="{{ $page }}">
    <input type="hidden" name="total_page" value="{{ $total_page }}">
    <input type="hidden" name="list_answer" value="{{ $list_answer }}">
    <input type="hidden" name="order_current_question" value="{{ isset($order_current_question) ? $order_current_question : '' }}">
    @foreach($questions as $key => $question)
      @php($choices = explode("\n", $question['choices'] ?? ''))
      @php($choices = array_map('trim', $choices))
      @php($choices = array_values(array_filter($choices, 'strlen')))
      <div class="form-group">
        <div class="_wrapper">
          <p class="{{ isset($question['required']) ? 'required' : '' }}">
            <span style="font-weight: bold">Q{{ $order_question + $key + 1 }}.</span>
            <span style="font-weight: bold">{{ $question['label'] }}</span>
          </p>
        </div>
        <div class="col-sm-12 _wrapper">
          <div class="col-sm-12 _wrapper content_answers" data-required="{{ isset($question['required']) ? 1 : 0 }}" data-type_id="{{ $question['data_type_id'] }}">
            @if ($question['data_type_id'] == \App\Models\Config::QUESTION_TEXT)
              <p class="error_message"></p>
              <input type="text" class="form-control">
            @endif

            @if ($question['data_type_id'] == \App\Models\Config::QUESTION_TEXTAREA)
              <p class="error_message"></p>
              <textarea class="form-control" style="height: 150px"></textarea>
            @endif

            @if ($question['data_type_id'] == \App\Models\Config::QUESTION_RADIO)
              <p class="error_message"></p>
              @foreach($choices as $choice_key => $answer)
                <div class="radio">
                  <label>
                    <input type="radio" name="answer_question[{{ $question['order'] }}]" value="{{ $choice_key }}">{{ $answer }}
                  </label>
                </div>
              @endforeach
              @if (isset($question['allow_other']))
                <div class="radio">
                  <label>
                    <input type="radio" name="answer_question[{{ $question['order'] }}]" value="{{ count($choices) }}">
                    その他
                  </label>
                  <input type="text" class="form-control qb-form-input-other mt15">
                </div>
              @endif
            @endif

            @if ($question['data_type_id'] == \App\Models\Config::QUESTION_SELECT)
              <p class="error_message"></p>
              <select class="form-control select-answer-question" name="answer_question[{{ $question['order'] }}]">
                <option value=""></option>
                @foreach($choices as $key => $answer)
                  <option value="{{ $key }}">{{ $answer }}</option>
                @endforeach
                @if (isset($question['allow_other']))
                  <option value="{{ count($choices) }}">その他</option>
                @endif
              </select>
              @if (isset($question['allow_other']))
                <input type="text" class="form-control qb-form-input-other mt15">
              @endif
            @endif

            @if($question['data_type_id'] == \App\Models\Config::QUESTION_CHECKBOX)
              <p class="error_message"></p>
              @foreach($choices as $checkbox_key => $answer)
              <div class="checkbox">
                <label>
                  <input type="checkbox" class="answer_question_[{{ $question['order'] }}] @if ($question['is_exclusion'] == $checkbox_key) exclusion_answer @endif" name="answer_question[{{ $question['order'] }}][]" value="{{ $checkbox_key }}"/>{{ $answer }}
                </label>
              </div>
              @endforeach
              @if(isset($question['allow_other']))
                <div class="checkbox">
                  <label>
                    <input type="checkbox" class="answer_question_[{{ $question['order'] }}] @if ($question['is_exclusion'] == count($choices)) exclusion_answer @endif" name="answer_question[{{ $question['order'] }}][]" value="{{ count($choices) }}">
                    その他
                  </label>
                  <input type="text" class="form-control qb-form-input-other mt15">
                </div>
              @endif
            @endif
          </div>
        </div>
      </div>
    @endforeach
    <div class="text-center" style="margin-top: 50px">
      <button type="submit" id="submit_next_preview" class="btn btn-danger">@if($page == $total_page) 回答を送信 @else 次へ @endif
        <div class="ripple-container"></div>
      </button>
    </div>
  </form>
</div>
@endsection
@section("js")
  @scope([
    'data_types' => [
      'text' => \App\Models\Config::QUESTION_TEXT,
      'text_area' => \App\Models\Config::QUESTION_TEXTAREA,
      'select' => \App\Models\Config::QUESTION_SELECT,
      'radio' => \App\Models\Config::QUESTION_RADIO,
      'checkbox' => \App\Models\Config::QUESTION_CHECKBOX,
    ]
  ])
  <script src="/js/survey/preview/preview.js"></script>
@endsection
