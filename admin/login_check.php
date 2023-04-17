<?php
session_start();

// Подключение к базе данных
require "../config/db.php";

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Запрос на проверку логина и пароля
    $stmt = $connection->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Авторизация успешна
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $user['id'];
        header("Location: admin.php");
    } else {
        // Неправильный логин или пароль
        header("Location: login.php?error=1");
    }

    $stmt->close();
}
$connection->close();
?>
