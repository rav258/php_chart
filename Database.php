<?php
require_once "config.php";

class Database
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPieData($dateFrom, $dateTo)
    {
        $query = "
            SELECT 
                'EUR Buy' AS category, AVG(EUR_BUY) AS value
            FROM currency_exchange
            WHERE DATE_TIME BETWEEN :dateFrom AND :dateTo

            UNION ALL

            SELECT 
                'EUR Sell' AS category, AVG(EUR_SELL) AS value
            FROM currency_exchange
            WHERE DATE_TIME BETWEEN :dateFrom AND :dateTo

            UNION ALL

            SELECT 
                'USD Buy' AS category, AVG(USD_BUY) AS value
            FROM currency_exchange
            WHERE DATE_TIME BETWEEN :dateFrom AND :dateTo

            UNION ALL

            SELECT 
                'USD Sell' AS category, AVG(USD_SELL) AS value
            FROM currency_exchange
            WHERE DATE_TIME BETWEEN :dateFrom AND :dateTo
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':dateFrom' => $dateFrom,
            ':dateTo' => $dateTo,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStockLineChartData($dateFrom, $dateTo)
    {
        $query = "
        SELECT 
            DATE(DATE_TIME) AS date,
            AVG(EUR_BUY) AS EUR_BUY,
            AVG(EUR_SELL) AS EUR_SELL,
            AVG(USD_BUY) AS USD_BUY,
            AVG(USD_SELL) AS USD_SELL
        FROM currency_exchange
        WHERE DATE_TIME BETWEEN :dateFrom AND :dateTo
        GROUP BY 
            DATE(DATE_TIME)
        ORDER BY 
            DATE(DATE_TIME)
    ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':dateFrom' => $dateFrom,
            ':dateTo' => $dateTo,
        ]);

        // Fetch the data as associative array
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $formattedData = [];

        foreach ($data as $row) {
            $formattedData[] = [
                'date' => $row['date'],
                'EUR_BUY' => (float) $row['EUR_BUY'],
                'EUR_SELL' => (float) $row['EUR_SELL'],
                'USD_BUY' => (float) $row['USD_BUY'],
                'USD_SELL' => (float) $row['USD_SELL'],
            ];
        }

        // Return the data as a JSON object
        return json_encode($formattedData);
    }

    public function getStepChartData($dateFrom, $dateTo, $category)
    {
        $query = "
        SELECT 
            DATE(c1.DATE_TIME) AS date,
            MAX(CASE WHEN c1.DATE_TIME = (SELECT MIN(DATE_TIME) 
                                          FROM currency_exchange 
                                          WHERE DATE(DATE_TIME) = DATE(c1.DATE_TIME)) 
                     THEN $category END) AS open_price,
            MAX(CASE WHEN c1.DATE_TIME = (SELECT MAX(DATE_TIME) 
                                          FROM currency_exchange 
                                          WHERE DATE(DATE_TIME) = DATE(c1.DATE_TIME)) 
                     THEN $category END) AS close_price
        FROM currency_exchange c1
        WHERE DATE_TIME BETWEEN :dateFrom AND :dateTo
        GROUP BY DATE(c1.DATE_TIME)
        ORDER BY DATE(c1.DATE_TIME)
    ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':dateFrom' => $dateFrom,
            ':dateTo' => $dateTo,
        ]);

        // Fetch the data as associative array
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $formattedData = [];
        foreach ($data as $row) {
            $formattedData[] = [
                'date' => $row['date'],
                'value' => (float) $row['close_price'] - (float) $row['open_price'],
            ];
        }

        // Return the data as a JSON object
        return json_encode($formattedData);
    }

    public function getMinMaxDates()
    {
        $query = "
        SELECT 
            MIN(DATE_TIME) AS minDate, 
            MAX(DATE_TIME) AS maxDate 
        FROM currency_exchange
    ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
