<?php
require_once "metodos.php";
if (isset($_POST['log_query'])) {
    $datos = json_decode($_POST['log_query'], true);
    $fecha = "";
    $habitacion = "";
    $parameters = "";
    if ($datos['f_inicio'] == "" && $datos['f_fin'] != "") {
        $fecha = " WHERE fecha<=:f_fin ";
        $parameters = [':f_fin' => $datos['f_fin']];
    } else if ($datos['f_fin'] == "" && $datos['f_inicio'] != "") {
        $fecha = " WHERE fecha>=:f_inicio ";
        $parameters = [':f_inicio' => $datos['f_inicio']];
    } else if ($datos['f_inicio'] != "" && $datos['f_fin'] != "") {
        $fecha = " WHERE fecha>=:f_inicio AND fecha<=:f_fin ";
        $parameters = [':f_inicio' => $datos['f_inicio'], ':f_fin' => $datos['f_fin']];
    }
    if ($datos['habitacion'] != "all") {
        if ($datos['f_inicio'] != "" || $datos['f_fin'] != "") {
            $habitacion = " AND habitacion = :habitacion ";
            $parameters = [':habitacion' => $datos['habitacion']];
        } else {
            $habitacion = " WHERE habitacion = :habitacion ";
            $parameters = [':habitacion' => $datos['habitacion']];
        }
    }
    $stmt = $conexion->prepare("SELECT * FROM log " . $fecha . $habitacion . " ORDER BY fecha DESC, hora DESC");
    if ($parameters=="") {
        $stmt->execute();
    } else {
        $stmt->execute($parameters);
    }
    $new_log = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($new_log)) :?>
        <code>
            <?php foreach ($new_log as $log) : ?>
                <span><?= $log['fecha'] . " " . $log['hora'] . "  -->  " . $log['info'] ?></span><br>
            <?php endforeach; ?>
        </code>
    <?php else : ?>
        <code>
            <span>No ha registros para ese filtrado de fechas</span>
        </code>
    <?php endif;
}

?>