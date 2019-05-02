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

    $stmt_cnt = $conexion->prepare("SELECT * FROM escena");
    $stmt_cnt->execute();
    $escenas = $stmt_cnt->fetchAll(PDO::FETCH_ASSOC);
    $id_scn = count($escenas);

    $stmt_cnt2 = $conexion->prepare("SELECT * FROM programa");
    $stmt_cnt2->execute();
    $programas = $stmt_cnt2->fetchAll(PDO::FETCH_ASSOC);
    $id_prg = count($programas);

    for($i=0; $i<count($_POST['scn_disp_name']); $i++) {
        $fecha_inicio = $_POST['scn_date'] . " " . $_POST['scn_disp_start'][$i] . ":00";
        if($_POST['scn_disp_end'][$i]=="") {
            $fecha_fin = null;
        } else {
            $fecha_fin = $_POST['scn_date'] . " " . $_POST['scn_disp_end'][$i] . ":00";
        }
        $stmt_prgr = $conexion->prepare("INSERT INTO programa (dispositivo_id, inicio, fin, temperatura) VALUES (:id, :inicio, :fin, :temp)");
        if($_POST['scn_disp_tmp'][$i]=="") {
            $parameters_prgr = [':id'=>$_POST['scn_disp_name'][$i], ':inicio'=>$fecha_inicio, ':fin'=>$fecha_fin, ':temp'=>NULL];
        } else {
            $parameters_prgr = [':id'=>$_POST['scn_disp_name'][$i], ':inicio'=>$fecha_inicio, ':fin'=>$fecha_fin, ':temp'=>$_POST['scn_disp_tmp'][$i]];
        }
        $stmt_prgr->execute($parameters_prgr);

        $id_prg++;

        $stmt_contiene = $conexion->prepare("INSERT INTO compuesta VALUES(:id1, :id2)");
        $parameters_contiene = [':id1'=>$id_scn, ':id2'=>$id_prg];
        $stmt_contiene->execute($parameters_contiene);
    }
}

include "views/partials/header.part.php";
include "views/control.view.phtml";
include "chat.php";
include "views/partials/footer.part.php";
?>