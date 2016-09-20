<?php
$serv = new swoole_server("0.0.0.0", 9501, SWOOLE_PROCESS);
$serv->set(array(
    'worker_num' => 1,
));
$serv->on('workerStart', function($serv, $worker_id) {
	//if($worker_id == 0) $serv->addtimer(300);
});
$serv->on('connect', function (swoole_server $serv, $fd){
	$serv->send($fd, filesize(__DIR__.'/test.jpg'));
    //echo "Client:Connect.\n";
});
$serv->on('receive', function (swoole_server $serv, $fd, $from_id, $data) {
    echo "Client[$fd]: $data\n";
    $serv->sendfile($fd, __DIR__.'/t.txt', 1024*3);
    //$serv->close($fd);
});
$serv->on('close', function ($serv, $fd) {
    //echo "Client: Close.\n";
});
$serv->start();

