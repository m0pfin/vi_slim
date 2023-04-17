<?php
// Подключение к базе данных
require "db.php";

// Получение данных из формы
$offer_name = htmlspecialchars($_POST['offer_name']);
$offer_link = htmlspecialchars($_POST['offer_link']);
$offer_geo = implode(',', array_map('htmlspecialchars', $_POST['offer_geo']));

$offer_name = mysqli_real_escape_string($connection, $offer_name);
$offer_link = mysqli_real_escape_string($connection, $offer_link);
$offer_geo = mysqli_real_escape_string($connection, $offer_geo);

// Загрузка изображения
$image_path = '';
$uploadOk = 1;

if ($_FILES['offer_image']['error'] == UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['offer_image']['tmp_name'];
    $filename = basename($_FILES['offer_image']['name']);
    $upload_dir = '../images/offers/';
    $file_path = $upload_dir . $filename;

    // Проверка, является ли файл изображением
    $check = getimagesize($tmp_name);
    if ($check !== false) {
        // Допустимые форматы файлов
        $allowed_formats = ['jpg', 'jpeg', 'png', 'gif'];
        $imageFileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($imageFileType, $allowed_formats)) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
            $response = [
                'status' => 'error',
                'message' => 'Недопустимый формат файла. Разрешены только JPG, JPEG, PNG и GIF.'
            ];
        }
    } else {
        $uploadOk = 0;
        $response = [
            'status' => 'error',
            'message' => 'Файл не является изображением.'
        ];
    }

    if ($uploadOk == 1 && move_uploaded_file($tmp_name, $file_path)) {
        $image_path = $file_path;
    } else {
        // Ошибка при перемещении файла
    }
} else {
    // Ошибка при загрузке файла
}

// Добавление записи в базу данных
if ($uploadOk == 1) {
    $stmt = $connection->prepare("INSERT INTO offers (offer_name, offer_link, offer_geo, offer_image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $offer_name, $offer_link, $offer_geo, $filename);

    if ($stmt->execute()) {
        $response = ['status' => 'success'];
    } else {
        $response = ['status' => 'error', 'message' => 'Ошибка при добавлении оффера в базу данных'];
    }

    $stmt->close();
}

$connection->close();

// Возвращаем JSON-ответ
header('Content-Type: application/json');
echo json_encode($response);
?>
