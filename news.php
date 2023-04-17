<!DOCTYPE html>
<html>
<head>
    <title>Новости</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/crabs_main.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <a class="navbar-brand" href="#">Новости</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="admin/admin.php">Добавить новость</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <?php
        // Подключаемся к базе данных
        require "config/db.php";

        // Получаем новости из базы данных
        $sql = "SELECT * FROM news ORDER BY id DESC";
        $result = mysqli_query($connection, $sql);

        // Выводим новости на страницу
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="col-md-4">';
            echo '<div class="card">';
            echo '<div class="bg-image" style="max-width: 22rem;">';
            echo '<img class="w-100" src="images/' . $row["image"] . '" alt="Card image" >';
            echo '<div class="position-absolute bottom-0 text-light" style="background-color: rgba(0, 0, 0, 0.5)"><p class="p-2 m-0">Lorem ipsum dolor sit amet.</p><div class="position-absolute bottom-0 text-light w-100" style="background-color: rgba(0, 0, 0, 0.5)"><p class="p-2 m-0">' . $row["title"] . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';


        }

        // Закрываем соединение с базой данных
        mysqli_close($connection);
        ?>
    </div>
</div>
<script src="js/bootstrap.min.js"></script>
<script src="js/crabs_best.js"></script>
</body>
</html>
