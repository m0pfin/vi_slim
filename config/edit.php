<?php

session_start();

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: ../admin/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование записи</title>
    <!-- Подключение Bootstrap 5 и других стилей -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"  crossorigin="anonymous"></script>

    <!-- Add Quill's stylesheet -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.core.css">

    <!-- Add Quill's JavaScript -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <style>
        #quill-editor {
            height: 150px; /* 5 строк * 20 пикселей на строку */
        }
    </style>

</head>
<nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <a class="navbar-brand" href="#">Редактировать новость</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="../admin/admin.php">Админ-панель</a>
            </li>
        </ul>
    </div>
</nav>
<body>
<div class="container">
    <form id="edit-form" enctype="multipart/form-data">
        <input type="hidden" id="edit-id" name="id">
        <div class="mb-3">
            <label for="edit-name" class="form-label">Title</label>
            <input type="text" class="form-control" id="edit-title" name="title">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <div id="quill-editor"></div>
            <input type="hidden" id="quill-data" name="description">
        </div>

        <div class="mb-3">
            <label for="edit-thumbnail" class="form-label">Миниатюра</label>
            <input type="file" class="form-control" id="edit-image" name="image"  accept="image/jpeg, image/png, image/gif">
            <img src="" alt="Миниатюра" id="edit-image-preview" width="200" style="display: none;">
        </div>
        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        <a href="../admin/admin.php" class="btn btn-secondary">Отмена</a>
    </form>
</div>

<!-- Подключение JS-файлов -->
<script type="text/javascript">
    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        const results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }


    document.addEventListener('DOMContentLoaded', () => {
        const quill = new Quill('#quill-editor', {
            modules: {
                toolbar: true
            },
            theme: 'snow'
        });

        // Загружаем данные из скрытого поля в редактор Quill
        const id = getUrlParameter('id');
        loadData(id, quill);

        // Обновляем скрытое поле данными из Quill перед отправкой формы
        document.getElementById('edit-form').addEventListener('submit', (event) => {
            event.preventDefault();
            const quillData = quill.root.innerHTML;
            document.getElementById('quill-data').value = quillData;

            // Здесь ваш код для отправки формы
        });
    });

    async function loadData(id, quill) {
        const response = await fetch('get_data.php?id=' + id);

        if (response.ok) {
            const data = await response.json();
            document.getElementById('edit-id').value = data.id;
            document.getElementById('edit-title').value = data.title;

            // Установка данных для Quill-редактора
            quill.setContents(quill.clipboard.convert(data.description));

            document.getElementById('edit-image-preview').src = '../images/' + data.image;
            document.getElementById('edit-image-preview').style.display = 'block';
        } else {
            alert('Ошибка при загрузке данных');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const id = getUrlParameter('id');
        loadData(id);

        const editForm = document.getElementById('edit-form');
        if (editForm) {
            editForm.addEventListener('submit', async (event) => {
                event.preventDefault();

                // Здесь ваш код для обработки формы и отправки данных на сервер
                const formData = new FormData(editForm);
                const response = await fetch('update_data.php', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.status === 'success') {
                        // Перенаправление на страницу администрирования после успешного редактирования
                        sessionStorage.setItem('editSuccess', 'true');
                        window.location.href = '../admin/admin.php';
                    } else {
                        // Вывод ошибки, если что-то пошло не так
                        alert(result.message);
                    }
                } else {
                    alert('Ошибка при отправке данных на сервер');
                }
            });
        }
    });
</script>
</body>
</html>

