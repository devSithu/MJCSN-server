$(window).on("load", function() {
    $('.login-box-body.animation').css('opacity', '1').css('transform', 'translateY(0)');
    $('body').tooltip({
        selector: '.qb-tooltip-link',
        template: '<div class="tooltip" style="opacity: 1" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
    });
});
