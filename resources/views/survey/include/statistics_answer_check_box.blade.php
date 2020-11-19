<div class="chart-body" id="chart-{{$list['survey_question_id']}}" style="margin-right: 0px"></div>
@push("ahead_javascript")
@scope([
  'survey_answer_chart' => $list->survey_answers,
  'survey_question_id' => $list['survey_question_id'],
  'order' => $list['order'],
  'content_question' => $list['content'],
  'number_user_ansewer' => count($list->survey_visitor_question_answers->groupBy('survey_visitor_id')),
  'number_user_not_ansewer' => $total_visitor_answersed - count($list->survey_visitor_question_answers->groupBy('survey_visitor_id'))
])
<script src="/js/survey/include/statistics_answer_check_box.js"></script>
@endpush

