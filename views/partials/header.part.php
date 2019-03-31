<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.css" >
    <link rel="stylesheet" href="css/main.css" >
    <title>Smart Living</title>
</head>
<script src="js/jquery-3.3.1.js"></script>
<script src="js/bootstrap.js"></script>
<body>
<header class="navbar navbar-dark navbar-expand bg-dark">
    <h1 class="navbar-brand"><a href="index.php"">Smart Living</a></h1>
    <?php if(!isset($_SESSION['email'])): ?>
        <button type="button" class="btn btn-light ml-auto mr-3">Iniciar sesión</button>
    <?php else: ?>
        <div class="dropdown ml-auto mr-3">
            <div class="dropdown-toggle" id="dropdownMenuId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: white">
                <img class="mr-3" src="<?= $usuario->getFoto() ?>"><?= $usuario->getNombre() ?>
            </div>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuId">
                <a class="dropdown-item" href="perfil.php">Perfil <span class="glyphicon glyphicon-user pull-right"></span></a>
                <a class="dropdown-item" href="graficas.php"><span class="glyphicon glyphicon-stats"></span> Gráficas</a>
                <hr>
                <form class="dropdown-item" method="post" action="index.php">
                    <button type="button" id="logout"><span class="glyphicon glyphicon-remove"></span> Cerrar sesión</button>
                    <input type="hidden" name="logout">
                </form>
            </div>
        </div>
        <!--
        <p class="ml-auto mt-3 mr-3"><a href="usuario.php"><?= $usuario->getNombre() ?></a></p>
        <form method="post" action="index.php">
            <button type="button" class="btn btn-light mr-3" id="logout">Cerrar sesión</button>
            <input type="hidden" name="logout">
        </form>
        -->
    <?php endif; ?>
    <form id="idiomas" class="form-inline my-2 my-lg-0" action="index.php" method="post">
        <img src="imgs/spanish.png" id="castellano">
        <img src="imgs/uk.png" id="ingles">
        <input type="hidden" name="lengua">
    </form>
</header>
<script src="js/logout.js"></script>