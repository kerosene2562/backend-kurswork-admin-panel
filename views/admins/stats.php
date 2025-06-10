<html>
<head>
  <script src="https://cdn.anychart.com/releases/v8/js/anychart-base.min.js"></script>
  <script src="https://cdn.anychart.com/releases/v8/js/anychart-ui.min.js"></script>
  <script src="https://cdn.anychart.com/releases/v8/js/anychart-exports.min.js"></script>
  <link href="https://cdn.anychart.com/releases/v8/css/anychart-ui.min.css" type="text/css" rel="stylesheet">
  <link href="https://cdn.anychart.com/releases/v8/fonts/css/anychart-font.min.css" type="text/css" rel="stylesheet">
  <style type="text/css">

    html,
    body,
    #container {
      width: 100%;
      height: 600px;
      margin: 0;
      padding: 0;
    }
  
</style>
</head>
<body>
  
  <div id="container"></div>
  

  <script>

    anychart.onDocumentReady(function () {
      // create line chart
      var chart = anychart.line();

      // set chart padding
      chart.padding([10, 20, 5, 20]);

      // turn on chart animation
      chart.animation(true);

      // turn on the crosshair
      chart.crosshair(true);

      // set chart title text settings
      chart.title('Customers Activity during the Week');

      // set y axis title
      chart.yAxis().title('Activity occurrences');

      // create logarithmic scale
      var logScale = anychart.scales.log();
      logScale.minimum(1).maximum(45000);

      // set scale for the chart, this scale will be used in all scale dependent entries such axes, grids, etc
      chart.yScale(logScale);

      // create data set on our data,also we can pud data directly to series
      var dataSet = anychart.data.set([
        ['Monday', '1120', '4732', '15176'],
        ['Tuesday', '720', '3689', '18910'],
        ['Wednesday', '404', '3904', '19004'],
        ['Thursday', '190', '754', '22233'],
        ['Friday', '15', '187 ', '922'],
        ['Saturday', '10', '45', '534'],
        ['Sunday', '7', '61', '343']
      ]);

      // map data for the first series,take value from first column of data set
      var firstSeriesData = dataSet.mapAs({ x: 0, value: 1 });

      // map data for the second series,take value from second column of data set
      var secondSeriesData = dataSet.mapAs({ x: 0, value: 2 });

      // map data for the third series, take x from the zero column and value from the third column of data set
      var thirdSeriesData = dataSet.mapAs({ x: 0, value: 3 });

      // temp variable to store series instance
      var series;

      // setup first series
      series = chart.line(firstSeriesData);
      series.name('Review about the product');
      // enable series data labels
      series.labels().enabled(true).anchor('left-bottom').padding(5);
      // enable series markers
      series.markers(true);

      // setup second series
      series = chart.line(secondSeriesData);
      series.name('Comment blog posts');
      // enable series data labels
      series.labels().enabled(true).anchor('left-bottom').padding(5);
      // enable series markers
      series.markers(true);

      // setup third series
      series = chart.line(thirdSeriesData);
      series.name('Email delivery support');
      // enable series data labels
      series.labels().enabled(true).anchor('left-bottom').padding(5);
      // enable series markers
      series.markers(true);

      // turn the legend on
      chart.legend().enabled(true).fontSize(13).padding([0, 0, 20, 0]);

      // set container for the chart and define padding
      chart.container('container');
      // initiate chart drawing
      chart.draw();
    });
  
</script>
</body>
</html>
                