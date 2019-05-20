
        #!/usr/bin/env node
        var io = require('../node_modules/socket.io-client');
        var socket = io.connect('132.321.12.21:11817', {reconnect: true});

        socket.on('connect', function (socket) {
            console.log('Connected!');
        });
        socket.on('disconnect', function (socket) {
            console.log('Desconectando!');
        });
        socket.emit('zona4', 0);
        