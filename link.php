<?php
require_once "metodos.php";
if(isset($_POST['email_chg'])) {
    $stmt = $conexion->prepare("SELECT email, pass FROM usuario WHERE email = :email");
    $parameters = [':email'=>$_POST['email_chg']];
    $stmt->execute($parameters);
    $usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!empty($usuario)) {
        $link="<a href='localhost/proyecto/reset.php?key=".$usuario[0]['email']."&reset=".$usuario[0]['password']."'>Click to reset password</a>";

    }

}
?>