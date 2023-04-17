<?php

require "db.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];


    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    $sql = "DELETE FROM news WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
    $connection->close();
} else {
    echo 'error';
}
