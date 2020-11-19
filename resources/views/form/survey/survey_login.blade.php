@extends('layouts.surveyanswer.main')

@section('content')

  <div class="page-header mt50">
    <h1 class="text-center">{{ $survey->name }}</h1>
  </div>

  <div class="answer-time text-center">
    <h3 class="list-title">ဖြေဆိုရန် လက်ခံမည့် အချိန်</h3> <br>
    <h5 class="list-title">{{ format_datetime($survey->start_datetime) }} 〜 {{ format_datetime($survey->end_datetime) }}</h5>
  </div>

  @if ((strtotime(format_datetime($survey->end_datetime)) < strtotime(format_datetime(date('Y-m-d H:i')))) || (strtotime(format_datetime($survey->start_datetime)) > strtotime(format_datetime(date('Y-m-d H:i')))))
    <div class="answer-survey-description text-center">
      <p class="list-title">
        <h3>လက်ရှိအချိန်တွင် ဖြေဆိုရန်မရသေးပါ</h3>
      </p>
    </div>
  @else
    <div class="answer-survey-description text-center">
      <p class="list-title start-survey-msg">{{ $survey->start_screen_message }}</p>
    </div>
    @if (isset($user_answered))
      <div class="answer-survey-description text-center">
        <p class="list-title">{{ $user_answered }}</p>
      </div>
    @else
      <div class="form-horizontal">
        <form method="post" action="{{ route('qvisitor_login_question_survey', ['url' => \Route::input('url')]) }}" id="form">
          {{ csrf_field() }}    
          @if(isset($communityuser))
            <input type="hidden" class="form-control" name="user_number" value="{{ $communityuser->user_number }}">
          @endif
          @if(isset($communityuser))
            <input type="hidden" class="form-control name-control name-first" name="user_name" value="{{ $communityuser->user_name }}">
          @endif
          <div class="text-center mt10">
            <button type="submit" class="btn btn-danger" id="survey-btn">Answer with consent</button>
          </div>
        </form>
      </div>
    @endif
  @endif
@endsection
@section("js")
<script src="/js/form/survey/survey_login.js"></script>
@endsection
