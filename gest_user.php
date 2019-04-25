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
            <div class="row mt-2" id="new_user_email">
                <div class="col-12 col-md-6 offset-md-3">
                    <label><?= $i_guser_texto1 ?></label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-at"></i></span>
                        </div>
                        <br>
                        <input type="text" class="form-control" name="new_email" required>
                    </div>
                    <div class="invalid-feedback"><?= $i_guser_error2 ?></div>
                    <input type="button" class="btn btn-primary" value="<?= $i_guser_boton1 ?>" id="btn_new_email" data-toggle="modal" data-target="#updEmailModal">
                </div>
            </div>
            <hr>
            <div class="container">
                <div class="row mt-4">
                    <div class="col-12 col-md-6">
                        <p><?= $i_guser_texto2 ?></p>
                    </div>
                    <div class="col-12 col-md-6">
                        <input type="number" class="form-control" value="1" min="1" name="new_su_hab_num">
                    </div>
                </div>
                <div class="new_habitaciones">
                    <hr>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label><?= $i_guser_texto3 ?></label>
                            <input type="text" class="form-control" name="new_su_hab_name[]" required>
                            <div class="invalid-feedback"><?= $i_guser_error2 ?></div>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label><?= $i_guser_texto4 ?></label>
                            <input type="number" class="form-control" value="1" min="1" name="new_su_hab_cant_disp[]">
                        </div>
                    </div>
                    <div class="new_dispositivos">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label><?= $i_guser_texto5 ?></label>
                                <input type="text" class="form-control" name="new_su_disp_name[]" required>
                                <div class="invalid-feedback"><?= $i_guser_error2 ?></div>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label><?= $i_guser_texto6 ?></label>
                                <input type="text" class="form-control" name="new_su_disp_pin[]" required>
                                <div class="invalid-feedback"><?= $i_guser_error2 ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <div class="custom-control custom-checkbox my-1 mr-sm-2">
                                    <input class="custom-control-input" type="checkbox" id="customControlInline" value="si" name="new_su_disp_temp[]">
                                    <label class="custom-control-label" for="customControlInline"><?= $i_cpanel_form7 ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <input type="hidden" name="user_mod_email" value="<?= $_POST['buscar'] ?>">
            <input type="hidden" name="user_mod_option" value="">
            <input type="button" class="btn btn-primary mt-2" value="<?= $i_guser_boton2 ?>" name="new_submit_btn">
            <button type="button" class="btn btn-danger mt-2" name="new_delete_btn" data-toggle="modal" data-target="#deleteModal">Eliminar usuario</button>
        </form>

        <!--  MODAL CAMBIAR CORREO -->
        <div class="modal fade" id="updEmailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2"><?= $i_guser_texto7 ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body-new-email">
                        <p><?= $i_guser_texto8 ?><?= $user['email'] ?></p>
                        <p><?= $i_guser_texto10 ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $i_guser_texto11 ?></button>
                        <button type="button" class="btn btn-primary" onclick="modificarEmail()"><?= $i_guser_texto12 ?></button>
                    </div>
                </div>
            </div>
        </div>

        <!--  MODAL ELIMINAR USUARIO -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1"><?= $i_guser_texto7 ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><?= $i_guser_texto9 ?><?= $user['email'] ?>!</p>
                        <p><?= $i_guser_texto10 ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $i_guser_texto11 ?></button>
                        <button type="button" class="btn btn-primary" onclick="confirmarDelete()"><?= $i_guser_texto12 ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif;
}
?>