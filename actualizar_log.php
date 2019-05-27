<?php
require_once "metodos.php";
redirect($_SERVER['REQUEST_METHOD']);

// Monta una consulta con los datos que le pasemos

if (isset($_POST['log_query'])) {
    $datos = json_decode($_POST['log_query'], true);
    $query = "";
    $parameters = "";

    $parameters = [':email' => $usuario->getEmail()];

    if ($datos['f_inicio'] != "") {
        $query .= " AND fecha >= :f_inicio ";
        $parameters[':f_inicio'] = $datos['f_inicio'];
    }
    if ($datos['f_fin'] != "") {
        $query .= " AND fecha <= :f_fin ";
        $parameters[':f_fin'] = $datos['f_fin'];
    }
    if ($datos['habitacion'] != "all" && $datos['habitacion'] != "") {
        $query .= " AND habitacion = :habitacion ";
        $parameters[':habitacion'] = $datos['habitacion'];
    }
    $stmt = $conexion->prepare("SELECT * FROM log WHERE usuario_email = :email " . $query . " ORDER BY fecha DESC, hora DESC");
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
            <span>No hay registros para ese filtrado de fechas</span>
        </code>
    <?php endif;
}

?>