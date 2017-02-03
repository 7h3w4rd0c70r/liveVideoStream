var url = require('url');
var app = require('express')();
var server = require('http').createServer(app);
var io = require('socket.io')(server);
var mysql = require('mysql-promise')();

mysql.configure({
    host: 'wm116.wedos.net',
    database: 'a132229_daxni',
    username: 'a132229_daxni',
    paswword: 'u5Exs7wc'
});

io.on('connection', function (connection) {
    var params = url.parse(connection.upgradeReq.url, true)['query'] || { };

    connection.on('LIST HOSTS', function (socket) {
        setInterval(function () {
            mysql
                .query('SELECT * FROM hosts')
                .then(function (hosts) {
                    socket.emmit('HOSTS', hosts);
                })
                .catch(function (error) { });
        }, 150);
    });

    connection.on('CONNECTED CLIENT', function (socket) {
        mysql
            .query('INSERT INTO clients(clientId,hostId,userId) VALUES (?,?,?)',
                [ params['clientId'], params['hsotId'], params['userId'] ])
            .then(function () { })
            .catch(function () { });
    });

    connection.on('CONNECTED HOST', function (socket) {
        mysql
            .query('INSERT INTO hosts(hostId,userId,streamName,category) VALUES (?,?,?,?)',
                [ params['hostId'], params['userId'], params['streamName'], params['category'] ])
            .then(function () { })
            .catch(function () { });
    });

    connection.on('DISCONNECTED CLIENT', function (socket) {
        mysql
            .query('UPDATE clients SET status="offline" WHERE clientId=?',
                [ params['clientId'] ])
            .then(function () { })
            .catch(function () { });
    });

    connection.on('DISCONNECTED HOST', function (socket) {
        mysql
            .query('DELETE FROM hosts WHERE hostId=?',
                [ params['hostId'] ])
            .then(function () { })
            .catch(function () { });
    });
});

server.listen(8090, function () {
    console.log('Listening on port: ' + server.address().port);
});
