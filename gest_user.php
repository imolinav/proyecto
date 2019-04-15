<?php
require_once "metodos.php";
if (isset($_POST['buscar'])) {
    $usuario = $_POST['buscar'];
    $stmt = $conexion->prepare("SELECT * FROM usuario WHERE email = '$usuario'");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user == false) :?>
<div class="row mt-3" id="user_data">
    <div class="col-6 offset-3"><h4 style="color: red; text-align: center">El usuario introducido no existe</h4></div>
</div>
<?php else: ?>
    <div id="modificar_usuario" style="display: none;">
        <div class="row mt-4" id="new_user_email">
            <div class="col-2 offset-1">
                Cambiar Email:
            </div>
            <div class="col-6">
                <input type="text" class="form-control" name="new_email" required>
                <div class="invalid-feedback">El campo email debe estar rellenado</div>
            </div>
            <div class="col-2">
                <input type="button" class="btn btn-primary" value="Cambiar" id="btn_new_email">
            </div>
        </div>

        <hr>

        <form action="cpanel.php" method="post" id="new_form_reg">
            <div class="row mt-4">
                <div class="col-6">
                    <p>Cantidad de habitaciones:</p>
                </div>
                <div class="col-6">
                    <input type="number" class="form-control" value="1" min="1" name="new_su_hab_num">
                </div>
            </div>
            <div class="new_habitaciones">
                <hr>
                <div class="row mt-4">
                    <div class="col-6">
                        <p>Habitación: </p>
                    </div>
                    <div class="col-6">
                        <p>Cantidad de dispositivos: </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="new_su_hab_name[]" required>
                        <div class="invalid-feedback">El nombre de la habitacion debe estar rellenado</div>
                    </div>
                    <div class="col-6">
                        <input type="number" class="form-control" value="1" min="1" name="new_su_hab_cant_disp[]">
                    </div>
                </div>
                <div class="new_dispositivos">
                    <div class="row mt-4">
                        <div class="col-6">
                            <p>Nombre del dispositivo: </p>
                        </div>
                        <div class="col-6">
                            <p>Pin: </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <input type="text" class="form-control" name="new_su_disp_name[]" required>
                            <div class="invalid-feedback">El nombre del dispositivo debe estar rellenado</div>
                        </div>
                        <div class="col-5">
                            <input type="text" class="form-control" name="new_su_disp_pin[]" required>
                            <div class="invalid-feedback">El pin del dispositivo debe estar rellenado</div>
                        </div>
                        <div class="col-1">
                            <input class="form-control" type="checkbox" value="si" name="new_su_disp_temp[]">
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <input type="hidden" name="user_mod_email" value="<?= $_POST['buscar'] ?>">
            <input type="button" class="btn btn-primary mt-2" value="Modificar usuario" name="new_submit_btn">

        </form>
    </div>

<?php endif;
}
?>