<?php
//session_start();
require_once "metodos.php";

//Busqueda de usuarios que se han puesto en contacto con el administrador

$stmt_usuarios = $conexion->prepare("select U.nombre as usuario, U.email as email from usuario U, mensaje M where U.email = M.de and U.email<>'admin@smartliving.es' group by U.nombre");
$stmt_usuarios->execute();
$usuarios_chat = $stmt_usuarios->fetchAll(PDO::FETCH_ASSOC);
include "views/partials/header.part.php";
include "views/cpanel.view.phtml";
include "views/partials/footer.part.php";

//Registro de usuarios

if(isset($_POST['su_name'])) {
    $stmt_recoger = $conexion->prepare("SELECT puerto FROM usuario");
    $stmt_recoger->execute();
    $puertos = $stmt_recoger->fetchAll(PDO::FETCH_ASSOC);
    do {
        $puerto = rand(0, 65535);
    } while(array_search($puerto, $puertos)!=false);
    $stmt = $conexion->prepare("INSERT INTO usuario VALUES (:email, :dni, :nombre, './imgs/generic.png', :pass, 0, :puerto, 0)");
    $parameters = [':email'=>$_POST['su_email'], ':dni'=>$_POST['su_dni'], ':nombre'=>$_POST['su_name'], ':pass'=>password_hash($_POST['su_pass'], PASSWORD_DEFAULT, ['cost'=>10]), ':puerto'=>$puerto];
    $stmt->execute($parameters);
    $dispositivos = $_POST['su_disp_name'];
    for($i=0; $i<$_POST['su_hab_num']; $i++) {
        for($j=0; $j<$_POST['su_hab_cant_disp'][$i]; $j++) {
            $dispositivo = array_shift($dispositivos);
            $stmt_disp = $conexion->prepare("INSERT INTO dispositivo (nombre, habitacion, encendido, num_encendidos, tiempo_encendido, temperatura, usuario_email) VALUES (:nombre, :habitacion, 0, 0, 0, :temperatura, :usuario)");
            if($_POST['su_disp_temp'][$i]=="si") {
                $parameters_disp = [':nombre'=>$dispositivo, ':habitacion'=>$_POST['su_hab_name'][$i], ':temperatura'=>0,':usuario'=>$_POST['su_email']];
            } else {
                $parameters_disp = [':nombre'=>$dispositivo, ':habitacion'=>$_POST['su_hab_name'][$i], ':temperatura'=>null, ':usuario'=>$_POST['su_email']];
            }
            $stmt_disp->execute($parameters_disp);
        }
    }
} if(isset($_POST['new_su_hab_num'])) {
    $dispositivos = $_POST['new_su_disp_name'];
    for($i=0; $i<$_POST['new_su_hab_num'];$i++) {
        for($j=0; $j<$_POST['new_su_hab_cant_disp'][$i]; $j++) {
            $dispositivo = array_shift($dispositivos);
            $stmt_disp = $conexion->prepare("INSERT INTO dispositivo (nombre, habitacion, encendido, num_encendidos, tiempo_encendido, temperatura, usuario_email) VALUES (:nombre, :habitacion, 0, 0, 0, :temperatura, :usuario)");
            if($_POST['new_su_disp_temp'][$i]=="si") {
                $parameters_disp = [':nombre'=>$dispositivo, ':habitacion'=>$_POST['new_su_hab_name'][$i], ':temperatura'=>0,':usuario'=>$_POST['user_mod_email']];
            } else {
                $parameters_disp = [':nombre'=>$dispositivo, ':habitacion'=>$_POST['new_su_hab_name'][$i], ':temperatura'=>null, ':usuario'=>$_POST['user_mod_email']];
            }
            $stmt_disp->execute($parameters_disp);
        }
    }
}
?>