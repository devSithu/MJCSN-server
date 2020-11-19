var number_user_ansewer = vars("number_user_ansewer");
var number_user_not_ansewer = vars("number_user_not_ansewer");
var content_question = vars("content_question");
var order = vars("order");

var survey_question_id = vars("survey_question_id");
var list_answer_visitor = vars("survey_answer_chart");

AmCharts.addInitHandler( function ( chart ) {
    // set base values
    var categoryWidth = 40;

    // calculate bottom margin based on number of data points
    var chartHeight = (categoryWidth * chart.dataProvider.length) + 150;

    // set the value
    chart.div.style.height = chartHeight + 'px';

    if (chart.truncateLabels === undefined)
    return;
    
    // iterate through the data and create truncated label properties
    for(var i = 0; i < chart.dataProvider.length; i++) {
        var label = chart.dataProvider[i][chart.categoryField];
        if (label.length > chart.truncateLabels) {
            label = label.substr(0, chart.truncateLabels-1)+'...';
            chart.dataProvider[i][chart.categoryField] = label;
        }
    }
}, ['serial'] );

var bar_chart = AmCharts.makeChart( "chart-"+survey_question_id, {
    "type": "serial",
    "theme": "light",
    "rotate": true,
    "dataProvider":list_answer_visitor,
    "startDuration": 0,
    "columnWidth": 0.4,
    "truncateLabels": 40,
    "valueAxes": [{
        "minimum": 0,
        "axisAlpha": 0,
    }],
    "graphs": [ {
        "balloonText": "[[category]]: <b>[[value]]</b>",
        "fillAlphas": 0.8,
        "fillColors" : "#dc3545",
        "lineAlpha": 0.2,
        "type": "column",
        "valueField": "survey_visitor_question_answers_count"
    } ],
    "categoryField": "content",
    "categoryAxis": {
        "gridPosition": "start",
        "gridAlpha": 0,
        "tickLength": 0
    },
    "export": {
        "enabled": true,
        "menu": []
    },
    "titles": [{
        "text": content_question,
        "align": "left",
        "type": "checkbox",
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
            "x": 0,
            "y": 12,
            "size": 15,
            "color": "#dc3545"
        }
    ]
});
