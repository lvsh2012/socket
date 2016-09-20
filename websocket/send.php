<?php
require __DIR__ . "/WebSocketClient.php";
$host = '192.168.146.128';
$prot = 9501;

$client = new WebSocketClient($host, $prot);
$client->connect();

$text = isset($_GET['t']) ? $_GET['t'] : 'www'.time();
$text .= "\r\n";
$client->send($text);
