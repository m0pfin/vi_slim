<?php
require 'db.php';

if (isset($_POST['offer_id'])) {
    $offer_id = (int)$_POST['offer_id'];




    $sql = "INSERT INTO statistics (date, offer_clicks) VALUES (NOW(), 1) ON DUPLICATE KEY UPDATE offer_clicks = offer_clicks + 1";
    $stmt = $connection->prepare($sql);
    $stmt->execute();

    $sql = "UPDATE offers SET offer_clicks = offer_clicks + 1 WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('i', $offer_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        http_response_code(200); // Успешно
    } else {
        http_response_code(500); // Ошибка сервера
    }

    $stmt->close();
} else {
    http_response_code(400); // Ошибка запроса
}

$connection->close();
?>
