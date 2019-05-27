<?php
require_once "metodos.php";

// Crea una escena
if (isset($_POST['scn_name'])) {

    $stmt_escena = $conexion->prepare("INSERT INTO escena (nombre, activa, usuario_email) VALUES (:nombre, 1, :email)");
    $parameters_escena = [':nombre' => $_POST['scn_name'], ':email' => $usuario->getEmail()];
    $stmt_escena->execute($parameters_escena);

    $info = "Escena con nombre \"" . $_POST['scn_name'] . "\" creada para el dia " . $_POST['scn_date'] . ".";
    $usuario->addLog($conexion, date("Y-m-d"), $info, date("H:i:s"));

    $stmt_cnt = $conexion->prepare("SELECT MAX(id) as id FROM escena");
    $stmt_cnt->execute();
    $escena_id = $stmt_cnt->fetch(PDO::FETCH_ASSOC);
    $escena_id = $escena_id['id'];

    $stmt_cnt2 = $conexion->prepare("SELECT MAX(id) as id FROM programa");
    $stmt_cnt2->execute();
    $programas = $stmt_cnt2->fetch(PDO::FETCH_ASSOC);
    $id_prg = $programas['id'];

    $repeats = "";

    foreach ($_POST['scn_repeats'] as $repeat) {
        if ($repeat == "si") {
            $repeats .= "S";
        } else {
            $repeats .= "N";
        }
    }

    $weekly = 0;
    if (isset($_POST['scn_weekly'])) {
        $weekly = 1;
    }

    for ($i = 0; $i < count($_POST['scn_disp_name']); $i++) {

        if ($_POST['scn_disp_start'][$i] === "") {
            $_POST['scn_disp_start'][$i] = null;
        }
        if ($_POST['scn_disp_end'][$i] === "") {
            $_POST['scn_disp_end'][$i] = null;
        }
        if ($_POST['scn_disp_tmp_start'][$i] === "") {
            $_POST['scn_disp_tmp_start'][$i] = null;
        }
        if ($_POST['scn_disp_tmp_end'][$i] === "") {
            $_POST['scn_disp_tmp_end'][$i] = null;
        }
        if ($_POST['scn_disp_tmp'][$i] === "") {
            $_POST['scn_disp_tmp'][$i] = null;
        }

        $stmt_prgr = $conexion->prepare("INSERT INTO programa (dispositivo_id, dia_inicio, hora_inicio, dia_fin, hora_fin, temp_inicio, temp_fin, temperatura, repetir_dias, repetir_sem) VALUES (:id, :dia_inicio, :hora_inicio, :dia_fin, :hora_fin, :temp_ini, :temp_fin, :temp, :dias, :semanal)");

        $parameters_prgr = [':id' => $_POST['scn_disp_name'][$i], ':dia_inicio' => $_POST['scn_date'], ':hora_inicio' => $_POST['scn_disp_start'][$i], ':dia_fin' => null, ':hora_fin' => $_POST['scn_disp_end'][$i], ':temp_ini' => $_POST['scn_disp_tmp_start'][$i], ':temp_fin' => $_POST['scn_disp_tmp_end'][$i], ':temp' => $_POST['scn_disp_tmp'][$i], ':dias' => $repeats, ':semanal' => $weekly];

        $stmt_prgr->execute($parameters_prgr);

        $id_prg++;

        $stmt_contiene = $conexion->prepare("INSERT INTO compuesta VALUES(:id1, :id2)");
        $parameters_contiene = [':id1' => $escena_id, ':id2' => $id_prg];
        $stmt_contiene->execute($parameters_contiene);
    }
    header("Location: control.php");

// Elimina una escena
} else if (isset($_POST['del_prg'])) {
    $id = $_POST['del_prg'];
    $stmt_del_prg = $conexion->prepare("DELETE FROM programa WHERE id = :id");
    $parameters_del_prg = [':id' => $id];
    $stmt_del_prg->execute($parameters_del_prg);
    header("Location: control.php");
}
$dispositivos = $usuario->getDispositivos($conexion);
$escenas = $usuario->getEscenas($conexion);
$habitaciones = $usuario->getHabitaciones($conexion);

include "views/partials/header.part.php";
include "views/control.view.phtml";
include "chat.php";
include "views/partials/footer.part.php";
?>
