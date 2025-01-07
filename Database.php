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
            DATE_TIME AS date, 
            'EUR Buy' AS category, EUR_BUY AS value
        FROM currency_exchange
        WHERE DATE_TIME BETWEEN :dateFrom AND :dateTo

        UNION ALL

        SELECT 
            DATE_TIME AS date, 
            'EUR Sell' AS category, EUR_SELL AS value
        FROM currency_exchange
        WHERE DATE_TIME BETWEEN :dateFrom AND :dateTo

        UNION ALL

        SELECT 
            DATE_TIME AS date, 
            'USD Buy' AS category, USD_BUY AS value
        FROM currency_exchange
        WHERE DATE_TIME BETWEEN :dateFrom AND :dateTo

        UNION ALL

        SELECT 
            DATE_TIME AS date, 
            'USD Sell' AS category, USD_SELL AS value
        FROM currency_exchange
        WHERE DATE_TIME BETWEEN :dateFrom AND :dateTo
    ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':dateFrom' => $dateFrom,
            ':dateTo' => $dateTo,
        ]);

        // Fetch the data as associative array
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Initialize an array to store formatted data
        $formattedData = [];

        // Process the data into the desired format
        foreach ($data as $row) {
            $formattedData[] = [
                'category' => $row['category'],
                'value' => (float) $row['value'],  // Ensure value is a float
                'date' => $row['date'],  // Include the date
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
