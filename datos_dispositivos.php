<?php
require_once "metodos.php";
if(isset($_POST['disp'])) {
    $parameters = [':id'=>$_POST['disp']];
} else {
    $parameters = [':id'=>$_POST['profile']];
}

$stmt_disp = $conexion->prepare("SELECT * FROM dispositivo WHERE id = :id");
$stmt_disp->execute($parameters);
$dispositivo = $stmt_disp->fetch(PDO::FETCH_ASSOC);

$stmt_prog = $conexion->prepare("SELECT * FROM programa WHERE dispositivo_id = :id");
$stmt_prog->execute($parameters);
$programas = $stmt_prog->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['disp'])) {
    if(!empty($dispositivo)):?>
    <div class="col-12 col-md-6 col-lg-5 col-xl-4 mt-5 mt-md-0 offset-lg-1 offset-xl-2 pl-5" id="datos_disp">
        <?php if($dispositivo['encendido']==1):?>
        <img src="imgs/on.png" height="100px" class="mb-5 hvr-grow" id="controlador">
        <?php else:?>
        <img src="imgs/off.png" height="100px" class="mb-5 hvr-grow" id="controlador">
            <?php endif; ?>
        <p><?= $i_disp_texto1 ?><?= $dispositivo['habitacion'] ?></p>
        <p><?= $i_disp_texto2 ?><?= $dispositivo['nombre'] ?></p>
        <p><?= $i_disp_texto3 ?><?= $dispositivo['num_encendidos'] ?></p>
        <p><?= $i_disp_texto4 ?><?= $dispositivo['tiempo_encendido'] ?></p>

        <hr>

        <?php if(!empty($programas)){
            foreach ($programas as $programa) {
                if($programa['dispositivo_id']==$dispositivo['id']):?>
                    <p><?= $i_disp_texto5 ?></p>
                    <p><?=$programa['inicio'] ?></p>
                    <p><?=$programa['fin'] ?></p>
                    <hr>
                <?php endif;
            }
        } ?>

        <p><?= $i_disp_texto6 ?></p>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i class="far fa-calendar-alt"></i></span>
            </div>
            <input type="date" class="form-control" name="prg_date_start">
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon2"><i class="far fa-clock"></i></span>
            </div>
            <input type="time" class="form-control" name="prg_hour_start" aria-describedby="basic-addon2">
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
        <?php if($dispositivo['temperatura']!=null):?>
        <p><?= $i_disp_texto8 ?></p>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i class="fas fa-thermometer-quarter"></i></span>
            </div>
            <input type="number" class="form-control" name="prg_temp" value="0" min="0" />
        </div>
        <?php endif; ?>
        <input type="button" class="btn btn-primary" value="<?= $i_disp_boton1 ?>" name="prg_enviar">
    </div>
    <?php endif;
} else if(isset($_POST['profile'])) {
    if (!empty($dispositivo)) : ?>

    <?php endif;
}
?>