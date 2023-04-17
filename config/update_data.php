<?php

require "db.php";

// Получение данных из формы
$id = $_POST['id'];
$name = $_POST['title'];
$email = $_POST['description'];
$thumbnail = null;

// Загрузка изображения
if (isset($_FILES['image']) && $_FILES['image']['tmp_name'] != '') {
    $target_dir = "../images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Проверка, является ли файл изображением
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    // Загрузка файла
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $thumbnail = $target_file;
        }
    }
}

// Обновление записи в базе данных
if ($thumbnail) {
    $sql = "UPDATE news SET title = ?, description = ?, image = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssi", $name, $email, $thumbnail, $id);
} else {
    $sql = "UPDATE news SET title = ?, description = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssi", $name, $email, $id);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}

$stmt->close();
$connection->close();
