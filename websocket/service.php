<?php


$serv = new swoole_websocket_server("192.168.146.128", 9502);

$serv->on('Open', function($server, $req) {
    echo "connection open: ".$req->fd."\r\n";
    file_put_contents( __DIR__ .'/log.txt' , $request->fd);
});

$serv->on('Message', function($serv, $server, $frame) {
    echo "message: ".$frame->data."\r\n";
    $data = $frame->data;
    $m = file_get_contents( __DIR__ .'/log.txt');
    for ($i=1 ; $i<= $m ; $i++) {
        echo PHP_EOL . '  i is  ' . $i .  '  data  is '.$data  . '  m = ' . $m;
        $server->push($i, $data );
    }
});

$serv->on('Close', function($server, $fd) {
    echo "connection close: ".$fd."\r\n";
});

$serv->start();

