<?php
function get_login($email)
{

    try {

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = "SELECT * FROM users WHERE email = '{$email}' AND status = 1";
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {
                $response = 'success';
            }

        } else {
            $response = 'error';
        }

    } catch (Exception $e) {
        $response = $e->getMessage();
    }

    return $response;
}

function get_btw_accounts($user_id)
{

    try {

        $sql_query_params = <<<EOT
        SELECT
            btw_accounts.account_uin,
            btw_accounts.user_id,
            btw_accounts.profile_id,
            btw_twitch_profiles.auto_id,
            btw_twitch_profiles.profile_login,
            btw_twitch_profiles.profile_profile_image_url,
            btw_twitch_profiles.profile_created_at
        FROM
            btw_accounts,
            btw_twitch_profiles
        WHERE
            btw_accounts.profile_id = btw_twitch_profiles.profile_id AND 
            btw_accounts.user_id = {$user_id}
        ORDER BY btw_twitch_profiles.auto_id DESC
        EOT;

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = $sql_query_params;
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {
                $response[] = [
                    'account_uin' => (string) $row['account_uin'],
                    'user_id' => (int) $row['user_id'],
                    'profile_id' => (int) $row['profile_id'],
                    'auto_id' => (int) $row['auto_id'],
                    'profile_login' => (string) $row['profile_login'],
                    'profile_profile_image_url' => (string) $row['profile_profile_image_url'],
                    'profile_created_at' => (string) $row['profile_created_at']
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

function get_btw_reports($user_id)
{

    try {

        $sql_query_params = <<<EOT
        SELECT
            btw_accounts.account_uin,
            btw_accounts.user_id,
            btw_accounts.profile_id,
            btw_twitch_profiles.profile_login,
            btw_twitch_profiles.profile_profile_image_url,
            btw_reports.report_unique,
            btw_reports.report_id,
            btw_reports.report_uuid,
            btw_reports.report_uin,
            btw_reports.report_created_at
        FROM
            btw_accounts,
            btw_twitch_profiles,
            btw_reports
        WHERE
            btw_accounts.profile_id = btw_twitch_profiles.profile_id AND 
            btw_accounts.user_id = btw_reports.user_id AND
            btw_accounts.profile_id = btw_reports.profile_id AND
            btw_accounts.user_id = {$user_id} AND
            btw_reports.report_status <> 2
        ORDER BY btw_reports.report_created_at DESC
        EOT;

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = $sql_query_params;
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {
                $response[] = [
                    'account_uin' => (string) $row['account_uin'],
                    'user_id' => (int) $row['user_id'],
                    'profile_id' => (int) $row['profile_id'],
                    'profile_login' => (string) $row['profile_login'],
                    'profile_profile_image_url' => (string) $row['profile_profile_image_url'],
                    'report_unique' => (string) $row['report_unique'],
                    'report_id' => (string) $row['report_id'],
                    'report_uuid' => (string) $row['report_uuid'],
                    'report_uin' => (string) $row['report_uin'],
                    'report_created_at' => (string) $row['report_created_at']
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

function get_btw_reports_view_data($report_unique)
{

    try {

        $sql_query_params = <<<EOT
        SELECT
            btw_reports.user_id,
            btw_reports.profile_id,
            btw_reports.report_uuid,
            btw_reports.report_uin,
            btw_reports.report_created_at,
            btw_twitch_profiles.profile_login,
            btw_twitch_profiles.profile_profile_image_url
        FROM
            btw_reports,
            btw_twitch_profiles
        WHERE
            btw_reports.profile_id = btw_twitch_profiles.profile_id AND
            btw_reports.report_unique = '{$report_unique}'
        ORDER BY btw_reports.report_created_at DESC
        EOT;

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = $sql_query_params;
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {
                $response = [
                    'user_id' => (int) $row['user_id'],
                    'profile_id' => (int) $row['profile_id'],
                    'report_uuid' => (string) $row['report_uuid'],
                    'report_uin' => (string) $row['report_uin'],
                    'report_created_at' => (string) $row['report_created_at'],
                    'profile_login' => (string) $row['profile_login'],
                    'profile_profile_image_url' => (string) $row['profile_profile_image_url']
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

function get_btw_reports_view($report_unique)
{

    try {

        $sql_query_params = <<<EOT
        SELECT
            btw_reports.user_id,
            btw_reports.profile_id,
            btw_reports.report_uuid,
            btw_reports.report_uin,
            btw_reports.report_created_at,
            btw_reports_msg.chat_msg_id,
            btw_chats.chat_msg,
            btw_chats.chat_target,
            btw_chats.chat_username,
            btw_chats.chat_date
        FROM
            btw_reports,
            btw_reports_msg,
            btw_chats
        WHERE
            btw_reports.report_uuid = btw_reports_msg.report_uuid AND 
            btw_reports.report_uin = btw_reports_msg.report_uin AND
            btw_reports_msg.chat_msg_id = btw_chats.chat_msg_id AND
            btw_reports.report_unique = '{$report_unique}'
        GROUP BY btw_reports_msg.chat_msg_id
        ORDER BY btw_reports.report_created_at DESC
        EOT;

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = $sql_query_params;
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {
                $response[] = [
                    'user_id' => (int) $row['user_id'],
                    'profile_id' => (int) $row['profile_id'],
                    'report_uuid' => (string) $row['report_uuid'],
                    'report_uin' => (string) $row['report_uin'],
                    'report_created_at' => (string) $row['report_created_at'],
                    'chat_msg_id' => (string) $row['chat_msg_id'],
                    'chat_msg' => (string) $row['chat_msg'],
                    'chat_target' => (string) $row['chat_target'],
                    'chat_username' => (string) $row['chat_username'],
                    'chat_date' => (string) $row['chat_date']
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

function google_login_picture($image, $user_email) {

    try {

        $image_name = md5($user_email).".png";
        $file = "../assets/images/users/{$image_name}";
        $server = get_server();
        $image_path = "{$server}/assets/images/users";

        if (!file_exists($file)) {
            $image_url = "{$image}";
            file_put_contents("{$file}", file_get_contents($image_url));
        } else {
            $image_url = "{$image}";
            file_put_contents("{$file}", file_get_contents($image_url));
        }

        $response = true;

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function google_login() {

    try {

        // init configuration
        $clientID = '';
        $clientSecret = '';
        $get_all_url = get_all_url();
        $redirectUri = "{$get_all_url}/callback";

        $client = new Google_Client();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->addScope("email");
        $client->addScope("profile");

        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($token['access_token']);

            // get profile info
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
            $email =  $google_account_info->email;
            $name =  $google_account_info->name;
            $picture = $google_account_info->picture;

            $session_login = [
                'fullname' => $google_account_info->name,
                'email' => $google_account_info->email,
                'picture' => $google_account_info->picture
            ];

            $google_login_picture = google_login_picture($session_login['picture'], $session_login['email']);

            $_SESSION['login'] = $session_login;

            $response = [
                'url_login' => false,
                'status' => 'success'
            ];

            $url_login = '';

        } else {
            //session_destroy();
            //session_unset();
            $url_login = $client->createAuthUrl();
            $url_login_status = 'false';
            $response = [
                'url_login' => $url_login,
                'status' => $url_login_status
            ];
        }

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function get_callback($data) {

    try {

        $code = rand(10,99).rand(10,99).rand(10,99);
        $uuid = get_uuid();

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $ttb_users = $sql_conn->executeStatement("INSERT INTO `btw_users` (`auto_id`, `user_fullname`, `user_email`, `user_picture`, `user_code`, `user_uuid`) VALUES (NULL, '{$data['fullname']}', '{$data['email']}', '{$data['picture']}', '{$code}', '{$uuid}') ON DUPLICATE KEY UPDATE user_fullname='{$data['fullname']}', user_picture='{$data['picture']}', user_code='{$code}', user_uuid='{$uuid}'");

        $response = true;

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function get_user_data($login)
{

    try {

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = "SELECT * FROM btw_users WHERE user_email = '{$login['email']}'";
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {
                $response = [
                    'auto_id' => (int) $row['auto_id'],
                    'user_fullname' => (string) $row['user_fullname'],
                    'user_email' => (string) $row['user_email'],
                    'user_picture' => (string) $row['user_picture'],
                    'user_code' => (string) $row['user_code'],
                    'user_uuid' => (string) $row['user_uuid'],
                    'user_phone' => (string) ($row['user_phone'] !== "0") ? $row['user_phone'] : false
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

function get_qr_comprobate($uuid)
{

    try {

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = "SELECT * FROM btw_users WHERE user_uuid = '{$uuid}'";
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {
                if ($row['user_status'] === 1 || $row['user_status'] === "1") {
                    $response = true;
                } else {
                    $response = false;
                }
            }

        } else {
            $response = false;
        }

    } catch (Exception $e) {
        $response = false;
    }

    return $response;
}

function get_qr($uuid) {

    try {

        if ($uuid !== false) {

            $result = Endroid\QrCode\Builder\Builder::create()
                ->writer(new Endroid\QrCode\Writer\PngWriter())
                ->writerOptions([])
                ->data("https://api.whatsapp.com/send?phone=573052518238&text={$uuid}")
                ->encoding(new Endroid\QrCode\Encoding\Encoding('UTF-8'))
                ->errorCorrectionLevel(new Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh())
                ->size(500)
                ->margin(10)
                ->roundBlockSizeMode(new Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin())
                ->logoPath('../assets/logo_qr.png')
                //->labelText('Little Sagan')
                //->labelFont(new NotoSans(20))
                //->labelAlignment(new LabelAlignmentCenter())
                ->build();

            $response = $result->getDataUri();
            //$response = $code;
        } else {
            $response = false;
        }

    } catch (Exception $e) {
        $response = $e->getMessage();
    }

    return $response;

}