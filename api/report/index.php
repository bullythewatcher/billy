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

$app->get('/messages', function ($request, $response, $args) {

    $data_status = 200;
    $data_content = json_decode($request->getBody(), true);
    $data = get_messages_process();
    #echo $data;
    return $response->withStatus($data_status)->withJSON($data, $data_status);
});

$app->get('/show', function ($request, $response, $args) {

    $data_status = 200;
    $data_content = json_decode($request->getBody(), true);
    $data = get_report();
    #echo $data;
    return $response->withStatus($data_status)->withJSON($data, $data_status);
});

$app->get('/create', function ($request, $response, $args) {

    $data_status = 200;
    $data_content = json_decode($request->getBody(), true);
    $data = get_report_create();
    #echo $data;
    return $response->withStatus($data_status)->withJSON($data, $data_status);
});

$app->get('/send', function ($request, $response, $args) {

    $data_status = 200;
    $data_content = json_decode($request->getBody(), true);
    $data = get_report_send();
    #echo $data;
    return $response->withStatus($data_status)->withJSON($data, $data_status);
});

$app->run();