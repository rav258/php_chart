function createPieChart() {
    am4core.useTheme(am4themes_animated);
    am4core.options.autoDispose = true;

    const chart = am4core.create("pie-chart", am4charts.PieChart3D);
    chart.hiddenState.properties.opacity = 0;

    chart.legend = new am4charts.Legend();

    const series = chart.series.push(new am4charts.PieSeries3D());
    series.dataFields.value = "value";
    series.dataFields.category = "category";

    const dateFrom = document.getElementById("pie-date-from").value;
    const dateTo = document.getElementById("pie-date-to").value;

    if (!dateFrom || !dateTo) return;

    const url = `/api/currency/pie.php?dateFrom=${encodeURIComponent(dateFrom)}&dateTo=${encodeURIComponent(dateTo)}`;

    fetch("/api/currency/pie.php", {
        method: "POST",
        body: JSON.stringify({
            dateFrom: dateFrom,
            dateTo: dateTo,
        })
    })
        .then(response => response.json())
        .then(data => {
            chart.data = data;
        })
        .catch(error => {
            console.error("Error fetching data:", error);
        });
}

function createStockLineChart() {
    am4core.useTheme(am4themes_animated);
    am4core.options.autoDispose = true;

    const chart = am4core.create("stock-line-chart", am4charts.XYChart);
    chart.padding(0, 15, 0, 15);
    chart.colors.step = 3;


    const dateFrom = document.getElementById("stock-date-from").value;
    const dateTo = document.getElementById("stock-date-to").value;

    if (!dateFrom || !dateTo) return;

    fetch("/api/currency/stock-line.php", {
        method: "POST",
        body: JSON.stringify({
            dateFrom: dateFrom,
            dateTo: dateTo,
        })
    })
        .then(response => response.json())
        .then(rawData => {
            chart.data = JSON.parse(rawData);
        })
        .catch(error => {
            console.error("Error fetching data:", error);
        });

    chart.dateFormatter.inputDateFormat = "yyyy-MM-dd";

    // Create x-axis (DateAxis)
    const dateAxis = chart.xAxes.push(new am4charts.DateAxis());
    dateAxis.renderer.minGridDistance = 50;
    dateAxis.title.text = "Date";

    // Create y-axis (ValueAxis)
    const valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.title.text = "Exchange Rate";

    // Create series for each data field
    function createSeries(field, name) {
        const series = chart.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = field;
        series.dataFields.dateX = "date";
        series.name = name;
        series.tooltipText = "{name}: [bold]{valueY}[/]";
        series.strokeWidth = 2;

    }

    // Add EUR Buy, EUR Sell, USD Buy, and USD Sell series
    createSeries("EUR_BUY", "EUR Buy");
    createSeries("EUR_SELL", "EUR Sell");
    createSeries("USD_BUY", "USD Buy");
    createSeries("USD_SELL", "USD Sell");

    // Add legend
    chart.legend = new am4charts.Legend();

    // Add cursor
    chart.cursor = new am4charts.XYCursor();

    // Add scrollbar
    chart.scrollbarX = new am4core.Scrollbar();

}

function createStepChart() {
    am4core.useTheme(am4themes_animated);
    am4core.options.autoDispose = true;

    const chart = am4core.create("step-line-chart", am4charts.XYChart);
    chart.padding(0, 15, 0, 15);


    const dateFrom = document.getElementById("step-date-from").value;
    const dateTo = document.getElementById("step-date-to").value;
    const category = document.getElementById("step-category").value;

    if (!dateFrom || !dateTo || !category) return;

    fetch("/api/currency/step-chart.php", {
        method: "POST",
        body: JSON.stringify({
            dateFrom: dateFrom,
            dateTo: dateTo,
            category: category
        })
    })
        .then(response => response.json())
        .then(rawData => {
            chart.data = JSON.parse(rawData);
        })
        .catch(error => {
            console.error("Error fetching data:", error);
        });

    chart.dateFormatter.inputDateFormat = "yyyy-MM-dd";

    const dateAxis = chart.xAxes.push(new am4charts.DateAxis());
    dateAxis.renderer.grid.template.location = 0;

    const valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.tooltip.disabled = true;
    valueAxis.renderer.minWidth = 35;

    const series = chart.series.push(new am4charts.StepLineSeries());
    series.dataFields.dateX = "date";
    series.dataFields.valueY = "value";
    series.noRisers = true;
    series.strokeWidth = 2;
    series.fillOpacity = 0.2;
    series.sequencedInterpolation = true;

    series.tooltipText = "{valueY.value}";
    chart.cursor = new am4charts.XYCursor();

    chart.scrollbarX = new am4charts.XYChartScrollbar();
    chart.scrollbarX.series.push(series);

}

