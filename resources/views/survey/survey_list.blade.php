@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="qb-container" role="main">
          <div class="qb-list-container">
            <div>
              <div id="main_table" class="mb20">
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
    'url_user_show_survey_detail'  => route('url_user_show_survey_detail_data', ['survey_id' => '@survey_id']),
    'data'  => $list_survey,
    'count_survey' => $list_survey->count(),
  ])
  <script src="/js/survey/survey_list.js"></script>
@endsection
