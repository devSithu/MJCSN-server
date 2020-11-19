$(function () {

    var j$ = jQuery,
        $nav = j$("#navigation"),
        $currentItem = $($nav).find("li.active");

    j$(function(){
        // Menu has active item
        if ($currentItem.length) {
            $currentItem.addClass('menu-slide-line');
        }

        $($nav).find("li").mouseover(function (e) {
            e.stopPropagation();
            $($nav).find("li").not(this).removeClass('menu-slide-line');
            $(this).addClass('menu-slide-line');
        });

        $($nav).find("li").mouseout(function () {
            $(this).removeClass('menu-slide-line');
            if ($currentItem.length) {
                $currentItem.addClass('menu-slide-line');
            }
        });
    });
    
	$("#js-slide-menu span").on("click", function(e) {
        $(this).next("ul").slideToggle();
        $(this).closest("li").siblings().children("ul").hide();
       
    });

    $(".major-items ul.active").parent('.major-items').css("background-color", "#263040");
});

$(function () {
    $('.has-error input').on("blur", function () {
        $(this).closest(".has-error").removeClass("has-error");
    });
    $('.has-error select').on("change", function () {
        $(this).closest(".has-error").removeClass("has-error");
    });
    $('.has-error textarea').on("change", function () {
        $(this).closest(".has-error").removeClass("has-error");
    });
    $(".qb-js-event-off").on("click", function (event) {
        event.preventDefault();
        event.stopPropagation();
    });
    $(document).on("click", ".qb-cal-icon", function () {
        $(this).prevAll(".qb-datepicker").trigger("focus");
    });
    // tooltip
    $('body').tooltip({
        selector: '.qb-tooltip-link',
        template: '<div class="tooltip" style="opacity: 1" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
    });

    $(document).on("keypress", "input[type=text], input[type=password], input[type=email]", function (event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            var inputs = $(this).closest('form').find('a[href], area[href], input[type!="hidden"]:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled])');
            inputs.eq(inputs.index(this) + 1).focus();
        }
    });

    $(".flash-message-dialog").delay(5000).fadeOut("slow").on('click', function () {
        $(this).hide();
    });

    $('form.with-checkbox').on('submit', function () {
        $(this).find("input[type='checkbox']").prop("disabled", false);
    });

});

function showLoading(timeout) {
    var id = '_loading_' + random();
    $('body').append($('<div/>', {style: "position:fixed;top:0;left:0;right:0;bottom:0; background: rgba(0, 0, 0, .3) url('/img/loading.gif') 50% 50% no-repeat;z-index: 1000;", class: '__loading_screen', id: id}));
    setTimeout(function () {
        timeout ? $("#" + id).remove() : '';
    }, timeout ? timeout : 0);
}

function hideLoading() {
    $(".__loading_screen").remove();
}

function setCheckAllBox(checkbox, target) {
    $(checkbox).on('change', function () {
        $(target).prop('checked', this.checked);
        $(target + ':first').trigger('change');
    });
    $(target).on('change', function () {
        if ($(target + ':checked').length == $(target).length) {
            $(checkbox).prop("checked", true);
        } else {
            $(checkbox).prop("checked", false);
        }
        is_check_disabled();
    });
    function is_check_disabled() {
        if ($(target + ':checked').length > 0) {
            $('.qb-is-check').removeClass('disable');
        } else {
            $('.qb-is-check').addClass('disable');
        }
    }
}

$.fn.dataTableExt.oApi._fnCallbackReg($.fn.DataTable.models.oSettings, 'aoDrawCallback', function (oS, oData) {
    $(".pagination").addClass("qb-pagination");
    $(".paginate_button.previous a").html('<span aria-hidden="true"> < </span>');
    $(".paginate_button.next a").html('<span aria-hidden="true"> > </span>');
    $(".paginate_button.next.disabled a").html("&raquo;");
    $(".paginate_button.previous.disabled a").html("&laquo;");
}, "custom_paging_style");
$.fn.DataTable.defaults.oLanguage.sZeroRecords = "検索結果が見つかりませんでした";
$.fn.DataTable.defaults.oLanguage.sSearch = "";
$.fn.DataTable.defaults.oLanguage.sSearchPlaceholder = "検索";
$.fn.DataTable.defaults.oLanguage.sProcessing = '';
$.fn.DataTable.defaults.oLanguage.sEmptyTable = 'データが登録されていません';
$.fn.DataTable.defaults.oLanguage.sLengthMenu = "<span class='lengthMenu'>表示件数</span><span class='l-menu'>_MENU_</span><span class='p-count'>件</span>";

function map_datatable_columns(coldef, options) {
    options = options ? options : {};

    function htmlEscapeEntities(d) {
        return d.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function toString(data) {
        return data ? data.toString() : '';
    }

    return   coldef.map(function (e, i) {
        var col = e.data ? e : {data: e};
        col.targets = i;
        col.defaultContent = "";
        if (options.disabled_cols) {
            col.searchable = options.disabled_cols.indexOf(i) < 0;
            col.orderable = options.disabled_cols.indexOf(i) < 0;
        }
        if (options.hidden_cols) {
            col.visible = options.hidden_cols.indexOf(i) < 0;
        }

        if ((!col.render) && (typeof col.data === 'string')) {
            col.render = function (data, type) {
                return type === 'display' ? htmlEscapeEntities(toString(data)) : data;
            }
        }
        return col;
    });
}

function nl2br (str, is_xhtml) {   
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}