<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: login.php");
    exit();
}
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
</head>

<body>
<nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <a class="navbar-brand" href="#">Офферы</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
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
                <a class="nav-link" href="admin.php">Новости</a>
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
                Добавить оффер
            </button>
        </div>
    </div>
</div>

<div class="container mt-4">
    <h1>Список офферов</h1>
    <table id="offers-table" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
            <th>ID</th>
            <th>IMG</th>
            <th>Название оффера</th>
            <th>Ссылка на оффер</th>
            <th>Гео оффера</th>
            <th>CTR</th>
            <th>Показы</th>
            <th>Клики</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Модальное окно добавления оффера -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addOfferModalLabel" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOfferModalLabel">Добавить оффер</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="add-offer-form" enctype="multipart/form-data">


                    <div class="mb-3">
                        <label for="offer-name" class="form-label">Название оффера</label>
                        <input type="text" class="form-control" id="offer-name" name="offer_name" required>
                    </div>

                    <div class="alert alert-warning" role="alert">
                        Вы можете добавить макрос с ID - новости и оффера для отслеживания у себя в трекере <br><br>
                        <b>{nid}</b> - ID новости <br>
                        <b>{oid}</b> - ID оффера <br><br>

                        <b>Пример:</b> https://site.ru/?n_id={nid}&o_id={oid}

                    </div>
                    <div class="mb-3">
                        <label for="offer-link" class="form-label">Ссылка на оффер</label>
                        <input type="text" class="form-control" id="offer-link" name="offer_link" required>
                    </div>
                    <div class="mb-3">
                        <label for="offer-geo" class="form-label">Гео-таргетинг</label>
                        <select class="form-select" id="offer-geo" name="offer_geo[]" multiple required>
                            <option value="RU">Россия</option>
                            <option value="UA">Украина</option>
                            <option value="BY">Беларусь</option>
                            <option value="KZ">Казахстан</option>
                            <option value="US">США</option>
                            <option value="CA">Канада</option>
                            <option value="GB">Великобритания</option>
                            <option value="DE">Германия</option>
                            <option value="FR">Франция</option>
                            <option value="IT">Италия</option>
                            <!-- Добавьте дополнительные страны по мере необходимости -->
                        </select>
                    </div>
                    <!-- Внутри тега form id="add-offer-form" -->
                    <div class="mb-3">
                        <label for="offer-image" class="form-label">Изображение оффера</label>
                        <input type="file" class="form-control" id="offer-image" name="offer_image"  accept="image/jpeg, image/png, image/gif" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Добавить оффер</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#offers-table').DataTable({
            ajax: '../config/fetch_offers.php', // Здесь должен быть URL файла, который будет обрабатывать запрос и возвращать данные офферов
            columns: [
                { data: 'id' },
                {
                    data: 'offer_image',
                    render: function (data, type, row) {
                        return `<img src="../images/offers/${data}" alt="Оффер" width="50" height="50" />`;
                    },
                },
                { data: 'offer_name' },
                { data: 'offer_link' },
                { data: 'offer_geo' },
                { data: 'ctr' },
                { data: 'offer_impressions' },
                { data: 'offer_clicks' },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                        <input type="hidden" id="copy_${data.id}" value="${data.offer_link}">
                        <button class="btn btn-sm btn-secondary" onclick="copyToClipboard('copy_${data.id}')"><i class="fi fi-rr-link"></i></button>
                        <a href="../config/edit_offer.php?id=${data.id}" class="btn btn-sm btn-primary"><i class="fi fi-rr-edit-alt"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="deleteOffer(${data.id})"><i class="fi fi-rr-trash"></i></button>
                    `;
                    },
                },
            ],
            order: [[0, 'desc']] // Сортировка по столбцу ID в обратном порядке
        });
    });


    async function deleteOffer(id) {
        const confirmation = confirm('Вы уверены, что хотите удалить этот оффер?');
        if (confirmation) {
            const response = await fetch(`../config/delete_offer.php?id=${id}`, { method: 'DELETE' });

            if (response.ok) {
                const result = await response.json();
                if (result.status === 'success') {
                    toastr.success('Оффер успешно удалён');
                    // Обновляем таблицу после успешного удаления
                    $('#offers-table').DataTable().ajax.reload();
                } else {
                    // Вывод ошибки, если что-то пошло не так
                    toastr.error('Ошибка при удалении оффера');
                }
            } else {
                toastr.error('Ошибка при отправке данных на сервер');
            }
        }
    }
    document.getElementById('add-offer-form').addEventListener('submit', async (event) => {
        event.preventDefault();

        // Здесь ваш код для обработки формы и отправки данных на сервер
        const formData = new FormData(event.target);
        const response = await fetch('../config/add_offer.php', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            const result = await response.json();
            if (result.status === 'success') {
                toastr.success('Оффер успешно добавлен');
                // Закрыть модальное окно и перезагрузить таблицу после успешного добавления
                $('#addModal').modal('hide'); // Замените эту строку

                // Очистить форму
                event.target.reset();

                // Перезагрузить таблицу
                const offersTable = $("#offers-table").DataTable();
                offersTable.ajax.reload(null, false);
            } else {
                // Вывод ошибки, если что-то пошло не так
                toastr.error('Ошибка при добавлении оффера');
            }
        } else {
            toastr.error('Ошибка при отправке данных на сервер');
        }
    });

    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    document.addEventListener('DOMContentLoaded', () => {
        // Проверяем, есть ли информация об успешном редактировании в sessionStorage
        if (sessionStorage.getItem('editSuccess')) {
            // Выводим уведомление об успешном редактировании
            toastr.success('Оффер успешно отредактирован');
            // Удаляем информацию об успешном редактировании из sessionStorage
            sessionStorage.removeItem('editSuccess');
        }
    });
</script>
<script>
    function copyToClipboard(id) {
        var input = document.getElementById(id);
        var isHidden = input.type === "hidden";

        if (isHidden) {
            input.type = "text";
            input.select();
        }

        document.execCommand("copy");

        if (isHidden) {
            input.type = "hidden";
        }

        alert("Текст скопирован в буфер обмена!");
    }
</script>
</body>
</html>

