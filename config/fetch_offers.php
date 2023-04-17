<?php
// Укажите здесь данные для подключения к вашей базе данных
require "db.php";

// Выбираем все офферы из таблицы
$sql = "SELECT *, (SUM(offer_clicks) / SUM(offer_impressions)) * 100 as ctr FROM offers GROUP BY id";
$result = $connection->query($sql);

// Заполняем массив данными из таблицы
$offers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['ctr'] !== null) {
            $row['ctr'] = round($row['ctr'], 2);
        }else{
            $row['offer_ctr'] = 0;
        }
        $offers[] = $row;
    }
}

// Закрываем соединение с базой данных
$connection->close();

// Возвращаем данные в формате JSON
header('Content-Type: application/json');
echo json_encode(['data' => $offers]);
?>
