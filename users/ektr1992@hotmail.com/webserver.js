
    let Gpio = require('onoff').Gpio; 
    let logFile = 'users/ektr1992@hotmail.com/registro.log';
    let express = require('express');
    let app = express();
    let server = require('http').createServer(app);
    let io = require('socket.io')(server);
    let fs = require('fs');
    
    server.listen(8080);
    registrar(logFile, 'Servidor lanzado escuchando por el puerto 8080');
    
    function registrar(file, text) {
        fs.appendFile(file, Date() + ' --> ' + text + '\n', function(err) {
            if(err) throw err;
        });
        console.log(Date() + ' --> ' + text);
    }
    
            let RELAY1 = new Gpio(23, 'out');
            let relayStatus1 = 0;
            
            let RELAY2 = new Gpio(24, 'out');
            let relayStatus2 = 0;
            
            let RELAY3 = new Gpio(25, 'out');
            let relayStatus3 = 0;
            
            let RELAY4 = new Gpio(26, 'out');
            let relayStatus4 = 0;
            io.sockets.on('connection', function(socket) { socket.emit('connected', { led1Status: relayStatus1, led2Status: relayStatus2, led3Status: relayStatus3, led4Status: relayStatus4 });
            socket.on('zona1', function(data) {
                relayStatus1 = data;
                if(relayStatus1 != RELAY1.readSync()) {
                    RELAY1.writeSync(relayStatus1);
                }
                registrar(logFile, 'Rele 1: ' + relayStatus1);
                socket.disconnect(true);
                io.sockets.emit('relay1', data);
            });
            socket.on('relay1', function(data) {
                relayStatus1 = data;
                if (relayStatus1 != RELAY1.readSync()) {
                    RELAY1.writeSync(relayStatus1);
                    registrar(logFile,'Relé 1: ' + relayStatus1);
                    io.sockets.emit('relay1', data);
                }
            });
            
            socket.on('zona2', function(data) {
                relayStatus2 = data;
                if(relayStatus2 != RELAY2.readSync()) {
                    RELAY2.writeSync(relayStatus2);
                }
                registrar(logFile, 'Rele 2: ' + relayStatus2);
                socket.disconnect(true);
                io.sockets.emit('relay2', data);
            });
            socket.on('relay2', function(data) {
                relayStatus2 = data;
                if (relayStatus2 != RELAY2.readSync()) {
                    RELAY2.writeSync(relayStatus2);
                    registrar(logFile,'Relé 2: ' + relayStatus2);
                    io.sockets.emit('relay2', data);
                }
            });
            
            socket.on('zona3', function(data) {
                relayStatus3 = data;
                if(relayStatus3 != RELAY3.readSync()) {
                    RELAY3.writeSync(relayStatus3);
                }
                registrar(logFile, 'Rele 3: ' + relayStatus3);
                socket.disconnect(true);
                io.sockets.emit('relay3', data);
            });
            socket.on('relay3', function(data) {
                relayStatus3 = data;
                if (relayStatus3 != RELAY3.readSync()) {
                    RELAY3.writeSync(relayStatus3);
                    registrar(logFile,'Relé 3: ' + relayStatus3);
                    io.sockets.emit('relay3', data);
                }
            });
            
            socket.on('zona4', function(data) {
                relayStatus4 = data;
                if(relayStatus4 != RELAY4.readSync()) {
                    RELAY4.writeSync(relayStatus4);
                }
                registrar(logFile, 'Rele 4: ' + relayStatus4);
                socket.disconnect(true);
                io.sockets.emit('relay4', data);
            });
            socket.on('relay4', function(data) {
                relayStatus4 = data;
                if (relayStatus4 != RELAY4.readSync()) {
                    RELAY4.writeSync(relayStatus4);
                    registrar(logFile,'Relé 4: ' + relayStatus4);
                    io.sockets.emit('relay4', data);
                }
            });
            
    });
    process.on('SIGINT', function() {RELAY1.writeSync(0); RELAY1.unexport();RELAY2.writeSync(0); RELAY2.unexport();RELAY3.writeSync(0); RELAY3.unexport();RELAY4.writeSync(0); RELAY4.unexport();process.exit();});