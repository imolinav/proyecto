
        #!/usr/bin/env node
        var io = require('../node_modules/socket.io-client');
        var socket = io.connect('217.186.12.5:8080', {reconnect: true});

        socket.on('connect', function (socket) {
            console.log('Connected!');
        });
        socket.on('disconnect', function (socket) {
            console.log('Desconectando!');
        });
        socket.emit('zona12', 0);
        