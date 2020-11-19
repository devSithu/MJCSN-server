var coldef = [
    function (row, type, val, meta) {
        return (vars("data").length) - meta.row;
    },
    "name",
    function (row, type, val, meta) {
        return (row.survey_visitors_count ? row.survey_visitors_count : 0);
    },
    function (row, type, val, meta) {
        var start_datetime = row.start_datetime.replace(' ', 'T');
        var end_datetime = row.end_datetime.replace(' ', 'T');
        return (new Date(start_datetime).format("yy/mm/dd HH:MM") + ' ～ ' + new Date(end_datetime).format("yy/mm/dd HH:MM"));
    }
];

var data = vars("data");

var table = $('.qb-list-table').DataTable({
    deferRender: true,
    order: [[ 0, "asc" ]],
    autoWidth: false,
    columnDefs: map_datatable_columns(coldef),
    data: data,
    dom: "<" +
    "<'qb-list-option clearfix'>" +
    "<'qb-search-option'<'qb-search-left'f> <'qb-search-right'Rl>>" +
    "<'qb-row-count'>" +
    "<'relative'rt>" +
    "<'qb-list-option'" + "p>" +
    ">",
    scrollX: true,
    pageLength: 10,
    lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
    rowCallback: function (row, data, index) {
        $(row).addClass('qb-visitor-line');
    }
});

// append
$(".dataTables_paginate").removeClass().addClass("qb-list-option-right");
$(".qb-list-container").append($(".qb-list-option"));
$(".qb-list-container").prepend($(".qb-list-option.clearfix"));

table.on('click', 'td', function () {
    var cell = table.cell(this).index();
    var survey_id = table.row(cell.row).data().survey_id;
    location.href = vars('url_user_show_survey_detail').replace('@survey_id', survey_id);
});
