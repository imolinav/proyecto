<?php
require_once "metodos.php";
if (isset($_GET['id'])) {
    $escena = $_GET['id'];
} else {
    $escena = $_POST['scn_id'];
}

$dispositivos = $usuario->getDispositivos($conexion);

$stmt = $conexion->prepare("SELECT * FROM escena WHERE usuario_email = :email AND id = :id");
$parameters = [':email' => $usuario->getEmail(), ':id' => $escena];
$stmt->execute($parameters);
$escena_usr = $stmt->fetch(PDO::FETCH_ASSOC);

// Si la escena existe, procedemos
if ($escena_usr || $_POST['scn_name']) {

    if (isset($_POST['scn_name'])) {
        $stmt = $conexion->prepare("UPDATE escena SET nombre = :nombre, activa = 1 WHERE id = :id");
        $parameters = [':nombre' => $_POST['scn_name'], ':id' => $_POST['scn_id']];
        $stmt->execute($parameters);

        $stmt2 = $conexion->prepare("SELECT P.id FROM programa P, escena E, compuesta C WHERE P.id = C.programa_id AND C.escena_id = E.id AND E.id = :id");
        $parameters2 = [':id' => $_POST['scn_id']];
        $stmt2->execute($parameters2);
        $programas = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        $stmt_cnt2 = $conexion->prepare("SELECT MAX(id) as id FROM programa");
        $stmt_cnt2->execute();
        $programas_max = $stmt_cnt2->fetch(PDO::FETCH_ASSOC);
        $id_prg = $programas_max['id'];

        $stmt_del = $conexion->prepare("DELETE FROM programa WHERE id = :id");

        foreach ($programas as $programa) {
            $parameters_del = [':id' => $programa['id']];
            $stmt_del->execute($parameters_del);
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

            $stmt_prgr = $conexion->prepare("INSERT INTO programa (dispositivo_id, dia_inicio, hora_inicio, dia_fin, hora_fin, temp_inicio, temp_fin, temperatura) VALUES (:id, :dia_inicio, :hora_inicio, :dia_fin, :hora_fin, :temp_ini, :temp_fin, :temp)");

            $parameters_prgr = [':id' => $_POST['scn_disp_name'][$i], ':dia_inicio' => $_POST['scn_date'], ':hora_inicio' => $_POST['scn_disp_start'][$i], ':dia_fin' => null, ':hora_fin' => $_POST['scn_disp_end'][$i], ':temp_ini' => $_POST['scn_disp_tmp_start'][$i], ':temp_fin' => $_POST['scn_disp_tmp_end'][$i], ':temp' => $_POST['scn_disp_tmp'][$i]];

            $stmt_prgr->execute($parameters_prgr);

            $id_prg++;

            $stmt_contiene = $conexion->prepare("INSERT INTO compuesta VALUES(:id1, :id2)");
            $parameters_contiene = [':id1' => $_POST['scn_id'], ':id2' => $id_prg];
            $stmt_contiene->execute($parameters_contiene);
        }
    }

    $disp_scn = $conexion->prepare("SELECT D.id, D.nombre, D.habitacion, P.id as id_prg, P.dia_inicio, P.hora_inicio, P.dia_fin, P.hora_fin, P.temp_inicio, P.temp_fin, P.temperatura FROM dispositivo D, programa P, compuesta C, escena E WHERE D.id = P.dispositivo_id AND P.id = C.programa_id AND C.escena_id = E.id AND E.id = :id");
    $parameters2 = [':id' => $escena];
    $disp_scn->execute($parameters2);
    $datos_scn = $disp_scn->fetchAll(PDO::FETCH_ASSOC);


    include "views/partials/header.part.php";
    include "chat.php";
    include "views/escena.view.phtml";
    include "views/partials/footer.part.php";

} else {
    header("Location: error_pages/403.html");
}
?>