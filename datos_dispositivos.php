<?php
require_once "metodos.php";
if (isset($_POST['disp'])) {
    $id = $_POST['disp'];
} else if (isset($_POST['scn'])) {
    $id = $_POST['scn'];
} else {
    $id = $_POST['profile'];
}

$parameters = [':id' => $id];

$dispositivo = buscarDispositivo($conexion, $id);

$stmt_prog = $conexion->prepare("SELECT * FROM programa WHERE dispositivo_id = :id AND (dia_fin >= :dia_actual OR dia_fin IS NULL)");
$parameters_prg = [':id' => $id, ':dia_actual' => date('Y-m-d')];
$stmt_prog->execute($parameters_prg);
$programas = $stmt_prog->fetchAll(PDO::FETCH_ASSOC);

$escena = getEscena($conexion, $id);

$disp_scn = $conexion->prepare("SELECT D.id, D.nombre, D.habitacion, P.dia_inicio, P.hora_inicio, P.dia_fin, P.hora_fin, P.temperatura FROM dispositivo D, programa P, compuesta C, escena E WHERE D.id = P.dispositivo_id AND P.id = C.programa_id AND C.escena_id = E.id AND E.id = :id");
$disp_scn->execute($parameters);
$disp_escenas = $disp_scn->fetchAll(PDO::FETCH_ASSOC);


if (isset($_POST['disp'])) {
    if (!empty($dispositivo)):?>
        <div class="col-12 col-md-6 col-lg-5 col-xl-4 mt-5 mt-md-0 offset-lg-1 offset-xl-2 pl-5" id="datos_disp">
            <?php if ($dispositivo['encendido'] == 1): ?>
                <img src="imgs/on.png" height="100px" class="mb-5 hvr-grow" id="controlador">
            <?php else: ?>
                <img src="imgs/off.png" height="100px" class="mb-5 hvr-grow" id="controlador">
            <?php endif; ?>
            <p><?= $i_disp_texto1 . $dispositivo['habitacion'] ?></p>
            <p><?= $i_disp_texto2 . $dispositivo['nombre'] ?></p>
            <p><?= $i_disp_texto3 . $dispositivo['num_encendidos'] ?></p>
            <p><?= $i_disp_texto4 . $dispositivo['tiempo_encendido'] ?></p>

            <hr>

            <?php if (!empty($programas)) : ?>
                <div class="prg_disp">
                    <?php foreach ($programas as $programa) {
                        if ($programa['dispositivo_id'] == $dispositivo['id']):?>
                            <form method="post" action="control.php" id="form_del_prg">
                                <button type="button" class="btn btn-danger" style="float: right;" id="btn_del_prg"><i class="far fa-trash-alt"></i></button>
                                <p><?= $i_disp_texto5 ?></p>
                                <p><?= $programa['dia_inicio'] . " " . $programa['hora_inicio'] ?></p>
                                <p><?= $programa['dia_fin'] . " " . $programa['hora_fin'] ?></p>
                                <input type="hidden" name="del_prg" value="<?= $programa['id'] ?>">
                            </form>
                            <hr>
                        <?php endif;
                    } ?>
                </div>
            <?php endif; ?>

            <p><?= $i_disp_texto6 ?></p>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="date" class="form-control" name="prg_date_start">
                <div class="invalid-feedback"><?= $i_guser_error2 ?></div>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon2"><i class="far fa-clock"></i></span>
                </div>
                <input type="time" class="form-control" name="prg_hour_start" aria-describedby="basic-addon2">
                <div class="invalid-feedback"><?= $i_guser_error2 ?></div>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon2"><i class="fas fa-thermometer-quarter"></i></span>
                </div>
                <input type="number" placeholder="0" min="0" class="form-control" name="prg_temp_start"
                       aria-describedby="basic-addon2">
                <div class="invalid-feedback"><?= $i_guser_error2 ?></div>
            </div>
            <p><?= $i_disp_texto7 ?></p>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="date" class="form-control" name="prg_date_end">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="far fa-clock"></i></span>
                </div>
                <input type="time" class="form-control" name="prg_hour_end">
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-thermometer-quarter"></i></span>
                </div>
                <input type="number" placeholder="0" min="0" class="form-control" name="prg_temp_end">
            </div>
            <?php if ($dispositivo['temperatura'] != null): ?>
                <p><?= $i_disp_texto8 ?></p>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-thermometer-quarter"></i></span>
                    </div>
                    <input type="number" class="form-control" name="prg_temp" placeholder="0" min="0"/>
                    <div class="invalid-feedback"><?= $i_guser_error2 ?></div>
                </div>
            <?php endif; ?>
            <input type="button" class="btn btn-primary" value="<?= $i_disp_boton1 ?>" name="prg_enviar">
        </div>
    <?php endif;
} else if (isset($_POST['scn'])) {
    if (!empty($escena)): ?>

        <div class="col-12 col-md-6 col-lg-5 col-xl-4 mt-5 mt-md-0 offset-lg-1 offset-xl-2 pl-5" id="datos_scn">
            <?php if ($escena['activa'] == 1): ?>
                <img src="imgs/on.png" height="100px" class="mb-5 hvr-grow" id="scn_apagar">
            <?php else: ?>
                <img src="imgs/off.png" height="100px" class="mb-5 hvr-grow" id="scn_apagar">
                <input type="date" name="reuse_scn_date" class="form-control mb-3">
                <button type="button" name="reuse_scn_btn" class="btn btn-primary mb-3" id="scn_update" disabled>
                    Actualizar escena
                </button>
            <?php endif;
            foreach ($disp_escenas as $disp) :?>
                <p><?= $disp['habitacion'] . " - " . $disp['nombre'] ?></p>
                <p><?= $disp['dia_inicio'] . " " . $disp['hora_inicio'] ?></p>
                <p><?= $disp['dia_fin'] . " " . $disp['hora_fin'] ?></p>
            <?php endforeach; ?>
            <hr>
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-primary mb-3" id="scn_modify">Modificar</button>
                <button type="button" class="btn btn-success mb-3" id="scn_clone">Clonar</button>
                <button type="button" class="btn btn-danger mb-3" id="scn_delete_conf">Eliminar</button>
            </div>
            </button>
        </div>

    <?php endif;
}
?>