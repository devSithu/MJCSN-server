$(document).ready(function () {
    $(".btn-scroll").each(function () {
        if($(this).parent().find(".scroll-block").height() >= 160) {
            $(this).html('<i class="material-icons">keyboard_arrow_down</i> すべて表示').show();
        }
    });
});

$(".btn-scroll").on("click", function () {
    var scrollBody = $(this).parent().find(".scroll-block");
    var minHeight = 160;
    var maxHeight = scrollBody.find(".scroll-body").height();

    if (scrollBody.hasClass("show-all")) {
        scrollBody.removeClass("show-all").animate({"max-height": minHeight}, 225);
        $(this).html('<i class="material-icons">keyboard_arrow_down</i> すべて表示');
    } else {
        scrollBody.addClass("show-all").animate({"max-height": maxHeight}, 225);
        $(this).html('<i class="material-icons">keyboard_arrow_up</i> 閉じる');
    }
});
