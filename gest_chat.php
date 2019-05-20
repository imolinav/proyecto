<?php
require_once "metodos.php";
if (isset($_POST['user'])) {
    $stmt_msgs = $conexion->prepare("SELECT * FROM mensaje WHERE de = :email OR para = :email ORDER BY fecha");
    $parameters = [':email' => $_POST['user']];
    $stmt_msgs->execute($parameters);
    $mensajes = $stmt_msgs->fetchAll(PDO::FETCH_ASSOC);

    if ($usuario->getAdmin() == 1) {
        $stmt_update = $conexion->prepare("UPDATE mensaje SET leido = 1 WHERE de = :email AND leido = 0");
    } else {
        $stmt_update = $conexion->prepare("UPDATE mensaje SET leido = 1 WHERE para = :email AND leido = 0");
    }
    $stmt_update->execute($parameters);


    if (!empty($mensajes)): ?>
        <table>
            <?php foreach ($mensajes as $mensaje) : ?>
                <tr>
                    <?php if ($mensaje['de'] == $_POST['user']) {
                        if ($usuario->getAdmin() == 1):?>
                            <td class="msg_user"><?= $mensaje['texto'] ?></td>
                        <?php else: ?>
                            <td class="msg_user"><?= $mensaje['texto'] ?></td>
                        <?php endif;
                    } else {
                        if ($usuario->getAdmin() == 1):?>
                            <td class="msg_admin"><?= $mensaje['texto'] ?></td>
                        <?php else: ?>
                            <td class="msg_admin"><?= $mensaje['texto'] ?></td>
                        <?php endif;
                    } ?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif;

} else if (isset($_POST['new_msg'])) {
    $datos = json_decode($_POST['new_msg'], true);
    if (isset($datos['mensaje'])) {
        $stmt_insert = $conexion->prepare("INSERT INTO mensaje (texto, de, para, fecha, leido) VALUES (:texto, :usuario, 'admin@smartliving.es', CURRENT_TIME, 0)");
        $mens = $datos['mensaje'];
    } else {
        $stmt_insert = $conexion->prepare("INSERT INTO mensaje (texto, de, para, fecha, leido) VALUES (:texto,'admin@smartliving.es', :usuario,  CURRENT_TIME , 0)");
        $mens = $datos['mensaje_admin'];
    }
    $parameters_insert = [':texto' => $mens, ':usuario' => $datos['usuario']];
    $stmt_insert->execute($parameters_insert);
    if (isset($datos['mensaje_admin'])):?>
        <tr>
            <td class="msg_admin"><?= $datos['mensaje_admin'] ?></td>
        </tr>
    <?php else: ?>
        <tr>
            <td class="msg_user"><?= $datos['mensaje'] ?></td>
        </tr>
    <?php endif;
} else if (isset($_POST['filter'])) {
    $string = $_POST['filter'];
    $stmt = $conexion->prepare("select U.nombre as usuario, U.email as email, U.foto as foto from usuario U, mensaje M where U.email = M.de and U.email<>'admin@smartliving.es' AND (U.nombre LIKE :cadena OR U.email LIKE :cadena) group by U.nombre order by M.fecha desc");

    $parameters = [':cadena' => '%' . $string . '%'];
    $stmt->execute($parameters);
    $usuarios_chat = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($usuarios_chat)) : ?>
        <div>
            <?php foreach ($usuarios_chat as $users) {
                $cont = 0;
                if (!empty($mensajes_nl)) {
                    foreach ($mensajes_nl as $mensaje_nl) {
                        if ($mensaje_nl['de'] == $users['email']) {
                            $cont++;
                        }
                    }
                }
                if ($cont > 0):?>
                    <div class="new_msg_admin"><?= $cont ?></div>
                <?php endif; ?>
                <p class="user_chat"><img src="<?= $users['foto'] ?>"><?= $users['usuario'] ?>
                </p>
                <input type="hidden" value="<?= $users['email'] ?>">
            <?php } ?>
        </div>
    <?php else : ?>
        <div><p id="texto_error" class="error_chat">Usuarios no encontrados</p></div>
    <?php endif;
}
?>