<?php
// Настройте доступ к базе данных
require "db.php";

// Получить данные из запроса
$id = $_POST['offer_id'];
$offer_name = $_POST['offer_name'];
$offer_link = $_POST['offer_link'];
$offer_geo = implode(',', $_POST['offer_geo']);

// Обработка загрузки изображения
$uploaded_image = null;
if (!empty($_FILES['offer_image']['tmp_name'])) {
    $file_path = '../images/offers/';
    $file_name = time() . '_' . basename($_FILES['offer_image']['name']);
    $uploaded_image = $file_path . $file_name;

    if (!move_uploaded_file($_FILES['offer_image']['tmp_name'], $uploaded_image)) {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка загрузки изображения']);
        exit;
    }
}

// Создать SQL-запрос для обновления оффера
if ($uploaded_image) {
    $sql = "UPDATE offers SET offer_name = ?, offer_link = ?, offer_geo = ?, offer_image = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('ssssi', $offer_name, $offer_link, $offer_geo, $file_name, $id);
} else {
    $sql = "UPDATE offers SET offer_name = ?, offer_link = ?, offer_geo = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('sssi', $offer_name, $offer_link, $offer_geo, $id);
}

// Выполнить запрос
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Оффер успешно обновлен']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка обновления оффера: ' . $connection->error]);
}

// Закрыть соединение
$stmt->close();
$connection->close();
?>
