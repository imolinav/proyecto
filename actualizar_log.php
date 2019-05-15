<?php
require_once "metodos.php";
if (isset($_POST['log_query'])) {
    $datos = json_decode($_POST['log_query'], true);
    $fecha = "";
    $habitacion="";
    if ($datos['f_inicio'] == "" && $datos['f_fin'] != "") {
        $fecha = "fecha<=:f_fin";
        $parameters = [':f_fin' => $datos['f_fin']];
    } else if ($datos['f_fin'] == "" && $datos['f_inicio'] != "") {
        $fecha = "fecha>=:f_inicio";
        $parameters = [':f_inicio' => $datos['f_inicio']];
    } else if($datos['f_inicio']!="" && $datos['f_fin']!="") {
        $fecha = "fecha>=:f_inicio && fecha<=:f_fin";
        $parameters = [':f_inicio' => $datos['f_inicio'], ':f_fin' => $datos['f_fin']];
    }
    if ($datos['habitacion']!="") {
        if($datos['f_inicio'] != "" || $datos['f_fin'] != "") {
            $habitacion = " AND habitacion = :habitacion ";
            $parameters[':habitacion'] = $datos['habitacion'];
        } else {
            $habitacion = "habitacion = :habitacion";
            $parameters = [':habitacion' => $datos['habitacion']];
        }
    }
    $stmt = $conexion->prepare("SELECT * FROM log WHERE ".$fecha.$habitacion." ORDER BY fecha DESC, hora DESC");
    $stmt->execute($parameters);
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