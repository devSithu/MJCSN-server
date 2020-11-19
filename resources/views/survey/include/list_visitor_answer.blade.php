<div class="qb-container" role="main">
  <div class="qb-list-container">
    <h2 class="qb-list-title table-count" id="table_count"></h2>
    <div class="mb20">
      <div id="main_table">
        <table class="qb-form-table qb-list-table list-visitor-answer">
          <thead>
            <tr>
              <th>User Number</th>
              <th>User Name</th>
              <th>Email Address</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@section('js')
@token
@scope([
    'url_data' => route('QB::Survey#get_visitor', ['survey_id' => $survey->survey_id]),
    'user_show_detail_answer_visitor' => route('user_show_detail_answer_visitor', ['user_number' => '@user_number', 'survey_id' => '@survey_id']),
    'survey_id' => $survey->survey_id,
])
<script src="/js/survey/include/list_visitor_answer.js"></script>
@endsection
