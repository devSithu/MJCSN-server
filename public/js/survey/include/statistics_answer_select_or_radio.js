var number_user_ansewer = vars("number_user_ansewer");
var number_user_not_ansewer = vars("number_user_not_ansewer");
var content_question = vars("content_question");
var order = vars("order");
var color = vars("color");

var survey_question_id = vars("survey_question_id");
var list_answer_visitor = vars("survey_answer_chart");
AmCharts.addInitHandler(function(chart) {
  if (chart.legend === undefined || chart.legend.truncateLabels === undefined)
    return;

  // iterate through the data and create truncated label properties
  for(var i = 0; i < chart.dataProvider.length; i++) {
    var label = chart.dataProvider[i][chart.titleField];
    if (label.length > chart.legend.truncateLabels) {
        label = label.substr(0, chart.legend.truncateLabels-1)+'...';
        chart.dataProvider[i][chart.titleField] = label;
    }
  }
}, ["pie"]);

var pieChart = AmCharts.makeChart( "chart-"+survey_question_id, {
    "type": "pie",
    "pullOutRadius": 0,
    "marginLeft": 40,
    "labelText": "",
    "dataProvider": list_answer_visitor,
    "valueField": "survey_visitor_question_answers_count",
    "titleField": "content",
    "colors": color,
    "innerRadius": "60%",
    "legend": {
        "backgroundColor": "#fff",
        "backgroundAlpha": 0.7,
        "maxColumns": 1,
        "position": "bottom",
        "markerType": "circle",
        "valueText": "[[close]]",
        "truncateLabels": 100
    },
    "balloon":{
        "fixedPosition":true
    },
    "export": {
        "enabled": true,
        "menu": ['PNG']
    },
    "titles": [{
        "text": content_question,
        "align": "left",
        "bold": false
    }, {
        "text": '回答数：' + number_user_ansewer + '  無回答：' + number_user_not_ansewer,
        "align": "left",
        "bold": false
    }],
    "allLabels": [
        {
            "text": "Q" + order,
            "bold": true,
            "color": "#dc3545",
            "x": 0,
            "y": 12,
            "size": 15
        }
    ],
    "height": 400,
} );
