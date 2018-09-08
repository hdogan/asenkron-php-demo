<?php

function http_client($url) {
	$parsedUrl = parse_url($url);
	$host = $parsedUrl['host'];
	$port = 80;
	$ssl = false;

	if ($parsedUrl['scheme'] == 'https') {
		$port = 443;
		$ssl = true;
	}

	go(function () use ($host, $port, $ssl, $url) {
		$client = new Co\Http\Client($host, $port, $ssl);

		if ($client->get('/')) {
			echo "\e[92m$url\e[39m -> " . $client->statusCode . "\n";
		} else {
			echo "\e[91m$url\e[39m -> ERROR\n";
		}
	});
}

http_client('https://www.google.com');
http_client('https://secure.php.net');
http_client('http://yazilimparki.com.tr');
http_client('https://www.lfkdfkldlfkdf.com');
http_client('http://hi.do');

echo "Bitti mi?\n";
