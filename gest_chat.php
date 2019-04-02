<?php
require_once "metodos.php";
if (isset($_POST['user'])) {
    $stmt_msgs = $conexion->prepare("SELECT * FROM mensaje WHERE de = :email OR para = :email ORDER BY fecha");
    $parameters = [':email'=>$_POST['user']];
    $stmt_msgs->execute($parameters);
    $mensajes = $stmt_msgs->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($mensajes)): ?>
        <table>
        <?php foreach($mensajes as $mensaje) : ?>
        <tr>
        <?php if ($mensaje['de'] == $_POST['user']) {
            if ($usuario->getAdmin() == 1):?>
                <td class="msg_user"><?= $mensaje['texto'] ?></td><td></td>
            <?php else: ?>
                <td></td><td class="msg_user"><?= $mensaje['texto'] ?></td>

                <?php endif;
        } else {
            if ($usuario->getAdmin() == 1):?>
                <td></td><td class="msg_admin"><?= $mensaje['texto'] ?></td>
            <?php else: ?>
                <td class="msg_admin"><?= $mensaje['texto'] ?></td><td></td>
            <?php endif;
        } ?>
        </tr>
            <?php endforeach;?>
        </table>
    <?php endif;

} else if (isset($_POST['new_msg'])) {
    $datos = json_decode($_POST['new_msg'], true);
    if (isset($datos['mensaje'])) {
        $stmt_insert = $conexion->prepare("INSERT INTO mensaje (texto, de, para, fecha) VALUES (:texto, :usuario, 'admin@smartliving.es', CURRENT_TIME )");
        $mens = $datos['mensaje'];
    } else {
        $stmt_insert = $conexion->prepare("INSERT INTO mensaje (texto, de, para, fecha) VALUES (:texto,'admin@smartliving.es', :usuario,  CURRENT_TIME )");
        $mens = $datos['mensaje_admin'];
    }
    $parameters_insert = [':texto'=>$mens, ':usuario'=>$datos['usuario']];
    $stmt_insert->execute($parameters_insert);
    if(isset($datos['mensaje_admin'])):?>
<td></td><td class="msg_admin"><?= $datos['mensaje_admin'] ?></td>
<?php endif;
}
?>