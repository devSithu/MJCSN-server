@extends('layouts.surveyanswer.main')
@section('content')

<div class="row justify-content-center mt100">
  <ol>
  @foreach ($surveys as $survey)
    <li>
      <a href="{{ route('qvisitor_show_survey', ['url' =>  $survey->url]) }}{{ $user_number ? '?user_number='.$user_number : null}}" style="text-decoration: underline;" target="_blank">
        {{ route('qvisitor_show_survey', ['url' =>  $survey->url]) }}
      </a>
    </li>
  @endforeach
  </ol>
</div>

@endsection

