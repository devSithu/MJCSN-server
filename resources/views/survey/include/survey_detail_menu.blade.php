<div class="survey_menu" id="nav_survey_menu">
  <ul class="qb-form-text">
    <li class="qb-form-text-title {{ $active == 1 ? 'slide-active' : ''}}" id="detail-btn">
      <a href= "{{ route('url_user_show_survey_detail_data', ['survey_id' => $survey->id]) }}">設定状況<div class="ripple-container"></div></a>
    </li>
    <li class="qb-form-text-title {{ $active == 2 ? 'slide-active' : ''}}" id="visitor-btn">
      <a href= "{{ route('QB::Survey#show_visitor_list', ['survey_id' => $survey->id]) }}">回答者</a>
    </li>
    <li class="qb-form-text-title {{ $active == 3 ? 'slide-active' : ''}}" id="survey-btn">
      <a href= "{{ route('QB::Survey#show_survey_question_and_answer', ['survey_id' => $survey->id]) }}">集計</a>
    </li>
  </ul>
  <span id="slide-active-line"></span>
</div>
@push("ahead_javascript")
<script src="/js/survey/include/survey_detail_menu.js"></script>
@endpush