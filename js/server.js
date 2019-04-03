var express = require('express');
var bodyParser = require('body-parser')
var app = express();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var mysql = require('mysql');

app.use(express.static(__dirname));
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({extended: false}));

app.get('/messages', (req, res) => {
    Message.find({},(err, messages)=> {
        res.send(messages);
    })
});

app.get('/messages/:user', (req, res) => {
    var user = req.params.user;
    Message.find({name: user},(err, messages)=> {
        res.send(messages);
    })
});

app.post('/messages', async (req, res) => {
    try{
        var message = new Message(req.body);

        var savedMessage = await message.save()
        console.log('saved');

        var censored = await Message.findOne({message:'badword'});
        if(censored)
            await Message.remove({_id: censored.id})
        else
            io.emit('message', req.body);
        res.sendStatus(200);
    }
    catch (error){
        res.sendStatus(500);
        return console.log('error',error);
    }
    finally{
        console.log('Message Posted')
    }

});

io.on('connection', () =>{
    console.log('a user is connected')
});

mongoose.connect(dbUrl ,{useMongoClient : true} ,(err) => {
    console.log('mongodb connected',err);
});

var server = http.listen(3000, () => {
    console.log('server is running on port', server.address().port);
});

var con = mysql.createConnection({
    host: "mysql:host=127.0.0.1;dbname=proyecto",
    user: "becario",
    password: "1111"
});

con.connect(function(err) {
    if (err) throw err;
    console.log("Connected!");
});


