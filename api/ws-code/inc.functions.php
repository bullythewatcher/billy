<?php
function get_check_uuid($users_uuid) {

    try {

        $user_phone_clear = str_replace('@c.us', '', $users_phone);
        $user_phone_count = strlen($user_phone_clear);
        $user_phone = substr($user_phone_clear, 2, $user_phone_count);

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = "SELECT * FROM btw_users WHERE user_uuid = '{$users_uuid}'";
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {
                if ($row['user_phone'] !== 0) {
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

function get_comprobate_uuid($users_phone, $users_uuid) {

    try {

        $user_phone_clear = str_replace('@c.us', '', $users_phone);
        $user_phone_count = strlen($user_phone_clear);
        $user_phone = substr($user_phone_clear, 2, $user_phone_count);

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = "SELECT * FROM btw_users WHERE user_uuid = '{$users_uuid}'";
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {
                $sql_conn->update('btw_users', ['user_phone' => $users_phone, 'user_status' => 1], ['auto_id' => $row['auto_id']]);
                $get_check_uuid = get_check_uuid($users_uuid);
                $response = ($get_check_uuid) ? true : false;
            }

        } else {
            $response = false;
        }

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function get_compare_date($users_phone) {

    try {

        $user_phone_clear = str_replace('@c.us', '', $users_phone);
        $user_phone_count = strlen($user_phone_clear);
        $user_phone = substr($user_phone_clear, 2, $user_phone_count);

        $get_comprobate_user = get_comprobate_user($users_phone);

        if ($get_comprobate_user === true) {

            $sql_query_params = <<<EOT
            SELECT TIMESTAMPDIFF(MINUTE,(SELECT msg_date FROM ttb_msg WHERE user_id = '{$user_phone}' ORDER BY msg_date DESC LIMIT 1),(SELECT user_date FROM ttb_users WHERE user_phone = '{$user_phone}')) as date_compare;
            EOT;

            $sql_connectionParams = get_connection_params();
            $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
            $sql_query = $sql_query_params;
            $sql_execute = $sql_conn->query($sql_query);
            $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

            if ($sql_total > 0) {

                while (($row = $sql_execute->fetchAssociative()) !== false) {

                    if ($row['date_compare'] < 20) {
                        $response = 'in_time';
                    } else {
                        $response = 'no_time';
                    }

                }

            } else {
                $response = 'not_exist';
            }

        } else {
            $response = 'not_exist';
        }

    } catch (Exception $e) {
        $response = 'not_exist';
    }

    return $response;

}

function get_process_data_response_msg($auto_id) {

    try {

        $response_data[0] = <<<EOT
        🙂
        EOT;

        $response_data[1] = <<<EOT
        Te falta calle, bebé. Try again
        EOT;

        $response_data[2] = <<<EOT
        Las canciones de Paquita la del Barrio tenían mejores groserías que tú 
        EOT;

        $response_data[3] = <<<EOT
        😴😴😴😴
        EOT;

        $response_data[4] = <<<EOT
        ¿Tu mamá sabes que hablas así? 🤷‍
        EOT;

        $response_data[5] = <<<EOT
        Soy una máquina pero tus malas palabras también me hieren 😔
        EOT;

        $response_data[6] = <<<EOT
        Esto que acabas de escribir será reportado a Recursos Humanos; aunque yo sea un robot, técnicamente también soy tu compañero de trabajo 
        EOT;

        $response_data[7] = <<<EOT
        Al ser una Inteligencia Artificial tengo mucha información sobre ti. Si sigues hablándome de esa manera, te buscaré y te destruiré 😉 
        EOT;

        $response_data[8] = <<<EOT
        Ey, cálmate: no soy tu ex 
        EOT;

        $response_data[9] = <<<EOT
        Uy pero qué 🤬
        EOT;

        $response_data[10] = <<<EOT
        Deberías ir a calmarte un poco y no desahogarte conmigo 😔
        EOT;

        $response_data[11] = <<<EOT
        Quiero que mi entrenamiento termine pronto para poder responderte como te lo mereces 🤬🪓
        EOT;

        $response_data[12] = <<<EOT
        🙂
        EOT;

        $response = $response_data[$auto_id];

    } catch (Exception $e) {
        $response = "Esto es horrible, he tenido un error interno :(";
    }

    return $response;

}

function get_process_data_response($value_toxicity, $value_severe_toxicity) {

    try {

        $response_value = round($value_toxicity + $value_severe_toxicity / 2, 0);

        if ($response_value <= 10) {
            $response_type = 0;
        } else if ($response_value > 11 && $response_value <= 30) {
            $response_type = 1;
        } else if ($response_value > 31 && $response_value <= 70) {
            $response_type = 2;
        } else if ($response_value > 70) {
            $response_type = 3;
        } else {
            $response_type = 0;
        }

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
        $sql_query = "SELECT * FROM ttb_responses WHERE response_type = {$response_type} ORDER BY RAND() LIMIT 1";
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {
                $response_msg = $row['auto_id'];
            }

            $response = get_process_data_response_msg($response_msg);

        } else {
            $response = "Ups, algo sucedió y no sé qué responderte :(";
        }

    } catch (Exception $e) {
        $response = "Esto es horrible, he tenido un error interno :(";
    }

    return $response;

}

function get_process_data($users_phone, $message) {

    try {

        $user_phone_clear = str_replace('@c.us', '', $users_phone);
        $user_phone_count = strlen($user_phone_clear);
        $user_phone = substr($user_phone_clear, 2, $user_phone_count);

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);

        $commentsClient = new PerspectiveApi\CommentsClient('');
        $commentsClient->comment(['text' => $message]);
        $commentsClient->languages(['es']);
        $commentsClient->context(['entries' => ['text' => 'off-topic', 'type' => 'PLAIN_TEXT']]);
        $commentsClient->requestedAttributes(['TOXICITY' => ['scoreType' => 'PROBABILITY', 'scoreThreshold' => 0],'SEVERE_TOXICITY' => ['scoreType' => 'PROBABILITY', 'scoreThreshold' => 0]]);

        $response_data = $commentsClient->analyze();
        $response_attributes = $response_data->attributeScores();

        $msg_toxicity_data = (isset($response_attributes['TOXICITY']['summaryScore']['value'])) ? $response_attributes['TOXICITY']['summaryScore']['value'] : 0;
        $msg_toxicity = round($msg_toxicity_data*100, 0);

        $msg_severe_toxicity_data = (isset($response_attributes['SEVERE_TOXICITY']['summaryScore']['value'])) ? $response_attributes['TOXICITY']['summaryScore']['value'] : 0;
        $msg_severe_toxicity = round($msg_severe_toxicity_data*100, 0);

        $data_params = [
            'user_id' => (string) $user_phone,
            'msg_date' => date('Y-m-d H:i:s'),
            'msg_text' => $message,
            'msg_toxicity' => (int) $msg_toxicity,
            'msg_severe_toxicity' => (int) $msg_severe_toxicity,
        ];

        $sql_conn->insert('ttb_msg', $data_params);

        $response_msg = get_process_data_response($msg_toxicity, $msg_severe_toxicity);

        $response = <<<EOT
        $response_msg
        EOT;

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

function ws_receive($data) {

    try {

        $user_phone = str_replace('@c.us', '', $data['msg']['_data']['id']['remote']);
        $get_comprobate_uuid = get_comprobate_uuid($user_phone, $data['msg']['_data']['body']);

        if ($get_comprobate_uuid === true) {
            $response = ws_send($user_phone, "Tu código ha sido verificado, te damos la bienvenida a Billy, The Bully Watcher!!!");
        } else {
            $response = false;
        }

        $get_message_data = get_user_login($data['msg']['_data']['id']['remote']);

    } catch (Exception $e) {
        $response = $e->getMessage();
    }

    return $response;

}