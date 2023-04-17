<?php
session_start();

unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_id']);

header("Location: login.php");
?>
