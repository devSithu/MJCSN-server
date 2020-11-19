$('.exportButton').click(function () {
    var survey_name = vars('survey_name');
    var question_order = $(this).data('question_order');

    var chart_id = $(this).data('chart_id');
    var doc = new jsPDF('p', 'pt', 'a3');

    $('#'+ chart_id + ' .amcharts-chart-div a').remove();
    var svg = $('#'+ chart_id + ' .amcharts-chart-div').html();
    svg = svg.replace(/\r?\n|\r/g, '').trim();

    var canvas = document.createElement('canvas');
    canvg(canvas, svg);
    var imgData = canvas.toDataURL();

    var pageHeight = doc.internal.pageSize.getHeight();
    var imgHeight = canvas.height * 0.75;
    var heightLeft = imgHeight;
    var position = 0;

    doc.addImage(imgData, 'PNG', 10, 10);
    heightLeft -= pageHeight;

    while (heightLeft >= 0) {
        position = (heightLeft - imgHeight) + 11;
        doc.addPage();
        doc.addImage(imgData, 'PNG', 10, position);
        heightLeft -= pageHeight;
    }

    var svgLebel = $('#'+ chart_id + ' .amcharts-legend-div').html();
    if(svgLebel){
        /**
         * for circle charts' labels. (selectbox and radio)
         */
        canvg(canvas, svgLebel);
        var imgDataLabel = canvas.toDataURL();
        var imgLabelHeight = canvas.height * 0.75;

        doc.addImage(imgDataLabel, 'PNG', 10, 320);

        var usedimgLabelHeight = pageHeight - (imgHeight + 10);
        var leftImgLabelHeight = imgLabelHeight - usedimgLabelHeight;

        while (leftImgLabelHeight >= 0) {
            position = (leftImgLabelHeight - imgLabelHeight) + 11;
            doc.addPage();
            doc.addImage(imgDataLabel, 'PNG', 10, position);
            leftImgLabelHeight -= pageHeight;
        }
    }
    doc.save(survey_name + '_Q' + question_order + '.pdf');
});

if ($('.qb-card-content').length != 0) {
    $(this).parent().prev().css('display', 'block');
    $(this).parent().css('padding-top', '20px');
}

var maxTime = 1000;
var startTime = Date.now();

var interval = setInterval(function () {
    if ($('.amcharts-main-div').length != 0) {
        $('.amcharts-main-div').each(function () {
            $(this).children(".amcharts-chart-div").css('overflow', 'unset');
            $(this).parent().parent().prev().css('display', 'block');
            $(this).parent().parent().css('padding-top', '20px');
        });
    }

    if (Date.now() - startTime > maxTime) {
        clearInterval(interval);
    }
}, 100);

$('.btn-simple').on('click', function () {
    $('.btn-simple').parent().each(function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        }
    });
});