<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/main.css" />
    <title>Smart Living</title>
</head>
<!-- <script src="js/bootstrap.bundle.js"></script> -->
<script src="js/jquery-3.3.1.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/socket.io/socket.io.js"></script>
<!-- <script src="js/util.js"></script> -->
<script src="js/canvasjs.min.js"></script>
<script src="js/canvasjs.react.js"></script>
<script src="js/jquery.canvasjs.min.js"></script>

<body>
<header class="navbar navbar-dark navbar-expand bg-dark">
    <h1 class="navbar-brand"><a href="index.php"">Smart Living</a></h1>
    <?php if(!isset($_SESSION['email'])): ?>
        <button type="button" class="btn btn-light ml-auto mr-3"><?= $i_header_login ?></button>
    <?php else: ?>
        <div class="dropdown ml-auto mr-3">
            <?php if($usuario->getAdmin() == 1) {
                if(!empty($mensajes_nl)) :?>
                <div class="msgs_nl_admin"><?= count($mensajes_nl) ?></div>
                <?php endif;
            } ?>
            <div class="dropdown-toggle" id="dropdownMenuId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: white">
                <img class="mr-3" src="<?= $usuario->getFoto() ?>"><?= $usuario->getNombre() ?>
            </div>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuId">
                <?php if($usuario->getAdmin()==0): ?>
                <a class="dropdown-item" href="control.php"><?= $i_header_control ?></a>
                <a class="dropdown-item" href="perfil.php"><?= $i_header_perfil ?></a>
                <a class="dropdown-item" href="graficas.php"><?= $i_header_graficas ?></a>
                <?php else: ?>
                <a class="dropdown-item" href="cpanel.php"><?= $i_header_panel ?></a>
                <?php endif; ?>
                <hr>
                <form class="dropdown-item" method="post" action="index.php">
                    <button type="button" id="logout"><?= $i_header_logout ?></button>
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