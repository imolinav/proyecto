<?php
require_once "metodos.php";
date_default_timezone_set("Europe/Madrid");
if(isset($_POST['li_dni'])) {
    $stmt = $conexion->prepare("SELECT * FROM usuario WHERE dni = :dni");
    $parameters = [':dni'=>$_POST['li_dni']];
    $stmt->execute($parameters);
    $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Usuario");
    $usuario = $stmt->fetch();
    $_SESSION['dni'] = $usuario->getDni();
}
$hora = date("H", time());
include "views/partials/header.part.php";
include "views/index.view.php";
include "views/partials/footer.part.php";
?>