<?php
session_start();
require_once "metodos.php";
require_once "entities/Usuario.php";
date_default_timezone_set("Europe/Madrid");
//login
if(isset($_POST['li_email'])) {
    $stmt = $conexion->prepare("SELECT * FROM usuario WHERE email = :email");
    $parameters = [':email'=>$_POST['li_email']];
    $stmt->execute($parameters);
    $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Usuario");
    $usuario = $stmt->fetch();
    if(empty($usuario)) {
        echo "<script type='text/javascript'>alert('El usuario no existe');</script>";
    } else if(password_verify($_POST['li_pass'], $usuario->getPass())) {
        $_SESSION['email'] = $usuario->getEmail();
    } else {
        echo "<script type='text/javascript'>alert('La contrasenya no es correcta');</script>";
    }

    if($usuario->getActivo() == 0) {
        header('Location: cambio_pass.php');
    }

}

//cambio pass
if(isset($_POST['pass_chg'])) {
    $usuario->updatePassFT($conexion, $_POST['pass_chg']);
}

$hora = date("H", time());
include "views/partials/header.part.php";
include "views/index_1.view.phtml";
include "views/partials/footer.part.php";
?>