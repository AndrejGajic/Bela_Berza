/* Luka Tomanovic 0410/2018
   Kosta Matijevic 0034/2018 */

import axios from "axios";

var dataPoints = [];

window.onload = function () {
    var currentDate = new Date(), rangeChangedTriggered = false;
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
            valueFormatString: "MMM DD, YYYY HH:mm:ss"
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
          xValueFormatString: "MMM DD, YYYY HH:mm:ss",
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
    
    
    var dataCount = 30, ystart = 50, interval = 1000, xstart = (currentDate.getTime() - (700 * 1000));
    updateChart(xstart, ystart, dataCount, interval);
    
    function updateChart(xstart, ystart, length, interval) {
        
        const data = null;

        const xhr = new XMLHttpRequest();
        xhr.withCredentials = true;

        xhr.addEventListener("readystatechange", function () {
                if (this.readyState === this.DONE) {
                        console.log(this.responseText);
                }
        });

        xhr.open("GET", "https://twelve-data1.p.rapidapi.com/time_series?symbol=AMZN&interval=1min&outputsize=30&format=json/");
        xhr.setRequestHeader("x-rapidapi-key", "4c9b48580dmsh1474e734b15ec04p1bf687jsn1b7cacdeea16");
        xhr.setRequestHeader("x-rapidapi-host", "twelve-data1.p.rapidapi.com");

        xhr.send(data);
        
        alert(data);
        
        
      var xVal = xstart, yVal = ystart;
      for(var i = 0; i < length; i++) {
        yVal = yVal +  Math.round(5 + Math.random() *(-5-5));
        yVal = Math.min(Math.max(yVal, 5), 90);
        dataPoints.push({x: xVal,y: yVal});
        xVal += interval;
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