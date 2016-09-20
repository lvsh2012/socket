<?php

function getConnectionList($serv){
    $start_fd = 0;
    while(true)
    {
        $conn_list = $serv->connection_list($start_fd, 10);
        if($conn_list===false or count($conn_list) === 0)
        {
            echo "finish\n";
            break;
        }
        $start_fd = end($conn_list);
        return $conn_list;
    }
}

$serv = new swoole_websocket_server("192.168.146.128", 9502);

$serv->on('Open', function($server, $req) {
    echo "connection open: ".$req->fd."\r\n";
});

$serv->on('Message', function($server, $frame) {
    echo "message: ".$frame->data."\r\n";
    $server->push($frame->fd, json_encode(["hello", "world"]));
    $connectList = getConnectionList($serv);
    foreach ($connectList as $fd){
        $server->push($fd, $frame->fd.":".$frame->data);
    }
});

$serv->on('Close', function($server, $fd) {
    echo "connection close: ".$fd."\r\n";
});

$serv->start();

