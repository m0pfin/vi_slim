<?php
// Подключение к базе данных
require 'db.php';
require "function.php";

// Получаем ГЕО
// Получение IP-адреса пользователя из POST-данных
$user_ip = isset($_POST['ip']) ? $_POST['ip'] : '';
$nid = isset($_POST['nid']) ? $_POST['nid'] : '';

// Получение кода страны пользователя
$country_code = getUserCountry($user_ip);

if ($country_code) {
    $sql = "SELECT * FROM offers WHERE FIND_IN_SET('$country_code', offer_geo) > 0 ORDER BY RAND() LIMIT 8";
} else {
    // Если страна пользователя не определена, выберите случайный оффер без учета гео-таргетинга
    $sql = "SELECT * FROM offers ORDER BY RAND() LIMIT 8";
}

// Запрос для выбора офферов из базы данных
$result = $connection->query($sql);

if ($result->num_rows > 0) {

    $count = 0;
    while ($row = $result->fetch_assoc()) {
        $offer_id = $row['id'];
        $offer_title = $row['offer_name'];
        $offer_image = $row['offer_image'];

        if ($count % 4 == 0) {
            if ($count > 0) {
                echo '</section>';
            }
            echo '<section class="clearfix">';
        }
        echo '<div class="column standard">';
        echo '<a href="' . replace_macros($row['offer_link'], $nid,  $row['id'])  . '" class="mid-preview js-ad-block click_link unread sec_wrap go_base crabs_remain" target="_blank" rel="nofollow">';
        echo '<div class="image"><img src="images/offers/' . $offer_image . '" class="lozad"></div>';
        echo '<div class="title">' . $offer_title . '</div>';
        echo '</a>';
        echo '</div>';

        $count++;
    }
    echo '</section>';
} else {
    echo "Данных больше нет...";
}

$connection->close();
?>
