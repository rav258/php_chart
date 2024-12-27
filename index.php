<?php
require_once "Database.php";

$db = new Database($pdo);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <title>Currency charts</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Currency charts">

    <!--custom res.-->
    <link href="resources/css/custom.css" rel="stylesheet">
    <script src="resources/js/amcharts-create.js"></script>

    <!--bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!--jquery-->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">

    <!--amcharts-->
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
</head>
<body>

<!--wykres kołowy-->
<div class="container mt-3">
    <div class="card">
        <div class="card-header">
            Wykres Kołowy zawierający średnią
            <div class="row">
                <div class="col-3">
                    <label for="pie-date-from">Data od</label>
                    <input id="pie-date-from" type="text" onchange="createPieChart()" class="form-control datepicker-class-control" placeholder="Wybierz...">
                </div>
                <div class="col-3">
                    <label for="pie-date-to">Data do</label>
                    <input id="pie-date-to" type="text" onchange="createPieChart()" class="form-control datepicker-class-control" placeholder="Wybierz...">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="pie-chart" class="chart-container"></div>
        </div>
    </div>
</div>


<!--wykres słupkowy-->
<div class="container mt-3">
    <div class="card">
        <div class="card-header">Header</div>
        <div class="card-body">Content</div>
    </div>
</div>

<!--wykres liniowy-->
<div class="container mt-3">
    <div class="card">
        <div class="card-header">Header</div>
        <div class="card-body">Content</div>
    </div>
</div>

<script>
    $(function() {
        $(".datepicker-class-control").datepicker({
            dateFormat: "yy-mm-dd"
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        fetch("/api/currency/minMaxDates.php")
            .then(response => response.json())
            .then(data => {
                if (data.minDate && data.maxDate) {
                    const dateFromInput = document.getElementById("pie-date-from");
                    const dateToInput = document.getElementById("pie-date-to");

                    dateFromInput.value = new Date(data.minDate).toISOString().split("T")[0];
                    dateToInput.value = new Date(data.maxDate).toISOString().split("T")[0];
                    createPieChart();
                } else {
                    console.error("Invalid date range received:", data);
                }
            })
            .catch(error => {
                console.error("Error fetching date range:", error);
            });

    });

</script>

</body>
</html>
