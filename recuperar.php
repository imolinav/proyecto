<?php
//session_start();
require_once "metodos.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
if(isset($_POST['email_chg'])) {
    $stmt_email = $conexion->prepare("SELECT * FROM usuario WHERE email = :email");
    $parameters_email = [':email' => $_POST['email_chg']];
    $stmt_email->execute($parameters_email);
    $usuario_ex = $stmt_email->fetch(PDO::FETCH_ASSOC);
    if (!empty($usuario_ex)) {
        try {
            $id = bin2hex(random_bytes(16));
            $token = bin2hex(random_bytes(16));
        } catch (\Exception $e) {
            try {
                $id = bin2hex(openssl_random_pseudo_bytes(16));
                $token = bin2hex(openssl_random_pseudo_bytes(16));
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        $email = $_POST['email_chg'];
        $direccion = "localhost/proyecto/rec_pass.php?id=" . $id . "&token=" . $token;
        $mensaje = "Para recuperar su contraseña acceda al siguiente link: " . $direccion;
        $email = new PHPMailer(TRUE);
        try {
            $email->setFrom('soporte@smartliving.com', 'Smart Living');
            $email->addAddress($_POST['email_chg'], $_POST['email_chg']);
            $email->Subject = '[SMART LIVING] - Recuperacion de contrasenya';
            $email->Body = $mensaje;
            $email->isSMTP();
            $email->Host = 'smtp.gmail.com';
            $email->SMTPAuth = TRUE;
            $email->SMTPSecure = 'tls';
            $email->Username = 'iamovaz@gmail.com';
            $email->Password = 'pdkfmhtdrmzhgkom';
            $email->Port = 587;
            if ($email->send()) {
                $_SESSION['recuperacion'] = "bien";
            } else {
                $_SESSION['recuperacion'] = "mal";
            }
        } catch (Exception $e) {
            echo $e->errorMessage();
        }
        $stmt_rec_sch = $conexion->prepare("SELECT * FROM pswd_rec WHERE usuario_email = :email");
        $parameters_rec_sch = [':email' => $_POST['email_chg']];
        $stmt_rec_sch->execute($parameters_rec_sch);
        $existe = $stmt_rec_sch->fetch(PDO::FETCH_ASSOC);
        if (!empty($existe)) {
            $stmt_del = $conexion->prepare("DELETE FROM pswd_rec WHERE usuario_email = :email");
            $parameters_del = [':email' => $_POST['email_chg']];
            $stmt_del->execute($parameters_del);
        }
        $stmt_rec = $conexion->prepare("INSERT INTO pswd_rec VALUES (:usuario, :id, :token, :tiempo)");
        $parameters_rec = [':usuario' => $_POST['email_chg'], ':id' => $id, ':token' => hash('md5', $token), ':tiempo' => time()];
        $stmt_rec->execute($parameters_rec);
        header("Location: index.php");
    } else {
        header("Location: recuperar.php?error=1");
    }
}

include "views/partials/header.part.php";
include "views/recuperar.view.phtml";
include "chat.php";
include "views/partials/footer.part.php";
?>