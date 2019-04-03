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
    <div class="col-4 offset-2 pl-5" id="datos_disp">
        <?php if($dispositivo['encendido']==1):?>
        <img src="imgs/on.png" height="100px" class="mb-3" id="controlador">
        <?php else:?>
        <img src="imgs/off.png" height="100px" class="mb-3" id="controlador">
            <?php endif; ?>
        <p>Habitacion: <?= $dispositivo['habitacion'] ?></p>
        <p>Dispositivo: <?= $dispositivo['nombre'] ?></p>
        <p>Veces encendido: <?= $dispositivo['num_encendidos'] ?></p>
        <p>Tiempo encendido: <?= $dispositivo['tiempo_encendido'] ?></p>

        <?php if(!empty($programas)):?>
        <hr>
        <p>Programas activos: </p>
        <?php endif; ?>

        <hr>
        <p>Inicio: </p>
        <input type="date" class="form-control mb-2" name="prg_date_start">
        <input type="time" class="form-control mb-2" name="prg_hour_start">
        <p>Fin: </p>
        <input type="date" class="form-control mb-2" name="prg_date_end">
        <input type="time" class="form-control mb-2" name="prg_hour_end">
        <?php if($dispositivo['temperatura']!=null):?>
        <p>Temperatura: </p>
        <input type="number" class="form-control mb-2" name="prg_temp" value="0" min="0" />
        <?php endif; ?>
        <input type="button" class="btn btn-primary" value="Programar dispositivo">
    </div>
    <?php endif;
} else if(isset($_POST['profile'])) {
    if (!empty($dispositivo)) : ?>

    <?php endif;
}
?>