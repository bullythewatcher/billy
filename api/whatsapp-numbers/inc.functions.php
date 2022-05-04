<?php
function get_delete($uuid) {

    try {

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_conn->update('btw_users', ['user_phone' => 0, 'user_status' => 0, 'user_uuid' => get_uuid()], ['user_uuid' => "{$uuid}"]);

        $response = true;

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}