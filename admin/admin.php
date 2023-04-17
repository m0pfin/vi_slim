<?php

session_start();

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: login.php");
    exit();
}
// Подключение к базе данных
require "../config/db.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Админ-панель</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Add Quill's stylesheet -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.core.css">
    <style>
        #quill-editor {
            height: 150px; /* 5 строк * 20 пикселей на строку */
        }
    </style>

    <!-- Add Quill's JavaScript -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>



</head>
<body>
<nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <a class="navbar-brand" href="#">Админ-панель</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">Витрина новостей</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="statistics.php">Статистика</a>
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


<div class="container">
    <div class="row my-3">
        <div class="col-6">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            Добавить новость
        </button>
        </div>
    </div>
</div>
<div class="container">

    <table id="myTable" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>IMG</th>
            <th>Title</th>
            <th>Views</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

</div>



<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Добавить запись</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="add-form" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="add-title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="add-title" name="title">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <div id="quill-editor" ></div>
                        <input type="hidden" id="quill-data" name="description">
                    </div>
                    <div class="mb-3">
                        <label for="add-image" class="form-label">Миниатюра</label>
                        <input type="file" class="form-control" id="add-image" name="image"  accept="image/jpeg, image/png, image/gif">
                        <img src="" alt="Миниатюра" id="add-image-preview" width="200" style="display: none;">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="add-submit">Добавить запись</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="../js/script.js"></script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
        // Проверяем, есть ли информация об успешном редактировании в sessionStorage
        if (sessionStorage.getItem('editSuccess')) {
            // Выводим уведомление об успешном редактировании
            toastr.success('Запись успешно отредактирована');
            // Удаляем информацию об успешном редактировании из sessionStorage
            sessionStorage.removeItem('editSuccess');
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const quill = new Quill('#quill-editor', {
            // Ваши настройки Quill
            modules: {
                toolbar: true    // Snow includes toolbar by default
            },
            theme: 'snow'
        });

        document.getElementById('add-submit').addEventListener('click', async () => {
            console.log('Button clicked');

            // Получаем данные из Quill и устанавливаем значение скрытого поля
            const quillData = quill.root.innerHTML;
            document.getElementById('quill-data').value = quillData;

            // Отправляем форму с обновленным значением поля 'description'
            const formData = new FormData(document.getElementById('add-form'));
            const response = await fetch('../config/insert_data.php', {
                method: 'POST',
                body: formData,
            });

            const data = await response.json();

            if (data.status === 'success') {
                // Выводим уведомление об успехе
                toastr.success('Запись успешно добавлена');
                // Закрываем модальное окно и обновляем таблицу
                $('#addModal').modal('hide');
                // Если вы используете DataTables, вызовите функцию для обновления таблицы
                window.table.ajax.reload();
            } else {
                // Выводим уведомление об ошибке
                toastr.error('Ошибка при добавлении записи');
            }
        });
    });

</script>
</body>
</html>
