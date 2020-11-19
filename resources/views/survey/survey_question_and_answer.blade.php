@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="/css/survey/survey_question_and_answer.css">
@endsection

@section('content')
@include('survey.include.survey_detail_menu')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        @foreach($list_survey_question as $list)
          <div class="qb-answer-dashboard">
            @if (in_array($list['data_type_id'], [\App\Models\Config::QUESTION_TEXT,\App\Models\Config::QUESTION_TEXTAREA])
            || ((in_array($list['data_type_id'], [\App\Models\Config::QUESTION_SELECT,\App\Models\Config::QUESTION_RADIO,\App\Models\Config::QUESTION_CHECKBOX])
              && $list['survey_visitor_question_answers_count'] == 0)))
              <div class="row">
                <div class="survey-question-order">
                  <span>Q{{ $list['order'] }}</span>
                </div>
                <div class="col-xs-11 survey-question-content">
                  <span>{{ $list['content'] }}</span><br>
                  回答数：{{ count($list->survey_visitor_question_answers) }}
                  無回答：{{ $total_visitor_answersed - count($list->survey_visitor_question_answers) }}<br>
                </div>
                <div class="float-right">
                  <div class="qb-list-option-right survey-button-list">
                    @if (in_array($list['data_type_id'], [\App\Models\Config::QUESTION_TEXT,\App\Models\Config::QUESTION_TEXTAREA]))
                      <a class="_can_download_icon btn btn-danger download-btn"
                        href="{{ route('user_download_csv_survey_answer_question_text', ['survey_question_id' => $list['survey_question_id'], 'survey_id' => $survey->survey_id]) }}">
                        ダウンロード<i class="material-icons">get_app</i>
                      </a>
                    @endif
                  </div>
                </div>
              </div>
            @elseif (in_array($list['data_type_id'], [\App\Models\Config::QUESTION_SELECT,\App\Models\Config::QUESTION_RADIO,\App\Models\Config::QUESTION_CHECKBOX]))
            <div class="float-right">
            <button type="button" 
                class="_can_download_icon btn btn-danger download-btn exportButton"
                data-chart_id="chart-{{ $list['survey_question_id'] }}" 
                data-question_order = {{ $list['order'] }} 
                data-title="グラフをPDFダウンロード" 
                data-type="download" 
                style="position: absolute;right: 20px; z-index: 1000; top: 20px;">
                ダウンロード<i class="material-icons">get_app</i>
              </button>
            </div>
            @endif

            <div class="">
              @if (in_array($list['data_type_id'], [\App\Models\Config::QUESTION_TEXT,\App\Models\Config::QUESTION_TEXTAREA]))
                <div class="panel-body">
                  @include('survey.include.statistics_answer_text')
                </div>
              @elseif (in_array($list['data_type_id'], [\App\Models\Config::QUESTION_SELECT,\App\Models\Config::QUESTION_RADIO]))
                <div class="panel-body-select-radio">
                  @if ($list['survey_visitor_question_answers_count'] > 0)
                    @include('survey.include.statistics_answer_select_or_radio')
                  @else
                    <div class="ml50"> データが登録されていません</div>
                  @endif
                </div>
              @elseif ($list['data_type_id'] == \App\Models\Config::QUESTION_CHECKBOX)
                <div class="panel-body-select-radio">
                  @if ($list['survey_visitor_question_answers_count'] > 0)
                    @include('survey.include.statistics_answer_check_box')
                  @else
                    <div class="ml50"> データが登録されていません</div>
                  @endif
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
  @scope([
    'survey_name' => $survey->name,
  ])
  <script src="/js/survey/survey_question_and_answer.js"></script>
@endsection
