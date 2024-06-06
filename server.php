<?php

use Ilex\SwoolePsr7\SwooleResponseConverter;
use Ilex\SwoolePsr7\SwooleServerRequestConverter;
use Nyholm\Psr7\Factory\Psr17Factory;

use Slim\App;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;


require __DIR__ . '/vendor/autoload.php';

$psr17Factory = new Psr17Factory;
$requestConverter = new SwooleServerRequestConverter(
    $psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory
);
$app = new App($psr17Factory);
$app->addRoutingMiddleware();
$app->get('/hello', \App\Controllers\HelloController::class);
$app->get('/go_away/{name}', \App\Controllers\HelloController::class);

$server = new Server("0.0.0.0", 8080);
$server->on("start", function(Server $server) {
    echo "HTTP Server ready at http://127.0.0.1:8080" . PHP_EOL;
});
$server->on('request', function(Request $request, Response $response) use ($app, $requestConverter) {
    $psr7Request = $requestConverter->createFromSwoole($request);
    $psr7Response = $app->handle($psr7Request);
    $converter = new SwooleResponseConverter($response);
    $converter->send($psr7Response);
});
$server->start();
