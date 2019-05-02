<?php
require_once "metodos.php";
if(isset($_POST['activar'])) {
    $disp = $_POST['activar'];
    $stmt_comp = $conexion->prepare("SELECT encendido FROM dispositivo WHERE id = :id");
    $parameters = [':id'=>$disp];
    $stmt_comp->execute($parameters);
    $estado = $stmt_comp->fetch(PDO::FETCH_ASSOC);

    if($estado['encendido']==1) {
        $stmt = $conexion->prepare("UPDATE dispositivo SET encendido = 0, num_encendidos = num_encendidos+1 WHERE id = :id");
    } else {
        $stmt = $conexion->prepare("UPDATE dispositivo SET encendido = 1, num_encendidos = num_encendidos+1  WHERE id = :id");
    }
    $stmt->execute($parameters);
    echo "bien";
} else if (isset($_POST['programar'])) {
    $datos = json_decode($_POST['programar'], true);

    $stmt = $conexion->prepare("INSERT INTO programa(dispositivo_id, dia_inicio, hora_inicio, dia_fin, hora_fin, temperatura) VALUES (:disp, :inicio_d, :inicio_h, :fin_d, :fin_h, :temp)");
    $parameters = [':disp'=>$datos['id_disp'], ':inicio_d'=>$datos['dia_ini'], ':inicio_h'=>$datos['hora_ini'], ':fin_d'=>$datos['dia_fin'], ':fin_h'=>$datos['hora_fin'], ':temp'=>$datos['temp']];
    $stmt->execute($parameters);
    echo "bien";

}
?>