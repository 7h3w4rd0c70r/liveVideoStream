var url = require('url');
var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var mysql = require('mysql-promise')();

mysql.configure({
    host: 'wm116.wedos.net',
    database: 'a132229_daxni',
    username: 'a132229_daxni',
    paswword: 'u5Exs7wc'
});

io.on('connection', function (connection) {
    connection.on('LIST HOSTS', function (data) {
        setInterval(function () {
            mysql
                .query('SELECT * FROM hosts')
                .then(function (hosts) {
                    connection.emmit('HOSTS', hosts);
                })
                .catch(function (error) { });
        }, 150);
    });

    connection.on('CONNECTED CLIENT', function (data) {
        mysql
            .query('INSERT INTO clients(clientId,hostId,userId) VALUES (?,?,?)',
                [ data['clientId'], data['hsotId'], data['userId'] ])
            .then(function () { })
            .catch(function () { });
    });

    connection.on('CONNECTED HOST', function (data) {
        mysql
            .query('INSERT INTO hosts(hostId,userId,streamName,category) VALUES (?,?,?,?)',
                [ data['hostId'], data['userId'], data['streamName'], data['category'] ])
            .then(function () { })
            .catch(function () { });
    });

    connection.on('DISCONNECTED CLIENT', function (data) {
        mysql
            .query('UPDATE clients SET status="offline" WHERE clientId=?',
                [ data['clientId'] ])
            .then(function () { })
            .catch(function () { });
    });

    connection.on('DISCONNECTED HOST', function (data) {
        mysql
            .query('DELETE FROM hosts WHERE hostId=?',
                [ data['hostId'] ])
            .then(function () { })
            .catch(function () { });
    });
});

server.listen(8089, function () {
    console.log('Listening on port: ' + server.address().port);
});
