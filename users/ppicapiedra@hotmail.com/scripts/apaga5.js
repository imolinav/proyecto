
        #!/usr/bin/env node
        var io = require('../node_modules/socket.io-client');
        var socket = io.connect('192.168.5.2:4182', {reconnect: true});

        socket.on('connect', function (socket) {
            console.log('Connected!');
        });
        socket.on('disconnect', function (socket) {
            console.log('Desconectando!');
        });
        socket.emit('zona5', 0);
        