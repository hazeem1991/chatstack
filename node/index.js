var server = require("http").Server();
var io = require("socket.io")(server);
var Redis = require ("ioredis");
var redis = new Redis();
redis.subscribe("new-message");
redis.on("message",function(channel,message){
    message = JSON.parse(message);
    io.emit(channel+":"+message.data.message.to,message.data);
});

server.listen(3000);

