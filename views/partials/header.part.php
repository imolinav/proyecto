<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/fonts.css" />
    <link rel="stylesheet" href="css/hover.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <title>Smart Living</title>
</head>
<script src="js/jquery-3.3.1.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/canvasjs.min.js"></script>


<body>
<header class="navbar navbar-dark navbar-expand bg-dark">
    <h1 class="navbar-brand d-none d-sm-block"><a href="index.php">Smart Living</a></h1>
    <?php if(!isset($_SESSION['email'])): ?>
        <button type="button" class="btn btn-light ml-auto mr-3"><?= $i_header_login ?></button>
    <?php else: ?>
        <div class="dropdown ml-auto mr-3">
            <?php if($usuario->getAdmin() == 1) {
                if(!empty($mensajes_nl)) :?>
                <div class="msgs_nl_admin"><?= count($mensajes_nl) ?></div>
                <?php endif;
            } ?>
            <div class="dropdown-toggle d-none d-md-block" id="dropdownMenuId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: white">
                <img class="mr-3" src="<?= $usuario->getFoto() ?>"><?= $usuario->getNombre() ?>
            </div>
            <div class="dropdown-toggle d-md-none" id="dropdownMenuId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: white">
                <img class="mr-3" src="<?= $usuario->getFoto() ?>">
            </div>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuId">
                <?php if($usuario->getAdmin()==0): ?>
                <a class="dropdown-item hvr-icon-forward" href="control.php"><i class="far fa-lightbulb hvr-icon"></i> <?= $i_header_control ?></a>
                <a class="dropdown-item hvr-icon-forward" href="perfil.php"><i class="far fa-user hvr-icon"></i> <?= $i_header_perfil ?></a>
                <a class="dropdown-item hvr-icon-forward" href="graficas.php"><i class="far fa-chart-bar hvr-icon"></i> <?= $i_header_graficas ?></a>
                <?php else: ?>
                <a class="dropdown-item hvr-icon-forward" href="cpanel.php"><i class="fas fa-cog hvr-icon"></i> <?= $i_header_panel ?></a>
                <?php endif; ?>
                <hr>
                <form class="dropdown-item hvr-icon-forward" method="post" action="index.php">
                    <button type="button" id="logout"><i class="fas fa-power-off hvr-icon"></i> <?= $i_header_logout ?></button>
                    <input type="hidden" name="logout">
                </form>
            </div>
        </div>
    <?php endif; ?>
    <form id="idiomas" class="form-inline my-2 my-lg-0" action="metodos.php" method="post">
        <img src="imgs/spanish.png" id="castellano">
        <img src="imgs/uk.png" id="ingles">
        <input type="hidden" name="lengua" id="idioma_selected">
        <input type="hidden" name="page" id="actual_page" value="<?= basename($_SERVER['PHP_SELF']) ?>">
    </form>
</header>
<script src="js/functions.js"></script>
<script src="js/login.js"></script>