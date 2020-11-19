<div class="qb-form-table-container table-word-break">
  <ul id="list_survey_question">
    <li class="head">
      <div class="q-content">
        <div></div>
        <div>アンケート設問</div>
        <div>入力タイプ</div>
        <div>形式/選択肢</div>
        <div>分岐</div>
        <div>必須</div>
      </div>
    </li>
    <li>
      <ul id="list_questions">
        @foreach ($list_survey_question as $data)
          <li class="question">
            @if ($data->split_page == 2)
              <div class="q-split_page active_split_page" style="background: #FAFAFA !important;">
                ---------------------------------------------------------------------------------------------------- 改ページ ----------------------------------------------------------------------------------------------------
                <i class="material-icons">content_cut</i>
              </div>
            @endif
            <div class="q-content">
              <div>Q{{ $data['order'] }}</div>
              <div>{{ $data['content'] }}</div>
              <div>{{ $data->data_type->name }}</div>
              <div>
                @if (count($data->survey_answers))
                  @foreach ($data->survey_answers as $survey_answer)
                    {{ $survey_answer['content'] }} <br>
                  @endforeach
                @else
                  @foreach ($data->validation_rules as $validation_rule)
                    @if (in_array($validation_rule->validation_rule_id, [2, 7, 11]))
                      {{ $validation_rule->view_name }}
                    @endif
                  @endforeach
                @endif
              </div>
              <div>
                @if (count($data->survey_question_branch_conditions))
                  @foreach ($data->survey_question_branch_conditions as $survey_question_branch)
                    Q{{ $survey_question_branch->survey_branch_question->order .'で'. $survey_question_branch->survey_answer->content .'を選択した場合' }}<br>
                  @endforeach
                @endif
              </div>
              <div>
                @if ($data->isRequired())
                  <label class="survey_require"> 必須 </label>
                @endif
              </div>
            </div>
          </li>
        @endforeach
      </ul>
    </li>
  </ul>
</div>
