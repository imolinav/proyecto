<?php
require "metodos.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if(isset($_POST['email_chg'])) {
    $id = "";
    $token = "";
    $email = $_POST['email_chg'];
    $direccion = "localhost/proyecto/rec_pass.php?id=".$id."&token=".$token;

    $mensaje = "Para recuperar su contraseña acceda al siguiente link: ".$direccion;

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

        if($email->send()) {
            $_SESSION['recuperacion'] = "bien";
        } else {
            $_SESSION['recuperacion'] = "mal";
        }

    } catch (Exception $e) {
        echo $e->errorMessage();
    }

    header("Location: index.php");

}
?>