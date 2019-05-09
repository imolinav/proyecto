<?php
require_once "metodos.php";

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

        if($_POST['scn_disp_start'][$i]==="") {
            $_POST['scn_disp_start'][$i]=null;
        }
        if($_POST['scn_disp_end'][$i]==="") {
            $_POST['scn_disp_end'][$i]=null;
        }
        if($_POST['scn_disp_tmp_start'][$i]==="") {
            $_POST['scn_disp_tmp_start'][$i]=null;
        }
        if($_POST['scn_disp_tmp_end'][$i]==="") {
            $_POST['scn_disp_tmp_end'][$i]=null;
        }
        if($_POST['scn_disp_tmp'][$i]==="") {
            $_POST['scn_disp_tmp'][$i]=null;
        }

        $stmt_prgr = $conexion->prepare("INSERT INTO programa (dispositivo_id, dia_inicio, hora_inicio, dia_fin, hora_fin, temp_inicio, temp_fin, temperatura) VALUES (:id, :dia_inicio, :hora_inicio, :dia_fin, :hora_fin, :temp_ini, :temp_fin, :temp)");

        $parameters_prgr = [':id'=>$_POST['scn_disp_name'][$i], ':dia_inicio'=>$_POST['scn_date'], ':hora_inicio'=>$_POST['scn_disp_start'][$i], ':dia_fin'=>null, ':hora_fin'=>$_POST['scn_disp_end'][$i], ':temp_ini'=>$_POST['scn_disp_tmp_start'][$i], ':temp_fin'=>$_POST['scn_disp_tmp_end'][$i], ':temp'=>$_POST['scn_disp_tmp'][$i]];

        $stmt_prgr->execute($parameters_prgr);

        $id_prg++;

        $stmt_contiene = $conexion->prepare("INSERT INTO compuesta VALUES(:id1, :id2)");
        $parameters_contiene = [':id1'=>$escena_id, ':id2'=>$id_prg];
        $stmt_contiene->execute($parameters_contiene);
    }
    header("Location: control.php");
}
$dispositivos = $usuario->getDispositivos($conexion);
$escenas = $usuario->getEscenas($conexion);
$habitaciones = $usuario->getHabitaciones($conexion);
$camaras = $usuario->getCamaras($conexion);

include "views/partials/header.part.php";
include "views/control.view.phtml";
include "chat.php";
include "views/partials/footer.part.php";
?>
