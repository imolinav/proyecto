
    let Gpio = require('onoff').Gpio; 
    let logFile = 'users/mrajoy@hotmail.com/registro.log';
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
    
            let RELAY1 = new Gpio(20, 'out');
            let relayStatus1 = 0;
            
            let RELAY2 = new Gpio(21, 'out');
            let relayStatus2 = 0;
            
            let RELAY3 = new Gpio(22, 'out');
            let relayStatus3 = 0;
            
            let RELAY4 = new Gpio(23, 'out');
            let relayStatus4 = 0;
            
            let RELAY5 = new Gpio(24, 'out');
            let relayStatus5 = 0;
            
            let RELAY6 = new Gpio(25, 'out');
            let relayStatus6 = 0;
            
            let RELAY7 = new Gpio(26, 'out');
            let relayStatus7 = 0;
            
            let RELAY8 = new Gpio(27, 'out');
            let relayStatus8 = 0;
            
            let RELAY9 = new Gpio(28, 'out');
            let relayStatus9 = 0;
            
            let RELAY10 = new Gpio(29, 'out');
            let relayStatus10 = 0;
            
            let RELAY11 = new Gpio(30, 'out');
            let relayStatus11 = 0;
            
            let RELAY12 = new Gpio(31, 'out');
            let relayStatus12 = 0;
            io.sockets.on('connection', function(socket) { socket.emit('connected', { led1Status: relayStatus1, led2Status: relayStatus2, led3Status: relayStatus3, led4Status: relayStatus4, led5Status: relayStatus5, led6Status: relayStatus6, led7Status: relayStatus7, led8Status: relayStatus8, led9Status: relayStatus9, led10Status: relayStatus10, led11Status: relayStatus11, led12Status: relayStatus12 });
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
            
            socket.on('zona5', function(data) {
                relayStatus5 = data;
                if(relayStatus5 != RELAY5.readSync()) {
                    RELAY5.writeSync(relayStatus5);
                }
                registrar(logFile, 'Rele 5: ' + relayStatus5);
                socket.disconnect(true);
                io.sockets.emit('relay5', data);
            });
            socket.on('relay5', function(data) {
                relayStatus5 = data;
                if (relayStatus5 != RELAY5.readSync()) {
                    RELAY5.writeSync(relayStatus5);
                    registrar(logFile,'Relé 5: ' + relayStatus5);
                    io.sockets.emit('relay5', data);
                }
            });
            
            socket.on('zona6', function(data) {
                relayStatus6 = data;
                if(relayStatus6 != RELAY6.readSync()) {
                    RELAY6.writeSync(relayStatus6);
                }
                registrar(logFile, 'Rele 6: ' + relayStatus6);
                socket.disconnect(true);
                io.sockets.emit('relay6', data);
            });
            socket.on('relay6', function(data) {
                relayStatus6 = data;
                if (relayStatus6 != RELAY6.readSync()) {
                    RELAY6.writeSync(relayStatus6);
                    registrar(logFile,'Relé 6: ' + relayStatus6);
                    io.sockets.emit('relay6', data);
                }
            });
            
            socket.on('zona7', function(data) {
                relayStatus7 = data;
                if(relayStatus7 != RELAY7.readSync()) {
                    RELAY7.writeSync(relayStatus7);
                }
                registrar(logFile, 'Rele 7: ' + relayStatus7);
                socket.disconnect(true);
                io.sockets.emit('relay7', data);
            });
            socket.on('relay7', function(data) {
                relayStatus7 = data;
                if (relayStatus7 != RELAY7.readSync()) {
                    RELAY7.writeSync(relayStatus7);
                    registrar(logFile,'Relé 7: ' + relayStatus7);
                    io.sockets.emit('relay7', data);
                }
            });
            
            socket.on('zona8', function(data) {
                relayStatus8 = data;
                if(relayStatus8 != RELAY8.readSync()) {
                    RELAY8.writeSync(relayStatus8);
                }
                registrar(logFile, 'Rele 8: ' + relayStatus8);
                socket.disconnect(true);
                io.sockets.emit('relay8', data);
            });
            socket.on('relay8', function(data) {
                relayStatus8 = data;
                if (relayStatus8 != RELAY8.readSync()) {
                    RELAY8.writeSync(relayStatus8);
                    registrar(logFile,'Relé 8: ' + relayStatus8);
                    io.sockets.emit('relay8', data);
                }
            });
            
            socket.on('zona9', function(data) {
                relayStatus9 = data;
                if(relayStatus9 != RELAY9.readSync()) {
                    RELAY9.writeSync(relayStatus9);
                }
                registrar(logFile, 'Rele 9: ' + relayStatus9);
                socket.disconnect(true);
                io.sockets.emit('relay9', data);
            });
            socket.on('relay9', function(data) {
                relayStatus9 = data;
                if (relayStatus9 != RELAY9.readSync()) {
                    RELAY9.writeSync(relayStatus9);
                    registrar(logFile,'Relé 9: ' + relayStatus9);
                    io.sockets.emit('relay9', data);
                }
            });
            
            socket.on('zona10', function(data) {
                relayStatus10 = data;
                if(relayStatus10 != RELAY10.readSync()) {
                    RELAY10.writeSync(relayStatus10);
                }
                registrar(logFile, 'Rele 10: ' + relayStatus10);
                socket.disconnect(true);
                io.sockets.emit('relay10', data);
            });
            socket.on('relay10', function(data) {
                relayStatus10 = data;
                if (relayStatus10 != RELAY10.readSync()) {
                    RELAY10.writeSync(relayStatus10);
                    registrar(logFile,'Relé 10: ' + relayStatus10);
                    io.sockets.emit('relay10', data);
                }
            });
            
            socket.on('zona11', function(data) {
                relayStatus11 = data;
                if(relayStatus11 != RELAY11.readSync()) {
                    RELAY11.writeSync(relayStatus11);
                }
                registrar(logFile, 'Rele 11: ' + relayStatus11);
                socket.disconnect(true);
                io.sockets.emit('relay11', data);
            });
            socket.on('relay11', function(data) {
                relayStatus11 = data;
                if (relayStatus11 != RELAY11.readSync()) {
                    RELAY11.writeSync(relayStatus11);
                    registrar(logFile,'Relé 11: ' + relayStatus11);
                    io.sockets.emit('relay11', data);
                }
            });
            
            socket.on('zona12', function(data) {
                relayStatus12 = data;
                if(relayStatus12 != RELAY12.readSync()) {
                    RELAY12.writeSync(relayStatus12);
                }
                registrar(logFile, 'Rele 12: ' + relayStatus12);
                socket.disconnect(true);
                io.sockets.emit('relay12', data);
            });
            socket.on('relay12', function(data) {
                relayStatus12 = data;
                if (relayStatus12 != RELAY12.readSync()) {
                    RELAY12.writeSync(relayStatus12);
                    registrar(logFile,'Relé 12: ' + relayStatus12);
                    io.sockets.emit('relay12', data);
                }
            });
            
    });
    process.on('SIGINT', function() {RELAY1.writeSync(0); RELAY1.unexport();RELAY2.writeSync(0); RELAY2.unexport();RELAY3.writeSync(0); RELAY3.unexport();RELAY4.writeSync(0); RELAY4.unexport();RELAY5.writeSync(0); RELAY5.unexport();RELAY6.writeSync(0); RELAY6.unexport();RELAY7.writeSync(0); RELAY7.unexport();RELAY8.writeSync(0); RELAY8.unexport();RELAY9.writeSync(0); RELAY9.unexport();RELAY10.writeSync(0); RELAY10.unexport();RELAY11.writeSync(0); RELAY11.unexport();RELAY12.writeSync(0); RELAY12.unexport();process.exit();});