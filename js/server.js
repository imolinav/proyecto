let datos = {
    'user':'ianmolinav@hotmail.com'
    ,'dispositivos':{
        'Cocina':{
            'luces':23,
            'cortinas':24
        },
        'Habitacion':{
            'luces':25,
            'cortinas':26
        }
    }};
let Gpio = require('onoff').Gpio;
let cant_disp = datos.dispositivos.length;
let relays = [];
let relaysStatus = [];
for(let i=0; i<cant_disp; i++) {
    relays.push(new Gpio(datos.dispositivos[i], 'out'));
    relaysStatus.push(0);
}
let logFile = '../logs/'+datos.user+'/registro.log';

let express = require('express');
let app = express();
let server = require('http').createServer(app);
let io = require('socket.io')(server);
let fs = require('fs');
let puerto = 8080;
let nsp = io.of('/'+datos.user);

server.listen(puerto, function() {
    console.log('Escuchando en el puerto: ' + puerto);
});

nsp.on('connection', function(socket) {
    for(let i = 1; i<=relays.length; i++) {
        socket.on('releRB'+i, function (data) {
            if(data==1) {
                registrar(logFile, 'Rele nº'+i+' encendido'); //Añadir habitacion del rele y nombre
            } else {
                registrar(logFile, 'Rele nº'+i+' apagado');
            }
            registrar(logFile, 'Estado del rele '+i+': ' + relaysStatus[i]);
            relaysStatus[i] = data;
            if(relaysStatus[i] != relays[i].readSync()) {
                relays[i].writeSync(relaysStatus[i]);
                registrar(logFile, 'Estado del rele '+i+' modificado');
            }
            socket.disconnect(true);
            nsp.emit('relay'+i, data);
        });

        socket.on('relay'+i, function(data) {
            relaysStatus[i] = data;
            if(relaysStatus[i] != relays[i].readySync()) {
                relays[i].writeSync(relaysStatus[i]);
                registrar(logFile, 'Rele 2: ' + relaysStatus[i]);
                nsp.emit('relay'+i, data);
            }
        });
    }
});

function registrar(file, text) {
    fs.appendFile(file, Date() + ' -> ' + text + '\n', function(err) {
        if(err) throw(err);
    });
}

process.on('SIGINT', function() {
    for(let i = 0; i<relays.length; i++) {
        relays[i].writeSync(0);
        relays[i].unexport();
    }
    process.exit();
});


/*var Gpio = require('onoff').Gpio;
//Aqui van los relays
var RELAY1 = new Gpio(23, 'out');
var RELAY2 = new Gpio(24, 'out');
var relayStatus1 = 0;

var relayStatus2 = 0;
var logFile = '../registro.log'; //Añadir una carpeta por usuario

var express = require('express');
var app = express();
var server = require('http').createServer(app);
var io = require('socket.io')(server);
var fs = require('fs');
let puerto = 8080;
let nsp = io.of('/username');

server.listen(puerto);
registrar(logFile, 'Servidor iniciado en el puerto ' + puerto);

nsp.on('connection', function (socket) {
   registrar(logFile, 'Nueva conexion!! id: ' + socket.id + ' from: ' + socket.conn.remoteAdress);
   socket.emit('connected', { led1Status: relayStatus1, led2Status: relayStatus2});

   socket.on('releRB1', function (data) {
      if(data==1) {
          registrar(logFile, 'Rele nº1 encendido'); //Añadir habitacion del rele y nombre
      } else {
          registrar(logFile, 'Rele nº1 apagado');
      }
      registrar(logFile, 'Estado del rele 1: ' + relayStatus1);
      relayStatus1 = data;
      if(relayStatus1 != RELAY1.readSync()) {
          RELAY1.writeSync(relayStatus1);
          registrar(logFile, 'Estado del rele 1 modificado');
      }
      socket.disconnect(true);
       nsp.emit('relay1', data);
   });

    socket.on('releRB2', function (data) {
        if (data == 1) {
            registrar(logFile, 'Rele nº2 encendido'); //Añadir habitacion del rele y nombre
        } else {
            registrar(logFile, 'Rele nº2 apagado');
        }
        registrar(logFile, 'Estado del rele 2: ' + relayStatus2);
        relayStatus2 = data;
        if (relayStatus2 != RELAY2.readSync()) {
            RELAY2.writeSync(relayStatus2);
            registrar(logFile, 'Estado del rele 2 modificado');
        }
        socket.disconnect(true);
        nsp.emit('relay2', data);
    });

    socket.on('relay1', function(data) {
       relayStatus1 = data;
       if(relayStatus1 != RELAY1.readySync()) {
           RELAY1.writeSync(relayStatus1);
           registrar(logFile, 'Rele 1: ' + relayStatus1);
           nsp.emit('relay1', data);
       }
    });

    socket.on('relay2', function(data) {
        relayStatus2 = data;
        if(relayStatus2 != RELAY2.readySync()) {
            RELAY2.writeSync(relayStatus2);
            registrar(logFile, 'Rele 2: ' + relayStatus2);
            nsp.emit('relay2', data);
        }
    });

});

//MODIFICAR LOG
function registrar(file, text) {
    fs.appendFile(file, Date() + ' -> ' + text + '\n', function(err) {
        if(err) throw(err);
    });
}


//APAGAR TODOS LOS RELAYS
process.on('SIGINT', function() {
    RELAY1.writeSync(0);
    RELAY2.writeSync(0);
    RELAY1.unexport();
    RELAY2.unexport();
    process.exit();
});
*/