<?php
function get_messages_process() {

    try {

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = "SELECT chat_msg_id FROM btw_chats WHERE chat_status = 0 AND chat_analyze = 1 ORDER BY chat_date ASC";
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {

                $get_perspective = get_perspective($row['chat_msg_id']);
                $get_sentiment = get_sentiment($row['chat_msg_id']);

                $response_data = [
                    'msg_toxicity' => $get_perspective['msg_toxicity'],
                    'msg_severe_toxicity' => $get_perspective['msg_severe_toxicity'],
                    'msg_magnitude' => $get_sentiment['msg_magnitude'],
                    'msg_score' => $get_sentiment['msg_score']
                ];

                $sql_conn->update('btw_chats', $response_data, ['chat_msg_id' => "{$row['chat_msg_id']}"]);

            }

            $response = true;

        } else {
            $response = false;
        }

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function get_messages($profile_login) {

    try {

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = "SELECT * FROM btw_chats WHERE chat_target = '{$profile_login}' AND chat_status = 0 ORDER BY chat_date DESC";
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {

                $response_msgs[$row['chat_msg_id']] = $row['chat_msg_id'];

            }

        } else {
            $response = false;
        }

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function get_perspective($chat_msg_id) {

    try {

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = "SELECT * FROM btw_msg_perspective WHERE chat_msg_id = '{$chat_msg_id}'";
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {
                $response = [
                    'msg_toxicity' => $row['msg_toxicity'],
                    'msg_severe_toxicity' => $row['msg_severe_toxicity']
                ];
            }

        } else {
            $response = $sql_query;
        }

    } catch (Exception $e) {
        $response = $e->getMessage();
    }

    return $response;

}

function get_sentiment($chat_msg_id) {

    try {

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = "SELECT * FROM btw_msg_sentiment WHERE chat_msg_id = '{$chat_msg_id}'";
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {
                $response = [
                    'msg_magnitude' => $row['msg_magnitude'],
                    'msg_score' => $row['msg_score']
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

function ws_send($phone, $message) {

    try {

        $get_server = get_server();
        $get_folder = 'api';

        $url = "http://localhost:5010/chat/sendmessage/{$phone}";
        $options = array(
            'timeout' => 120
        );

        $data_msg = array(
            'message' => $message
        );
        $payload = json_encode($data_msg, true);
        $request = Requests::post($url, array(), $data_msg);

        $data = json_decode($request->body, true);
        $response = $data;#$request->body;

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function ws_send_report_welcome($fullname, $phone) {

    try {

        $response_msg_welcome = <<<EOT
        Hola {$fullname}, soy Billy, The BullyWatcher y este es el resumen de los últimos cinco minutos de actividad que deberías revisar en el chat de tu hij@ 🙂
        EOT;

        ws_send($phone, $response_msg_welcome);

        $response = true;

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function ws_send_report($phone, $report_unique) {

    try {

        $get_server = get_server();

        $response_msg = <<<EOT
        https://www.bullythewatcher.com/btw/users/reports-view-all/{$report_unique}
        EOT;

        ws_send($phone, $response_msg);

        $response = true;

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function get_report() {

    try {

        $get_report_create = get_report_create();

        if (isset($get_report_create) && isset($get_report_create['reports']) && isset($get_report_create['messages'])) {

            if ($get_report_create['reports'] !== false && $get_report_create['messages'] !== false) {

                $sql_connectionParams = get_connection_params();
                $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);

                # create report - begin
                foreach ($get_report_create['reports'] as $report_key => $report_key_value) {

                    foreach ($report_key_value as $message_key => $message_value) {

                        $response_data = [
                            'user_id' => $message_value['user_id'],
                            'profile_id' => $message_value['profile_id'],
                            'report_unique' => (string) md5("{$report_key}_{$message_value['report_uuid']}_{$message_key}"),
                            'report_id' => (string) $report_key,
                            'report_uuid' => (string) $message_value['report_uuid'],
                            'report_uin' => (string) $message_key,
                            'report_created_at' => (string) $message_value['report_created_at']
                        ];

                        # insert report - begin
                        $sql_conn->executeStatement("INSERT IGNORE INTO `btw_reports` (`auto_id`, `user_id`, `profile_id`, `report_unique`, `report_id`, `report_uuid`, `report_uin`, `report_created_at`) VALUES (NULL, '{$response_data['user_id']}', '{$response_data['profile_id']}', '{$response_data['report_unique']}', '{$response_data['report_id']}', '{$response_data['report_uuid']}', '{$response_data['report_uin']}', '{$response_data['report_created_at']}')");
                        # insert report - end

                        foreach ($get_report_create['messages'][$report_key][$message_key] as $chat_msg) {
                            $response_data_msg = [
                                'report_unique' => (string) md5("{$report_key}_{$message_value['report_uuid']}_{$message_key}"),
                                'report_id' => (string) $report_key,
                                'report_uuid' => (string) $message_value['report_uuid'],
                                'report_uin' => (string) $message_key,
                                'chat_uin' => (string) md5("{$report_key}_{$message_value['report_uuid']}_{$message_key}_{$chat_msg}"),
                                'chat_msg_id' => (string) $chat_msg
                            ];

                            # insert report msg - begin
                            $sql_conn->executeStatement("INSERT IGNORE INTO `btw_reports_msg` (`auto_id`, `report_unique`, `report_id`, `report_uuid`, `report_uin`, `chat_uin`, `chat_msg_id`) VALUES (NULL, '{$response_data_msg['report_unique']}', '{$response_data_msg['report_id']}', '{$response_data_msg['report_uuid']}', '{$response_data_msg['report_uin']}', '{$response_data_msg['chat_uin']}', '{$response_data_msg['chat_msg_id']}')");
                            # insert report msg - end

                        }

                    }

                }
                # create report - end

                # chat msg id - begin
                foreach ($get_report_create['chat_msg_id'] as $chat_key => $chat_value) {
                    $response_data_msg_id[] = $chat_value;
                    $sql_conn->update('btw_chats', ['chat_status' => 1], ['chat_msg_id' => "{$chat_value}"]);
                }
                # chat msg id - end

                # debug chats - begin
                get_report_create_debug();
                # debug chats - end

                $response = true;

            } else {
                $response = false;
            }

        } else {
            $response = false;
        }

    } catch (Exception $e) {
        $response = $e->getMessage();
    }

    return $response;

}

function get_report_create() {

    try {

        $sql_query_params = <<<EOT
        SELECT
            btw_users.auto_id as user_id,
            btw_accounts.account_uin,
            btw_accounts.profile_id,
            btw_chats.chat_msg_id,
            btw_chats.chat_msg,
            btw_twitch_profiles.profile_login as chat_target,
            btw_chats.chat_username,
            btw_chats.chat_stop_words,
            btw_chats.msg_toxicity,
            btw_chats.msg_severe_toxicity,
            btw_chats.msg_magnitude,
            btw_chats.msg_score,
            (SUM(btw_chats.msg_toxicity)+SUM(btw_chats.msg_severe_toxicity)+SUM(btw_chats.msg_score)+SUM(btw_chats.chat_stop_words)) as total_report
        FROM
            btw_users,
            btw_accounts,
            btw_twitch_profiles,
            btw_chats
        WHERE
            btw_users.auto_id = btw_accounts.user_id AND 
            btw_accounts.profile_id = btw_twitch_profiles.profile_id AND 
            btw_twitch_profiles.profile_login = btw_chats.chat_target AND 
            btw_chats.chat_status = 0 AND 
            btw_chats.chat_analyze = 1 AND 
            btw_chats.msg_toxicity > 5 AND 
            btw_chats.msg_severe_toxicity > 5
        GROUP BY
            btw_users.auto_id, btw_accounts.account_uin, btw_accounts.profile_id, btw_chats.chat_target, btw_chats.chat_username, btw_chats.chat_msg_id
        ORDER BY
            btw_users.auto_id, btw_accounts.account_uin, btw_accounts.profile_id, btw_chats.chat_target, btw_chats.chat_username, btw_chats.chat_msg_id ASC;
        EOT;

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = $sql_query_params;
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {

                $report_uuid = md5(get_uuid());
                $report_uin = md5("{$row['user_id']}_{$row['account_uin']}_{$row['profile_id']}");

                $response['chat_msg_id'][$row['chat_msg_id']] = $row['chat_msg_id'];

                $response['reports'][$report_uin][$row['account_uin']] = [
                    'user_id' => (int) $row['user_id'],
                    'profile_id' => (int) $row['profile_id'],
                    'report_uuid' => (string) $report_uuid,
                    'report_uin' => (string) $row['account_uin'],
                    'report_created_at' => (string) date('Y-m-d H:i:s', strtotime('-5 hours'))
                ];

                $response['messages'][$report_uin][$row['account_uin']][] = (string) $row['chat_msg_id'];

            }

            if (!isset($response)) {
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

function get_report_create_debug() {

    try {

        $sql_query_params = <<<EOT
        SELECT
            btw_chats.chat_msg_id
        FROM
            btw_users,
            btw_accounts,
            btw_twitch_profiles,
            btw_chats
        WHERE
            btw_users.auto_id = btw_accounts.user_id AND
            btw_accounts.profile_id = btw_twitch_profiles.profile_id AND
            btw_twitch_profiles.profile_login = btw_chats.chat_target AND
            btw_chats.chat_status = 0 AND
            btw_chats.chat_analyze = 1 AND
            btw_chats.msg_toxicity <= 5 AND
            btw_chats.msg_severe_toxicity <= 5
        GROUP BY
            btw_chats.chat_msg_id
        EOT;

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = $sql_query_params;
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {

                $sql_conn->update('btw_chats', ['chat_status' => 1], ['chat_msg_id' => "{$row['chat_msg_id']}"]);

            }

            if (!isset($response)) {
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

function get_report_data_content($user_id) {

    try {

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = "SELECT user_id, report_unique, report_uuid, report_uin FROM btw_reports WHERE user_id = '{$user_id}' AND report_status = 0";
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {

                $response[] = [
                    'user_id' => $row['user_id'],
                    'report_unique' => $row['report_unique'],
                    'report_uuid' => $row['report_uuid'],
                    'report_uin' => $row['report_uin']
                ];

            }

        } else {
            $response = false;
        }

    } catch (Exception $e) {
        $response = $e->getMessage();
    }

    return $response;

}

function get_report_data_recipients() {

    try {

        $sql_query_params = <<<EOT
        SELECT
            btw_users.auto_id as user_id,
            btw_users.user_fullname,
            btw_users.user_phone
        FROM
            btw_users,
            btw_reports
        WHERE
            btw_users.auto_id = btw_reports.user_id AND
            btw_reports.report_status = 0 AND
            btw_users.user_phone <> '0'
        GROUP BY
            btw_users.auto_id
        EOT;

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = $sql_query_params;
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {

                $get_report_data_content = get_report_data_content($row['user_id']);

                $response[] = [
                    'user_id' => $row['user_id'],
                    'user_fullname' => $row['user_fullname'],
                    'user_phone' => $row['user_phone'],
                    'reports' => $get_report_data_content
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

function get_report_send() {

    try {

        $get_report_data_recipients = get_report_data_recipients();

        #$response = $get_report_send_data;

        if ($get_report_data_recipients !== false) {

            $sql_connectionParams = get_connection_params();
            $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);

            foreach ($get_report_data_recipients as $report_recipients) {

                ws_send_report_welcome($report_recipients['user_fullname'], $report_recipients['user_phone']);

                foreach ($report_recipients['reports'] as $recipient_report) {
                    ws_send_report($report_recipients['user_phone'], $recipient_report['report_unique']);
                    $sql_conn->executeStatement("UPDATE btw_reports SET report_status = 1 WHERE report_uuid = '{$recipient_report['report_uuid']}' AND report_uin = '{$recipient_report['report_uin']}'; ");
                }

            }

            $response = true;

        } else {
            $response = false;
        }

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}