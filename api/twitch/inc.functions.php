<?php
function get_generate_chat_bot() {

    try {

        $get_server = get_server();
        $get_folder = 'api';

        $url = "{$get_server}/api/config/chat-bot";
        $options = array(
            'timeout' => 120
        );

        $request = Requests::get($url, array(), $options);

        $data = json_decode($request->body, true);
        $response = $data;#$request->body;

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

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

function get_token_twitch() {

    try {

        $url = "https://id.twitch.tv/oauth2/token";
        $options = array(
            'timeout' => 120
        );

        $twitch_params = get_twitch_params();

        $data_params = "client_id={$twitch_params['client_id']}&client_secret={$twitch_params['client_secret']}&grant_type={$twitch_params['grant_type']}";
        //$payload = json_encode($data_params);
        $request = Requests::post($url, array(), $data_params);

        $data = json_decode($request->body, true);
        $response = $data;#$request->body;

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function get_twitch_username_picture($image, $user_id) {

    try {

        $image_name = "{$user_id}.png";
        $file = "../../assets/images/profiles/{$image_name}";
        $server = get_server();
        $image_path = "{$server}/assets/images/profiles";

        if (!file_exists($file)) {
            $image_url = "{$image}";
            file_put_contents("{$file}", file_get_contents($image_url));
        }

        $response = true;

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function get_twitch_username_data($username) {

    try {

        $get_twitch_params = get_twitch_params();
        $get_token = get_token();

        if ($get_twitch_params !== false && $get_token !== false) {

            $url = "https://api.twitch.tv/helix/users?login={$username}";
            $options = array(
                'timeout' => 120
            );

            $headers = [
                "Authorization" => "Bearer {$get_token['access_token']}",
                "Client-Id" => "{$get_twitch_params['client_id']}"
            ];
            $request = Requests::get($url, $headers, array());

            $data = json_decode($request->body, true);

            if (isset($data['data'][0])) {

                $created_at_parse = str_replace('T', ' ', $data['data'][0]['created_at']);
                $created_at = str_replace('Z', '', $created_at_parse);

                $response = [
                    'profile_id' => (int) $data['data'][0]['id'],
                    'profile_login' => (string) $data['data'][0]['login'],
                    'profile_display_name' => (string) $data['data'][0]['display_name'],
                    'profile_type' => (string) $data['data'][0]['type'],
                    'profile_broadcaster_type' => (string) $data['data'][0]['broadcaster_type'],
                    'profile_description' => (string) $data['data'][0]['description'],
                    'profile_profile_image_url' => (string) $data['data'][0]['profile_image_url'],
                    'profile_offline_image_url' => (string) $data['data'][0]['offline_image_url'],
                    'profile_view_count' => (string) $data['data'][0]['view_count'],
                    'profile_created_at' => (string) $created_at
                ];
            } else {
                $response = false;
            }

        } else {
            $response = false;
        }

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function get_twitch_username($user_id, $username) {

    try {

        $get_twitch_username_data = get_twitch_username_data($username);

        if ($get_twitch_username_data !== false) {

            $sql_connectionParams = get_connection_params();
            $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);

            $get_twitch_username_picture = get_twitch_username_picture($get_twitch_username_data['profile_profile_image_url'], $get_twitch_username_data['profile_id']);

            # insert profile
            $profile_display_name = htmlspecialchars($get_twitch_username_data['profile_display_name'], ENT_QUOTES);
            $profile_description = htmlspecialchars($get_twitch_username_data['profile_description'], ENT_QUOTES);
            $tbw_twitch_profiles = $sql_conn->executeStatement("INSERT INTO `btw_twitch_profiles` (`auto_id`, `profile_id`, `profile_login`, `profile_display_name`, `profile_type`, `profile_broadcaster_type`, `profile_description`, `profile_profile_image_url`, `profile_offline_image_url`, `profile_view_count`, `profile_created_at`) VALUES (NULL, '{$get_twitch_username_data['profile_id']}', '{$get_twitch_username_data['profile_login']}', '{$profile_display_name}', '{$get_twitch_username_data['profile_type']}', '{$get_twitch_username_data['profile_broadcaster_type']}', '{$profile_description}', '{$get_twitch_username_data['profile_id']}.png', '{$get_twitch_username_data['profile_offline_image_url']}', '{$get_twitch_username_data['profile_view_count']}', '{$get_twitch_username_data['profile_created_at']}') ON DUPLICATE KEY UPDATE `profile_login`='{$get_twitch_username_data['profile_login']}', `profile_display_name`='{$profile_display_name}', `profile_description`='{$profile_description}', `profile_view_count`='{$get_twitch_username_data['profile_view_count']}'");

            # insert account
            $account_uin = md5(md5($user_id)."_".md5($get_twitch_username_data['profile_id']));
            $tbw_accounts = $sql_conn->executeStatement("INSERT IGNORE INTO `btw_accounts` (`auto_id`, `account_uin`, `user_id`, `profile_id`) VALUES (NULL, '{$account_uin}', '{$user_id}', '{$get_twitch_username_data['profile_id']}')");

            $response = true;

        } else {
            $response = false;
        }

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function get_twitch_username_delete($account_uin) {

    try {

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_conn->delete('btw_accounts', ['account_uin' => $account_uin]);

        $response = true;

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function get_token_update() {

    try {

        $get_token_twitch = get_token_twitch();

        if ($get_token_twitch !== false) {

            $sql_connectionParams = get_connection_params();
            $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
            $sql_conn->update('btw_tokens', ['access_token' => $get_token_twitch['access_token'], 'expires_in' => $get_token_twitch['expires_in'], 'token_type' => $get_token_twitch['token_type']], ['auto_id' => 1]);

            $response = true;

        } else {
            $response = false;
        }

    } catch (Exception $e) {
        $response = $e->getMessage();
    }

    return $response;

}

function get_token()
{

    try {

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = "SELECT * FROM btw_tokens WHERE auto_id = 1";
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {
                $response = [
                    'access_token' => (string) $row['access_token'],
                    'expires_in' => (int) $row['expires_in'],
                    'token_type' => (string) $row['token_type']
                ];
            }

        } else {
            $response = false;
        }

    } catch (Exception $e) {
        $response = false;
    }

    return $response;
}

function get_users_profile($user) {

    try {

        $get_twitch_params = get_twitch_params();
        $get_token = get_token();

        if ($get_twitch_params !== false && $get_token !== false) {

            $twitch_client_id = $get_twitch_params['client_id'];
            $twitch_client_secret = $get_twitch_params['client_id'];

            $twitch_access_token = $get_token['access_token'];

            $helixGuzzleClient = new \TwitchApi\HelixGuzzleClient($twitch_client_id);

            $twitchApi = new \TwitchApi\TwitchApi($helixGuzzleClient, $twitch_client_id, $twitch_client_secret);

            $response = $twitchApi->getUserByUsername('summit1g');

        } else {
            $response = false;
        }

    } catch (Exception $e) {
        $response = false;
    }

    return $response = false;

}