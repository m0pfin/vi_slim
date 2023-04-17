<?php
session_start();


$start_time = microtime();  //получаем сверхточную временную метку в виде милисекунды секунды  начало скрипта
$start = explode(' ', $start_time);//разбиваем полученную строку на массив

require "config/db.php";
require "config/function.php";

// Получаем ГЕО
$user_country = getUserCountry();

?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title><?php

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM news WHERE id = $id";
            $result = mysqli_query($connection, $sql);
            $row = mysqli_fetch_assoc($result);
            if ($row) {
                echo $row["title"];
            }else{
                echo "СРОЧНАЯ НОВОСТЬ";
            }
        }
        ?></title>
    <meta name="keywords" content="новости,шоу-бизнес,общество,культура,экономика,политика,звёзды,скандалы" />
    <meta name="description" content="Нечестивые сотрудники ДПС стали вип таксистами" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link rel="stylesheet" href="css/mr_crabs.css" media="screen">
    <script src="js/jquery.min.js"></script>
    <script src="js/crabs_main.js"></script>



    <script src="https://cdn.jsdelivr.net/npm/lozad@1.14.0/dist/lozad.min.js"></script>

</head>

<body id="main">
<header class="">
    <div class="center-wrapper">
        <div class="content clearfix">
            <div class="logotype">
                <a href="index.php" class="logotype logo_link">СРОЧНЫЕ НОВОСТИ</a>
            </div>
            <div id="mobile-menu-toggle" class=" show-on-728"></div>
        </div>
    </div>
</header>
<main>
    <div class="center-wrapper">
        <div class="content" id="jsContentWrapper">
            <div class="container" id="app">
                <!-- Контент -->

                <?php

                if (isset($_GET['id'])) {
                $id = (int)$_GET['id'];

                if (isset($_GET['id']) && ($id = (int)$_GET['id']) > 0 && !in_array($id, getViewedNews())) {
                    $sql1 = "UPDATE news SET views = views + 1 WHERE id = ?";
                    $stmt1 = $connection->prepare($sql1);
                    $stmt1->bind_param("i", $id);
                    $stmt1->execute();
                    $stmt1->close();

                    $sql2 = "INSERT INTO statistics (date, news_views) VALUES (NOW(), 1) ON DUPLICATE KEY UPDATE news_views = news_views + 1";
                    mysqli_query($connection, $sql2);
                    updateViewedNews($id);
                }

                $sql = "SELECT * FROM news WHERE id = $id";
                $result = mysqli_query($connection, $sql);
                $row = mysqli_fetch_assoc($result);
                if ($row) {

                ?>

                <div class="row bottom_line page_step step_1" id="fullPage">
                    <section class="clearfix full-news-section">
                        <article class="clearfix">
                            <div class="column wide">
                                <div class="image main-news">
                                    <img src="images/<?php  echo $row["image"]; ?>" class="lk-short-image">
                                    <div class="title"><?php  echo $row["title"]; ?></div>
                                </div>
                                <div class="article-content">
                                    <div class="status-line">
                                        <span class="date">Опубликовано: <?php  echo $row["date"]; ?></span>|&nbsp;
                                        <img src="css/icon-font-eye.png"><div class="views"><?php  $views = rand(10309, 89030); echo $views; ?></div>
                                    </div>
                                    <a id="fullNewsStart"></a>
                                    <div class="text" id="js-full-article-text-block">

                                        <p><p><?php  echo $row["description"]; ?></p></p>
                                        <div id="jsAdBlockInText1" class="container-custom-bl0ck-0b1">
                                            <?php
//
                    function generateSqlQuery($user_country) {
                        $sql_base = "SELECT * FROM offers";
                        $sql_order_limit = "ORDER BY RAND() LIMIT 1";

                        if ($user_country) {
                            $sql_geo = "WHERE FIND_IN_SET('$user_country', offer_geo) > 0";
                            return "$sql_base $sql_geo $sql_order_limit";
                        }

                        return "$sql_base $sql_order_limit";
                    }

                    function displayOffer($row, $connection) {
                        incrementOfferViews($row['id'], $connection);
                        $offer_link = replace_macros($row['offer_link'], $_GET['id'],  $row['id']);

                        echo <<<HTML
                            <div class="offer" data-offer-id="{$row['id']}">
                                <a href="$offer_link" class="offer-link custom-bl0ck-0b1 js-ad-top-position js-ad-block utm_links_t click_link unread crabs_remain"  target="_blank" rel="nofollow">
                                    <div class="img-bl0ck-0b1"><img src="images/offers/{$row['offer_image']}" data-src="images/offers/{$row['offer_image']}"></div>
                                    <div class="text-bl0ck-0b1">
                                        <div class="title-bl0ck-0b1">{$row['offer_name']}</div>
                                    </div>
                                    </a>
</div>
HTML;
                                     }
                                            $sql = generateSqlQuery($user_country);
                                            $result = mysqli_query($connection, $sql);

                                            while ($row = mysqli_fetch_assoc($result)) {
                                                displayOffer($row, $connection);
                                            }
                                            ?>

                                        </div>
                                        <?php
                                        } else {
                                            echo '<h2>Новость не найдена</h2>';
                                        }
                                        } else {
                                            echo '<h2>Новость не найдена</h2>';
                                        }

                                        ?>

                                    </div>
                                    <div class='onesignal-customlink-container'></div>

                                </div>
                                <div class="lines read-also mob_hide_block">
                                    <div class="lines-title">
                                        ЧИТАЙТЕ ТАКЖЕ:
                                    </div>
                                    <div class="line clearfix" id="content_els">

                                        <?php
                                        // Получаем 3 случайные новости из базы данных
                                        function displayNews($row) {
                                            $news_link = "view.php?id={$row['id']}";
                                            $image_src = "images/{$row['image']}";

                                            echo <<<HTML
<a href="$news_link" class="new-micro-preview js-ad-top-position js-ad-block utm_links_t click_link unread crabs_remain" target="_blank">
    <div class="image"><img class="lazyload" src="$image_src" data-src="$image_src"></div>
    <div class="title">
        <div class="headline">{$row['title']}</div>
    </div>
</a>
HTML;
                                        }

                                        $sql = "SELECT * FROM news ORDER BY RAND() LIMIT 3";
                                        $result = mysqli_query($connection, $sql);

                                        while ($row = mysqli_fetch_assoc($result)) {
                                            displayNews($row);
                                        }

                                        ?>

                                    </div>
                                </div>

                        </article>
                        <aside class="sticky-top-on-scroll">
                            <div class="js-block-relatively-floating">

                                <?php
                                // Получаем офферы

                                $sql = "SELECT *, (offer_clicks / GREATEST(offer_impressions, 1)) * 100 AS ctr FROM offers WHERE FIND_IN_SET('$user_country', offer_geo) > 0 ORDER BY (offer_clicks / GREATEST(offer_impressions, 1)) * 100 DESC, RAND() LIMIT 6";
                                $result = mysqli_query($connection, $sql);

                                $offers = [];
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $offers[] = $row;
                                }

                                for ($i = 0; $i < count($offers); $i += 2) {
                                    echo '<section class="clearfix">';
                                    for ($j = $i; $j < $i + 2; $j++) {
                                        if (isset($offers[$j])) {
                                            $offer = $offers[$j];
                                            //считаем просмотры оффера
                                            incrementOfferViews($offer['id'], $connection);

                                            echo '<div class="column standard">';
                                            echo  '<div class="offer" data-offer-id="'   .   $offer['id']    .   '">';
                                            echo '<a href="' . replace_macros($offer['offer_link'], $_GET['id'],  $offer['id']) . '" class="offer-link mid-preview js-ad-top-position js-ad-block utm_links_t click_link unread sec_wrap crabs_remain"  target="_blank" rel="nofollow">';
                                            echo '<div class="image"><img  data-src="images/offers/' . $offer["offer_image"] . '" class="lozad"></div>'; // ТУТ
                                            echo '<div class="title">';
                                            echo '<div class="headline">' . $offer["offer_name"] . '</div>';
                                            echo '</div>';
                                            echo '</a>';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    }
                                    echo '</section>';
                                }

                                $end_time = microtime(); //получаем сверхточную временную метку в виде милисекунды секунды  начало скрипта конец скрипта
                                $end = explode(' ', $end_time);//разбиваем полученную строку на массив
                                $rez = $end[1] - $start[1] + $end[0] - $start[0];  //вычитаем разница и есть время выполнения
                                echo $rez;//вывод результата
                                ?>


                                <div class="clearfix"></div>
                            </div>
                        </aside>
                    </section>

                    <!--noindex-->
                    <div id="br_autoload">
                    </div>
                    <!--/noindex-->
                    <div id="loader" style="display: none;">
                        <div class="spinner-border text-dark" role="status">
                            <span class="sr-only" style="color: black;">Загрузка...</span>
                        </div>
                    </div>


                </div>

                <script>
                    $(document).ready(function () {
                        var inProgress = false;
                        $(window).scroll(function () {
                            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 300 && !inProgress) {
                                var userIP = '<?php echo getUserIp(); ?>';
                                var newsID = '<?php echo $_GET['id']; ?>';
                                $.ajax({
                                    url: 'config/offers_load.php',
                                    method: 'POST',
                                    data: {'ip': userIP},
                                    data: {'nid': newsID},
                                    beforeSend: function () {
                                        inProgress = true;
                                        $("#loader").show(); // Показать лоадер
                                    }
                                }).done(function (data) {
                                    $("#br_autoload").append(data);
                                    inProgress = false;
                                    $("#loader").hide(); // Скрыть лоадер после загрузки данных
                                });
                            }
                        });
                    });
                </script>

                <!-- /Контент -->
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="modal_crabs_news" tabindex="-1" role="dialog" aria-labelledby="modal_crabs_newsa" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_crabs_newsa">Срочная новость!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <a href="/oclick/74x2" target="_blank" class="btn btn-primary crabs_remain">Читать новость</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const observer = lozad('.lozad');
        observer.observe();
    });
</script>
<script src="js/buildall.js"></script>


<footer style="display:none">
    <div class="center-wrapper">
        <div class="content clearfix">
            <div class="column">
                <a href="/" class="logotype logo_link">СРОЧНЫЕ НОВОСТИ</a>
                <div class="copyright">
                    Copyright © 2021 «СРОЧНЫЕ НОВОСТИ». Все права защищены.<br />Для детей старше 16 лет.
                </div>
            </div>
            <div class="column clearfix"></div>
        </div>
    </div>
</footer>
<?php
mysqli_close($connection);
?>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const offerLinks = document.querySelectorAll('.offer-link');

        offerLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const offerId = event.target.closest('.offer').dataset.offerId;

                // Увеличиваем счетчик кликов на оффер
                fetch('config/update_clicks.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'offer_id=' + encodeURIComponent(offerId)
                }).then(response => {
                    if (response.ok) {
                        // Если успешно обновлено, открываем ссылку оффера
                        window.open(event.target.closest('.offer-link').href, '_blank');
                    } else {
                        console.error('Ошибка при обновлении счетчика кликов оффера');
                    }
                });
            });
        });
    });
</script>
<script src="js/bootstrap.min.js"></script>
<script src="js/crabs_best.js"></script>

</body>
</html>