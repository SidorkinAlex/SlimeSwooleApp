<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HelloController
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $response->getBody()->write(
            json_encode(
                [
                    'message' => 'Hello World'
                ]
            )
        );

        return $response;
    }
}

