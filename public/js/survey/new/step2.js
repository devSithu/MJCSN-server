$(function () {
    $('select').wSelect({theme: 'qb', size: 5});
    $(document).on('input', '.textarea', function () {
        $(this).height($(this).val().split('\n').length * 20 + 1).css("overflow", "hidden");
    });
    $('.textarea').each(function () {
        $(this).height($(this).val().split('\n').length * 20 + 1).css("overflow", "hidden");
    });

    $(document).on("click", "#form-preview-btn", function () {
        $('input').each(function () {
            $(this).attr("value", $(this).val());
        });
        $('select').each(function () {
            $(this).val($(this).val());
        });
        $('textarea').each(function () {
            $(this).html($(this).val());
        });
        var form = $(this).closest("form").clone();
        var sname = "survey_question_items_form";
        var route = vars("route", sname);
        form.attr("target", "form-preview_window");
        form.attr("action", route);
        window.open('about:blank', 'form-preview_window');
        form.hide();
        $("body").append(form);
        form.submit();
        form.remove();
    });

});

var surveyApp = angular.module('surveyApp', ['ngMessages', 'ui']);

surveyApp.controller('surveyFormController', ['$scope', '$timeout',
    function ($scope, $timeout) {
        $scope.array = array;
        var ctrl = this;
        var sname = "survey_question_items_form";
        var data_types = array(vars("data_types", sname));
        var data_formats = array(vars("data_formats", sname));

        function item_map(item) {
            item.required = parseBool(item.is_required);
            item.allow_other = parseBool(item.allow_other);
            item.data_format_id = parseInt(item.validation_rule_id);
            item.data_type_id = item.data_type_id.toString();
            item.max_length = parseInt(item.max_length);
            item.min_length = parseInt(item.min_length);
            item.label = item.content;
            item.branchs = item.survey_question_branch_conditions;
            return item;
        }

        ctrl.survey_questions = vars("survey_questions", sname).map(item_map);
        var array_question_id = [];
        var array_question_answer_id = [];
        for(var i = 0; i< ctrl.survey_questions.length; i++){
            array_question_id[ctrl.survey_questions[i].survey_question_id] = i;
            array_question_answer_id[ctrl.survey_questions[i].survey_question_id]= [];
            for(var m=0;m<ctrl.survey_questions[i].survey_answers.length;m++){
                array_question_answer_id[ctrl.survey_questions[i].survey_question_id][ctrl.survey_questions[i].survey_answers[m].survey_answer_id] = m;
            }
            if(ctrl.survey_questions[i].is_exclusion == 0){
                ctrl.survey_questions[i].is_exclusion = -1;
            }else{
                ctrl.survey_questions[i].is_exclusion = array_question_answer_id[ctrl.survey_questions[i].survey_question_id][ctrl.survey_questions[i].is_exclusion].toString();
            }
            ctrl.survey_questions[i].survey_question_id = i;
            for(var j = 0; j<ctrl.survey_questions[i].branchs.length;j++){
                ctrl.survey_questions[i].branchs[j].survey_answer_id = array_question_answer_id[ctrl.survey_questions[i].branchs[j].branch_question_id][ctrl.survey_questions[i].branchs[j].branch_answer_id];
                ctrl.survey_questions[i].branchs[j].question_branch_id = array_question_id[ctrl.survey_questions[i].branchs[j].branch_question_id];
            }
        }
        ctrl.data_types = data_types.whereIn('is_view', [true]);
        ctrl.additional_column = {};
        ctrl.survey_question_edit_branch = {};
        ctrl.survey_question_edit_branch_index = 0;
        ctrl.survey_question_edit_branch_error = false;
        ctrl.startPosMoveQuestion = 0;
        ctrl.endPosMoveQuestion = 0;
        ctrl.number_can_choice = 0;
        ctrl.dataFormats = function () {
            if (ctrl.additional_column.data_type_id == 1 || ctrl.additional_column.data_type_id == 2 ) {//text and textarea
                return data_formats.whereIn('view_name', ['文字列', '英数字', '数字']);
            }
            return data_formats;
        };

        ctrl.maxLengthFor = function (column) {
            if (column.data_type_id == 1) {//text
                return 200;
            }
            if (column.data_type_id == 2) {//textarea
                return 1000;
            }
            return 0;
        };
        function convertCode(str) {
            return str.split('〜').join('～');
        }
        function getChoices(value) {
            return value ? convertCode(value).replace(/[ 　]+[\r\n]/g, "\n").match(/[^\n\r 　][^\r\n]*/g) : [];
        }
        ctrl.checkChoices = function () {
            var column = ctrl.additional_column;
            column.error = null;

            var choices = getChoices(column.choices);
            if (choices.length > 200) {
                column.error = 'choices_max';
            } else {
                $.each(choices, function (i, item) {
                    if (item.length > 200) {
                        column.error = 'choice_len';
                        return false;
                    }else if(choices.lastIndexOf(item) > i){
                        column.error = 'choice_duplicate';
                        return false;
                    }else if(item == 'その他'){
                        column.error = 'choice_duplicate_other';
                        return false;
                    }
                });
            }
        }

        ctrl.updateColumn = function () {
            if (!ctrl.isFreeAnswer(ctrl.additional_column)) {
                if (ctrl.additional_column.error) {
                    return false;
                }
                ctrl.additional_column.choices = getChoices(ctrl.additional_column.choices).join("\n");
                ctrl.additional_column.choices_branch = ctrl.additional_column.choices;
                if(ctrl.additional_column.allow_other){
                    ctrl.additional_column.choices_branch = ctrl.additional_column.choices_branch + "\nその他";
                }
            }
            $('#column-add-modal').modal('hide');
            if(ctrl.is_edit){
                if(ctrl.isFreeAnswer(ctrl.additional_column) && ctrl.survey_questions[ctrl.additional_column._index].data_type_id > 2){
                    for(var i= 0;i<ctrl.survey_questions.length;i++){
                        var branchs = ctrl.survey_questions[i].branchs;
                        if (Array.isArray(branchs)) {
                            for(var j = 0; j<branchs.length;j++){
                                if(branchs[j].question_branch_id == ctrl.additional_column._index){
                                    branchs.splice(j, 1);
                                }
                            }
                        }
                    }
                    ctrl.additional_column.is_exclusion = -1;
                }else if (!ctrl.isFreeAnswer(ctrl.additional_column)) {
                    if(ctrl.additional_column.choices != ctrl.survey_questions[ctrl.additional_column._index].choices || ctrl.additional_column.allow_other == false && ctrl.survey_questions[ctrl.additional_column._index].allow_other == true){
                        for(var i= 0;i<ctrl.survey_questions.length;i++){
                            var branchs = ctrl.survey_questions[i].branchs;
                            if (Array.isArray(branchs)) {
                                for(var j = 0; j<branchs.length;j++){
                                    if(branchs[j].question_branch_id == ctrl.additional_column._index){
                                        branchs.splice(j, 1);
                                    }
                                }
                            }
                        }
                        ctrl.additional_column.is_exclusion = -1;
                    }
                }
                ctrl.survey_questions[ctrl.additional_column._index] = ctrl.additional_column;
            }else{
                if(ctrl.survey_questions.length === 0){
                    ctrl.additional_column.split_page = 0;
                }
                ctrl.survey_questions.push(ctrl.additional_column);
            }
        };
        ctrl.add_split_page = function (index) {
            ctrl.survey_questions[index].split_page = 2;
        };

        ctrl.cancel_split_page = function (index) {
            if( ctrl.survey_questions[index].branchs.length === 0 ){
                ctrl.survey_questions[index].split_page = 1;
            }
        };

        ctrl.deleteColumn = function ($index) {
            for(var i= 0;i<ctrl.survey_questions.length;i++){
                if(i> $index){
                    var branchs = ctrl.survey_questions[i].branchs;
                    var branchNew = [];
                    for(var j = 0; j<branchs.length;j++){
                        if(branchs[j].question_branch_id < $index){
                            branchNew.push({question_branch_id: branchs[j].question_branch_id, survey_answer_id: branchs[j].survey_answer_id, error: branchs[j].error});
                        }else if(branchs[j].question_branch_id > $index){
                            branchNew.push({question_branch_id: ''+ (parseInt(branchs[j].question_branch_id) - 1), survey_answer_id: branchs[j].survey_answer_id, error: branchs[j].error});
                        }
                    }
                    ctrl.survey_questions[i].branchs = branchNew;
                }
            }
            ctrl.survey_questions.splice($index, 1);
            if(ctrl.survey_questions.length){
                ctrl.survey_questions[0].split_page = 0;
            }
        };

        ctrl.showEditorialModal = function (index) {
            $scope.additionalColumnForm.$setPristine();

            ctrl.is_edit = index >= 0;
            ctrl.additional_column = ctrl.is_edit ? angular.copy(ctrl.survey_questions[index]) : {id: 0, split_page: 1, allow_other: false, branchs: [], is_exclusion: -1};
            if (!ctrl.additional_column.data_type_id) {
                ctrl.additional_column.data_type_id = parseInt(data_types[0].data_type_id);
            }

            ctrl.additional_column._index = index;
            $('#column-add-modal').modal();
            //fix bug angularjs for wSelect
            $timeout(function () {
                $('select[name="data_type"]').change();
                ctrl.changeAdditionalColumnDataType();
            }, 0);
        };
        ctrl.showAdditionalModal = function () {
            array(ctrl.survey_questions).length < 100 ? ctrl.showEditorialModal(-1) : alert('追加できる項目数は最大100までです。');
        };

        ctrl.changeAdditionalColumnDataType = function () {
            if (!ctrl.dataFormats().whereIn('validation_rule_id', [ctrl.additional_column.data_format_id]).length) {
                ctrl.additional_column.data_format_id = ctrl.dataFormats()[0].validation_rule_id;
            }
            //fix bug angularjs for wSelect
            $timeout(function () {
                $('select[name="data_format"]').wSelect('reset').wSelect({theme: 'qb', size: 3});
            }, 0);
        };


        ctrl.sortableOptions = {
            'handle': '.draggable',
            'axis ': 'y',
            'start': function (event, ui) {
                ui.item.startPos = ui.item.index();
            },
            'stop': function (event, ui) {
                var startPos = ui.item.startPos;
                var endPos = ui.item.index();
                if (!angular.equals(startPos, endPos)) {
                    var rowData = ctrl.survey_questions[parseInt(startPos)];
                    ctrl.survey_questions.splice(startPos, 1);
                    ctrl.survey_questions.splice(endPos, 0, rowData);
                    if(startPos < endPos){
                        ctrl.survey_questions[0].split_page = 0;
                        if(ctrl.survey_questions[1].split_page == 0){
                            ctrl.survey_questions[1].split_page = 1;
                        }
                        if(ctrl.survey_questions[endPos].split_page == 0){
                            ctrl.survey_questions[endPos].split_page = 1;
                        }
                        updateIndexStartPosSmallendPos(startPos, endPos);
                    }else{
                        if( ctrl.survey_questions[endPos].branchs.length > 0 ){
                            ctrl.startPosMoveQuestion = startPos;
                            ctrl.endPosMoveQuestion = endPos;
                            $('#show-warning-remove-branch-modal').modal();
                        }else{
                            ctrl.accessRemoveBranch(startPos, endPos);
                        }
                    }
                }
                $scope.$apply();
            }
        };

        ctrl.accessRemoveBranch = function(startPos, endPos){
            ctrl.survey_questions[0].split_page = 0;
            if(ctrl.survey_questions[1].split_page == 0){
                ctrl.survey_questions[1].split_page = 1;
            }
            updateIndexstartPosBigendPos(startPos, endPos);
        };
        ctrl.cancelRemoveBranch = function (startPos, endPos) {
            var rowData = ctrl.survey_questions[parseInt(endPos)];
            ctrl.survey_questions.splice(endPos, 1);
            ctrl.survey_questions.splice(startPos, 0, rowData);
        }

        ctrl.isFreeAnswer = function (item) {
            var data_type_id = parseInt(item.data_type_id);
            return item && data_types.whereIn('input_type', ["textarea", "text", "name", "password"]).whereIn('data_type_id', [data_type_id]).length > 0;
        };

        ctrl.getDataFormatName = function (item) {
            var index = array(data_formats).pluck('validation_rule_id').indexOf(item.data_format_id);
            if (index >= 0) {
                return data_formats[index].view_name;
            }
            return '';
        };

        ctrl.typeOf = function (item) {
            if (!ctrl.isFreeAnswer(item)) {
                return 'choices';
            }
            return 'format_text';
        };

        ctrl.labelTypeOf = function (item) {
            return 'normal';
        };

        ctrl.getDataTypeName = function (item) {
            var index = array(data_types).pluck('data_type_id').indexOf(parseInt(item.data_type_id));
            return index < 0 ? 'unknown' : data_types[index].name;
        };

        ctrl.showEditBranchs = function (index) {
            ctrl.survey_question_edit_branch = angular.copy(ctrl.survey_questions[index]);
            ctrl.survey_question_edit_branch_index = index;
            ctrl.survey_question_edit_branch_error = false;
            ctrl.number_can_choice = 0;
            for(var j = 0; j<ctrl.survey_question_edit_branch.branchs.length;j++){
                ctrl.survey_question_edit_branch.branchs[j].question_branch_id = ctrl.survey_question_edit_branch.branchs[j].question_branch_id.toString();
                ctrl.survey_question_edit_branch.branchs[j].survey_answer_id = ctrl.survey_question_edit_branch.branchs[j].survey_answer_id.toString();
            }
            for(var i = 0;i<ctrl.survey_questions.length;i++){
                if(ctrl.survey_questions[i].data_type_id > 2 && index > i){
                    ctrl.number_can_choice += ctrl.survey_questions[i].choices_branch.split('\n').length;
                }
            }
            if (ctrl.number_can_choice != 0 && ctrl.survey_question_edit_branch.branchs.length == 0) {
                ctrl.survey_question_edit_branch.branchs.push({question_branch_id: -1, survey_answer_id: -1, error: '', error_required: ''});
            }
            $('#show-edit-modal').modal();
        };

        ctrl.addConditionBranch = function () {
            if (ctrl.survey_question_edit_branch.branchs.length < 50) {
                ctrl.survey_question_edit_branch.branchs.push({question_branch_id: -1, survey_answer_id: -1, error: '', error_required: ''});
            }
            $scope.is_hide = ctrl.survey_question_edit_branch.branchs.length < 50 ? false : true;
        };

        ctrl.changeCondition = function(index){
            var branch = ctrl.survey_question_edit_branch.branchs[index];
            if(branch.question_branch_id > -1 && branch.survey_answer_id > -1){
                branch.error_required = '';
            }
            check_duplicate_branch();
        }

        function updateIndexStartPosSmallendPos(startPos, endPos){
            for(var i= startPos;i<ctrl.survey_questions.length;i++){
                if(i<endPos){
                    var branchs = ctrl.survey_questions[i].branchs;
                    var branchNew = [];
                    for(var j = 0; j<branchs.length;j++){
                        if(branchs[j].question_branch_id < startPos){
                            branchNew.push({question_branch_id: branchs[j].question_branch_id, survey_answer_id: branchs[j].survey_answer_id, error: branchs[j].error, error_required: branchs[j].error_required});
                        }else if(branchs[j].question_branch_id > startPos){
                            branchNew.push({question_branch_id: ''+ (parseInt(branchs[j].question_branch_id) - 1), survey_answer_id: branchs[j].survey_answer_id, error: branchs[j].error, error_required: branchs[j].error_required});
                        }
                    }
                    ctrl.survey_questions[i].branchs = branchNew;
                }else if(i > endPos){
                    var branchs = ctrl.survey_questions[i].branchs;
                    var branchNew = [];
                    for(var j = 0; j<branchs.length;j++){
                        if(branchs[j].question_branch_id < startPos || branchs[j].question_branch_id >endPos){
                            branchNew.push({question_branch_id: branchs[j].question_branch_id, survey_answer_id: branchs[j].survey_answer_id, error: branchs[j].error, error_required: branchs[j].error_required});
                        }else if(branchs[j].question_branch_id == startPos){
                            branchNew.push({question_branch_id: ''+ endPos, survey_answer_id: branchs[j].survey_answer_id, error: branchs[j].error});
                        }else if(branchs[j].question_branch_id > startPos && branchs[j].question_branch_id <= endPos){
                            branchNew.push({question_branch_id: ''+ (parseInt(branchs[j].question_branch_id) - 1), survey_answer_id: branchs[j].survey_answer_id, error: branchs[j].error, error_required: branchs[j].error_required});
                        }
                    }
                    ctrl.survey_questions[i].branchs = branchNew;
                }
            }
        }
        function updateIndexstartPosBigendPos(startPos, endPos){
            for(var i= endPos;i<ctrl.survey_questions.length;i++){
                var branchs = ctrl.survey_questions[i].branchs;
                var branchNew = [];
                if( i == endPos && endPos > 0 ){
                    ctrl.survey_questions[i].split_page = 1;
                }else if( i > endPos && i<=startPos ){
                    for(var j = 0; j<branchs.length;j++) {
                        if (branchs[j].question_branch_id < endPos) {
                            branchNew.push({question_branch_id: branchs[j].question_branch_id, survey_answer_id: branchs[j].survey_answer_id, error: branchs[j].error, error_required: branchs[j].error_required});
                        } else {
                            branchNew.push({question_branch_id: '' + (parseInt(branchs[j].question_branch_id) + 1), survey_answer_id: branchs[j].survey_answer_id, error: branchs[j].error, error_required: branchs[j].error_required});
                        }
                    }
                }else if( i> endPos ){
                    for(var j = 0; j<branchs.length;j++) {
                        if (branchs[j].question_branch_id < endPos || branchs[j].question_branch_id > startPos) {
                            branchNew.push({question_branch_id: branchs[j].question_branch_id, survey_answer_id: branchs[j].survey_answer_id, error: branchs[j].error, error_required: branchs[j].error_required});
                        } else if (branchs[j].question_branch_id >= endPos && branchs[j].question_branch_id < startPos) {
                            branchNew.push({question_branch_id: '' + (parseInt(branchs[j].question_branch_id) + 1), survey_answer_id: branchs[j].survey_answer_id, error: branchs[j].error, error_required: branchs[j].error_required});
                        } else if (branchs[j].question_branch_id == startPos) {
                            branchNew.push({question_branch_id: '' + endPos, survey_answer_id: branchs[j].survey_answer_id, error: branchs[j].error, error_required: branchs[j].error_required});
                        }
                    }
                }
                ctrl.survey_questions[i].branchs = branchNew;
            }
        }

        function check_duplicate_branch(){
            var branchs = ctrl.survey_question_edit_branch.branchs;
            var length_branchs = branchs.length;
            ctrl.survey_question_edit_branch_error = false;
            for (var i= 0;i<length_branchs;i++) {
                var flag = false;
                for (var j= 0;j<length_branchs;j++) {
                    if( i == j ) continue;
                    if(branchs[i].question_branch_id> -1 && branchs[i].survey_answer_id > -1 && branchs[i].question_branch_id == branchs[j].question_branch_id && branchs[i].survey_answer_id == branchs[j].survey_answer_id ){
                        flag = true;
                        ctrl.survey_question_edit_branch_error = true;
                        break;
                    }
                }
                branchs[i].error = flag ? '同じ条件が入っています' : '';
            }
        }
        
        ctrl.updateBranch = function (index) {
            var branchs = ctrl.survey_question_edit_branch.branchs;
            var flagError = false;
            for (var i= 0;i<branchs.length;i++) {
                if(branchs[i].question_branch_id == -1 || branchs[i].survey_answer_id == -1){
                    branchs[i].error_required = '表示条件を入力してください';
                    flagError = true;
                }else{
                    branchs[i].error_required = '';
                }
            }
            if(!flagError){
                $('#show-edit-modal').modal('hide');
                ctrl.survey_questions[index] = ctrl.survey_question_edit_branch;
                if( ctrl.survey_question_edit_branch.branchs.length > 0 ){
                    ctrl.survey_questions[index].split_page = 2;
                }
            }
        }

        ctrl.removeConditionBranch = function (index) {
            ctrl.survey_question_edit_branch.branchs.splice(index, 1);
            check_duplicate_branch();
            $scope.is_hide = ctrl.survey_question_edit_branch.branchs.length < 50 ? false : true;
        };
    }
]);
