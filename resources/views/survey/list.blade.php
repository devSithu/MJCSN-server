@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="/css/survey/list.css">
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="qb-container" role="main">
          <div class="qb-list-container">
            <div class="">
              <div id="main_table">
                <table class="qb-form-table qb-list-table trim-text">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>アンケート名</th>
                      <th>回答数</th>
                      <th>回答受付期間</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th colspan="4">
                        <div class="btn-add-group">
                          <a id="btn_add_dropdown" href="{{ route('user_show_create_survey_form1') }}" class="btn btn-danger">
                            アンケートの追加<i class="material-icons">add</i>
                          </a>
                        </div>
                      </th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
  @scope([
    'url_user_show_survey_detail' => route('url_user_show_survey_detail', ['survey_id' => '@survey_id']),
    'data' => $list_survey,
    'count_survey' => $list_survey->count(),
  ])
  <script src="/js/survey/list.js"></script>
@endsection