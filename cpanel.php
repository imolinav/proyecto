<?php
require_once "metodos.php";

//Registro de usuarios

if(isset($_POST['su_name'])) {

    //Asignamos puerto aleatorio no usado con anterioridad
    $stmt_recoger = $conexion->prepare("SELECT puerto FROM usuario");
    $stmt_recoger->execute();
    $puertos = $stmt_recoger->fetchAll(PDO::FETCH_ASSOC);
    do {
        $puerto = rand(0, 65535);
    } while(array_search($puerto, $puertos)!=false);

    //Creamos el usuario
    $stmt = $conexion->prepare("INSERT INTO usuario VALUES (:email, :nombre, './imgs/generic.png', :pass, 0, :puerto, 0, :ip, :cp)");
    $parameters = [':email'=>$_POST['su_email'], ':nombre'=>$_POST['su_name'], ':pass'=>password_hash($_POST['su_email'], PASSWORD_DEFAULT, ['cost'=>10]), ':puerto'=>$puerto, ':ip'=>$_POST['su_rbip'], ':cp'=>$_POST['su_cp']];
    $stmt->execute($parameters);

    //Creamos un array de reles y uno de metodos donde meteremos los strings de texto pertinentes para crear el servidor .js
    $relays = [];
    $relays_metodos = [];
    $relay_num = 1;

    //Guardamos los dispositivos y los pins en variables para acceder a ellas de forma mas sencilla
    $dispositivos = $_POST['su_disp_name'];
    $pins = $_POST['su_disp_pin'];

    for($i=0; $i<$_POST['su_hab_num']; $i++) {
        for($j=0; $j<$_POST['su_hab_cant_disp'][$i]; $j++) {

            //Sacamos del array el primer dispositivo y el primer pin
            $dispositivo = array_shift($dispositivos);
            $pin = array_shift($pins);

            $stmt_disp = $conexion->prepare("INSERT INTO dispositivo (nombre, habitacion, encendido, num_encendidos, tiempo_encendido, temperatura, usuario_email, pin) VALUES (:nombre, :habitacion, 0, 0, 0, :temperatura, :usuario, :pin)");
            if($_POST['su_disp_temp'][$i]=="si") {
                $parameters_disp = [':nombre'=>$dispositivo, ':habitacion'=>$_POST['su_hab_name'][$i], ':temperatura'=>0,':usuario'=>$_POST['su_email'], ':pin'=>$pin];
            } else {
                $parameters_disp = [':nombre'=>$dispositivo, ':habitacion'=>$_POST['su_hab_name'][$i], ':temperatura'=>null, ':usuario'=>$_POST['su_email'], ':pin'=>$pin];
            }
            $stmt_disp->execute($parameters_disp);

            //Creacion de objetos GPIO (revisar el out)
            $relay_txt = "
            let RELAY".$relay_num." = new Gpio(".$pin.", 'out');
            let relayStatus".$relay_num." = 0;
            ";
            array_push($relays, $relay_txt);

            $metodos_txt = "
            socket.on('zona".$relay_num."', function(data) {
                relayStatus".$relay_num." = data;
                if(relayStatus".$relay_num." != RELAY".$relay_num.".readSync()) {
                    RELAY".$relay_num.".writeSync(relayStatus".$relay_num.");
                }
                registrar(logFile, 'Rele ".$relay_num.": ' + relayStatus".$relay_num.");
                socket.disconnect(true);
                io.sockets.emit('relay".$relay_num."', data);
            });
            socket.on('relay".$relay_num."', function(data) {
                relayStatus".$relay_num." = data;
                if (relayStatus".$relay_num." != RELAY".$relay_num.".readSync()) {
                    RELAY".$relay_num.".writeSync(relayStatus".$relay_num.");
                    registrar(logFile,'Relé ".$relay_num.": ' + relayStatus".$relay_num.");
                    io.sockets.emit('relay".$relay_num."', data);
                }
            });
            ";

            array_push($relays_metodos, $metodos_txt);

            $relay_num++;
        }
    }

    $path = 'users/'.$_POST['su_email'].'/scripts';

    if(file_exists($path)) {
        $path = 'users/'.$_POST['su_email'].date('Y-m-d H:i:s', time()).'/scripts';
    }

    mkdir($path, 0777, true);

    $fichero = fopen('users/'.$_POST['su_email'].'/webserver.js', 'w');

    $txt = "
    let Gpio = require('onoff').Gpio; 
    let logFile = 'users/".$_POST['su_email']."/registro.log';
    let express = require('express');
    let app = express();
    let server = require('http').createServer(app);
    let io = require('socket.io')(server);
    let fs = require('fs');
    
    server.listen(8080);
    registrar(logFile, 'Servidor lanzado escuchando por el puerto 8080');
    
    function registrar(file, text) {
        fs.appendFile(file, Date() + ' --> ' + text + '\\n', function(err) {
            if(err) throw err;
        });
        console.log(Date() + ' --> ' + text);
    }
    ";
    fwrite($fichero, $txt);

    foreach($relays as $rel) {
        fwrite($fichero, $rel);
    }

    fwrite($fichero, "io.sockets.on('connection', function(socket) { socket.emit('connected', { ");
    for($i=1; $i<=count($relays); $i++) {
        if($i<count($relays)) {
            fwrite($fichero, "led".$i."Status: relayStatus$i, ");
        } else {
            fwrite($fichero, "led".$i."Status: relayStatus$i });");
        }
    }

    foreach($relays_metodos as $rel_met) {
        fwrite($fichero, $rel_met);
    }
    fwrite($fichero, "
    });
    process.on('SIGINT', function() {");
    for($i=1; $i<=count($relays); $i++) {
        fwrite($fichero, "RELAY".$i.".writeSync(0); RELAY".$i.".unexport();");
    }
    fwrite($fichero, "process.exit();});");
    fclose($fichero);

    //Creacion de scripts

    for($i=1; $i<=count($relays); $i++) {
        $fichero = fopen('users/'.$_POST['su_email'].'/scripts/enciende'.$i.'.js', "w");
        $text = "
        #!/usr/bin/env node
        let io = require('../node_modules/socket.io-client');
        let socket = io.connect('".$_POST['su_rbip'].":8080', {reconnect: true});
        socket.on('connect', function(socket) {
            console.log('Connected!');
        });
        socket.on('disconnect', function(socket) {
            console.log('Desconectando!');
        });
        socket.emit('zona".$i."', 1);
        ";
        fwrite($fichero, $text);
        fclose($fichero);

        $fichero = fopen('users/'.$_POST['su_email'].'/scripts/apaga'.$i.'.js', "w");
        $text = "
        #!/usr/bin/env node
        var io = require('../node_modules/socket.io-client');
        var socket = io.connect('".$_POST['su_rbip'].":8080', {reconnect: true});

        socket.on('connect', function (socket) {
            console.log('Connected!');
        });
        socket.on('disconnect', function (socket) {
            console.log('Desconectando!');
        });
        socket.emit('zona".$i."', 0);
        ";
        fwrite($fichero, $text);
        fclose($fichero);
    }
    $fichero = fopen('users/'.$_POST['su_email'].'/scripts/enciende.js', 'w');
    $text = "
        #!/usr/bin/env node
        var io = require('../node_modules/socket.io-client');
        var socket = io.connect('".$_POST['su_rbip'].":8080', {reconnect: true});

        socket.on('connect', function (socket) {
            console.log('Connected!');
        });
        socket.on('disconnect', function (socket) {
            console.log('Desconectando!');
        });
        socket.emit(process.argv[2], 1);
        ";
    //Revisar el argv[2]
    fwrite($fichero, $text);
    fclose($fichero);

    $fichero = fopen('users/'.$_POST['su_email'].'/scripts/apaga.js', 'w');
    $text = "
        #!/usr/bin/env node
        var io = require('../node_modules/socket.io-client');
        var socket = io.connect('".$_POST['su_rbip'].":8080', {reconnect: true});

        socket.on('connect', function (socket) {
            console.log('Connected!');
        });
        socket.on('disconnect', function (socket) {
            console.log('Desconectando!');
        });
        socket.emit(process.argv[2], 0);
        ";
    fwrite($fichero, $text);
    fclose($fichero);

}

//Modificacion de usuarios

else if(isset($_POST['user_mod_option'])) {
    $option = $_POST['user_mod_option'];
    $email = $_POST['user_mod_email'];
    if($option == "delete") {
        $stmt_delete = $conexion->prepare("DELETE FROM usuario WHERE email = '$email'");
        $stmt_delete->execute();
    } else if($option == "add") {
        $dispositivos = $_POST['new_su_disp_name'];
        for($i=0; $i<$_POST['new_su_hab_num'];$i++) {
            for($j=0; $j<$_POST['new_su_hab_cant_disp'][$i]; $j++) {
                $dispositivo = array_shift($dispositivos);
                $stmt_disp = $conexion->prepare("INSERT INTO dispositivo (nombre, habitacion, encendido, num_encendidos, tiempo_encendido, temperatura, usuario_email, pin) VALUES (:nombre, :habitacion, 0, 0, 0, :temperatura, :usuario, :pin)");
                if($_POST['new_su_disp_temp'][$j]=="si") {
                    $parameters_disp = [':nombre'=>$dispositivo, ':habitacion'=>$_POST['new_su_hab_name'][$i], ':temperatura'=>0,':usuario'=>$_POST['user_mod_email'], ':pin'=>$_POST['new_su_disp_pin'][$j]];
                } else {
                    $parameters_disp = [':nombre'=>$dispositivo, ':habitacion'=>$_POST['new_su_hab_name'][$i], ':temperatura'=>null, ':usuario'=>$_POST['user_mod_email'], ':pin'=>$_POST['new_su_disp_pin'][$j]];
                }
                $stmt_disp->execute($parameters_disp);
            }
        }
    } else if($option == "update") {
        $new_email = $_POST['new_email'];
        $stmt_update = $conexion->prepare("UPDATE usuario SET email = '$new_email' WHERE email = '$email'");
        $stmt_update->execute($parameters);
    }
}

//Busqueda de usuarios que se han puesto en contacto con el administrador

$stmt_usuarios = $conexion->prepare("select U.nombre as usuario, U.email as email, U.foto as foto from usuario U, mensaje M where U.email = M.de and U.email<>'admin@smartliving.es' group by U.nombre");
$stmt_usuarios->execute();
$usuarios_chat = $stmt_usuarios->fetchAll(PDO::FETCH_ASSOC);

$stmt_users = $conexion->prepare("SELECT * FROM usuario WHERE email<>'admin@smartliving.es'");
$stmt_users->execute();
$usuarios = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

include "views/partials/header.part.php";
include "views/cpanel.view.phtml";
include "views/partials/footer.part.php";
?>