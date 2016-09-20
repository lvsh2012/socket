<?php
$opt = getopt("c:n:k:");
print_r($opt);
if (empty($opt['c']) || empty($opt['n']))
{
    echo "examples:  php client.php -c 100 -n 10000" . PHP_EOL;
    return;
}

$n_client = $opt['c'];
$count = $opt['n'];
$size = empty($opt['k']) ? 0 : $opt['k'];
require __DIR__ . "/WebSocketClient.php";
$host = '192.168.146.128';
$prot = 9501;

for ($i = 0; $i < $n_client; $i++)
{
    $client = new WebSocketClient($host, $prot);
    $client->connect();
    $clients[] = $client;
}
echo "finish\n";
sleep(10000);

//echo $data;
$data = "data";
if (!empty($size))
{
    $data = str_repeat("A", $size * 1024);
}
for ($i = 0; $i < $count; $i++)
{
    $client->send("hello swoole, number:" . $i . " data:" . $data);
    $recvData = "";
    //while(1) {
    $frame = $client->recv();
    if (empty($frame))
    {
        break;
    }
    //}
    echo "size:" . strlen($frame->data) . PHP_EOL;
}
echo PHP_EOL . "======" . PHP_EOL;
sleep(1);
echo 'finish' . PHP_EOL;
