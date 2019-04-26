<?php
//session_start();
require_once "metodos.php";

$email = $_SESSION['email'];
$stmt_usos = $conexion->prepare("SELECT * FROM dispositivo WHERE usuario_email = '$email'");
$stmt_usos->execute();
$dispositivos = $stmt_usos->fetchAll(PDO::FETCH_ASSOC);

$disp = json_encode($dispositivos);

include "views/partials/header.part.php";
include "views/graficas.view.phtml";
include "chat.php";
include "views/partials/footer.part.php";
?>