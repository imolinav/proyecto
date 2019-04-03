<?php
require_once "metodos.php";

$dispositivos = $usuario->getDispositivos($conexion);
$escenas = $usuario->getEscenas($conexion);
$habitaciones = $usuario->getHabitaciones($conexion);
$camaras = $usuario->getCamaras($conexion);

include "views/partials/header.part.php";
include "views/control.view.phtml";
include "chat.php";
include "views/partials/footer.part.php";
?>