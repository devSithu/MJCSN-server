<div class="chart-body" id="chart-{{$list['survey_question_id']}}" class="static_answer"></div>
@push("ahead_javascript")
@scope([
  'survey_answer_chart' => $list->survey_answers,
  'survey_question_id' => $list['survey_question_id'],
  'color' => App\Models\SurveyQuestion::COLOR,
  'order' => $list['order'],
  'content_question' => $list['content'],
  'number_user_ansewer' => count($list->survey_visitor_question_answers),
  'number_user_not_ansewer' => $total_visitor_answersed - count($list->survey_visitor_question_answers)
])
<script src="/js/survey/include/statistics_answer_select_or_radio.js"></script>
@endpush