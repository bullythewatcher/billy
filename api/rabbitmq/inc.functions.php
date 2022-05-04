<?php
function get_add($data) {

    try {

        $config = get_rabbitmq();

        $data = [
            'chat_id' => $data['chat_id'],
            'chat_target' => str_replace('#', '', $data['chat_target']),
            'chat_username' => $data['chat_username'],
            'chat_msg' => $data['chat_msg'],
            'chat_created_at' => date('Y-m-d H:i:s')
        ];

        $connection = new \PhpAmqpLib\Connection\AMQPStreamConnection($config['server'], 5672, $config['user'], $config['password']);
        $channel = $connection->channel();

        $channel->queue_declare('test_queue', false, true, false, false);

        $msg = new \PhpAmqpLib\Message\AMQPMessage(
            json_encode($data, true),
            array('delivery_mode' => \PhpAmqpLib\Message\AMQPMessage::DELIVERY_MODE_PERSISTENT)
        );

        $channel->basic_publish($msg, '', 'test_queue');

        $response = true;

        $channel->close();
        $connection->close();

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function get_add_all($data) {

    try {

        $data = [
            'chat_id' => $data['chat_id'],
            'chat_target' => str_replace('#', '', $data['chat_target']),
            'chat_username' => $data['chat_username'],
            'chat_msg' => $data['chat_msg'],
            'chat_created_at' => date('Y-m-d H:i:s')
        ];

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = "SELECT * FROM btw_chats WHERE chat_msg_id = '{$data['chat_id']}'";
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {
            $response = false;
        } else {

            $config = get_rabbitmq();

            $connection = new \PhpAmqpLib\Connection\AMQPStreamConnection($config['server'], 5672, $config['user'], $config['password']);
            $channel = $connection->channel();

            $channel->queue_declare('test_queue', false, true, false, false);

            $msg = new \PhpAmqpLib\Message\AMQPMessage(
                json_encode($data, true),
                array('delivery_mode' => \PhpAmqpLib\Message\AMQPMessage::DELIVERY_MODE_PERSISTENT)
            );

            $channel->basic_publish($msg, '', 'test_queue');

            $response = true;

            $channel->close();
            $connection->close();

        }

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}