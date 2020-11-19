@extends('layouts.app')

@section('css')
  <link href="/css/survey/new/step2.css" rel="stylesheet">
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div ng-app="surveyApp" ng-controller="surveyFormController as ctrl">
          <div class="qb-form-container">
            <form id='form_edit_question' class="form-horizontal qb-form with-checkbox" @if(empty($survey_questions)) action="{{route('user_create_survey')}}" @else action="{{route('user_edit_survey', ['survey_id' => $survey_id])}}" @endif method="post">
              {{ CpsForm::renderFormIdField() }}
              {{ csrf_field() }}

              @if ($errors->has('visitor_categories'))
                <div class="alert alert-danger">
                  {!! CpsForm::printErrorMessage($errors->first('visitor_categories')) !!}
                </div>
              @endif

              <div class="qb-container qb-card-content-no-bottom-padding" role="main">
                <div class="preview-btn-container" id="screen-preview">
                  <h4 class="qb-list-title mt20">アンケート項目</h4>
                  <button type="button" class="btn btn-default btn-preview" id="form-preview-btn">プレビュー<i class="material-icons" style="margin-left: 15px;">pageview</i></button>                    
                </div>
                @if ($errors->has('visitor_categories'))
                  <div class="alert alert-danger">
                    {!! CpsForm::printErrorMessage($errors->first('visitor_categories')) !!}
                  </div>
                @endif

                <div class="qb-form-table-container">
                  <ul id='list_survey_question'>
                    <li class='head'>
                      <div class="q-content">
                        <div></div>
                        <div></div>
                        <div>アンケート設問</div>
                        <div>入力タイプ</div>
                        <div>形式/選択肢</div>
                        <div>分岐</div>
                        <div>必須</div>
                        <div>編集</div>
                        <div></div>
                      </div>
                    </li>
                    <li>
                      <ul id='list_questions' class="draggable-table" ng-cloak ui-sortable="ctrl.sortableOptions">
                        <li class='question' ng-repeat="item in ctrl.survey_questions" ng-style="$index%2 != 0 ? {'background': 'white'} : {}">
                          {{--改ページ--}}
                          <div ng-if="item.split_page == 1" class="q-split_page hide_split_page" ng-click="ctrl.add_split_page($index)">
                            <i class="material-icons">content_cut</i>
                            <input type="hidden" name="survey_questions[@{{ $index }}][split_page]" value="1">
                          </div>
                          <div ng-if="item.split_page ==2" class="q-split_page active_split_page" ng-click="ctrl.cancel_split_page($index)" style="background: #FAFAFA !important;">
                            ---------------------------------------------------------------------------------------------------- 改ページ ----------------------------------------------------------------------------------------------------
                            <i class="material-icons" ng-style="(ctrl.survey_questions[$index].branchs.length > 0) ? {'color': '#666', 'cursor': 'default'} : {}">content_cut</i>
                            <input type="hidden" name="survey_questions[@{{ $index }}][split_page]" value="2">
                          </div>
                          <div class="q-content">
                            {{--削除--}}
                            <div>
                              <a ng-click="ctrl.deleteColumn($index)" class="btn-delete">削除</a>
                            </div>
                            <div>
                              <input type="hidden" name="survey_questions[@{{ $index }}][order]" value="@{{ $index }}">
                              <input type="hidden" name="survey_questions[@{{ $index }}][id]" value="@{{ item.id }}">
                              Q@{{$index + 1}}
                            </div>
                            <div>
                              <input type="hidden" name="survey_questions[@{{ $index }}][label]" value="@{{ item.label }}">
                              @{{ item.label }}
                            </div>
                            <div>
                              <input type="hidden" name="survey_questions[@{{ $index }}][data_type_id]" value="@{{ item.data_type_id }}">
                              @{{ ctrl.getDataTypeName(item) }}
                            </div>
                            {{--形式/選択肢--}}
                            <div ng-if="ctrl.typeOf(item) == 'format_text'">
                              @{{ ctrl.getDataFormatName(item) }}
                              <input type="hidden" name="survey_questions[@{{ $index }}][validation_rule_id]" value="@{{ ctrl.getDataFormatName(item) }}">
                              <input ng-if="ctrl.maxLengthFor(item)" type="hidden" name="survey_questions[@{{ $index}}][max_length]" value="@{{ ctrl.maxLengthFor(item) }}">
                            </div>
                            <div ng-if="ctrl.typeOf(item) == 'choices'" class="item-choice">
                              <label class="survey-choices">@{{ item.choices }}<br><span ng-if="item.allow_other">その他</span></label>
                              <input type="hidden" name="survey_questions[@{{ $index }}][choices]" value="@{{ item.choices }}">
                              <input type="hidden" name="survey_questions[@{{ $index }}][allow_other]" value="1" ng-if="item.allow_other">
                            </div>
                            {{--分岐--}}
                            <div>
                              <button type="button" class="btn-ic-edit btn btn-simple btn-fab btn-fab-mini" ng-click="ctrl.showEditBranchs($index)" style="font-size: 0">
                                <img src="/img/call_branch.png" alt="" style="width: 17px;">
                              </button>

                              <input type="hidden" ng-repeat-start="branch in item.branchs" name="survey_questions[@{{ $parent.$index }}][branch_condition][@{{ $index }}][question_branch_id]" value="@{{ branch.question_branch_id }}">
                              <input type="hidden" ng-repeat-end name="survey_questions[@{{ $parent.$index }}][branch_condition][@{{ $index }}][survey_answer_id]" value="@{{ branch.survey_answer_id }}">
                            </div>
                            {{--必須--}}
                            <div>
                              <label class="checkbox-inline">
                                <input type="checkbox" name="survey_questions[@{{ $index }}][required]" ng-model="item.required" value="1">
                              </label>
                            </div>
                            {{--編集--}}
                            <div>
                              <button type="button" class="btn-ic-edit btn btn-simple btn-fab btn-fab-mini" ng-click="ctrl.showEditorialModal($index)">
                                <i class="material-icons">edit</i>
                              </button>
                            </div>
                            {{--並び替え--}}
                            <div class="draggable ui-sortable-handle">
                              <i class="material-icons material-drag-handle">drag_handle</i>
                              <input type="hidden" name="survey_questions[@{{ $index }}][is_exclusion]" value="@{{ item.is_exclusion }}">
                            </div>
                          </div>
                        </li>
                      </ul>
                    </li>
                    <li class="btn_add_question">
                      <a href="" class="btn btn-danger" ng-click="ctrl.showAdditionalModal()">
                      アンケート項目の追加<i class="material-icons">add</i>
                      </a>
                    </li>
                  </ul>
                </div>
                @if ($errors->has('survey_questions'))
                {!! CpsForm::printErrorMessage($errors->first('survey_questions')) !!}
                @endif
              </div>
              <div class="form-btn-triple-group">
                <div class="btn-triple-group text-center">
                  @if (!isset($survey_id))
                    <a class="btn btn-default btn-cancel" href="{{ route('user_show_create_survey_form1', [CpsForm::getInputName() => CpsForm::getFormId()]) }}">戻る</a>
                  @else
                    <a class="btn btn-default btn-cancel" href="{{ route('user_setting_editing_survey', [$survey_id, CpsForm::getInputName() => CpsForm::getFormId()]) }}">戻る</a>
                  @endif
                  <button type="submit" class="btn btn-danger" >保存</button>
                </div>
              </div>          
            </form>
          </div>
          @include("survey.include.question_item_modal")
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section("js")
@scope([
  'survey_questions' => $survey_questions,
  'data_types' => App\Models\DataType::all(),
  'route'     => route("user_show_preview_survey"),
  'data_formats' => App\Models\ValidationRule::getDataFormats(),
  'errors' => $errors->messages(),
], 'survey_question_items_form')
<script src="/plugins/angular/angular.min.js"></script>
<script src="/plugins/angular/angular-animate.min.js"></script>
<script src="/plugins/angular/angular-ui.min.js"></script>
<script src="/plugins/angular/angular-messages.js"></script>
<script src="/js/survey/new/step2.js"></script>
@endsection