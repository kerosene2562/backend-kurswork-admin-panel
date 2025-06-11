anychart.onDocumentReady(function () {
    let sortedDataArray = [];
    fetch(`/lost_admin/admins/getStats`)
        .then(response => response.json())
        .then(data => {
            data.forEach(log => {
                if (log['status_code'] < 300) {
                    return;
                }
                let date = log["created_at"].split(" ")[0];

                if (!(date in sortedDataArray)) {
                    sortedDataArray[date] = { '3xx': 0, '4xx': 0, '5xx': 0 };
                }
                let status = log['status_code'];
                if (status >= 300 && status < 400) {
                    sortedDataArray[date]['3xx']++;
                } else if (status >= 400 && status < 500) {
                    sortedDataArray[date]['4xx']++;
                } else if (status >= 500) {
                    sortedDataArray[date]['5xx']++;
                }
            })

            let chartData = Object.keys(sortedDataArray)
                .sort()
                .map(date => [
                    date,
                    sortedDataArray[date]['3xx'],
                    sortedDataArray[date]['4xx'],
                    sortedDataArray[date]['5xx']
                ]);

            drawChart(chartData);
        })
});


function drawChart(chartData) {
    var chart = anychart.line();

    chart.padding([10, 20, 5, 20]);

    chart.animation(true);

    chart.crosshair(true);

    chart.title('Статистика логування запитів');

    chart.yAxis().title('Кількість логів');

    chart.background().fill("rgb(54, 54, 54)");

    var logScale = anychart.scales.log();
    logScale.minimum(1).maximum(45000);

    chart.yScale(logScale);

    var dataSet = anychart.data.set(chartData)

    var firstSeriesData = dataSet.mapAs({ x: 0, value: 1 });

    var secondSeriesData = dataSet.mapAs({ x: 0, value: 2 });

    var thirdSeriesData = dataSet.mapAs({ x: 0, value: 3 });

    var series;

    series = chart.line(firstSeriesData);
    series.name('3**');
    series.labels().enabled(true).anchor('left-bottom').padding(5);
    series.markers(true);

    series = chart.line(secondSeriesData);
    series.name('4**');
    series.labels().enabled(true).anchor('left-bottom').padding(5);
    series.markers(true);

    series = chart.line(thirdSeriesData);
    series.name('5**');
    series.labels().enabled(true).anchor('left-bottom').padding(5);
    series.markers(true);

    chart.legend().enabled(true).fontSize(13).padding([0, 0, 20, 0]);

    chart.container('container');
    chart.draw();
}