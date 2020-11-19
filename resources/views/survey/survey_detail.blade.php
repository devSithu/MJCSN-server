@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="/css/survey/survey_detail.css">
@endsection

@section('content')
@include('survey.include.survey_detail_menu')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        <div class="form-btn-triple-group">
          <div class="btn-triple-group">
            <a href="{{ route('user_download_csv_survey', ['survey_id' => $survey->survey_id]) }}" class="btn btn-outline-danger download-btn">
              回答ダウンロード 
              <i class="material-icons">get_app</i>
            </a>
            <a href="{{ route('user_download_csv_survey_question', ['survey_id' => $survey->survey_id]) }}" 
            class="btn btn-outline-danger download-btn btn-correspondence">
              対応表ダウンロード 
              <i class="material-icons">get_app</i>
            </a>
          </div>
        </div>

        <div class="qb-card-content mb20">
          <h4 class="qb-list-title">アンケート概要</h4>
          <table class="qb-form-table">
            <tr>
              <td class="box-10">名称</td>
              <td class="box-20">{{ $survey->name }}</td>
            </tr>
            <tr>
              <td class="box-10">回答URL</td>
              <td class="box-20">
                <a href="{{ route('qvisitor_show_survey', ['']) }}/{{ $survey->url }}" target="_blank">
                  {{ route('qvisitor_show_survey', ['']) }}/{{ $survey->url }}
                </a>
                <button id='btn_download_url_code' type="button" class="btn btn-outline-danger btn-sm _can_download" data-title="QRコード発行">
                  QRコード発行
                </button>
              </td>
            </tr>
            <tr>
              <td class="box-10">回答数</td>
              <td class="box-20">{{ $total_visitor_answersed }}人</td>
            </tr>
            <tr>
              <td class="box-10">回答受付期間</td>
              <td class="box-20">{{ format_datetime($survey->start_datetime) }} 〜 {{ format_datetime($survey->end_datetime) }}</td>
            </tr>
            <tr>
              <td class="box-10">開始画面メッセージ</td>
              <td class="box-20">{{ $survey->start_screen_message }}</td>
            </tr>
            <tr>
              <td class="box-10">終了画面メッセージ</td>
              <td class="box-20">{{ $survey->finish_screen_message }}</td>
            </tr>
          </table>
        </div>

        <div class="qb-card-content">
          <h4 class="qb-list-title">アンケート項目</h4>
          @if (count($list_survey_question))
            @include('survey.include.question_content')
          @endif
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
  @scope([
    'url_code' => 'data:image/png;base64,'.base64_encode(QrCode::format('png')->size(200)->generate(route('qvisitor_show_survey', ['']).'/'.$survey->url))
  ])
  <script src="/js/survey/survey_detail.js"></script>
@endsection
