<?php
require_once "metodos.php";
if(isset($_POST['datos'])) {
    $datos = json_decode($_POST['datos'], true);
    $usuario = buscarUsuario($conexion, $datos['email']);
    if(empty($usuario)) {
        echo "opcion1";
    } else {
        if(password_verify($datos['pass'], $usuario[0]['pass'])) {
            echo "opcion3";
        } else {
            echo "opcion1";
        }
    }
}
?>