<?php
require_once "metodos.php";
if (isset($_GET['id']) && isset($_GET['token'])) {
    $id = $_GET['id'];
    $token = $_GET['token'];

    $stmt_reco = $conexion->prepare("SELECT * FROM pswd_rec WHERE id = :id");
    $parameters_reco = [':id' => $id];
    $stmt_reco->execute($parameters_reco);
    $datos_rec = $stmt_reco->fetch(PDO::FETCH_ASSOC);
    $ok = true;
    if (((time() - $datos_rec['time']) > 86400) || hash('md5', $token) !== $datos_rec['token']) {
        $ok = false;
    }
} else {
    $ok = false;
}

require_once "views/partials/header.part.php";
require_once "chat.php";
require_once "views/rec_pass.view.phtml";
require_once "views/partials/footer.part.php";
?>