var Gpio = require('onoff').Gpio;
var logFile = __dirname + '/registro.log';
var express = require('express');
var app = express();
var server = require('http').createServer(app);
var io = require('socket.io')(server);
var fs = require('fs');
var CronJob = require('cron').CronJob;

server.listen(9480);
registrar(logFile, 'Servidor lanzado escuchando por el puerto 9480');

function registrar(file, text) {
    fs.appendFile(file, Date() + ' --> ' + text + '\n', function (err) {
        if (err) throw err;
    });
    console.log(Date() + ' --> ' + text);
}

var RELAY1 = new Gpio(2, 'out');
var relayStatus1 = 0;

var RELAY2 = new Gpio(3, 'out');
var relayStatus2 = 0;

var RELAY3 = new Gpio(4, 'out');
var relayStatus3 = 0;

var RELAY4 = new Gpio(5, 'out');
var relayStatus4 = 0;

var LCD = require('lcdi2c');
var lcd = new LCD(6, 0x27, 20, 2);

var sensor = require('node-dht-sensor');

let relays = [], pins = [], status = [], scripts = [];
relays.push(RELAY1, RELAY2, RELAY3, RELAY4);
pins.push(2, 3, 4, 5);
status.push(relayStatus1, relayStatus2, relayStatus3, relayStatus4);
scripts.push("activar2.js","activar3.js","activar4.js","activar5.js");


function leerTemp() {
    sensor.read(8, function(err, temperature) {
        if(!err) {
            return temperature;
        }
    })
}

function leerHumedad() {
    sensor.read(8, function(err, humidity) {
        if(!err) {
            return humidity;
        }
    })
}

lcd.on();
setInterval(function() {
    lcd.clear();
    lcd.print('Temperatura: '+leerTemp()+'ºC');
    lcd.print('Humedad: '+leerHumedad()+'%');
}, 300000);

io.sockets.on('connection', function (socket) {


    socket.emit('connected', status);

        /*
        { led1Status: relayStatus1,
        led2Status: relayStatus2,
        led3Status: relayStatus3,
        led4Status: relayStatus4 });
        */

    socket.on('activar', function(data) {
        // data => disp, action, date, temp, repeats, weekly
        for (let i=0; i<pins.length; i++) {
            if(data.disp === pins[i]) {
                status[i] = data.action;
                if(status[i]!=relays[i].readSync()) {
                    relays[i].writeSync(status[i]);
                    if(data.temp!==null) {
                        let interval = "";
                        if(data.action === 1) {
                            interval = setInterval(function() {
                                if(data.temp + 5 >= leerTemp()) {
                                    relays[i].writeSync(0);
                                } else if(data.temp - 5 <= leerTemp()) {
                                    relays[i].writeSync(1);
                                }
                            }, 30000);
                        } else {
                            if(interval !== "") {
                                clearInterval(interval);
                            }
                        }
                    }
                }
                break;
            }
        }
    });

    socket.on('programar', function(data) {
        let date = data.date.split('-');
        let hour = data.hour.split(';');
        for(let i = 0; i<pins.length; i++) {
            if(data.disp === pins[i]) {
                let reps = "";
                for(let i = 0; i<data.repeats.length; i++) {
                    if(data.repeats.charAt(i)==="S") {
                        if(reps ==="") {
                            reps+=i;
                        } else {
                            reps += ","+i;
                        }
                    }
                }
                if(reps === "") {
                    reps = "*";
                }
                let data_cron = hour[2]+" "+hour[1]+" "+hour[0]+" "+date[2]+" "+date[1]+" "+reps;
                new CronJob(data_cron, function () {
                    if(status[i]==1) {
                        relays[i].writeSync(0);
                        status[i]=0;
                    } else {
                        relays[i].writeSync(1);
                        status[i]=1;
                    }
                }, null, true, 'Europe/Madrid');
                break;
            }
        }
    })
});

process.on('SIGINT', function () {
    RELAY1.writeSync(0);
    RELAY1.unexport();
    RELAY2.writeSync(0);
    RELAY2.unexport();
    RELAY3.writeSync(0);
    RELAY3.unexport();
    RELAY4.writeSync(0);
    RELAY4.unexport();
    lcd.off();
    process.exit();
});