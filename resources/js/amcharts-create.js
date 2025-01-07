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
        .then(data => {
            console.log(data)
            chart.data = data;
        })
        .catch(error => {
            console.error("Error fetching data:", error);
        });






}
