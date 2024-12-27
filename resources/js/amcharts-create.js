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
    chart.dataSource.url = url;
    chart.dataSource.load();
}
