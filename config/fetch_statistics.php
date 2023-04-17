<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require "db.php";

// Получение дат из запроса
$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : '';
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : '';

// Запрос к базе данных
$sql = "SELECT DATE(date) as date, SUM(news_views) as news_views, (SUM(offer_clicks) / SUM(news_views)) * 100 as news_ctr, SUM(offer_impressions) as offer_impressions, SUM(offer_clicks) as offer_clicks, (SUM(offer_clicks) / SUM(offer_impressions)) * 100 as offer_ctr FROM statistics WHERE date >= ? AND date <= ? GROUP BY DATE(date) ORDER BY date";
//$sql = "SELECT DATE(date) as date, SUM(news_views) as news_views, (SUM(offer_clicks) / SUM(news_views)) * 100 as news_ctr, SUM(offer_impressions) as offer_impressions, SUM(offer_clicks) as offer_clicks, (SUM(offer_clicks) / SUM(offer_impressions)) * 100 as offer_ctr FROM statistics WHERE date >= ? AND date <= ? GROUP BY DATE(date) ORDER BY date";

//$sql = "SELECT DATE(date) as date, SUM(news_views) as news_views, (SUM(offer_clicks) / SUM(news_views)) * 100 as news_ctr, SUM(offer_impressions) as offer_impressions, SUM(offer_clicks) as offer_clicks, (SUM(offer_clicks) / SUM(offer_impressions)) * 100 as offer_ctr FROM statistics WHERE date >= '$startDate' AND date <= '$endDate' GROUP BY DATE(date) ORDER BY date";
//
//$result = mysqli_query($connection, $sql);

        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ss', $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        // Формирование массива с данными
        $data = array();
        while ($row = $result->fetch_assoc()) {
        // Округление значений CTR до двух знаков после запятой
            if ($row['news_ctr'] !== null) {
                $row['news_ctr'] = round($row['news_ctr'], 2);
            }
            if ($row['offer_ctr'] !== null) {
                $row['offer_ctr'] = round($row['offer_ctr'], 2);
            }else{
                $row['offer_ctr'] = 0;
            }
            $data[] = $row;
        }

// Отправка данных в формате JSON
header('Content-Type: application/json');
echo json_encode($data);
?>