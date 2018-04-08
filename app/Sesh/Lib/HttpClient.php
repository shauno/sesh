<?php

namespace Sesh\Lib;

use Psr\Http\Message\ResponseInterface;

interface HttpClient
{
    /**
     * @param array $config
     */
    public function __construct(array $config = []);

    /**
     * Create and send an HTTP request.
     *
     * @param string $method  HTTP method
     * @param string $uri     URI object or string
     * @param array  $options Request options to apply
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function request(string $method, string $uri, array $options = []) : ResponseInterface;
}
