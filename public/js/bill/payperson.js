$(".s_number").keyup(function () {
    if (this.value.length == this.maxLength) {
        var $next = $(this).next('.s_number');
        if ($next.length)
            $(this).next('.s_number').focus();
        else
            $(this).blur();
    }
});
