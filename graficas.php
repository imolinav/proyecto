<?php
//session_start();
require_once "metodos.php";

$dispositivos = $usuario->getDispositivos($conexion);
$historial = $usuario->getHistorial($conexion);
$meses = $usuario->getHistMeses($conexion);


$disp_actual = json_encode($dispositivos);

include "views/partials/header.part.php";
include "views/graficas.view.phtml";
include "chat.php";
include "views/partials/footer.part.php";
?>