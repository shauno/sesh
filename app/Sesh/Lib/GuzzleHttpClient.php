<?php

namespace Sesh\Lib;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class GuzzleHttpClient implements HttpClient
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {
        $this->client = new Client($config);
    }

    /**
     * @inheritdoc
     */
    public function request(string $method, string $uri, array $options = []): ResponseInterface
    {
        return $this->client->request($method, $uri, $options);
    }
}
