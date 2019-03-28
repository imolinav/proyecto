<?php
session_start();
require_once "metodos.php";
require_once "entities/Usuario.php";
date_default_timezone_set("Europe/Madrid");
if(isset($_POST['li_email'])) {
    $stmt = $conexion->prepare("SELECT * FROM usuario WHERE email = :email");
    $parameters = [':email'=>$_POST['li_email']];
    $stmt->execute($parameters);
    $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Usuario");
    $usuario = $stmt->fetch();
    $_SESSION['email'] = $usuario->getEmail();
}

$hora = date("H", time());
include "views/partials/header.part.php";
include "views/index.view.php";
include "views/partials/footer.part.php";
?>