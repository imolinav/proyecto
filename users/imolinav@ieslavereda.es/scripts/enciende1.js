
        #!/usr/bin/env node
        let io = require('../node_modules/socket.io-client');
        let socket = io.connect('321.321.321.321:59407', {reconnect: true});
        socket.on('connect', function(socket) {
            console.log('Connected!');
        });
        socket.on('disconnect', function(socket) {
            console.log('Desconectando!');
        });
        socket.emit('zona1', 1);
        