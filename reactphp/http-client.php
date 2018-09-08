<?php

require 'vendor/autoload.php';

$loop = React\EventLoop\Factory::create();
$client = new React\HttpClient\Client($loop);

function http_client($client, $url) {
	$request = $client->request('GET', $url);

	$request->on('response', function (\React\HttpClient\Response $response) use ($url) {
		echo "\e[92m$url\e[39m -> " . $response->getCode() . "\n";
	});

	$request->on('error', function (\Exception $e) use ($url) {
		echo "\e[91m$url\e[39m -> ERROR\n";
	});

	$request->end();
}

http_client($client, 'https://www.google.com');
http_client($client, 'https://github.com');
http_client($client, 'https://secure.php.net');
http_client($client, 'https://www.lfkdfkldlfkdf.com');
http_client($client, 'http://hi.do');

$loop->run();

echo "Bitti mi?\n";
