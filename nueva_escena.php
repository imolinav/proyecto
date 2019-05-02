<?php
require_once "metodos.php";

$email = $usuario->getEmail();
$stmt_disp = $conexion->prepare("SELECT * FROM dispositivo WHERE usuario_email = '$email'");
$stmt_disp->execute();
$dispositivos = $stmt_disp->fetchAll(PDO::FETCH_ASSOC);

include "views/partials/header.part.php";
include "views/nueva_escena.view.phtml";
include "chat.php";
include "views/partials/footer.part.php";
?>