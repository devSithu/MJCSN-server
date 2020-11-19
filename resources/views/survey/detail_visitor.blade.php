@extends('layouts.app')

@section('content')
@include('survey.include.survey_detail_menu')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        @include('survey.include.list_visitor_answer')
      </div>
    </div>
  </div>
</div>
@endsection
