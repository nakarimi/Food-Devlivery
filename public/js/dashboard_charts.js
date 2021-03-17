$(document).ready(function() {
// Bar Chart
    $.ajax({
        type: 'get',
        url:'/get_orders_by_status',
        success: function (response) {
            console.log(
                response);
            createChart(response, 'Order Statistics Based on Status', 'orders_status_chart', 400, '100%');
        },
        error: function (e) {
            console.log(e);
        }
    });

    function createChart(response, title, divId, height, width) {
        response.unshift(["Element", "Total", { role: "style" } ]);
        google.charts.load("current", {packages:['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable(response);

            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                { calc: "stringify",
                    sourceColumn: 1,
                    type: "string",
                    role: "annotation" },
                2]);

            var options = {
                title: '',
                width: width,
                height: height,
                bar: {groupWidth: "80%"},
                legend: { position: "none" },
            };

            var chart = new google.visualization.ColumnChart(document.getElementById(divId));
            chart.draw(view, options);
        }
    }
});
