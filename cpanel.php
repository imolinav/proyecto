<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require_once "metodos.php";
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$stmt = $conexion->prepare("SELECT P.poblacion, C.codigopostalid FROM poblacion P, codigopostal C WHERE P.id = C.poblacionid");

//Registro de usuarios

if (isset($_POST['su_name'])) {

    //Asignamos puerto aleatorio no usado con anterioridad
    $stmt_recoger = $conexion->prepare("SELECT puerto FROM usuario");
    $stmt_recoger->execute();
    $puertos = $stmt_recoger->fetchAll(PDO::FETCH_ASSOC);
    do {
        $puerto = rand(0, 65535);
    } while (array_search($puerto, $puertos) != false);

    // Creamos una contraseña aleatoria para el usuario
    try {
        $rand_pass = bin2hex(random_bytes(16));
    } catch (\Exception $e) {
        try {
            $rand_pass = bin2hex(openssl_random_pseudo_bytes(16));
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    // Mandamos un correo al usuario con la contraseña generada. Esta se cambiara una vez inicie sesion por primera vez
    $mensaje = "<h2>Bienvenido a SmartLiving!</h2>";
    $mensaje .= "<p>Enseguida podra empezar a controlar su casa desde cualquier dispositivo.</p>";
    $mensaje .= "<p>Para empezar inicie sesion con su correo y la siguiente contrasenya: </p>";
    $mensaje .= "<b>".$rand_pass."</b>";
    $mensaje .= "<p>Para cualquier duda no dude en ponerse en contacto</p>";
    $mensaje .= "<i>iamovaz@gmail.com</i>";

    $mensajeAlt = "Acceda a su cuenta con la siguiente contrasenya: ".$rand_pass." Una vez iniciada sesión se le pedira que la cambie.";
    $email = new PHPMailer(TRUE);
    try {
        $email->setFrom('soporte@smartliving.com', 'Smart Living');
        $email->addAddress($_POST['su_email'], $_POST['su_email']);
        $email->Subject = '[SMART LIVING] - Gracias por registrarse';
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
            $_SESSION['registrado'] = "bien";
        } else {
            $_SESSION['registrado'] = "mal";
        }
    } catch (Exception $e) {
        echo $e->errorMessage();
    }

    // Creamos el usuario
    $stmt = $conexion->prepare("INSERT INTO usuario VALUES (:email, :nombre, './imgs/generic.png', :pass, 0, :puerto, 0, :ip, :cp)");
    $parameters = [':email' => $_POST['su_email'], ':nombre' => $_POST['su_name'], ':pass' => password_hash($rand_pass, PASSWORD_DEFAULT, ['cost' => 10]), ':puerto' => $puerto, ':ip' => $_POST['su_rbip'], ':cp' => $_POST['su_cp']];
    $stmt->execute($parameters);

    //Creamos un array de reles donde meteremos los strings de texto pertinentes para crear el servidor .js
    $relays = [];
    $relay_num = 1;

    // Guardamos los dispositivos y los pins en variables para acceder a ellas de forma mas sencilla
    $dispositivos = $_POST['su_disp_name'];
    $pin = 2;
    $tipos = $_POST['su_disp_type'];

    for ($i = 0; $i < $_POST['su_hab_num']; $i++) {
        for ($j = 0; $j < $_POST['su_hab_cant_disp'][$i]; $j++) {

            //Sacamos del array el primer dispositivo y el primer tipo
            $dispositivo = array_shift($dispositivos);
            $tipo = array_shift($tipos);

            addDispositivo($conexion, $dispositivo, $_POST['su_hab_name'][$i], $_POST['su_email'], $pin, $tipo);

            //Creacion de objetos GPIO
            switch($tipo) {
                case 1:
                    $relay_txt = "
                    var RELAY" . $relay_num . " = new Gpio(" . $pin . ", 'out');
                    var relayStatus" . $relay_num . " = 0;
                    relays.push(RELAY".$relay_num.");
                    pins.push(".$pin.");
                    status.push(0);
                    ";
                    break;
                case 2:
                    $relay_txt = "
                    var sensor".$relay_num." = require('node-dht-sensor');
                    ";
                    break;
                case 3:
                    $relay_txt = "
                    var infrared" . $relay_num . " = new Gpio(" . $pin . ", 'out');
                    var relayStatus" . $relay_num . " = 0;
                    ";
                    break;
                case 4:
                    $relay_txt = "
                    var lcd".$relay_num."= new LCD(".$pin.", 0x27, 20, 2);
                    ";
                    break;
            }
            array_push($relays, $relay_txt);

            $relay_num++;
            $pin++;
        }
    }

    $path = 'users/' . $_POST['su_email'] . '/scripts';

    if (file_exists($path)) {
        $path = 'users/' . $_POST['su_email'] . date('Y-m-d H:i:s', time()) . '/scripts';
    }

    mkdir($path, 0777, true);

    $fichero = fopen('users/' . $_POST['su_email'] . '/webserver.js', 'w');

    $txt = "
    var Gpio = require('onoff').Gpio;
    var express = require('express');
    var app = express();
    var server = require('http').createServer(app);
    var io = require('socket.io')(server);
    var CronJob = require('cron').CronJob;
    var LCD = require('lcdi2c');
    
    let relays = [], pins = [], status = [];
    
    server.listen(" . $puerto . ");
    ";
    fwrite($fichero, $txt);

    foreach ($relays as $rel) {
        fwrite($fichero, $rel);
    }

    $cuerpo = "
    
    function leerTemp(pin) {
        sensor.read(pin, function(err, temperature) {
            if(!err) {
                return temperature;
            }
        })
    }
    
    function leerHumedad(pin) {
        sensor.read(pin, function(err, humidity) {
            if(!err) {
                return humidity;
            }
        })
    }
    
    setInterval(function() {
        for(let i = 0; i<status.length; i++) {
            if(status[i]!==relays[i].readSync()) {
                status[i] = relays[i].readSync;
                socket.emit('actualizar',{
                    disp: pins[i],
                    status: status[i]
                })
            }
        }
    }, 10000);
    
    io.sockets.on('connection', function (socket) {
    
        socket.emit('connected', status);
    
        // Funcion para cuando se activa algun dispositivo desde la web
        socket.on('activar', function(data) {
            // data => disp, action, date, temp, repeats, weekly
            for (let i=0; i<pins.length; i++) {
                if(data.disp === pins[i]) {
                    status[i] = data.action;
                    if(status[i]!=relays[i].readSync()) {
                        relays[i].writeSync(status[i]);
                        if(data.temp!==null) {
                            let interval = '';
                            if(data.action === 1) {
                                interval = setInterval(function() {
                                    if(data.temp + 5 >= leerTemp()) {
                                        relays[i].writeSync(0);
                                    } else if(data.temp - 5 <= leerTemp()) {
                                        relays[i].writeSync(1);
                                    }
                                }, 30000);
                            } else {
                                if(interval !== '') {
                                    clearInterval(interval);
                                }
                            }
                        }
                    }
                    break;
                }
            }
        });
    
        // Funcion para cuando se programa algun dispositivo desde la web
        socket.on('programar', function(data) {
            let date = data.date.split('-');
            let hour = data.hour.split(':');
            for(let i = 0; i<pins.length; i++) {
                if(data.disp === pins[i]) {
                    let reps = '';
                    for(let i = 0; i<data.repeats.length; i++) {
                        if(data.repeats.charAt(i)==='S') {
                            if(reps ==='') {
                                reps+=i;
                            } else {
                                reps += ','+i;
                            }
                        }
                    }
                    if(reps === '') {
                        reps = '*';
                    }
                    let data_cron = hour[2]+' '+hour[1]+' '+hour[0]+' '+date[2]+' '+date[1]+' '+reps;
                    new CronJob(data_cron, function () {
                        if(status[i]==1) {
                            relays[i].writeSync(0);
                            status[i]=0;
                        } else {
                            relays[i].writeSync(1);
                            status[i]=1;
                        }
                        socket.emit('actualizar', {
                            disp: pins[i],
                            status: status[i]
                        })
                    }, null, true, 'Europe/Madrid');
                    break;
                }
            }
        });
    });
    ";
    fwrite($fichero, $cuerpo);

    fwrite($fichero, "
    process.on('SIGINT', function() {");
    for ($i = 1; $i <= count($relays); $i++) {
        fwrite($fichero, "RELAY" . $i . ".writeSync(0); RELAY" . $i . ".unexport();");
    }
    fwrite($fichero, "
    lcd.off();
    process.exit();});");
    fclose($fichero);

    header("Location: cpanel.php");
}

// Modificacion de usuarios
else if (isset($_POST['user_mod_option'])) {
    $option = $_POST['user_mod_option'];
    $email = $_POST['user_mod_email'];
    if ($option == "delete") {
        $stmt_delete = $conexion->prepare("DELETE FROM usuario WHERE email = :email");
        $parameters = [':email' => $email];
        $stmt_delete->execute($parameters);
    } else if ($option == "add") {
        $dispositivos = $_POST['new_su_disp_name'];
        $stmt_pin = $conexion->prepare("SELECT MAX(pin) as pin FROM dispositivo WHERE usuario_email = :email");
        $parameters_pin = [':email'=>$email];
        $stmt_pin->execute($parameters_pin);
        $pin_max = $stmt_pin->fetch(PDO::FETCH_ASSOC);
        $pin = $pin_max['pin'] + 1;
        //$pins = $_POST['new_su_disp_pin'];
        $tipos = $_POST['new_su_disp_type'];

        for ($i = 0; $i < $_POST['new_su_hab_num']; $i++) {
            for ($j = 0; $j < $_POST['new_su_hab_cant_disp'][$i]; $j++) {
                $dispositivo = array_shift($dispositivos);
                //$pin = array_shift($pins);
                $tipo = array_shift($tipos);

                addDispositivo($conexion, $dispositivo, $_POST['new_su_hab_name'][$i], $_POST['user_mod_email'], $pin, $tipo);

                $pin++;
            }
        }
    } else if ($option == "update") {
        $new_email = $_POST['new_email'];
        $stmt_update = $conexion->prepare("UPDATE usuario SET email = :nemail WHERE email = :email");
        $parameters_update = [':nemail' => $new_email, ':email' => $email];
        $stmt_update->execute($parameters);
    }
    header("Location: cpanel.php");
}

// Busqueda de usuarios que se han puesto en contacto con el administrador

$stmt_usuarios = $conexion->prepare("select U.nombre as usuario, U.email as email, U.foto as foto from usuario U, mensaje M where U.email = M.de and U.email<>'admin@smartliving.es' group by U.nombre order by M.fecha desc");
$stmt_usuarios->execute();
$usuarios_chat = $stmt_usuarios->fetchAll(PDO::FETCH_ASSOC);

$stmt_users = $conexion->prepare("SELECT * FROM usuario WHERE email<>'admin@smartliving.es'");
$stmt_users->execute();
$usuarios = $stmt_users->fetchAll(PDO::FETCH_ASSOC);


include "views/partials/header.part.php";
include "views/cpanel.view.phtml";
include "views/partials/footer.part.php";
?>