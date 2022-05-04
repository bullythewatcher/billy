<?php
require('../../vendor/autoload.php');

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

$app->post('/text', function ($request, $response, $args) {

    $data_status = 200;
    $data_content = json_decode($request->getBody(), true);
    $data = get_analyze('0b34c588-bb64-11ec-89a9-e563104461e9', $data_content);

    return $response->withStatus($data_status)->withJSON($data, $data_status);
});

$app->run();