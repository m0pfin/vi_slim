<?php
header('Content-Type: application/json');

require "db.php";

// Получение ID оффера из параметров запроса
$offer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($offer_id > 0) {
    // Удаление оффера из базы данных
    $sql = "DELETE FROM offers WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $offer_id);
    $result = $stmt->execute();

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Оффер успешно удален']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка удаления оффера: ' . $connection->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Неверный ID оффера']);
}

// Закрытие подключения
$connection->close();
?>
