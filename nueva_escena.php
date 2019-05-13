<?php
require_once "metodos.php";

$dispositivos = $usuario->getDispositivos($conexion);

include "views/partials/header.part.php";
include "views/nueva_escena.view.phtml";
include "chat.php";
include "views/partials/footer.part.php";
?>