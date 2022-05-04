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

$app->post('/receive', function ($request, $response, $args) {

    if (!empty($request->getBody())) {

        $data_status = 200;
        $data_content = json_decode($request->getBody(), true);
        $data = ws_receive($data_content);

    } else {
        $data_status = 401;
        $data = false;
    }

    return $response->withStatus($data_status)->withJSON($data, $data_status);
});

$app->get('/report/{number}', function ($request, $response, $args) {

    $data_status = 200;
    $data_content = json_decode($request->getBody(), true);
    $data = ws_send_report($args['number']);

    return $response->withStatus($data_status)->withJSON($data, $data_status);
});

$app->run();