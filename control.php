<?php
require_once "metodos.php";

$dispositivos = $usuario->getDispositivos($conexion);
$escenas = $usuario->getEscenas($conexion);
$habitaciones = $usuario->getHabitaciones($conexion);
$camaras = $usuario->getCamaras($conexion);

if(isset($_POST['scn_name'])) {

    $stmt_escena = $conexion->prepare("INSERT INTO escena (nombre, activa, usuario_email) VALUES (:nombre, 1, :email)");
    $parameters_escena = [':nombre'=>$_POST['scn_name'], ':email'=>$usuario->getEmail()];
    $stmt_escena->execute($parameters_escena);

    $stmt_log = $conexion->prepare("INSERT INTO log (fecha, info, usuario_email, hora) VALUES (:dia, :info, :usuario_email, :hora)");
    $info = "Escena con nombre \"".$_POST['scn_name']. "\" creada para el dia ".$_POST['scn_date'].".";
    $parameters_log = [':dia'=>date("Y-m-d"), ':info'=>$info, ':usuario_email'=>$usuario->getEmail(), ':hora'=>date("H:i:s")];
    $stmt_log->execute($parameters_log);

    $stmt_cnt = $conexion->prepare("SELECT MAX(id) as id FROM escena");
    $stmt_cnt->execute();
    $escena_id = $stmt_cnt->fetchAll(PDO::FETCH_ASSOC);
    $escena_id = $escena_id[0]['id'];

    $stmt_cnt2 = $conexion->prepare("SELECT MAX(id) as id FROM programa");
    $stmt_cnt2->execute();
    $programas = $stmt_cnt2->fetchAll(PDO::FETCH_ASSOC);
    $id_prg = $programas[0]['id'];

    for($i=0; $i<count($_POST['scn_disp_name']); $i++) {

        /*$fecha_inicio = $_POST['scn_date'] . " " . $_POST['scn_disp_start'][$i] . ":00";*/
        if($_POST['scn_disp_end'][$i]=="") {
            $fecha_fin = null;
        } else {
            $fecha_fin = $_POST['scn_disp_end'][$i];
        }
        $stmt_prgr = $conexion->prepare("INSERT INTO programa (dispositivo_id, dia_inicio, hora_inicio, dia_fin, hora_fin, temperatura) VALUES (:id, :dia_inicio, :hora_inicio, :dia_fin, :hora_fin, :temp)");
        if($_POST['scn_disp_tmp'][$i]=="") {
            $parameters_prgr = [':id'=>$_POST['scn_disp_name'][$i], ':dia_inicio'=>$_POST['scn_date'], ':hora_inicio'=>$_POST['scn_disp_start'][$i], ':dia_fin'=>$_POST['scn_date'], ':hora_fin'=>$fecha_fin, ':temp'=>NULL];
        } else {
            $parameters_prgr = [':id'=>$_POST['scn_disp_name'][$i], ':dia_inicio'=>$_POST['scn_date'], ':hora_inicio'=>$_POST['scn_disp_start'][$i], ':dia_fin'=>$_POST['scn_date'], ':hora_fin'=>$fecha_fin, ':temp'=>$_POST['scn_disp_tmp'][$i]];
        }
        $stmt_prgr->execute($parameters_prgr);

        $id_prg++;

        $stmt_contiene = $conexion->prepare("INSERT INTO compuesta VALUES(:id1, :id2)");
        $parameters_contiene = [':id1'=>$escena_id, ':id2'=>$id_prg];
        $stmt_contiene->execute($parameters_contiene);
    }
}

include "views/partials/header.part.php";
include "views/control.view.phtml";
include "chat.php";
include "views/partials/footer.part.php";
?>