$(document).ready(function() {
    $.ajax({
        type: "get",
        url: "/get_restaurant_total_income",
        success: function(response) {
            drawResChar(response);
        },
        error: function(e) {
            console.log(e);
        }
    });
    function drawResChar(dataToDraw) {
        google.charts.load("current", { packages: ["corechart", "line"] });
        google.charts.setOnLoadCallback(drawBackgroundColor);

        function drawBackgroundColor() {
            var data = new google.visualization.DataTable();
            data.addColumn("number", "X");
            data.addColumn("number", "Fifty Fifty");
            data.addColumn("number", "Ghulam Bargar");
            data.addColumn("number", "Herat Super Market");

            data.addRows(dataToDraw);

            var options = {
                hAxis: {
                    title: "Day"
                },
                vAxis: {
                    title: "Total Income"
                },
                backgroundColor: "#f1f8e9"
            };

            var chart = new google.visualization.LineChart(
                document.getElementById("chart_div")
            );
            chart.draw(data, options);
        }
    }
    // Bar Chart
    $.ajax({
        type: "get",
        url: "/get_orders_by_status",
        success: function(response) {
            createChart(
                response,
                "Order Statistics Based on Status",
                "orders_status_chart",
                400,
                "100%"
            );
        },
        error: function(e) {
            console.log(e);
        }
    });

    function createChart(response, title, divId, height, width) {
        response.unshift(["Element", "Total", { role: "style" }]);
        google.charts.load("current", { packages: ["corechart"] });
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable(response);

            var view = new google.visualization.DataView(data);
            view.setColumns([
                0,
                1,
                {
                    calc: "stringify",
                    sourceColumn: 1,
                    type: "string",
                    role: "annotation"
                },
                2
            ]);

            var options = {
                title: "",
                width: width,
                height: height,
                bar: { groupWidth: "80%" },
                legend: { position: "none" }
            };

            var chart = new google.visualization.ColumnChart(
                document.getElementById(divId)
            );
            chart.draw(view, options);
        }
    }
});
