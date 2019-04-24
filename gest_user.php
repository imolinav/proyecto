<?php
require_once "metodos.php";
if (isset($_POST['buscar'])) {
    $usuario = $_POST['buscar'];
    $stmt = $conexion->prepare("SELECT * FROM usuario WHERE email = '$usuario'");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user == false) :?>
<div class="row mt-3" id="user_data">
    <div class="col-6 offset-3"><h4 style="color: red; text-align: center"><?= $i_guser_error1 ?></h4></div>
</div>
<?php else: ?>
    <div id="modificar_usuario" style="display: none;">
        <form action="cpanel.php" method="post" id="new_form_reg">
            <div class="row mt-4" id="new_user_email">
                <div class="col-2 offset-1">
                    <?= $i_guser_texto1 ?>
                </div>
                <div class="col-6">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-at"></i></span>
                        </div>
                        <input type="text" class="form-control" name="new_email" required>
                    </div>
                    <div class="invalid-feedback"><?= $i_guser_error2 ?></div>
                </div>
                <div class="col-2">
                    <input type="button" class="btn btn-primary" value="<?= $i_guser_boton1 ?>" id="btn_new_email">
                </div>
            </div>
            <hr>
            <div class="row mt-4">
                <div class="col-6">
                    <p><?= $i_guser_texto2 ?></p>
                </div>
                <div class="col-6">
                    <input type="number" class="form-control" value="1" min="1" name="new_su_hab_num">
                </div>
            </div>
            <div class="new_habitaciones">
                <hr>
                <div class="row mt-4">
                    <div class="col-6">
                        <p><?= $i_guser_texto3 ?></p>
                    </div>
                    <div class="col-6">
                        <p><?= $i_guser_texto4 ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="new_su_hab_name[]" required>
                        <div class="invalid-feedback"><?= $i_guser_error2 ?></div>
                    </div>
                    <div class="col-6">
                        <input type="number" class="form-control" value="1" min="1" name="new_su_hab_cant_disp[]">
                    </div>
                </div>
                <div class="new_dispositivos">
                    <div class="row mt-4">
                        <div class="col-6">
                            <p><?= $i_guser_texto5 ?></p>
                        </div>
                        <div class="col-6">
                            <p><?= $i_guser_texto6 ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <input type="text" class="form-control" name="new_su_disp_name[]" required>
                            <div class="invalid-feedback"><?= $i_guser_error2 ?></div>
                        </div>
                        <div class="col-5">
                            <input type="text" class="form-control" name="new_su_disp_pin[]" required>
                            <div class="invalid-feedback"><?= $i_guser_error2 ?></div>
                        </div>
                        <div class="col-1">
                            <input class="form-control" type="checkbox" value="si" name="new_su_disp_temp[]">
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <input type="hidden" name="user_mod_email" value="<?= $_POST['buscar'] ?>">
            <input type="hidden" name="user_mod_option" value="">
            <input type="button" class="btn btn-primary mt-2" value="<?= $i_guser_boton2 ?>" name="new_submit_btn">
            <button type="button" class="btn btn-danger mt-2" name="new_delete_btn">Eliminar usuario</button>
        </form>
    </div>

<?php endif;
}
?>