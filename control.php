<?php
require_once "metodos.php";

$parameters = [':email'=>$usuario->getEmail()];

$stmt_disp = $conexion->prepare("SELECT * FROM dispositivo WHERE usuario_email = :email");
$stmt_disp->execute($parameters);
$dispositivos = $stmt_disp->fetchAll(PDO::FETCH_ASSOC);

$stmt_esc = $conexion->prepare("SELECT * FROM escena WHERE usuario_email = :email");
$stmt_esc->execute($parameters);
$escenas = $stmt_esc->fetchAll(PDO::FETCH_ASSOC);

$stmt_hab = $conexion->prepare("SELECT habitacion FROM dispositivo WHERE usuario_email = :email GROUP BY habitacion");
$stmt_hab->execute($parameters);
$habitaciones = $stmt_hab->fetchAll(PDO::FETCH_ASSOC);

$stmt_cam = $conexion->prepare("SELECT * FROM camara WHERE usuario_email = :email");
$stmt_cam->execute($parameters);
$camaras = $stmt_cam->fetchAll(PDO::FETCH_ASSOC);


include "views/partials/header.part.php";
include "views/control.view.phtml";
include "chat.php";
include "views/partials/footer.part.php";
?>