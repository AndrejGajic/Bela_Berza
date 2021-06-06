function displayGraph () {
    
    var data = document.getElementById("chartData").innerHTML.replace("\n", "").replace("\t","").trim().split(";");
    alert(data);
    
    var dataPoints = [], currentDate = new Date(), rangeChangedTriggered = false;
    
    var stockChart = new CanvasJS.StockChart("chartContainer",{
      theme: "light2",
      title:{
        text:"Stock price chart"
      },
      rangeChanged: function(e) {
          rangeChangedTriggered = true;
      },
      charts: [{
        axisX: {
           crosshair: {
            enabled: true,
            valueFormatString: "YYYY-MM-DD HH:mm:ss"
          }
        },
        axisY: {
          title: "Price [\u20ac]",
          stripLines: [{
            showOnTop: true,
            lineDashType: "dash",
            color: "#ff4949",
            labelFontColor: "#ff4949",
            labelFontSize: 14
          }]
        },
        toolTip: {
          shared: true
        },
        data: [{
          type: "line",
          name: "Pageviews",
          xValueFormatString: "YYYY-MM-DD HH:mm:ss",
          xValueType: "dateTime",
          dataPoints : dataPoints,
        }]
      }],
      navigator: {
        slider: {
          minimum: new Date(currentDate.getTime() - (90 * 1000))
        }
      },
      rangeSelector: {
        enabled: false
      }
    });
    
    
    var dataCount = 30, xstart = data[0], interval = 1000, ystart = parseInt(data[1]);
    updateChart(xstart, ystart, dataCount, interval);
    
    
    function updateChart(xstart, ystart, length, interval) {
        
      var xVal = xstart.replace("\n", "").replace("\t",""), yVal = ystart;
      
      for(var i = 0; i < data.length; i+= 2) {
        //yVal = yVal +  Math.round(5 + Math.random() *(-5-5));
        
        xVal = data[i].replace("\n", "").replace("\t","");
        yVal = parseInt(data[i + 1]);
        
        dataPoints.push({x: xVal,y: yVal});
        //xVal += interval;
      }
      
      if(!rangeChangedTriggered) {
          stockChart.options.navigator.slider.minimum = new Date(xVal - (90 * 1000));
      }
      stockChart.options.charts[0].axisY.stripLines[0].value =  dataPoints[dataPoints.length - 1].y;
      stockChart.options.charts[0].axisY.stripLines[0].label = stockChart.options.charts[0].axisY.stripLines[0]["value"];
      var turn=Math.random()%2;
      stockChart.options.charts[0].color="green";
      xstart = xVal;
      dataCount = 1;
      ystart = yVal;
      stockChart.render();
      setTimeout(function() { updateChart(xstart, ystart, dataCount, interval); }, 1000);
    }
  }