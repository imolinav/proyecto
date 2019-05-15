<?php
require_once "metodos.php";
if (isset($_POST['log_query'])) {
    $datos = json_decode($_POST['log_query'], true);
    if ($datos['f_inicio'] == "") {
        $stmt = $conexion->prepare("SELECT * FROM log WHERE fecha<=:f_fin ORDER BY fecha DESC, hora DESC");
        $parameters = [':f_fin' => $datos['f_fin']];
    } else if ($datos['f_fin'] == "") {
        $stmt = $conexion->prepare("SELECT * FROM log WHERE fecha>=:f_inicio ORDER BY fecha DESC, hora DESC");
        $parameters = [':f_inicio' => $datos['f_inicio']];
    } else {
        $stmt = $conexion->prepare("SELECT * FROM log WHERE fecha>=:f_inicio && fecha<=:f_fin ORDER BY fecha DESC, hora DESC");
        $parameters = [':f_inicio' => $datos['f_inicio'], ':f_fin' => $datos['f_fin']];
    }
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