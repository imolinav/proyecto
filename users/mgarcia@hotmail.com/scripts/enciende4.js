
        #!/usr/bin/env node
        let io = require('../node_modules/socket.io-client');
        let socket = io.connect('132.321.12.21:11817', {reconnect: true});
        socket.on('connect', function(socket) {
            console.log('Connected!');
        });
        socket.on('disconnect', function(socket) {
            console.log('Desconectando!');
        });
        socket.emit('zona4', 1);
        