$(function () {
    $(".exclusion_answer").click(function () {
        if ($(this).prop('checked') == true){
            $(this).parent().parent().parent().find('input').prop('disabled', true);
            $(this).parent().parent().parent().find('input').prop('checked', false);
            $(this).prop('disabled', false);
            $(this).prop('checked', true);
        } else {
            $(this).parent().parent().parent().find('input').prop('disabled', false);
        }
    });

    $(".col-sm-12 ._wrapper").each(function() {
        if ($(this).children(".checkbox:first").length !== 0) {
            var labelLen = [];
            $(this).children().children("label").each(function () {
                labelLen.push($(this).width());
            });
            calculateColumn(Math.max.apply(this, labelLen), $(this));
        }
    });

    function calculateColumn(maxLen, checkBoxParent) {
        if (checkBoxParent.children(".checkbox").length > 10) {
            if (window.matchMedia('(min-width: 320px)').matches) {
                if (maxLen <= 60) {
                    checkBoxParent.children(".checkbox").css({
                        'display': 'table',
                        'width': '33.33%',
                        'float': 'left'
                    });
                } else if (maxLen <= 100) {
                    checkBoxParent.children(".checkbox").css({
                        'display': 'table',
                        'width': '50%',
                        'float': 'left'
                    });
                } else {
                    checkBoxParent.children(".checkbox").css({
                        'display': 'table',
                        'width': '100%',
                        'float': 'left'
                    });
                }
            }

            if (window.matchMedia('(min-width:600px)').matches) {
                if (maxLen <= 150) {
                    checkBoxParent.children(".checkbox").css({
                        'display': 'table',
                        'width': '33.33%',
                        'float': 'left'
                    });
                } else if (maxLen <= 250) {
                    checkBoxParent.children(".checkbox").css({
                        'display': 'table',
                        'width': '50%',
                        'float': 'left'
                    });
                } else {
                    checkBoxParent.children(".checkbox").css({
                        'display': 'table',
                        'width': '100%',
                        'float': 'left'
                    });
                }
            }

            if (window.matchMedia('(min-width: 1024px)').matches) {
                if (maxLen <= 250) {
                    checkBoxParent.children(".checkbox").css({
                        'display': 'table',
                        'width': '33.33%',
                        'float': 'left'
                    });
                } else if (maxLen <= 400) {
                    checkBoxParent.children(".checkbox").css({
                        'display': 'table',
                        'width': '50%',
                        'float': 'left'
                    });
                } else {
                    checkBoxParent.children(".checkbox").css({
                        'display': 'table',
                        'width': '100%',
                        'float': 'left'
                    });
                }
            }
        }
    }

    $(".col-sm-12 ._wrapper").each(function() {
        $(this).closest("._wrapper").find(".qb-form-input-other").prop("disabled", true);
        $(this).children(".radio:last").children("label").css('margin-bottom', '0px');
        $(this).children(".checkbox:last").children("label").css('margin-bottom', '0px');
        if ($(this).children(".checkbox:first").length !== 0) {
            if ($.trim($(this).children(".checkbox:last").text()) === "その他" || $.trim($(this).children(".checkbox:last").text()) === "Other") {
                $(this).children(".checkbox:last").css({
                    'width': '100%'
                });
            }
        }
    });

    $(document).on("change", "select,input[type='radio']", function () {
        $(this).closest("._wrapper").find(".qb-form-input-other").prop("disabled", true);
        if ($.trim($(this).parent().text()) === "その他" || $.trim($(this).parent().text()) === "Other") {
            var other_input = $(this).closest("._wrapper").find(".qb-form-input-other");
            other_input.prop("disabled", !$(this).prop("checked"));
        }

        if ($.trim($(this).find(':selected').text()) === "その他" || $.trim($(this).find(':selected').text()) === "Other") {
            var other_input = $(this).closest("._wrapper").find(".qb-form-input-other");
            other_input.prop("disabled", false);
        }
    });

    $(document).on("change", "input[type='checkbox']", function () {
        if ($.trim($(this).parent().text()) === "その他" || $.trim($(this).parent().text()) === "Other") {
            var other_input = $(this).closest("._wrapper").find(".qb-form-input-other");
            other_input.prop("disabled", !$(this).prop("checked"));
        }
    });

    $(".qb-form-input-other").prop("disabled", true);
    $("select, input[type='checkbox'] , input[type='radio']:checked").trigger("change");
    $(".qb-form-input-other:disabled").val("");

    $(document).ready(function() {
        var old_value_arr = vars('old_value_arr');
        $(window).keydown(function(event){
            if ((event.keyCode == 13) && ($.inArray(event.target, $('input[type=text]'))) >= 0) {
                event.preventDefault();
                return false;
            }
        });
        //prevent form submit on 'Enter' Key while the target is input text & textarea.

        $("input[type=text], textarea").each(function(){
            var local_storage_value = localStorage.getItem($(this).attr('name'));
            var old_value = $(this).attr('name') in old_value_arr ? old_value_arr[$(this).attr('name')] : '';
            var value = local_storage_value ? local_storage_value : old_value;
            $(this).val(value);
            //set local storage value to respective input box

            $(this).change(function(){
                var key = $(this).attr('name');
                var value = $(this).val();
                localStorage.setItem(key,value);
            });
            //save text input value to local storage.
        });

        $("select").each(function() {
            if ($(this).hasClass('form-control')) {
                var selected_value = localStorage.getItem($(this).attr('name'));
                $(this).children().each(function() {
                    var option = $(this);
                    if (option.val() == selected_value) {
                        option.attr('selected', true);
                        if ((option.text() == 'その他' || option.text() == 'Other') && option.parent().next().hasClass('qb-form-input-other')) {
                            var other_input = $(this).closest("._wrapper").find(".qb-form-input-other");
                            other_input.prop("disabled", false);
                        }
                    }
                });
                //set local storage value to select box element

                $(this).change(function() {
                    var key = $(this).attr('name');
                    var value = $(this).children("option:selected").val();
                    localStorage.setItem(key, value);
                });
                //save selectbox selected value to local storage
            }
        });
        
        $("input[type='radio']").each(function() {
            var checked_value = localStorage.getItem($(this).attr('name'));
            if ($(this).attr('value') == checked_value) {
                $(this).attr('checked', true);
                if ($(this).parent().next().length > 0 && $(this).parent().next().hasClass('qb-form-input-other')) {
                    $(this).parent().next().attr('disabled', false);
                }
            }
            //set local storage value to radio element

            $(this).change(function() {
                var key = $(this).attr('name');
                var value = $(this).attr('value');
                localStorage.setItem(key, value);
            });
            //save radio checked value to local storage
        });

        $("input[type='checkbox']").each(function() {
            var checked_items = localStorage.getItem($(this).attr('name'));
            checked_item_arr = (checked_items) ? JSON.parse(checked_items) : [];
            var value = $(this).attr('value');
            if (checked_item_arr.indexOf(value) >= 0) {
                $(this).attr('checked', true);
                if ($(this).parent().next().length > 0 && $(this).parent().next().hasClass('qb-form-input-other')) {
                    $(this).parent().next().attr('disabled', false);
                }
            }
            //set local storage value to checkbox item

            $(this).change(function() {
                checked_items = localStorage.getItem($(this).attr('name'));
                checked_item_arr = (checked_items) ? JSON.parse(checked_items) : [];
                var key = $(this).attr('name');
                var checkbox_value = $(this).attr('value');
                if (checked_item_arr.indexOf(checkbox_value) < 0) {
                    checked_item_arr.push(checkbox_value);
                } else {
                    checked_item_arr = jQuery.grep(checked_item_arr, function(value) {
                        return value != checkbox_value;
                    });
                }
                localStorage.setItem(key, JSON.stringify(checked_item_arr));
            });
            //save checkbox checked value to local storage
        });
    });

    var confirm_submit = true;
    $('.btn-confirm').on("click", function() {
        if (confirm_submit) {            
           confirm_submit = false;
           if ($(this).attr('name') == '回答を送信') {
                showLoading();
           }
        } else {
            return false;
        }
    });
    //prevent double click on form submit.
});