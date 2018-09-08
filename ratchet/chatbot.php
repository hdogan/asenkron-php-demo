<?php
require __DIR__ . '/vendor/autoload.php';

use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use MyApp\ChatBot;

$loop = React\EventLoop\Factory::create();
$sock = new React\Socket\Server(8080, $loop);
$ws = new WsServer(new ChatBot($loop));

$server = new IoServer(new HttpServer($ws), $sock, $loop);
$server->run();
