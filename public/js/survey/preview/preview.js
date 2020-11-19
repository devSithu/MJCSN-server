$(function () {
    $(document).on("click", "#submit_next_preview", function () {
        var data_types = array(vars("data_types"));
        var flagError = false;
        $('.content_answers').each(function (index) {
            if($(this).data('required') == 1){
                var data_type_id = $(this).data('type_id');
                switch(data_type_id){
                    case data_types['text']: var value = $(this).find('input').val().trim();break;
                    case data_types['text_area']: var value = $(this).find('textarea').val().trim();;break;
                    case data_types['select']: var value = $(this).find('select').val();break;
                    case data_types['radio']: var value = $(this).find("input[type='radio']:checked").length;break;
                    case data_types['checkbox']: var value = $(this).find("input[type='checkbox']:checked").length;
                }
                if(data_type_id == data_types['select']){
                    if( value == ''){
                        flagError = true;
                        $(this).find('.error_message').html("入力してください。");
                    }else{
                        $(this).find('.error_message').html("");
                    }
                }else{
                    if( value == '' || value == 0 ){
                        flagError = true;
                        $(this).find('.error_message').html("入力してください。");
                    }else{
                        $(this).find('.error_message').html("");
                    }
                }
            }
        });
        if (!flagError) {
            return true;
        } else {
            return false;
        }
    });

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
            })
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
});