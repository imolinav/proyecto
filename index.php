<?php
require_once "metodos.php";

// Login
if (isset($_POST['li_email'])) {
    $usuario = getUsuario($conexion, $_POST['li_email']);
    if (empty($usuario)) {
        echo "<script type='text/javascript'>alert('El usuario no existe');</script>";
    } else if (password_verify($_POST['li_pass'], $usuario->getPass())) {
        $_SESSION['email'] = $usuario->getEmail();
        $reco = getPsswRec($conexion, $usuario->getEmail());
        if (!empty($reco)) {
            deletePswdRec($conexion, $usuario->getEmail());
        }
        $usuario->actualizarGraficas($conexion);
    } else {
        echo "<script type='text/javascript'>alert('La contrasenya no es correcta');</script>";
    }

    if ($usuario->getActivo() == 0) {
        header('Location: cambio_pass.php');
    }
    if ($usuario->getAdmin() == 1) {
        header('Location: cpanel.php');
    }

    $mensajes_nl = comprobarMsgs($conexion, $_POST['li_email']);
}

// Cambio pass la primera vez que inicias sesion
if (isset($_POST['pass_chg'])) {
    $usuario->updatePassFT($conexion, $_POST['pass_chg']);
}

// Recuperacion de contraseña
if (isset($_POST['pass_recovery'])) {
    $stmt = $conexion->prepare("UPDATE usuario SET pass = :pass, activo = 1 WHERE email = :email");
    $parameters = [':pass' => password_hash($_POST['pass_recovery'], PASSWORD_DEFAULT, ['cost' => 10]), ':email' => $_POST['user_email_recovery']];
    $stmt->execute($parameters);
    deletePswdRec($conexion, $_POST['user_email_recovery']);
}

$hora = date("H", time());
include "views/partials/header.part.php";
include "views/index.view.phtml";
include "chat.php";
include "views/partials/footer.part.php";
?>