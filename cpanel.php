<?php
require_once "metodos.php";

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

    //Creamos el usuario
    $stmt = $conexion->prepare("INSERT INTO usuario VALUES (:email, :nombre, './imgs/generic.png', :pass, 0, :puerto, 0, :ip, :cp)");
    $parameters = [':email' => $_POST['su_email'], ':nombre' => $_POST['su_name'], ':pass' => password_hash($_POST['su_email'], PASSWORD_DEFAULT, ['cost' => 10]), ':puerto' => $puerto, ':ip' => $_POST['su_rbip'], ':cp' => $_POST['su_cp']];
    $stmt->execute($parameters);

    //Creamos un array de reles y uno de metodos donde meteremos los strings de texto pertinentes para crear el servidor .js
    $relays = [];
    $relays_metodos = [];
    $relay_num = 1;

    // TODO: ~ Modificar la creacion de scripts
    // Guardamos los dispositivos y los pins en variables para acceder a ellas de forma mas sencilla
    $dispositivos = $_POST['su_disp_name'];
    $pins = $_POST['su_disp_pin'];
    $tipos = $_POST['su_disp_type'];

    for ($i = 0; $i < $_POST['su_hab_num']; $i++) {
        for ($j = 0; $j < $_POST['su_hab_cant_disp'][$i]; $j++) {

            //Sacamos del array el primer dispositivo, el primer pin y el primer tipo
            $dispositivo = array_shift($dispositivos);
            $pin = array_shift($pins);
            $tipo = array_shift($tipos);

            addDispositivo($conexion, $dispositivo, $_POST['su_hab_name'][$i], $_POST['su_email'], $pin, $tipo);

            //Creacion de objetos GPIO (revisar el out)
            $relay_txt = "
            let RELAY" . $relay_num . " = new Gpio(" . $pin . ", 'out');
            let relayStatus" . $relay_num . " = 0;
            ";
            array_push($relays, $relay_txt);

            $metodos_txt = "
            socket.on('zona" . $relay_num . "', function(data) {
                relayStatus" . $relay_num . " = data;
                if(relayStatus" . $relay_num . " != RELAY" . $relay_num . ".readSync()) {
                    RELAY" . $relay_num . ".writeSync(relayStatus" . $relay_num . ");
                }
                registrar(logFile, 'Rele " . $relay_num . ": ' + relayStatus" . $relay_num . ");
                socket.disconnect(true);
                io.sockets.emit('relay" . $relay_num . "', data);
            });
            socket.on('relay" . $relay_num . "', function(data) {
                relayStatus" . $relay_num . " = data;
                if (relayStatus" . $relay_num . " != RELAY" . $relay_num . ".readSync()) {
                    RELAY" . $relay_num . ".writeSync(relayStatus" . $relay_num . ");
                    registrar(logFile,'Relé " . $relay_num . ": ' + relayStatus" . $relay_num . ");
                    io.sockets.emit('relay" . $relay_num . "', data);
                }
            });
            ";

            array_push($relays_metodos, $metodos_txt);

            $relay_num++;
        }
    }

    $path = 'users/' . $_POST['su_email'] . '/scripts';

    if (file_exists($path)) {
        $path = 'users/' . $_POST['su_email'] . date('Y-m-d H:i:s', time()) . '/scripts';
    }

    mkdir($path, 0777, true);

    $fichero = fopen('users/' . $_POST['su_email'] . '/webserver.js', 'w');

    $txt = "
    let Gpio = require('onoff').Gpio; 
    let logFile = 'users/" . $_POST['su_email'] . "/registro.log';
    let express = require('express');
    let app = express();
    let server = require('http').createServer(app);
    let io = require('socket.io')(server);
    let fs = require('fs');
    
    server.listen(" . $puerto . ");
    registrar(logFile, 'Servidor lanzado escuchando por el puerto " . $puerto . "');
    
    function registrar(file, text) {
        fs.appendFile(file, Date() + ' --> ' + text + '\\n', function(err) {
            if(err) throw err;
        });
        console.log(Date() + ' --> ' + text);
    }
    ";
    fwrite($fichero, $txt);

    foreach ($relays as $rel) {
        fwrite($fichero, $rel);
    }

    fwrite($fichero, "io.sockets.on('connection', function(socket) { socket.emit('connected', { ");
    for ($i = 1; $i <= count($relays); $i++) {
        if ($i < count($relays)) {
            fwrite($fichero, "led" . $i . "Status: relayStatus$i, ");
        } else {
            fwrite($fichero, "led" . $i . "Status: relayStatus$i });");
        }
    }

    foreach ($relays_metodos as $rel_met) {
        fwrite($fichero, $rel_met);
    }
    fwrite($fichero, "
    });
    process.on('SIGINT', function() {");
    for ($i = 1; $i <= count($relays); $i++) {
        fwrite($fichero, "RELAY" . $i . ".writeSync(0); RELAY" . $i . ".unexport();");
    }
    fwrite($fichero, "process.exit();});");
    fclose($fichero);

    //Creacion de scripts

    for ($i = 1; $i <= count($relays); $i++) {
        $fichero = fopen('users/' . $_POST['su_email'] . '/scripts/enciende' . $i . '.js', "w");
        $text = "
        #!/usr/bin/env node
        let io = require('../node_modules/socket.io-client');
        let socket = io.connect('" . $_POST['su_rbip'] . ":" . $puerto . "', {reconnect: true});
        socket.on('connect', function(socket) {
            console.log('Connected!');
        });
        socket.on('disconnect', function(socket) {
            console.log('Desconectando!');
        });
        socket.emit('zona" . $i . "', 1);
        ";
        fwrite($fichero, $text);
        fclose($fichero);

        $fichero = fopen('users/' . $_POST['su_email'] . '/scripts/apaga' . $i . '.js', "w");
        $text = "
        #!/usr/bin/env node
        var io = require('../node_modules/socket.io-client');
        var socket = io.connect('" . $_POST['su_rbip'] . ":" . $puerto . "', {reconnect: true});

        socket.on('connect', function (socket) {
            console.log('Connected!');
        });
        socket.on('disconnect', function (socket) {
            console.log('Desconectando!');
        });
        socket.emit('zona" . $i . "', 0);
        ";
        fwrite($fichero, $text);
        fclose($fichero);
    }
    $fichero = fopen('users/' . $_POST['su_email'] . '/scripts/enciende.js', 'w');
    $text = "
        #!/usr/bin/env node
        var io = require('../node_modules/socket.io-client');
        var socket = io.connect('" . $_POST['su_rbip'] . ":" . $puerto . "', {reconnect: true});

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

    $fichero = fopen('users/' . $_POST['su_email'] . '/scripts/apaga.js', 'w');
    $text = "
        #!/usr/bin/env node
        var io = require('../node_modules/socket.io-client');
        var socket = io.connect('" . $_POST['su_rbip'] . ":" . $puerto . "', {reconnect: true});

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

    header("Location: cpanel.php");
} //Modificacion de usuarios

else if (isset($_POST['user_mod_option'])) {
    $option = $_POST['user_mod_option'];
    $email = $_POST['user_mod_email'];
    if ($option == "delete") {
        $stmt_delete = $conexion->prepare("DELETE FROM usuario WHERE email = :email");
        $parameters = [':email' => $email];
        $stmt_delete->execute($parameters);
    } else if ($option == "add") {
        $dispositivos = $_POST['new_su_disp_name'];
        $pins = $_POST['new_su_disp_pin'];
        $tipos = $_POST['new_su_disp_type'];

        for ($i = 0; $i < $_POST['new_su_hab_num']; $i++) {
            for ($j = 0; $j < $_POST['new_su_hab_cant_disp'][$i]; $j++) {
                $dispositivo = array_shift($dispositivos);
                $pin = array_shift($pins);
                $tipo = array_shift($tipos);

                addDispositivo($conexion, $dispositivo, $_POST['new_su_hab_name'][$i], $_POST['user_mod_email'], $pin, $tipo);
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