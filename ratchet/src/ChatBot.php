<?php
namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use React\HttpClient\Client as HttpClient;
use React\HttpClient\Response as HttpResponse;

class ChatBot implements MessageComponentInterface
{
    protected $clients;
    protected $loop;

    public function __construct($loop)
    {
        $this->loop = $loop;
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "Yeni bağlantı: {$conn->resourceId}\n";
    }

    public function onMessage(ConnectionInterface $from, $message)
    {
        $message = trim($message);

        if ($message === 'dolar') {
            $from->send("Birazdan dolar kuru geliyor...\n");

            $http = new HttpClient($this->loop);
            $json = '';
            $request = $http->request('GET', 'https://www.doviz.com/api/v1/currencies/USD/latest');

            $request->on('response', function (HttpResponse $response) use ($from, &$json) {
                $response->on('data', function ($chunk) use (&$json) {
                    $json .= $chunk;
                });

                $response->on('end', function () use ($from, &$json) {
                    $currency = json_decode($json);
                    $from->send("Dolar Alış: {$currency->buying} Satış: {$currency->selling}\n");
                });
            });

            $request->on('error', function (\Exception $e) use ($from) {
                $from->send("Dolar kurunu alamadım. Sebebi: {$e->getMessage()}\n");
            });

            $request->end();
        } elseif ($message === 'euro') {
            $from->send("Birazdan euro kuru geliyor...\n");

            $http = new HttpClient($this->loop);
            $json = '';
            $request = $http->request('GET', 'https://www.doviz.com/api/v1/currencies/EUR/latest');

            $request->on('response', function (HttpResponse $response) use ($from, &$json) {
                $response->on('data', function ($chunk) use (&$json) {
                    $json .= $chunk;
                });

                $response->on('end', function () use ($from, &$json) {
                    $currency = json_decode($json);
                    $from->send("Euro Alış: {$currency->buying} Satış: {$currency->selling}\n");
                });
            });

            $request->on('error', function (\Exception $e) use ($from) {
                $from->send("Euro kurunu alamadım. Sebebi: {$e->getMessage()}\n");
            });

            $request->end();
        } else {
            $from->send("Anlamadım?\n");
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Bağlantı kapandı: {$conn->resourceId}\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
        echo "Hata: {$e->getMessage()}\n";
    }
}
