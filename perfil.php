<?php
//session_start();
require_once "metodos.php";

if (isset($_POST['upd_name'])) {
    $usuario->updateNombre($conexion, $_POST['upd_name']);
}
if (isset($_POST['upd_dni'])) {
    $usuario->updateDni($conexion, $_POST['upd_dni']);
}
if (isset($_FILES['upd_foto'])) {
    $fichero = $_FILES['upd_foto'];
    if ($fichero['size'] != 0) {
        $rutaImagen = 'imgs/users/' . $fichero['name'];
        if (!(strpos($fichero['type'], 'png') || strpos($fichero['type'], 'jpg') || strpos($fichero['type'], 'jpeg'))) {
            echo "<script type='text/javascript'>alert('ERROR! El formato es invalido!');</script>";
        } else if (is_file($rutaImagen) === true) {
            $idUnico = time();
            $nombreUnico = $idUnico . '_' . $fichero['name'];
            $rutaImagen = 'imgs/users/' . $nombreUnico;
        }
        move_uploaded_file($fichero['tmp_name'], $rutaImagen);
        $usuario->updateFoto($conexion, $rutaImagen);
    }
}

$dispositivos = $usuario->getDispositivos($conexion);
$escenas = $usuario->getEscenas($conexion);
$habitaciones = $usuario->getHabitaciones($conexion);
$camaras = $usuario->getCamaras($conexion);
$programas = $usuario->getProgramas($conexion);

include "views/partials/header.part.php";
include "views/perfil.view.phtml";
include "chat.php";
include "views/partials/footer.part.php";
?>