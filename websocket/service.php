<?php


$serv = new swoole_websocket_server("192.168.146.128", 9502);

$serv->on('Open', function($server, $req) {
    echo "connection open: ".$req->fd."\r\n";
});

$serv->on('Message', function($server, $frame) {
    echo "message: ".$frame->data."\r\n";
    $server->push($frame->fd, json_encode(["hello", "world"]));
    $start_fd = 0;
    while(true)
    {
        $connectList = $serv->connection_list($start_fd, 10);
        if($connectList === false or count($connectList) === 0){
            echo "finish\n";
            break;
        }else{

			$start_fd = end($connectList);
        }
        
    }
    foreach ($connectList as $fd){
        $server->push($fd, $frame->fd.":".$frame->data);
    }
});

$serv->on('Close', function($server, $fd) {
    echo "connection close: ".$fd."\r\n";
});

$serv->start();

