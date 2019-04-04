<?php
require_once "metodos.php";
if(isset($_POST['activar'])) {
    $disp = $_POST['activar'];
    $stmt_comp = $conexion->prepare("SELECT encendido FROM dispositivo WHERE id = :id");
    $parameters = [':id'=>$disp];
    $stmt_comp->execute($parameters);
    $estado = $stmt_comp->fetch(PDO::FETCH_ASSOC);

    if($estado['encendido']==1) {
        $stmt = $conexion->prepare("UPDATE dispositivo SET encendido = 0 WHERE id = :id");
    } else {
        $stmt = $conexion->prepare("UPDATE dispositivo SET encendido = 1 WHERE id = :id");
    }
    $stmt->execute($parameters);
    echo "bien";
} else if (isset($_POST['programar'])) {
    $datos = json_decode($_POST['programar'], true);

    $dia_inicio = $datos['dia_ini']." ".$datos['hora_ini'].":00";
    $dia_fin = $datos['dia_fin']." ".$datos['hora_fin'].":00";

    $stmt = $conexion->prepare("INSERT INTO programa(dispositivo_id, inicio, fin, temperatura) VALUES (:disp, :inicio, :fin, :temp)");

    /*if(is_null($datos['temp'])) {
        $parameters = [':disp'=>$datos['id_disp'], ':inicio'=>$dia_inicio, ':fin'=>$dia_fin, null];
    } else {*/
        $parameters = [':disp'=>$datos['id_disp'], ':inicio'=>$dia_inicio, ':fin'=>$dia_fin, ':temp'=>$datos['temp']];
    /*}*/

    $stmt->execute($parameters);
    echo "bien";
}
?>