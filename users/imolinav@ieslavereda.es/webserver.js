
    let Gpio = require('onoff').Gpio; 
    let logFile = 'users/imolinav@ieslavereda.es/registro.log';
    let express = require('express');
    let app = express();
    let server = require('http').createServer(app);
    let io = require('socket.io')(server);
    let fs = require('fs');
    
    server.listen(59407);
    registrar(logFile, 'Servidor lanzado escuchando por el puerto 59407');
    
    function registrar(file, text) {
        fs.appendFile(file, Date() + ' --> ' + text + '\n', function(err) {
            if(err) throw err;
        });
        console.log(Date() + ' --> ' + text);
    }
    
            let RELAY1 = new Gpio(23, 'out');
            let relayStatus1 = 0;
            io.sockets.on('connection', function(socket) { socket.emit('connected', { led1Status: relayStatus1 });
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
            
    });
    process.on('SIGINT', function() {RELAY1.writeSync(0); RELAY1.unexport();process.exit();});