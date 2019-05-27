<?php
require_once "metodos.php";
redirect($_SERVER['REQUEST_METHOD']);

// Comprueba que el usuario existe y la contraseña es correcta

if (isset($_POST['datos'])) {
    $datos = json_decode($_POST['datos'], true);
    $usuario = buscarUsuario($conexion, $datos['email']);
    if (empty($usuario)) {
        // El usuario no existe
        echo "opcion1";
    } else {
        if (password_verify($datos['pass'], $usuario['pass'])) {
            // Usuario y contraseña correctos
            echo "opcion3";
        } else {
            // Contraseña incorrecta
            echo "opcion1";
        }
    }
}
?>