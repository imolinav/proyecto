#!/usr/bin/env node
let io = require('../node_modules/socket.io-client');
let socket = io.connect('217.182.67.9:'+ puerto + '/' + usuario, {reconnect: true}); //Aqui como creamos los scripts para la raspberry podemos poner el puerto y el usuario directamente

socket.on('connect', function(socket) {
   console.log('Connected!');
});
socket.on('disconnect', function(socket) {
    console.log('Desconectando!');
});
socket.emit('zona1', 1);