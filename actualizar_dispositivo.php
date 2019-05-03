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

} else if (isset($_POST['escena'])) {
    $dato = json_decode($_POST['escena'], true);
    $parameters = [':id'=>$dato['id_escena']];
    if($dato['accion']=="scn_delete") {
        $stmt_scn_del = $conexion->prepare("DELETE FROM escena WHERE id = :id");
        $stmt_scn_del->execute($parameters);
        echo "eliminado";
    } else if($dato['accion']=='scn_update') {
        $stmt_prgs = $conexion->prepare("SELECT P.id as id FROM programa P, compuesta C, escena E WHERE P.id = C.programa_id AND C.escena_id = E.id AND E.id = :id");
        $stmt_prgs->execute($parameters);
        $programas = $stmt_prgs->fetchAll(PDO::FETCH_ASSOC);
        foreach($programas as $programa) {
            $stmt_prg = $conexion->prepare("UPDATE programa SET dia_inicio = :dia1, dia_fin = :dia2 WHERE id = :id");
            $parameters_prg =  [':dia1'=>$dato['fecha'], ':dia2'=>$dato['fecha'], ':id'=>$programa['id']];
            $stmt_prg->execute($parameters_prg);
        }
        $stmt_scn_upd = $conexion->prepare("UPDATE escena SET activa = 1 WHERE id = :id");
        $stmt_scn_upd->execute($parameters);
        echo "actualizado";
    } else if($dato['accion']=='scn_apagar') {
        $stmt_scn_upd = $conexion->prepare("UPDATE escena SET activa = 0 WHERE id = :id");
        $stmt_scn_upd->execute($parameters);
        echo "apagado";
    }
}
?>