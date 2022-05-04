<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\Uid\Uuid;

require('../../vendor/autoload.php');

# include functions
require_once('../includes/inc.api.php');
require_once('inc.functions.php');

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$c = new \Slim\Container($configuration);

$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write('Page not found');
    };
};

$app = new \Slim\App($c);

$app->get('/chat-bot', function ($request, $response, $args) {

    $data_status = 200;
    $data = get_chat_bot();

    return $response->withStatus($data_status)->withJSON($data, $data_status);
});

$app->get('/chat-bot-all', function ($request, $response, $args) {

    $data_status = 200;
    $data = get_chat_bot_all();

    return $response->withStatus($data_status)->withJSON($data, $data_status);
});

$app->get('/rabbitmq', function ($request, $response, $args) {

    $data_status = 200;
    $data = get_rabbit();

    return $response->withStatus($data_status)->withJSON($data, $data_status);
});

$app->get('/ws-code', function ($request, $response, $args) {

    $data_status = 200;
    $data = get_ws_code();

    return $response->withStatus($data_status)->withJSON($data, $data_status);
});

$app->run();