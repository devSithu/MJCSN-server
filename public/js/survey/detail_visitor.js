$('.btn-simple').on('click', function () {
    $('.btn-simple').parent().each(function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        }
    });
});