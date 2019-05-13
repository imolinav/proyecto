let Gpio = require('onoff').Gpio;
let logFile = 'users/ppicapiedra@hotmail.com/registro.log';
let express = require('express');
let app = express();
let server = require('http').createServer(app);
let io = require('socket.io')(server);
let fs = require('fs');
let sensor = require('node-dht-sensor');
let lcdi2c = require('lcdi2c');

server.listen(4182);
registrar(logFile, 'Servidor lanzado escuchando por el puerto 4182');

function registrar(file, text) {
    fs.appendFile(file, Date() + ' --> ' + text + '\n', function (err) {
        if (err) throw err;
    });
    console.log(Date() + ' --> ' + text);
}



function readTemp() {

    let resultado = sensor.read(sensorType, ssensorPin);

    sendMessage('Temperature: ' + resultado.temperature.toFixed(2) + ' ºC', 'Humedad: ' + resultado.humidity.toFixed(2) + '%');       // toFixed(2) convierte un float en un string con dos decimales
    setTimeout(readTemp, 5000);
}

readTemp();

function sendMessage(message1, message2) {
    LCD.clear();
    LCD.println(message1, 1);
    LCD.println(message2, 2);
}

let LCD = new lcdi2c(2, 0x27, 16, 2);       // pin, canal del mensaje, columnas, filas

let RELAY2 = new Gpio(3, 'out');
let relayStatus2 = 0;

let sensorType = 11;        // tipo de sensor DHT
let ssensorPin = 4;         // pin en el que esta conectado
if (!sensor.initialize(sensorType, ssensorPin)) {
    console.warn('Error');
    process.exit(1);
}

let RELAY4 = new Gpio(5, 'out');
let relayStatus4 = 0;

let RELAY5 = new Gpio(6, 'out');
let relayStatus5 = 0;

let RELAY6 = new Gpio(7, 'out');
let relayStatus6 = 0;

io.sockets.on('connection', function (socket) {
    socket.emit('connected', {
        led1Status: relayStatus1,
        led2Status: relayStatus2,
        led3Status: relayStatus3,
        led4Status: relayStatus4,
        led5Status: relayStatus5,
        led6Status: relayStatus6
    });

    socket.on('zona1', function (data) {
        relayStatus1 = data;
        if (relayStatus1 !== RELAY1.readSync()) {
            RELAY1.writeSync(relayStatus1);
        }
        registrar(logFile, 'Rele 1: ' + relayStatus1);
        socket.disconnect(true);
        io.sockets.emit('relay1', data);
    });
    socket.on('relay1', function (data) {
        relayStatus1 = data;
        if (relayStatus1 !== RELAY1.readSync()) {
            RELAY1.writeSync(relayStatus1);
            registrar(logFile, 'Relé 1: ' + relayStatus1);
            io.sockets.emit('relay1', data);
        }
    });

    socket.on('zona2', function (data) {
        relayStatus2 = data;
        if (relayStatus2 !== RELAY2.readSync()) {
            RELAY2.writeSync(relayStatus2);
        }
        registrar(logFile, 'Rele 2: ' + relayStatus2);
        socket.disconnect(true);
        io.sockets.emit('relay2', data);
    });
    socket.on('relay2', function (data) {
        relayStatus2 = data;
        if (relayStatus2 !== RELAY2.readSync()) {
            RELAY2.writeSync(relayStatus2);
            registrar(logFile, 'Relé 2: ' + relayStatus2);
            io.sockets.emit('relay2', data);
        }
    });

    socket.on('zona3', function (data) {
        relayStatus3 = data;
        if (relayStatus3 !== RELAY3.readSync()) {
            RELAY3.writeSync(relayStatus3);
        }
        registrar(logFile, 'Rele 3: ' + relayStatus3);
        socket.disconnect(true);
        io.sockets.emit('relay3', data);
    });
    socket.on('relay3', function (data) {
        relayStatus3 = data;
        if (relayStatus3 !== RELAY3.readSync()) {
            RELAY3.writeSync(relayStatus3);
            registrar(logFile, 'Relé 3: ' + relayStatus3);
            io.sockets.emit('relay3', data);
        }
    });

    socket.on('zona4', function (data) {
        relayStatus4 = data;
        if (relayStatus4 !== RELAY4.readSync()) {
            RELAY4.writeSync(relayStatus4);
        }
        registrar(logFile, 'Rele 4: ' + relayStatus4);
        socket.disconnect(true);
        io.sockets.emit('relay4', data);
    });
    socket.on('relay4', function (data) {
        relayStatus4 = data;
        if (relayStatus4 !== RELAY4.readSync()) {
            RELAY4.writeSync(relayStatus4);
            registrar(logFile, 'Relé 4: ' + relayStatus4);
            io.sockets.emit('relay4', data);
        }
    });

    socket.on('zona5', function (data) {
        relayStatus5 = data;
        if (relayStatus5 !== RELAY5.readSync()) {
            RELAY5.writeSync(relayStatus5);
        }
        registrar(logFile, 'Rele 5: ' + relayStatus5);
        socket.disconnect(true);
        io.sockets.emit('relay5', data);
    });
    socket.on('relay5', function (data) {
        relayStatus5 = data;
        if (relayStatus5 !== RELAY5.readSync()) {
            RELAY5.writeSync(relayStatus5);
            registrar(logFile, 'Relé 5: ' + relayStatus5);
            io.sockets.emit('relay5', data);
        }
    });

    socket.on('zona6', function (data) {
        relayStatus6 = data;
        if (relayStatus6 !== RELAY6.readSync()) {
            RELAY6.writeSync(relayStatus6);
        }
        registrar(logFile, 'Rele 6: ' + relayStatus6);
        socket.disconnect(true);
        io.sockets.emit('relay6', data);
    });
    socket.on('relay6', function (data) {
        relayStatus6 = data;
        if (relayStatus6 !== RELAY6.readSync()) {
            RELAY6.writeSync(relayStatus6);
            registrar(logFile, 'Relé 6: ' + relayStatus6);
            io.sockets.emit('relay6', data);
        }
    });

});
process.on('SIGINT', function () {
    RELAY1.writeSync(0);
    RELAY1.unexport();
    RELAY2.writeSync(0);
    RELAY2.unexport();
    RELAY3.writeSync(0);
    RELAY3.unexport();
    RELAY4.writeSync(0);
    RELAY4.unexport();
    RELAY5.writeSync(0);
    RELAY5.unexport();
    RELAY6.writeSync(0);
    RELAY6.unexport();
    process.exit();
});