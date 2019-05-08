<?php
if(isset($_POST['email_contacto'])) {
    $opcion = $_POST['opciones_contacto'];
    if ($opcion == 'otra') {
        $opcion = $_POST['opcion_contacto'];
    }

    $mensaje = "";

    $mensaje .= 'De: '.$_POST['nombre_contacto'].'
    Email: '.$_POST['email_contacto'].'
    Razón de contacto: '.$opcion. "
    ---------------------------------
    ".$_POST['mensaje_contacto'];

    mail('ianmolinav@hotmail.com', $opcion, $mensaje);

    header('Location: index.php');
}
?>