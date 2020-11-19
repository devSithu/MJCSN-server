<h2 style="font-size: 14px;" class="qb-list-title table-count" id="table_count_{{$list['survey_question_id']}}"></h2>
<div>
  <div id="main_table">
    <table class="qb-form-table qb-list-table statistics-text-{{$list['survey_question_id']}}">
      <thead>
        <tr>
          <th>回答</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>
@push("ahead_javascript")
@scope([
  'answer_text'  => $list->survey_visitor_question_answers,
  'survey_question_id' => $list['survey_question_id'],
])
<script src="/js/survey/include/statistics_answer_text.js"></script>
@endpush
