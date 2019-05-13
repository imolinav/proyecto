<?php
require_once "metodos.php";
$stmt_log = $conexion->prepare("INSERT INTO log (fecha, info, usuario_email, hora) VALUES (:dia, :info, :usuario_email, :hora)");
if(isset($_POST['activar'])) {
    $disp = $_POST['activar'];
    $dispositivo = buscarDispositivo($conexion, $disp);
    $accion = "";

    if($dispositivo['encendido']==1) {
        $stmt_faux = $conexion->prepare("SELECT fecha_aux FROM dispositivo WHERE id = '$disp'");
        $stmt_faux->execute();
        $faux = $stmt_faux->fetch(PDO::FETCH_ASSOC);
        $new_time = time() - $faux['fecha_aux'];
        $stmt = $conexion->prepare("UPDATE dispositivo SET encendido = 0, num_encendidos = num_encendidos+1, tiempo_encendido = tiempo_encendido + :fnew WHERE id = :id");
        $parameters = [':fnew'=>$new_time, ':id'=>$disp];
        $accion = " apagado.";
    } else {
        $stmt = $conexion->prepare("UPDATE dispositivo SET encendido = 1, num_encendidos = num_encendidos+1, fecha_aux = :faux  WHERE id = :id");
        $parameters = [':faux'=>time(), ':id'=>$disp];
        $accion = " encendido.";
    }
    $stmt->execute($parameters);
    $info = $dispositivo['habitacion']." - ". $dispositivo['nombre'].": dispositivo ".$accion;
    $parameters_log = [':dia'=>date("Y-m-d"), ':info'=>$info, ':usuario_email'=>$usuario->getEmail(), ':hora'=>date("H:i:s")];
    $stmt_log->execute($parameters_log);
    echo "bien";

} else if (isset($_POST['programar'])) {
    $datos = json_decode($_POST['programar'], true);
    $dispositivo = buscarDispositivo($conexion, $datos['id_disp']);

    $info = $dispositivo['habitacion']." - ". $dispositivo['nombre'].": dispositivo programado para el dia ".$datos['dia_ini']. " a las ".$datos['hora_ini'];
    $parameters_log = [':dia'=>date("Y-m-d"), ':info'=>$info, ':usuario_email'=>$usuario->getEmail(), ':hora'=>date("H:i:s")];
    $stmt_log->execute($parameters_log);

    foreach($datos as $k => $v) {
        if($v==="" || empty($v)) {
            $datos[$k]=null;
        }
    }

    $stmt = $conexion->prepare("INSERT INTO programa(dispositivo_id, dia_inicio, hora_inicio, dia_fin, hora_fin, temp_inicio, temp_fin, temperatura) VALUES (:disp, :inicio_d, :inicio_h, :fin_d, :fin_h, :temp_ini, :temp_fin, :temp)");
    $parameters = [':disp'=>$datos['id_disp'], ':inicio_d'=>$datos['dia_ini'], ':inicio_h'=>$datos['hora_ini'], ':fin_d'=>$datos['dia_fin'], ':fin_h'=>$datos['hora_fin'], ':temp_ini'=>$datos['temp_ini'], ':temp_fin'=>$datos['temp_fin'], ':temp'=>$datos['temp']];
    $stmt->execute($parameters);
    echo "bien";

} else if (isset($_POST['escena'])) {
    $dato = json_decode($_POST['escena'], true);
    $parameters = [':id'=>$dato['id_escena']];
    $stmt_scn_get = $conexion->prepare("SELECT * FROM escena WHERE id = :id");
    $stmt_scn_get->execute($parameters);
    $escena = $stmt_scn_get->fetch(PDO::FETCH_ASSOC);
    if($dato['accion']=="scn_delete") {
        $stmt_scn_del = $conexion->prepare("DELETE FROM escena WHERE id = :id");
        $stmt_scn_del->execute($parameters);

        $info = "Escena con nombre \"".$escena['nombre']. "\" eliminada.";
        $parameters_log = [':dia'=>date("Y-m-d"), ':info'=>$info, ':usuario_email'=>$usuario->getEmail(), ':hora'=>date("H:i:s")];
        $stmt_log->execute($parameters_log);

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

        $info = "Escena con nombre \"".$escena['nombre']. "\" activada para fecha ".$dato['fecha'];
        $parameters_log = [':dia'=>date("Y-m-d"), ':info'=>$info, ':usuario_email'=>$usuario->getEmail(), ':hora'=>date("H:i:s")];
        $stmt_log->execute($parameters_log);

        echo "actualizado";
    } else if($dato['accion']=='scn_apagar') {
        $stmt_scn_upd = $conexion->prepare("UPDATE escena SET activa = 0 WHERE id = :id");
        $stmt_scn_upd->execute($parameters);

        $info = "Escena con nombre \"".$escena['nombre']. "\" apagada.";
        $parameters_log = [':dia'=>date("Y-m-d"), ':info'=>$info, ':usuario_email'=>$usuario->getEmail(), ':hora'=>date("H:i:s")];
        $stmt_log->execute($parameters_log);

        echo "apagado";
    }
}
?>