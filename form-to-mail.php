<?php
require "metodos.php";
redirect($_SERVER['REQUEST_METHOD']);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Creamos un email con informacion rellenada por el usuario y nos lo mandamos a nosotros mismos

if (isset($_POST['email_contacto'])) {
    $opcion = $_POST['opciones_contacto'];
    if ($opcion == 'otra') {
        $opcion = $_POST['opcion_contacto'];
    }
    $mensaje = "<p>De: ".$_POST['nombre_contacto']."</p><br>";
    $mensaje .= "<p>Email: ".$_POST['email_contacto']."</p><br>";
    $mensaje .= "<p>Razon de contacto: ".$opcion."</p><hr>";
    $mensaje .= "<p>".$_POST['mensaje_contacto']."</p>";

    $mensajeAlt = "De: " . $_POST['nombre_contacto'];
    $mensajeAlt .= "Email: " . $_POST['email_contacto'];
    $mensajeAlt .= "Razón de contacto: " . $opcion;
    $mensajeAlt .= "----------------------------------------------------";
    $mensajeAlt .= $_POST['mensaje_contacto'];

    $email = new PHPMailer(TRUE);

    try {
        $email->setFrom($_POST['email_contacto'], $_POST['nombre_contacto']);
        $email->addAddress('iamovaz@gmail.com', 'Ian Molina');
        $email->Subject = '[SMART LIVING] - ' . $opcion;
        $email->isHTML(true);
        $email->Body = $mensaje;
        $email->AltBody = $mensajeAlt;

        $email->isSMTP();
        $email->Host = 'smtp.gmail.com';
        $email->SMTPAuth = TRUE;
        $email->SMTPSecure = 'tls';
        $email->Username = 'iamovaz@gmail.com';
        $email->Password = 'pdkfmhtdrmzhgkom';
        $email->Port = 587;

        if ($email->send()) {
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