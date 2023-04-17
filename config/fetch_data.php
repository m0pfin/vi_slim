<?php
require "db.php";


if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
}

$sql = "SELECT * FROM news ORDER BY id DESC";
$result = $connection->query($sql);
$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode(['data' => $data]);
$connection->close();