<?php
include "metodos.php";
$logs = $usuario->getLog($conexion);
if(isset($_POST['export_xls'])) {
    $filename="log_dispositivos.xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $isPrintHeader = false;
    if(!empty($logs)) {
        foreach ($logs as $log) {
            if(!$isPrintHeader) {
                echo implode("\t", array_keys($log)). "\n";
                $isPrintHeader = true;
            }
            echo implode("\t", array_values($log)). "\n";
        }
    }
    exit();
}
require_once "views/partials/header.part.php";
require_once "views/historial.view.phtml";
require_once "chat.php";
require_once "views/partials/footer.part.php";


?>