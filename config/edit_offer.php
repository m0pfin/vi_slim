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
    <title>Редактирование оффера</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"  crossorigin="anonymous"></script>
</head>
<body>
<nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <a class="navbar-brand" href="#">Редактировать оффер</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="../admin/admin.php">Админ-панель</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../admin/offers.php">Офферы</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
            require "db.php";

            // Получение ID оффера из параметров запроса
            $offer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

            if ($offer_id > 0) {
                // Получение информации об оффере из базы данных
                $sql = "SELECT * FROM offers WHERE id = ?";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param("i", $offer_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $offer = $result->fetch_assoc();
                } else {
                    echo '<p>Оффер не найден</p>';
                    exit;
                }
            } else {
                echo '<p>Неверный ID оффера</p>';
                exit;
            }
            ?>

            <form id="edit-offer-form" enctype="multipart/form-data">
                <input type="hidden" name="offer_id" value="<?= $offer['id'] ?>">
                <div class="mb-3">
                    <label for="offer-name" class="form-label">Название оффера</label>
                    <input type="text" class="form-control" id="offer-name" name="offer_name" value="<?= $offer['offer_name'] ?>" required>
                </div>

                <div class="alert alert-warning" role="alert">
                    Вы можете добавить макрос с ID - новости и оффера для отслеживания у себя в трекере <br><br>
                    <b>{nid}</b> - ID новости <br>
                    <b>{oid}</b> - ID оффера <br><br>

                    <b>Пример:</b> https://site.ru/?n_id={nid}&o_id={oid}

                </div>
                <div class="mb-3">
                    <label for="offer-link" class="form-label">Ссылка на оффер</label>
                    <input type="text" class="form-control" id="offer-link" name="offer_link" value="<?= $offer['offer_link'] ?>" required>
                </div>
                <!-- Вставьте сюда код для полей Гео и изображ -->
                <div class="mb-3">
                    <label for="offer-geo" class="form-label">Гео-таргетинг</label>
                    <select class="form-select" id="offer-geo" name="offer_geo[]" multiple required>
                        <?php
                        $geo_options = [
                            'RU' => 'Россия',
                            'UA' => 'Украина',
                            'BY' => 'Беларусь',
                            'KZ' => 'Казахстан',
                            'US' => 'США',
                            'CA' => 'Канада',
                            'GB' => 'Великобритания',
                            'DE' => 'Германия',
                            'FR' => 'Франция',
                            'IT' => 'Италия'
                        ];
                        $selected_geo = explode(',', $offer['offer_geo']);
                        foreach ($geo_options as $code => $name) {
                            $selected = in_array($code, $selected_geo) ? 'selected' : '';
                            echo "<option value='{$code}' {$selected}>{$name}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <img src="../images/offers/<?= $offer['offer_image'] ?>" alt="Миниатюра" id="edit-image-preview" width="200">
                    <label for="offer-image" class="form-label">Изображение оффера (оставьте поле пустым, чтобы сохранить текущее изображение)</label>
                    <input type="file" class="form-control" id="offer-image" name="offer_image"  accept="image/jpeg, image/png, image/gif">
                </div>


                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('edit-offer-form').addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(event.target);
        const response = await fetch('../config/update_offer.php', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            const result = await response.json();
            if (result.status === 'success') {
                window.location.href = '../admin/offers.php';
            } else {
                alert(result.message);
            }
        } else {
            alert('Ошибка при отправке данных на сервер');
        }
    });

    document.getElementById('edit-offer-form').addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(event.target);
        const response = await fetch('../config/update_offer.php', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            const result = await response.json();
            if (result.status === 'success') {
                sessionStorage.setItem('editSuccess', 'true');
                window.location.href = '../admin/offers.php';
            } else {
                alert(result.message);
            }
        } else {
            alert('Ошибка при отправке данных на сервер');
        }
    });
</script>
</body>
</html>
</body>
</html>
