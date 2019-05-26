
        #!/usr/bin/env node
        let io = require('../node_modules/socket.io-client');
        let socket = io.connect('192.168.1.128:9480', {reconnect: true});
        socket.on('connect', function(socket) {
            console.log('Connected!');
        });
        socket.on('disconnect', function(socket) {
            console.log('Desconectando!');
        });
        socket.emit('zona1', 1);
        