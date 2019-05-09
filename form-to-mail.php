<?php
require "metodos.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if(isset($_POST['email_contacto'])) {
    $opcion = $_POST['opciones_contacto'];
    if ($opcion == 'otra') {
        $opcion = $_POST['opcion_contacto'];
    }
    $mensaje = "
    De: ".$_POST['nombre_contacto']."
    Email: ".$_POST['email_contacto']."
    Razón de contacto: ".$opcion."
    ----------------------------------------------------
    ".$_POST['mensaje_contacto'];

    $email = new PHPMailer(TRUE);

    try {
        $email->setFrom($_POST['email_contacto'], $_POST['nombre_contacto']);
        $email->addAddress('iamovaz@gmail.com', 'Ian Molina');
        $email->Subject = '[SMART LIVING] - '.$opcion;
        $email->Body = $mensaje;

        $email->isSMTP();
        $email->Host = 'smtp.gmail.com';
        $email->SMTPAuth = TRUE;
        $email->SMTPSecure = 'tls';
        $email->Username = 'iamovaz@gmail.com';
        $email->Password = 'pdkfmhtdrmzhgkom';
        $email->Port = 587;

        if($email->send()) {
            $_SESSION['contacto'] = "bien";
        } else {
            $_SESSION['contacto'] = "mal";
        }

    } catch (Exception $e) {
        echo $e->errorMessage();
    }

    header("Location: contacto.php");
}
?>