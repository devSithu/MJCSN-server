$('#btn_download_url_code').click(function(){download(vars('url_code'), 'qr_code.png', 'image/png')});

$('.btn-simple').on('click', function () {
    $('.btn-simple').parent().each(function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        }
    });
});
