<?php
require_once "metodos.php";
redirect($_SERVER['REQUEST_METHOD']);

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

$disp_scn = $conexion->prepare("SELECT D.id, D.nombre, D.habitacion, P.dia_inicio, P.hora_inicio, P.dia_fin, P.hora_fin, P.temp_inicio, P.temp_fin, P.temperatura FROM dispositivo D, programa P, compuesta C, escena E WHERE D.id = P.dispositivo_id AND P.id = C.programa_id AND C.escena_id = E.id AND E.id = :id");
$disp_scn->execute($parameters);
$disp_escenas = $disp_scn->fetchAll(PDO::FETCH_ASSOC);

// Devolvemos los datos del dispositivo seleccionado
if (isset($_POST['disp'])) {
    if (!empty($dispositivo)):?>
        <div class="col-12 col-md-6 col-lg-5 col-xl-4 mt-5 mt-md-0 offset-lg-1 offset-xl-2 pl-5" id="datos_disp">
            <?php if ($dispositivo['encendido'] == 1): ?>
                <img src="imgs/on.png" height="100px" class="mb-5 hvr-grow" id="controlador">
            <?php else: ?>
                <img src="imgs/off.png" height="100px" class="mb-5 hvr-grow" id="controlador">
            <?php endif; ?>
            <?php if ($dispositivo['temperatura'] != null): ?>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-thermometer-quarter"></i></span>
                    </div>
                    <input type="number" class="form-control" name="disp_temp" placeholder="0" min="0"
                           value="<?= $dispositivo['temperatura'] ?>"/>
                    <div class="invalid-feedback"><?= $i_guser_error2 ?></div>
                </div>
                <button type="button" class="btn btn-primary mb-4" id="upd_temp_disp">Actualizar temperatura</button>
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
                                <button type="button" class="btn btn-danger" name="btn_del_prg"><i class="far fa-trash-alt"></i></button>
                                <p><?= $i_disp_texto5 ?></p>
                                <?php if (!is_null($programa['dia_inicio'])) : ?>
                                    <p>Dia: <?= $programa['dia_inicio'] ?></p>
                                <?php endif;
                                if (!is_null($programa['repetir_dias'])) : ?>
                                    <p>Dias: <?= $programa['repetir_dias'] ?></p>
                                <?php endif;
                                if (!is_null($programa['hora_inicio'])) : ?>
                                    <p>Hora inicio: <?= $programa['hora_inicio'] ?></p>
                                <?php endif;
                                if (!is_null($programa['hora_fin'])) : ?>
                                    <p>Hora fin: <?= $programa['hora_fin'] ?></p>
                                <?php endif;
                                if (!is_null($programa['temp_inicio'])) : ?>
                                    <p>Temperatura inicio: <?= $programa['temp_inicio'] ?></p>
                                <?php endif;
                                if (!is_null($programa['temp_fin'])) : ?>
                                    <p>Temperatura fin: <?= $programa['temp_fin'] ?></p>
                                <?php endif;
                                if (!is_null($programa['temperatura'])) : ?>
                                    <p>Temperatura: <?= $programa['temperatura'] ?></p>
                                <?php endif; ?>
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
                <input type="date" class="form-control" name="prg_date_start" min="<?= date('Y-m-d') ?>">
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
            <div class="row mt-4">
                <div class="container">
                    <div class="custom-control custom-checkbox my-1 mr-sm-2">
                        <input type="checkbox" class="custom-control-input" id="repeat1" name="prg_repeats[]"
                               value="si">
                        <label class="custom-control-label" for="repeat1">Lunes</label>
                    </div>
                    <div class="custom-control custom-checkbox my-1 mr-sm-2">
                        <input type="checkbox" class="custom-control-input" id="repeat2" name="prg_repeats[]"
                               value="si">
                        <label class="custom-control-label" for="repeat2">Martes</label>
                    </div>
                    <div class="custom-control custom-checkbox my-1 mr-sm-2">
                        <input type="checkbox" class="custom-control-input" id="repeat3" name="prg_repeats[]"
                               value="si">
                        <label class="custom-control-label" for="repeat3">Miércoles</label>
                    </div>
                    <div class="custom-control custom-checkbox my-1 mr-sm-2">
                        <input type="checkbox" class="custom-control-input" id="repeat4" name="prg_repeats[]"
                               value="si">
                        <label class="custom-control-label" for="repeat4">Jueves</label>
                    </div>
                    <div class="custom-control custom-checkbox my-1 mr-sm-2">
                        <input type="checkbox" class="custom-control-input" id="repeat5" name="prg_repeats[]"
                               value="si">
                        <label class="custom-control-label" for="repeat5">Viernes</label>
                    </div>
                    <div class="custom-control custom-checkbox my-1 mr-sm-2">
                        <input type="checkbox" class="custom-control-input" id="repeat6" name="prg_repeats[]"
                               value="si">
                        <label class="custom-control-label" for="repeat6">Sábado</label>
                    </div>
                    <div class="custom-control custom-checkbox my-1 mr-sm-2">
                        <input type="checkbox" class="custom-control-input" id="repeat7" name="prg_repeats[]"
                               value="si">
                        <label class="custom-control-label" for="repeat7">Domingo</label>
                    </div>
                    <hr>
                    <div class="custom-control custom-checkbox my-1 mr-sm-2 mb-3">
                        <input type="checkbox" class="custom-control-input" id="weekly" name="prg_weekly">
                        <label class="custom-control-label" for="weekly">Semanal</label>
                    </div>
                </div>
            </div>
            <input type="button" class="btn btn-primary" value="<?= $i_disp_boton1 ?>" name="prg_enviar">
        </div>
    <?php endif;

// Devolvemos los datos de la escena seleccionada
} else if (isset($_POST['scn'])) {
    if (!empty($escena)): ?>

        <div class="col-12 col-md-6 col-lg-5 col-xl-4 mt-5 mt-md-0 offset-lg-1 offset-xl-2 pl-5" id="datos_scn">
            <?php if ($escena['activa'] == 1): ?>
                <img src="imgs/on.png" height="100px" class="mb-5 hvr-grow" id="scn_apagar">
            <?php else: ?>
                <img src="imgs/off.png" height="100px" class="mb-5 hvr-grow" id="scn_apagar">
                <input type="date" name="reuse_scn_date" class="form-control mb-3" min="<?= date('Y-m-d') ?>">
                <button type="button" name="reuse_scn_btn" class="btn btn-primary mb-3" id="scn_update" disabled>
                    Actualizar escena
                </button>
            <?php endif;
            foreach ($disp_escenas as $disp) :?>
                <p><?= $disp['habitacion'] . " - " . $disp['nombre'] ?></p>
                <p>Dia inicio: <?= $disp['dia_inicio'] ?></p>
                <?php if (!is_null($disp['dia_fin'])) : ?>
                    <p>Dia fin: <?= $disp['dia_fin'] ?></p>
                <?php endif;
                if (!is_null($disp['hora_inicio'])) :?>
                    <p>Hora inicio: <?= $disp['hora_inicio'] ?></p>
                <?php endif;
                if (!is_null($disp['hora_fin'])) :?>
                    <p>Hora fin: <?= $disp['hora_fin'] ?></p>
                <?php endif;
                if (!is_null($disp['temp_inicio'])) :?>
                    <p>Temperatura inicio: <?= $disp['temp_inicio'] ?></p>
                <?php endif;
                if (!is_null($disp['temp_fin'])) :?>
                    <p>Temperatura fin: <?= $disp['temp_fin'] ?></p>
                <?php endif;
                if (!is_null($disp['temperatura'])) :?>
                    <p>Temperatura: <?= $disp['temperatura'] ?></p>
                <?php endif; ?>
                <hr>
            <?php endforeach; ?>
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