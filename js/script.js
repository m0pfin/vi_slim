$(document).ready(function () {
    window.table = $('#myTable').DataTable({
        'ajax': '../config/fetch_data.php',
        'columns': [
            { 'data': 'id', 'type': 'num' },
            {
                'data': 'image',
                'render': function(data, type, row) {
                    return '<img src="../images/' + data + '" alt="Миниатюра" width="50" height="50">';
                }
            },
            { 'data': 'title' },
            { 'data': 'views' },
            {
                'data': null,
                'render': function(data, type, row) {
                    return '<button class="btn btn-primary btn-sm" onclick="window.location.href=\'../config/edit.php?id=' + row.id + '\'"><i class="fi fi-rr-edit-alt"></i></button> <button class="delete btn btn-danger btn-sm remove-btn"><i class="fi fi-rr-trash"></i></button>';
                }
            }
        ],
        'responsive': true,
        'pagingType': 'simple_numbers',
        'order': [[0, 'desc']],
        createdRow: function (row, data) {
            $(row).attr('data-id', data.id);
        }
    });

    $('#myTable tbody').on('click', '.delete', function () {
        const data = table.row($(this).parents('tr')).data();
        const id = data.id;
        if (confirm('Вы действительно хотите удалить запись с ID ' + id + '?')) {
            $.ajax({
                url: '../config/delete_data.php',
                type: 'POST',
                data: { 'id': id },
                success: function (response) {
                    if (response === 'success') {
                        toastr.success('Запись успешно удалена');
                        table.ajax.reload();
                    } else {
                        toastr.error('Ошибка при удалении записи');
                    }
                }
            });
        }
    });

    // document.getElementById('add-submit').addEventListener('click', async () => {
    //     const addForm = document.getElementById('add-form');
    //     const formData = new FormData(addForm);
    //     const response = await fetch('../config/insert_data.php', {
    //         method: 'POST',
    //         body: formData
    //     });
    //
    //     if (response.ok) {
    //         const result = await response.json();
    //         if (result.status === 'success') {
    //
    //             toastr.success('Запись успешно добавлена');
    //             // Закрываем модальное окно
    //             const modalElement = document.getElementById('addModal');
    //             const modalInstance = bootstrap.Modal.getInstance(modalElement);
    //             modalInstance.hide();
    //
    //             // Очищаем форму
    //             addForm.reset();
    //
    //             // Обновляем таблицу с новыми данными
    //             table.ajax.reload(null, false);
    //         } else {
    //             // Вывод ошибки, если что-то пошло не так
    //             // Выводим уведомление об ошибке
    //             toastr.error('Ошибка при добавлении записи');
    //         }
    //     } else {
    //
    //         alert('Ошибка при отправке данных на сервер');
    //     }
    // });

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

});


// Функция для получения UTM-меток из URL
function getUTMParameters() {
    const urlParams = new URLSearchParams(window.location.search);
    const utmParams = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
    let utmData = {};

    utmParams.forEach(param => {
        if (urlParams.has(param)) {
            utmData[param] = urlParams.get(param);
        }
    });

    return utmData;
}

// Функция для сохранения UTM-меток в локальное хранилище
function saveUTMParametersToLocalStorage(utmData) {
    localStorage.setItem('utmData', JSON.stringify(utmData));
}

// Функция для получения сохраненных UTM-меток из локального хранилища
function getSavedUTMParameters() {
    const utmData = localStorage.getItem('utmData');
    return utmData ? JSON.parse(utmData) : {};
}

// Функция для добавления UTM-меток к внешней ссылке
function appendUTMParametersToLink(link, utmData) {
    const url = new URL(link);
    for (const [key, value] of Object.entries(utmData)) {
        url.searchParams.set(key, value);
    }
    return url.toString();
}

// Сохранение UTM-меток при первом посещении сайта
if (!localStorage.getItem('utmData')) {
    const utmData = getUTMParameters();
    saveUTMParametersToLocalStorage(utmData);
}

// Получение сохраненных UTM-меток
const savedUTMData = getSavedUTMParameters();

// Добавление UTM-меток к внешним ссылкам
document.querySelectorAll('a.external-link').forEach(link => {
    link.href = appendUTMParametersToLink(link.href, savedUTMData);
});
