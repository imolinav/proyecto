<?php
//session_start();
require_once "metodos.php";

// Actualiza el nombre
if (isset($_POST['upd_name'])) {
    $usuario->updateNombre($conexion, $_POST['upd_name']);
}

// Actualiza el email
if (isset($_POST['upd_email'])) {
    $usuario->updateEmail($conexion, $_POST['upd_email']);
}

// Actualiza la foto
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

// Recogemos los datos del usuario (dispositivos, escenas habitaciones y programas)
$dispositivos = $usuario->getDispositivos($conexion);
$escenas = $usuario->getEscenas($conexion);
$habitaciones = $usuario->getHabitaciones($conexion);
$programas = $usuario->getProgramas($conexion);

include "views/partials/header.part.php";
include "views/perfil.view.phtml";
include "chat.php";
include "views/partials/footer.part.php";
?>