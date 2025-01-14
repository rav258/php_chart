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

<div class="container mt-5">
    <h1 class="text-center">Analiza Zmian Kursów Walutowych – EUR i USD</h1>
    <p class="text-center mt-3">
        Odkryj zmiany na rynku walutowym dzięki naszym interaktywnym wykresom!
        Strona przedstawia szczegółowe analizy dzienne kursów EUR i USD w różnych kategoriach:
        Kupno i Sprzedaż. Wizualizacje ułatwiają zrozumienie trendów oraz fluktuacji cen w wybranym okresie.
    </p>

    <ul class="list-group">
        <li class="list-group-item d-flex justify-content-between align-items-center">📊 Interaktywne wizualizacje – Zobacz szczegóły zmian kursów walutowych na dynamicznych wykresach.</li>
        <li class="list-group-item d-flex justify-content-between align-items-center">📅 Wybierz zakres dat – Wprowadź interesujący Cię okres, aby zobaczyć dane historyczne.</li>
        <li class="list-group-item d-flex justify-content-between align-items-center">💱 Kursy EUR i USD – Szczegółowe dane na temat kursów kupna i sprzedaży walut.</li>
    </ul>


    <div class="row mt-4">
        <div class="col-md-4">
            <button class="btn btn-chart" data-target="#pie-chart">
                <span class="card" role="complementary">
                    <img class="card-img-top" src="resources/img/pie-chart.png" alt="pie-chart" style="width:100%">
                </span>
                <span>Wykres Kołowy</span>
            </button>
        </div>

        <div class="col-md-4">
            <button class="btn btn-chart" data-target="#stock-line-chart">
                <span class="card" role="complementary">
                    <img class="card-img-top" src="resources/img/stock-chart.png" alt="stock-line-chart" style="width:100%">
                </span>
                <span>Wykres Liniowy</span>
            </button>
        </div>

        <div class="col-md-4">
            <button class="btn btn-chart" data-target="#step-line-chart">
                <span class="card" role="complementary">
                    <img class="card-img-top" src="resources/img/step-chart.png" alt="step-line-chart" style="width:100%">
                </span>
                <span>Zmiany dzienne</span>
            </button>
        </div>
    </div>

</div>

<!--wykres kołowy-->
<div class="container mt-3">
    <div class="card">
        <div class="card-header">
            <h5>Wykres Kołowy zawierający średnią</h5>
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


<!--wykres liniowy-->
<div class="container mt-3">
    <div class="card">
        <div class="card-header">
            <h5>Wykres Liniowy</h5>
            <div class="row">
                <div class="col-3">
                    <label for="stock-date-from">Data od</label>
                    <input id="stock-date-from" type="text" onchange="createStockLineChart()" class="form-control datepicker-class-control" placeholder="Wybierz...">
                </div>
                <div class="col-3">
                    <label for="stock-date-to">Data do</label>
                    <input id="stock-date-to" type="text" onchange="createStockLineChart()" class="form-control datepicker-class-control" placeholder="Wybierz...">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="stock-line-chart" class="chart-container"></div>
        </div>
    </div>
</div>

<!--wykres słupkowy-->
<div class="container mt-3">
    <div class="card">
        <div class="card-header">
            <h5>Wykres Zmian dziennych</h5>
            <div class="row">
                <div class="col-3">
                    <label for="step-date-from">Data od</label>
                    <input id="step-date-from" type="text" onchange="createStepChart()" class="form-control datepicker-class-control" placeholder="Wybierz...">
                </div>
                <div class="col-3">
                    <label for="step-date-to">Data do</label>
                    <input id="step-date-to" type="text" onchange="createStepChart()" class="form-control datepicker-class-control" placeholder="Wybierz...">
                </div>
                <div class="col-3">
                    <label for="step-category">Kategoria</label>
                    <select name="step-category" id="step-category" onchange="createStepChart()" class="form-select">
                        <option value="EUR_BUY">EUR_BUY</option>
                        <option value="EUR_SELL">EUR_SELL</option>
                        <option value="USD_BUY">USD_BUY</option>
                        <option value="USD_SELL">USD_SELL</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="step-line-chart" class="chart-container"></div>
        </div>
    </div>
</div>

<footer class="mt-5 p-4 bg-dark text-white text-center">
    <p>&copy; <a class="link" href="mailto:ravmaster48@gmail.com">Rav</a>
    </p>
</footer>

<script>
    $(function() {
        $(".datepicker-class-control").datepicker({
            dateFormat: "yy-mm-dd"
        });
    });

    document.querySelectorAll('.btn-chart').forEach(button => {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });


    document.addEventListener("DOMContentLoaded", function () {
        fetch("/api/currency/minMaxDates.php")
            .then(response => response.json())
            .then(data => {
                if (data.minDate && data.maxDate) {
                    const dateFromInput = document.getElementById("pie-date-from");
                    const dateFromInputStock = document.getElementById("stock-date-from");
                    const dateFromInputStep = document.getElementById("step-date-from");

                    const dateToInput = document.getElementById("pie-date-to");
                    const dateToInputStock = document.getElementById("stock-date-to");
                    const dateToInputStep = document.getElementById("step-date-to");

                    dateFromInput.value = new Date(data.minDate).toISOString().split("T")[0];
                    dateFromInputStock.value = new Date(data.minDate).toISOString().split("T")[0];
                    dateFromInputStep.value = new Date(data.minDate).toISOString().split("T")[0];

                    dateToInput.value = new Date(data.maxDate).toISOString().split("T")[0];
                    dateToInputStock.value = new Date(data.maxDate).toISOString().split("T")[0];
                    dateToInputStep.value = new Date(data.maxDate).toISOString().split("T")[0];

                    createPieChart();
                    createStockLineChart();
                    createStepChart();
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
