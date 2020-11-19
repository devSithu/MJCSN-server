@extends('layouts.surveyanswer.main')

@section('content')
  <div class="page-header qb-page-header qb-sub-page-header survey-name">
    <h1 class="text-center">{{ $survey->name }}</h1>
  </div>
  <div class="text-center">
    <div class="finish-survey-msg">{{ $survey->finish_screen_message }}</div>
  </div>
@endsection
@section("js")
@token
@scope([
  'survey_id' => $survey->survey_id,
])
<script src="/js/form/survey/finish_survey.js"></script>
@endsection