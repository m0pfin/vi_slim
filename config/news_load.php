<?php
// Подключение к базе данных
require 'db.php';
require "function.php";


// Запрос для выбора новостей из базы данных
$sql = "SELECT * FROM news ORDER BY RAND() LIMIT 4";
$result = $connection->query($sql);

if ($result->num_rows > 0) {

    $count = 0;
    while ($row = $result->fetch_assoc()) {
        $news_id = $row['id'];
        $news_title = $row['title'];
        $news_image = $row['image'];

        if ($count % 4 == 0) {
            echo '<section class="clearfix">';
        }
        echo '<div class="column standard lt-1">';
        echo '<a href="view.php?id=' . $news_id  . '" class="mid-preview js-ad-block crabs_remain" target="_blank">';
        echo '<div class="image"><img src="images/' . $news_image . '" data-src="images/' . $news_image . '" class="lazyload"></div>';
        echo '<div class="title">' . $news_title . '</div>';
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
