<!DOCTYPE html>
<html>
<head>
    <title>Добавить новость</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>
<nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <a class="navbar-brand" href="#">Добавить новость</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="news.html">Новости</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <h1>Добавить новость</h1>
            <form>
                <div class="form-group">
                    <label for="title">Заголовок</label>
                    <input type="text" class="form-control" id="title" placeholder="Введите заголовок новости">
                </div>
                <div class="form-group">
                    <label for="description">Описание</label>
                    <textarea class="form-control" id="description" rows="3" placeholder="Введите описание новости"></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Изображение</label>
                    <input type="file" class="form-control-file" id="image">
                </div>
                <button type="submit" class="btn btn-primary">Добавить новость</button>
            </form>
        </div>
        <div class="col-md-4">
            <h1>Категории</h1>
            <ul class="list-group">
                <li class="list-group-item active">Все категории</li>
                <li class="list-group-item">Политика</li>
                <li class="list-group-item">Экономика</li>
                <li class="list-group-item">Культура</li>
                <li class="list-group-item">Наука</li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
