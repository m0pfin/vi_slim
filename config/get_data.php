<?php
// Подключение к базе данных
require "db.php";

header('Content-Type: application/json');
// Получение переданного ID записи
$id = $_GET['id'];

// Получение данных записи из базы данных
$sql = "SELECT * FROM news WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Возвращение данных в формате JSON
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    // Возвращение сообщения об ошибке, если запись не найдена
    echo json_encode(["status" => "error", "message" => "Запись не найдена"]);
}

$connection->close();
?>
