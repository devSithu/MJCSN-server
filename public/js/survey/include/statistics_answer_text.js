var coldef = ['content'];
var survey_question_id = vars("survey_question_id");

var table = $('.statistics-text-'+survey_question_id).DataTable({
    deferRender: true,
    autoWidth: true,
    columnDefs: map_datatable_columns(coldef),
    data: vars("answer_text"),
    dom: "<" +
    "<'qb-list-option clearfix'>" +
    "<'qb-search-option'<'qb-search-left'f> <'qb-search-right'Rl>>" +
    "<'qb-row-count'>" +
    "<'relative'rt>" +
    "<'qb-list-option'" + "p>" +
    ">",
    scrollX: false,
    pageLength: 5,
    lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
    drawCallback: function () {
        $("#table_count_"+survey_question_id).html("検索結果 " + "<strong>" + this.api().rows({search: 'applied'}).count() + "</strong>" + "件");
    }
});
// append
$(".dataTables_paginate").removeClass().addClass("qb-list-option-right");
