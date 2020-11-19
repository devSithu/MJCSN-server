<form novalidate class="form-horizontal qb-form" name="additionalColumnForm">
  <div class="modal qb-modal fade" id="column-add-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content qb-modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-center">アンケート項目の追加</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body qb-modal-body">
          <div class="form-group qb-form-group">
            <label for="form1" class="control-label mt10 pr40 required mb-10">項目名</label>
            <div class="qb-form-inputs">
              <div ng-messages="additionalColumnForm.label.$error" ng-show="additionalColumnForm.$submitted || additionalColumnForm.label.$dirty">
                <div ng-message="required">{!! CpsForm::printErrorMessage("入力してください") !!}</div>
                <div ng-message="maxlength">{!! CpsForm::printErrorMessage("200文字以下で入力してください") !!}</div>
              </div>
              <input type="text" ng-required="true" class="form-control box-100 mb-10" placeholder="項目名" name="label" ng-model="ctrl.additional_column.label" ng-maxlength="200">
            </div>
          </div>

          <div class="mb-10 form-group qb-form-group mt00">
            <label for="form1" class="control-label mt10 pr40 mb-10">入力タイプ</label>
            <div class="qb-custom-select">
              <select class="form-control qb-custom-select-tar box-100" name="data_type" ng-model="ctrl.additional_column.data_type_id" ng-change="ctrl.changeAdditionalColumnDataType()" convert-to-number>
                <option ng-repeat="data_type in ctrl.data_types"
                  value="@{{ data_type.data_type_id }}">@{{ data_type.name }}</option>
              </select>
            </div>
          </div>

          <div ng-if="ctrl.isFreeAnswer(ctrl.additional_column)">
            <div class="form-group qb-form-group">
              <label for="form1" class="control-label mt10 mb-10 pr30 required">形式/選択肢</label>
              <div class="qb-custom-select">
              <select class="form-control box-100 qb-custom-select-tar"
                name="data_format"
                ng-model="ctrl.additional_column.data_format_id"
                ng-options="data_format.validation_rule_id as data_format.view_name for data_format in ctrl.dataFormats()"> </select>
              </div>
            </div>
          </div>

          <div ng-if="!ctrl.isFreeAnswer(ctrl.additional_column)">
            <div class="form-group qb-form-group">
              <label for="form1" class="control-label mb-10 required" style="margin-top: 8px !important;">形式/選択肢</label>
              <div class="qb-form-inputs">
                <div class="one-row-color">
                  1行が1つの選択肢として表示されます。
                </div>
                <div ng-messages="additionalColumnForm.choices.$error" ng-show="additionalColumnForm.$submitted || additionalColumnForm.choices.$dirty">
                  <div ng-message="required">{!! CpsForm::printErrorMessage("入力してください") !!}</div>
                  <div ng-if="ctrl.additional_column.error=='choices_max'">{!! CpsForm::printErrorMessage("選択肢の数は200以下にしてください。") !!}</div>
                  <div ng-if="ctrl.additional_column.error=='choice_len'">{!! CpsForm::printErrorMessage("選択項目は200文字以下にしてください。") !!}</div>
                  <div ng-if="ctrl.additional_column.error=='choice_duplicate'">{!! CpsForm::printErrorMessage("同じ選択肢は入っています。") !!}</div>
                  <div ng-if="ctrl.additional_column.error=='choice_duplicate_other'">{!! CpsForm::printErrorMessage("その他は入ることができません。") !!}</div>
                </div>
                <textarea class="form-control box-100" placeholder="1行ずつ入力" ng-model="ctrl.additional_column['choices']" name="choices" required ng-change="ctrl.checkChoices()"></textarea>
              </div>
            </div>
          </div>

          <div class="form-group qb-form-group mb-10">
            <label class="control-label mb-10">必須表示</label>
            <label class="checkbox-inline box-100">
              <input type="checkbox" ng-model="ctrl.additional_column['required']">
              必須
            </label>
          </div>

          <div class="form-group qb-form-group mb-10" id="ad-others" ng-show="!ctrl.isFreeAnswer(ctrl.additional_column)">
            <label class="checkbox-inline">
              <input type="checkbox" ng-model="ctrl.additional_column['allow_other']">
              その他
            </label>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal">
            キャンセル
          </button>
          <button class="btn btn-danger" ng-click="(additionalColumnForm.$submitted = true) && additionalColumnForm.$valid && ctrl.updateColumn()">
            @{{ ctrl.is_edit ? '変更' : '追加' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</form>

<form novalidate class="form-horizontal qb-form" name="settingBranchQuestionForm">
  <div class="modal qb-modal fade" id="show-edit-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content qb-modal-content">
        <div class="modal-header">
          <h4 class="modal-title">分岐条件の設定</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body qb-modal-body">
          <div class="form-group qb-form-group">
            <label style="font-size: 15px; display: flex" class="label-item-question"><span>Q@{{ ctrl.survey_question_edit_branch_index +1 }}</span>　<p class="content-question">@{{ ctrl.survey_question_edit_branch.label }}</p></label><br>
            <div class="mb10 mt10">
              <i class="material-icons" style="font-size: 13px;">brightness_1</i>
              <label class="label-item-question">表示条件の設定</label>
            </div>

            <table style="width: 100%">
              <tbody>
                <tr style="height: 50px;" ng-repeat-start="branch in ctrl.survey_question_edit_branch.branchs">
                  <td style="width: 155px;">
                    <select class="form-control select-arrow" ng-model="ctrl.survey_question_edit_branch.branchs[$index].question_branch_id" ng-change="ctrl.changeCondition($index)">
                      <option ng-repeat="item in ctrl.survey_questions" ng-if="ctrl.survey_question_edit_branch_index > $index && item.data_type_id > 2" value="@{{ $index }}">Q@{{ $index + 1 }} @{{ item.label }}</option>
                    </select>
                  </td>
                  <td style="padding: 8px 10px;" class="td-label">
                    <label for="" class="label-item-question">で</label>
                  </td>
                  <td style="width: 150px;">
                    <select class="form-control select-arrow" ng-model="ctrl.survey_question_edit_branch.branchs[$index].survey_answer_id" ng-change="ctrl.changeCondition($index)">
                      <option ng-repeat="textShow in ctrl.survey_questions[branch.question_branch_id].choices_branch.split('\n')" value="@{{ $index }}">@{{ textShow }}</option>
                    </select>
                  </td>
                  <td style="padding: 8px 10px;" class="td-label">
                    <label for="" class="label-item-question">を選択した場合</label>
                  </td>
                  <td style="vertical-align: bottom">
                    <button type="button" class="btn-ic-delete btn btn-simple btn-fab btn-fab-mini float-right mb05" ng-click="ctrl.removeConditionBranch($index)">
                      <i class="material-icons">delete</i>
                    </button>
                  </td>
                </tr>
                <tr><td colspan=5 class="text-danger">@{{ branch.error_required }}</td></tr>
                <tr ng-repeat-end><td colspan=5 class="text-danger">@{{ branch.error }}</td></tr>
              </tbody>
            </table>

            <div ng-if="ctrl.number_can_choice == 0" class="text-danger text-center">表示条件がありません。</div>

            <div class="float-right mb10 mt10">
              <button ng-hide="is_hide" data-ng-disabled="ctrl.number_can_choice == 0" type="button" class="btn btn-primary btn-fab btn-fab-mini btn-round btn-add-mini" ng-click="ctrl.addConditionBranch()"></button>
            </div>
            <div class="mt30" ng-if="ctrl.survey_question_edit_branch.data_type_id == 5">
              <div class="mb10 mt10">
                <i class="material-icons" style="font-size: 13px;">brightness_1</i>
                <label style="font-size: 15px;" class="label-item-question">排他設定</label>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <select class="form-control select-arrow" ng-model="ctrl.survey_question_edit_branch.is_exclusion">
                    <option ng-if="ctrl.survey_question_edit_branch.is_exclusion > -1" value="-1"></option>
                    <option ng-repeat="exclusion in ctrl.survey_question_edit_branch.choices_branch.split('\n')" value="@{{ $index }}">@{{ exclusion }}</option>
                  </select>
                </div>
                <div class="col-md-6" class="mb10 mt10">
                  <label for="" class="label-item-question">を選択した場合</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            キャンセル
          </button>
          <button data-ng-disabled="ctrl.survey_question_edit_branch_error" class="btn btn-danger" ng-click="ctrl.updateBranch(ctrl.survey_question_edit_branch_index)">
            追加
          </button>
        </div>
      </div>
    </div>
  </div>
</form>
<div class="modal qb-modal fade" id="show-warning-remove-branch-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content qb-modal-content">
      <div class="modal-header">
        <h4 class="modal-title">分岐設定の解除</h4>
      </div>
      <div class="modal-body qb-modal-body" style="padding-top: 0px !important;">並び替えを行うと、設定した分岐条件が解除されます。<br>よろしいですか？</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal" ng-click="ctrl.cancelRemoveBranch(ctrl.startPosMoveQuestion, ctrl.endPosMoveQuestion)">キャンセル</button>
        <button class="btn btn-primary" data-dismiss="modal" ng-click="ctrl.accessRemoveBranch(ctrl.startPosMoveQuestion, ctrl.endPosMoveQuestion)">確認</button>
      </div>
    </div>
  </div>
</div>
