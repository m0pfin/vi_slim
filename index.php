<!DOCTYPE html><html lang="ru"><head>
    <meta charset="utf-8">
    <title>Новости</title>
    <meta name="keywords" content="новости,шоу-бизнес,общество,культура,экономика,политика,звёзды,скандалы">
    <meta name="description" content="Новости">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/mr_crabs.css" media="screen">
    <script src="js/jquery.min.js"></script>
    <script src="js/crabs_main.js"></script>
    <script src="https://api-maps.yandex.ru/2.0-stable/?load=package.standard&amp;lang=ru-RU" type="text/javascript"></script>
</head>

<body id="main">
<header class>
    <div class="center-wrapper">
        <div class="content clearfix">

            <div class="logotype">
                <a href="/" class="logotype logo_link">СРОЧНЫЕ НОВОСТИ</a>
            </div>
            <nav>
            </nav>

        </div>
    </div>
</header>
<main>
    <div class="center-wrapper">
        <div class="content" id="jsContentWrapper">
            <div class="container" id="app"><!-- Контент -->

                <!--noindex-->
                <div id="br_autoload">
                </div>
                <!--/noindex-->
                <section class="clearfix">
                    <h1>ПРОКРУТИТЕ СТРАНИЦУ ВНИЗ</h1>
                </section>
            </div>
        </div>


        <!-- /Контент -->
    </div>
</main>


<script>
    $(document).ready(function () {
        var inProgress = false;
        $(window).scroll(function () {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 300 && !inProgress) {

                var newsID = '<?php echo ''; ?>';
                $.ajax({
                    url: 'config/news_load.php',
                    method: 'POST',
                    data: {'nid': newsID},
                    beforeSend: function () {
                        inProgress = true;
                    }
                }).done(function (data) {
                    $("#br_autoload").append(data);
                    inProgress = false;
                });
            }
        });
    });
</script>

<script>
    var crabs_modalled = '1';
    var crabs_modalled_tab = '0';
    var crabs_modalled_id = '41';
</script>
<script src="js/buildall.js"></script>


<footer style="display:none">
    <div class="center-wrapper">
        <div class="content clearfix">
            <div class="column">
                <a href="/" class="logotype logo_link">СРОЧНЫЕ НОВОСТИ</a>
                <div class="copyright">
                    Copyright © 2021 «СРОЧНЫЕ НОВОСТИ». Все права защищены.<br>Для детей старше 16 лет.
                </div>
            </div>
            <div class="column clearfix"></div>
        </div>
    </div>
</footer>

<script src="js/bootstrap.min.js"></script>
<script src="js/crabs_best.js"></script>


</body></html>