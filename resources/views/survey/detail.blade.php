@extends('layouts.app')
@section('css')
  <link href="/css/survey/detail.css" rel="stylesheet">
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="qb-container">
          <div class="form-btn-triple-group mb20">
            <div class="btn-triple-group">
              <a id="delete_next_button" 
              @if ($survey->survey_visitors_count > 0) disabled class="btn btn-outline-danger delete-btn disabled"
              @else data-toggle="modal" data-target="#delete_modal" class="btn btn-outline-danger delete-btn"
              @endif>
                アンケートの削除 
                <i class="material-icons survey-delete">delete</i>
              </a>
            </div>
          </div>

          <div class="qb-card-content">
            <h4 class="qb-list-title">アンケート概要</h4>        
            <a id="edit_next_button"
            @if (($survey->survey_visitors_count) > 0) disabled class="btn btn-danger btn-edit disabled"
            @else href="{{ route('user_setting_editing_survey', ['survey_id' => $survey->survey_id]) }}" class="btn btn-danger btn-edit"
            @endif>
              編集
            </a>
            <table class="qb-form-table">
              <tr>
                <td class="box-10">名称</td>
                <td class="box-20">{{$survey->name}}</td>
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
      
          <div class="modal qb-modal fade" id="delete_modal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content qb-modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">アンケート削除</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body qb-modal-body">
                  <form class="form-horizontal qb-form" method="POST"
                    action="{{ route('user_delete_survey',['survey_id' => $survey->survey_id]) }}">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}
                    <i class="material-icons survey-noti">error</i>
                    <div class="text-center">
                      <span class="text-danger">アンケートを削除しますか？</span>
                    </div>
                    <div class="modal-footer">
                      <button data-bb-handler="cancel" type="button" class="btn btn-success" data-dismiss="modal">
                        キャンセル
                      </button>
                      <button data-bb-handler="confirm" type="submit" class="btn btn-danger" id="delete_button">
                        確認
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section("js")
  @scope([
    'url_code' => 'data:image/png;base64,'.base64_encode(QrCode::format('png')->size(200)->generate(route('qvisitor_show_survey', ['']).'/'.$survey->url))
  ])
  <script src="/js/survey/detail.js"></script>
@endsection
