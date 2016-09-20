<?php
$client = new \swoole_client(SWOOLE_SOCK_TCP);
//设置事件回调函数
$client->on("connect", function($cli) {
    $cli->send("hello world\r\n");
});
$client->on("receive", function($cli, $data){
    echo "Received: ".$data."\r\n";
});
$client->on("error", function($cli){
    echo "Connect failed\r\n";
});
$client->on("close", function($cli){
    echo "Connection close\r\n";
});
//发起网络连接
$client->connect('192.168.146.128', 9502, 0.5);

function send($client, $data, $type = 'text', $masked = false)
{
    switch($type)
    {
        case 'text':
            $_type = WEBSOCKET_OPCODE_TEXT;
            break;
        case 'binary':
        case 'bin':
            $_type = WEBSOCKET_OPCODE_BINARY;
            break;
        default:
            return false;
    }
    return $client ->send(swoole_websocket_server::pack($data, $_type, true, $masked));
}

$text = isset($_GET['text']) ? $_GET['text'] : time();
send($client, $text."\r\n");