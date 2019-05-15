<?php
require_once "metodos.php";
if (isset($_POST['activar'])) {
    $disp = $_POST['activar'];
    $dispositivo = buscarDispositivo($conexion, $disp);
    $accion = "";

    if ($dispositivo['encendido'] == 1) {
        $stmt_faux = $conexion->prepare("SELECT fecha_aux FROM dispositivo WHERE id = :id");
        $parameters = [':id' => $disp];
        $stmt_faux->execute($parameters);
        $faux = $stmt_faux->fetch(PDO::FETCH_ASSOC);
        $new_time = time() - $faux['fecha_aux'];
        $stmt = $conexion->prepare("UPDATE dispositivo SET encendido = 0, num_encendidos = num_encendidos+1, tiempo_encendido = tiempo_encendido + :fnew WHERE id = :id");
        $parameters = [':fnew' => $new_time, ':id' => $disp];
        $accion = " apagado.";
    } else {
        $stmt = $conexion->prepare("UPDATE dispositivo SET encendido = 1, num_encendidos = num_encendidos+1, fecha_aux = :faux  WHERE id = :id");
        $parameters = [':faux' => time(), ':id' => $disp];
        $accion = " encendido.";
    }
    $stmt->execute($parameters);
    $info = $dispositivo['habitacion'] . " - " . $dispositivo['nombre'] . ": dispositivo " . $accion;
    echo "bien";

} else if (isset($_POST['programar'])) {
    $datos = json_decode($_POST['programar'], true);
    $dispositivo = buscarDispositivo($conexion, $datos['id_disp']);
    foreach ($datos as $k => $v) {
        if ($v === "" || empty($v)) {
            $datos[$k] = null;
        }
    }
    addPrgrm($conexion, $datos['id_disp'], $datos['dia_ini'], $datos['hora_ini'], $datos['dia_fin'], $datos['hora_fin'], $datos['temp_ini'], $datos['temp_fin'], $datos['temp']);
    $info = $dispositivo['habitacion'] . " - " . $dispositivo['nombre'] . ": dispositivo programado para el dia " . $datos['dia_ini'] . " a las " . $datos['hora_ini'];
    echo "bien";

} else if (isset($_POST['escena'])) {
    $dato = json_decode($_POST['escena'], true);
    $escena = getEscena($conexion, $dato['id_escena']);
    $parameters = [':id' => $dato['id_escena']];
    if ($dato['accion'] == "scn_delete") {
        $stmt_scn_del = $conexion->prepare("DELETE FROM escena WHERE id = :id");
        $stmt_scn_del->execute($parameters);

        $info = "Escena con nombre \"" . $escena['nombre'] . "\" eliminada.";
        echo "eliminado";
    } else if ($dato['accion'] == 'scn_update') {
        $stmt_prgs = $conexion->prepare("SELECT P.id as id FROM programa P, compuesta C, escena E WHERE P.id = C.programa_id AND C.escena_id = E.id AND E.id = :id");
        $stmt_prgs->execute($parameters);
        $programas = $stmt_prgs->fetchAll(PDO::FETCH_ASSOC);
        foreach ($programas as $programa) {
            $stmt_prg = $conexion->prepare("UPDATE programa SET dia_inicio = :dia1, dia_fin = :dia2 WHERE id = :id");
            $parameters_prg = [':dia1' => $dato['fecha'], ':dia2' => $dato['fecha'], ':id' => $programa['id']];
            $stmt_prg->execute($parameters_prg);
        }
        $stmt_scn_upd = $conexion->prepare("UPDATE escena SET activa = 1 WHERE id = :id");
        $stmt_scn_upd->execute($parameters);
        $info = "Escena con nombre \"" . $escena['nombre'] . "\" activada para fecha " . $dato['fecha'];
        echo "actualizado";
    } else if ($dato['accion'] == 'scn_apagar') {
        $stmt_scn_upd = $conexion->prepare("UPDATE escena SET activa = 0 WHERE id = :id");
        $stmt_scn_upd->execute($parameters);
        $info = "Escena con nombre \"" . $escena['nombre'] . "\" apagada.";
        echo "apagado";
    } else if($dato['accion'] == 'scn_clone') {
        //TODO: revisar esto
        $escena = getEscena($conexion, $dato['id_escena']);

        $stmt_escena = $conexion->prepare("INSERT INTO escena (nombre, activa, usuario_email) VALUES (:nombre, 1, :email)");
        $parameters_escena = [':nombre' => $escena['nombre'], ':email' => $usuario->getEmail()];
        $stmt_escena->execute($parameters_escena);

        $info = "Escena con nombre \"" . $escena['nombre'] . "\" clonada.";
        $usuario->addLog($conexion, date("Y-m-d"), $info, date("H:i:s"));

        $stmt_cnt = $conexion->prepare("SELECT MAX(id) as id FROM escena");
        $stmt_cnt->execute();
        $escena_id = $stmt_cnt->fetch(PDO::FETCH_ASSOC);
        $escena_id = $escena_id['id'];

        $stmt_cnt2 = $conexion->prepare("SELECT MAX(id) as id FROM programa");
        $stmt_cnt2->execute();
        $cant_programa = $stmt_cnt2->fetch(PDO::FETCH_ASSOC);
        $id_prg = $cant_programa['id'];

        $stmt_prg_escena = $conexion->prepare("SELECT P.dispositivo_id as dispositivo_id, P.dia_inicio as dia_inicio, P.hora_inicio as hora_inicio, P.dia_fin as dia_fin, P.hora_fin as hora_fin, P.temp_inicio as temp_inicio, P.temp_fin as temp_fin, P.temperatura as temperatura FROM programa P, compuesta C, escena E WHERE P.id = C.programa_id AND C.escena_id = E.id AND E.id = :id");
        $parameters = [':id'=>$dato['id_escena']];
        $stmt_prg_escena->execute($parameters);
        $programas = $stmt_prg_escena->fetchAll(PDO::FETCH_ASSOC);

        /*var_dump($programas);
        die();*/

        foreach($programas as $program) {
            $id_prg++;

            addPrgrm($conexion, $program['dispositivo_id'], $program['dia_inicio'], $program['hora_inicio'], $program['dia_fin'], $program['hora_fin'], $program['temp_inicio'], $program['temp_fin'], $program['temperatura']);

            $stmt_contiene = $conexion->prepare("INSERT INTO compuesta VALUES(:id1, :id2)");
            $parameters_contiene = [':id1' => $escena_id, ':id2' => $id_prg];
            $stmt_contiene->execute($parameters_contiene);
        }

        echo "clonado";
    }
}
$usuario->addLog($conexion, date('Y-m-d'), $info, date('H:i:s'));
?>