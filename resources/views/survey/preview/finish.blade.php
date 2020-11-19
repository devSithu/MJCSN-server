@extends('layouts.surveyanswer.main')
@section('content')
<div class="page-header qb-page-header qb-sub-page-header survey-name">
  <h1 class="text-center">{{ $survey_name }}</h1>
</div>
<div class="text-center">
  <div class="finish-survey-msg-preview">{{ $finish_screen_message }}</div>
</div>
@endsection
