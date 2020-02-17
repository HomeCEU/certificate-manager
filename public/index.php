<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use HomeCEU\API\App;

require __DIR__ . '/../vendor/autoload.php';

$app = (new App())->get();

$app->get('/test', function (Request $request, Response $response) {
    $response->getBody()->write('A whole new world!');
    return $response;
});

$app->run();