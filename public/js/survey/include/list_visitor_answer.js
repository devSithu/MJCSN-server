var invisible_cols = [];
var coldef = [
    'user_number', 'user_name', 'email', 'created_at'
];

var table = $('.qb-list-table').DataTable({
    order: [["0", "asc"]],
    deferRender: true,
    processing: true,
    autoWidth: false,
    columnDefs: map_datatable_columns(coldef),
    serverSide: true,
    ajax: {
        url: vars('url_data') + "?_token=" + token(),
        method: "post",
        dataSrc: function (json) {
            total = json.recordsTotal
            return json.data;
        }
    },
    dom: "<" +
    "<'qb-list-option clearfix'>" +
    "<'qb-search-option'<'qb-search-left'f> <'qb-search-right'Rl>>" +
    "<'qb-row-count'>" +
    "<'relative'rt>" +
    "<'button-add'>" +
    "<'qb-list-option'" + "p>" +
    ">",
    scrollX: true,
    pageLength: 5,
    lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
    drawCallback: function () {
        $("#table_count").html("回答者 " + "<strong>" + total + "</strong>" + "件");
    },
    rowCallback: function (row, data, index) {
        $(row).addClass('qb-visitor-line');
    },
    stateSave: true,
});

var state = table.state.loaded();
if (state == null) {
    table.columns(invisible_cols).visible(false);
}

// append
$(".dataTables_paginate").removeClass().addClass("qb-list-option-right");
$(".qb-list-container").append($(".qb-list-option"));
$(".qb-list-container").prepend($(".qb-list-option.clearfix"));
$("#table_count").insertAfter(".qb-search-option");

table.on('click', 'td', function () {
    var cell = table.cell(this).index();
    var user_number = table.row(cell.row).data().user_number;
    var survey_id = vars("survey_id");
    location.href = vars('user_show_detail_answer_visitor').replace('@user_number', user_number).replace('@survey_id', survey_id);
});

table.on('column-visibility.dt', function () {
    table.columns.adjust().draw();
});