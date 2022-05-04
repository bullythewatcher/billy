<?php
function get_chat_bot_channels()
{

    try {

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = "SELECT profile_id, profile_login FROM btw_twitch_profiles ORDER BY auto_id ASC";
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {
                $response[] = (string) $row['profile_login'];
            }

        } else {
            $response = false;
        }

    } catch (Exception $e) {
        $response = false;
    }

    return $response;
}

function get_chat_bot() {

    try {

        $get_server = get_server();

        $response_data = [
            'username' => 'username-bot',
            'password' => 'oauth',
            'channels' => get_chat_bot_channels(),
            'rabbitmq' => "{$get_server}/api/rabbitmq/add"
        ];
        $response_json = json_encode($response_data, true);

        file_put_contents("../../components/chat-bot/config/config.json", $response_json);

        $response = true;

    } catch (Exception $e) {
        $response = $e->getMessage();
    }

    return $response;

}

function get_chat_bot_all() {

    try {

        $get_server = get_server();

        $response_data = [
            'username' => 'username',
            'password' => 'oauth',
            'channels' => get_chat_bot_channels(),
            'rabbitmq' => "{$get_server}/api/rabbitmq/add-all"
        ];

        $response_json = json_encode($response_data, true);

        file_put_contents("../../components/chat-bot-all/config/config.json", $response_json);

        $response = true;

    } catch (Exception $e) {
        $response = $e->getMessage();
    }

    return $response;

}

function get_rabbit() {

    try {

        $get_rabbitmq = get_rabbitmq();
        $get_server = get_server();

        $response_data = [
            'rabbitmq_url' => "amqp://{$get_rabbitmq['user']}:{$get_rabbitmq['password']}@{$get_rabbitmq['server']}",
            'chatbot_url' => "{$get_server}/api/chat-bot/msg"
        ];

        $response_json = json_encode($response_data, true);

        file_put_contents("../../components/rabbitmq/config.json", $response_json);

        $response = true;

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function get_ws_code() {

    try {

        $get_server = get_server();

        $response_data = [
            'port' => (int) 5010,
            'webhook' => [
                'enabled' => (bool) true,
                'path' => "{$get_server}/api/ws-code/receive"
            ]
        ];

        $response_json = json_encode($response_data, true);

        file_put_contents("../../components/ws-code/config.json", $response_json);

        $response = true;

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}