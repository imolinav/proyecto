
        #!/usr/bin/env node
        let io = require(../node_modules/socket.io-client');
        let socket = io.connect('217.186.12.5:8080', {reconnect: true});
        socket.on('connect', function(socket) {
            console.log('Connected!');
        });
        socket.on('disconnect', function(socket) {
            console.log('Desconectando!');
        });
        socket.emit('zona3', 1);
        