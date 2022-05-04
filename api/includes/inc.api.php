<?php
function get_rabbitmq() {

    $response = [
        'server' => "",
        'user' => '',
        'password' => ''
    ];

    return $response;

}

function get_uuid() {

    try {

        $uuid = Symfony\Component\Uid\Uuid::v1();

        if (!empty($uuid)) {
            $response = $uuid;
        } else {
            $response = false;
        }

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function get_connection_params() {

    $connectionParams = array(
        'dbname' => '',
        'user' => '',
        'password' => '',
        'host' => '',
        'driver' => 'pdo_mysql',
    );

    return $connectionParams;

}

function get_server() {

    $server_folder = "btw";

    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        $url = "https://";
    else
        $url = "http://";

    $url.= $_SERVER['HTTP_HOST'];
    #$url.= $_SERVER['REQUEST_URI'];
    $url.= "/{$server_folder}";
    return $url;

}

function get_folder() {

    $response = 'btw';
    return $response;

}

function call_api($params, $method) {

    try {

        $get_server = get_server();
        $get_folder = 'api';

        $url = "{$get_server}/{$get_folder}/{$params['path']}";
        $options = array(
            'timeout' => 120
        );

        if ($method === 'get') {
            $request = Requests::get($url, array(), $options);
        } else {
            $data = array(
                'username' => 'teamsepulveda@gmail.com',
                'password' => 'Temporal1*'
            );
            $payload = json_encode($params['data']);
            $request = Requests::post($url, array(), $payload);
        }

        $data = json_decode($request->body, true);
        $response = $data;#$request->body;

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function get_all_url() {

    $server = get_server();
    $folder = get_folder();
    $url = "{$server}/users";
    return $url;

}

function get_twitch_params() {

    try {

        $response = [
            'client_id' => '',
            'client_secret' => '',
            'grant_type' => ''
        ];

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}