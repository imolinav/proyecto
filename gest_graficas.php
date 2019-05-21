<?php
require "metodos.php";
if(isset($_POST['graph'])) {
    if($_POST['graph']=="actual") {
        $stmt = $conexion->prepare("SELECT * FROM dispositivo WHERE usuario_email = :email");
        $parameters = [':email'=>$usuario->getEmail()];
    } else if($_POST['graph']=="total") {
        $stmt = $conexion->prepare("SELECT * FROM disp_total WHERE usuario_email = :email");
        $parameters = [':email'=>$usuario->getEmail()];
    } else {
        $datos = $_POST['graph'];
        list($mes, $anyo) = explode("-", $datos);
        $stmt = $conexion->prepare("SELECT * FROM disp_mensual WHERE mes = :mes AND anyo = :anyo AND usuario_email = :email");
        $parameters = [':mes'=>$mes, ':anyo'=>$anyo, ':email'=>$usuario->getEmail()];
    }
    $stmt->execute($parameters);
    $dispositivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dispositivos = json_encode($dispositivos);
    echo $dispositivos;
}

?>