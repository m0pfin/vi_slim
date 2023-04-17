<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: login.php");
    exit();
}
// Подключение к базе данных
require_once '../config/db.php';

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель - Офферы</title>
    <!-- Подключение Bootstrap 5 и других стилей -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"  crossorigin="anonymous"></script>

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>


    <!-- jQuery UI для datepicker -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>
<body>
<nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <a class="navbar-brand" href="#">Статистика</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">Витрина новостей</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin.php">Новости</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="offers.php">Офферы</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Выход</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <p>
        <label for="startDate">Начальная дата:</label>
        <input type="text" id="startDate" readonly>
        <label for="endDate">Конечная дата:</label>
        <input type="text" id="endDate" readonly>
    </p>
    <table id="statisticsTable"  class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Дата</th>
            <th>Просмотры новостей</th>
            <th>CTR новостей</th>
            <th>Показы офферов</th>
            <th>Клики по офферам</th>
            <th>CTR офферов</th>
        </tr>
        </thead>
        <tbody>
        <!-- Здесь будут добавляться строки с данными -->
        </tbody>
    </table>
</div>


<script>
    $(document).ready(function() {
        // Инициализация DataTable
        var table = $('#statisticsTable').DataTable({
            "order": [[0, "desc"]] // Сортировка по дате по умолчанию
        });

        // Инициализация datepicker
        $("#startDate").datepicker({
            dateFormat: 'yy-mm-dd',
            onClose: function(selectedDate) {
                $("#endDate").datepicker("option", "minDate", selectedDate);
            }
        });

        $("#endDate").datepicker({
            dateFormat: 'yy-mm-dd',
            onClose: function(selectedDate) {
                $("#startDate").datepicker("option", "maxDate", selectedDate);
            }
        });

        // Функция для обновления таблицы
        function updateTable(startDate, endDate) {
            // Запрос к серверу для получения данных в выбранном диапазоне дат
            $.ajax({
                url: '../config/fetch_statistics.php',
                type: 'POST',
                data: {
                    startDate: startDate,
                    endDate: endDate
                },
                dataType: 'json',
                success: function(data) {
                    // Очистка таблицы
                    table.clear().draw();

                    // Добавление новых строк в таблицу
                    for (var i = 0; i < data.length; i++) {
                        table.row.add([
                            data[i].date,
                            data[i].news_views,
                            data[i].news_ctr,
                            data[i].offer_impressions,
                            data[i].offer_clicks,
                            data[i].offer_ctr
                        ]).draw(false);
                    }
                }
            });
        }

        // Обработчик изменения даты в datepicker
        $("#startDate, #endDate").on("change", function() {
            var startDate = $("#startDate").val();
            var endDate = $("#endDate").val();

            if (startDate && endDate) {
                updateTable(startDate, endDate);
            }
        });
        // Загрузка данных за последние 7 дней при загруз
        function getLastWeek() {
            var today = new Date();
            var lastWeek = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 7);

            return lastWeek;
        }

        $(function() {
            var lastWeek = getLastWeek();
            var today = new Date();

            $("#startDate").datepicker("setDate", lastWeek);
            $("#endDate").datepicker("setDate", today);
            $("#endDate").val($("#endDate").val() + " " + today.toLocaleTimeString());

            updateTable(
                $("#startDate").val(),
                $("#endDate").val()
            );
        });
    });

</script>
    </body>
</html>
