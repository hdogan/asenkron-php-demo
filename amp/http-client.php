<?php

require 'vendor/autoload.php';

function http_client($url) {
	$client = new Amp\Artax\DefaultClient;

	$promise = Amp\call(function () use ($client, $url) {
		try {
			$response = yield $client->request($url);
			echo "\e[92m{$url}\e[39m -> " . $response->getStatus() . "\n";
		} catch (\Exception $e) {
			echo "\e[91m{$url}\e[39m -> ERROR\n";
		}

	});

	return $promise;
}

Amp\Loop::run(function () {
	$r1 = http_client('https://www.google.com');
	$r2 = http_client('https://secure.php.net');
	$r3 = http_client('https://github.com');
	$r4 = http_client('https://www.lfkdfkldlfkdf.com');
	$r5 = http_client('http://hi.do');

	Amp\Promise\wait(Amp\Promise\all([$r1, $r2, $r3, $r4, $r5]));
	Amp\Loop::stop();
});

echo "Bitti mi?\n";
