
        #!/usr/bin/env node
        var io = require('../node_modules/socket.io-client');
        var socket = io.connect('321.321.321.321:59407', {reconnect: true});

        socket.on('connect', function (socket) {
            console.log('Connected!');
        });
        socket.on('disconnect', function (socket) {
            console.log('Desconectando!');
        });
        socket.emit(process.argv[2], 0);
        