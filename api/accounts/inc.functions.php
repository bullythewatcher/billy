<?php
function get_add($data) {

    try {

        if (isset($data['user_email']) && isset($data['account_username'])) {

            $account_uin = md5(md5($data['user_email'])."_".md5($data['account_username']));
            $user_email = $data['user_email'];
            $account_username = str_replace("@", "", $data['account_username']);

            $sql_connectionParams = get_connection_params();
            $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
            $ttb_users = $sql_conn->executeStatement("INSERT IGNORE INTO `btw_accounts` (`auto_id`, `account_uin`, `user_email`, `account_username`) VALUES (NULL, '{$account_uin}', '{$user_email}', '{$account_username}')");

            $response = true;

        } else {
            $response = $data;
        }

    } catch (Exception $e) {
        $response = 3;
    }

    return $response;

}

function get_delete($data) {

    try {

        if (isset($data['account_uin'])) {

            $account_uin = $data['account_uin'];

            $sql_connectionParams = get_connection_params();
            $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
            $sql_conn->delete('btw_accounts', ['account_uin' => $account_uin]);

            $response = true;

        } else {
            $response = $data;
        }

    } catch (Exception $e) {
        $response = 3;
    }

    return $response;

}