<?php
$connection = mysqli_connect('localhost', 'root', 'root', 'vi_slim');

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>