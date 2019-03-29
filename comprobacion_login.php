<?php
require_once "metodos.php";
if(isset($_POST['datos'])) {
    $datos = json_decode($_POST['datos'], true);
    $stmt = $conexion->prepare("SELECT * FROM usuario WHERE email = :email");
    $parameters = [':email'=>$datos['email']];
    $stmt->execute($parameters);
    $usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(empty($usuario)) {
        echo "opcion1";
    } else {
        if(password_verify($datos['pass'], $usuario[0]['pass'])) {
            echo "opcion3";
        } else {
            echo "opcion2";
        }
    }
}
?>