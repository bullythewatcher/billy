<?php
function get_perspective($text) {

    try {

        $commentsClient = new PerspectiveApi\CommentsClient('token');
        $commentsClient->comment(['text' => $text]);
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
            'msg_toxicity' => (int) $msg_toxicity,
            'msg_severe_toxicity' => (int) $msg_severe_toxicity,
        ];

        $response = $data_params;

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function get_sentiment($text) {

    try {

        $languageServiceClient = new Google\Cloud\Language\V1\LanguageServiceClient([
            'credentials' => 'config.json'
        ]);
        try {
            // Create a new Document, pass text and set type to PLAIN_TEXT
            $document = (new Google\Cloud\Language\V1\Document())
                ->setContent($text)
                ->setType(Google\Cloud\Language\V1\Document\Type::PLAIN_TEXT);

            // Set Features to extract ['entities', 'syntax', 'sentiment']
            $features = (new Google\Cloud\Language\V1\AnnotateTextRequest\Features())
                ->setExtractEntities(true)
                ->setExtractSyntax(true)
                ->setExtractDocumentSentiment(true);

            $response_data = $languageServiceClient->annotateText($document, $features);

            $entities = $response_data->getEntities();
            if ($entities) {

                foreach ($entities as $entity) {
                    $response_data_entities[] = [
                        'uin' => md5(strtolower($entity->getName())."_".Google\Cloud\Language\V1\Entity\Type::name($entity->getType())),
                        'name' => strtolower($entity->getName()),
                        'type' => Google\Cloud\Language\V1\Entity\Type::name($entity->getType()),
                        'salience' => round($entity->getSalience()*100, 0),
                    ];
                }

            }

            $document_sentiment = $response_data->getDocumentSentiment();

            $response_sentiment = [
                'magnitude' => round($document_sentiment->getMagnitude()*100, 0),
                'score' => round($document_sentiment->getScore()*100, 0)
            ];

            $sentences = $response_data->getSentences();
            foreach ($sentences as $sentence) {

                $sentiment = $sentence->getSentiment();
                if ($sentiment) {
                    $response_sentences[] = [
                        'magnitude' => round($sentiment->getMagnitude()*100, 0),
                        'score' => round($sentiment->getScore()*100, 0)
                    ];
                } else {
                    $response_sentences[] = [
                        'content' => $sentence->getText()->getContent(),
                        'magnitude' => 0,
                        'score' => 0
                    ];
                }

            }

            $tokens = $response_data->getTokens();

            foreach ($tokens as $token) {
                $response_tokens[] = [
                    'uin' => md5(strtolower($token->getText()->getContent())."_".Google\Cloud\Language\V1\PartOfSpeech\Tag::name($token->getPartOfSpeech()->getTag())),
                    'text' => strtolower($token->getText()->getContent()),
                    'part' => Google\Cloud\Language\V1\PartOfSpeech\Tag::name($token->getPartOfSpeech()->getTag())
                ];
            }

        } finally {
            $languageServiceClient->close();
        }

        $response = [
            'sentiment' => (isset($response_sentiment)) ? $response_sentiment : false,
            'sentences' => (isset($response_sentences)) ? $response_sentences : false,
            'tokens' => (isset($response_tokens)) ? $response_tokens : false,
            'entities' => (isset($response_data_entities)) ? $response_data_entities : false
        ];

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function get_analyze($text) {

    try {

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);

        $get_perspective = get_perspective($text);
        $get_sentiment = get_sentiment($text);

        if ($get_perspective !== false) {

            $data_perspective = [
                'msg_toxicity' => (int) $get_perspective['msg_toxicity'],
                'msg_severe_toxicity' => (int) $get_perspective['msg_severe_toxicity']
            ];

        }

        if ($get_sentiment !== false) {

            if (isset($get_sentiment['sentiment'])) {
                $data_sentiment = [
                    'msg_magnitude' => (int) $get_sentiment['sentiment']['magnitude'],
                    'msg_score' => (int) $get_sentiment['sentiment']['score']
                ];
            }

            if (isset($get_sentiment['sentences'])) {

                foreach ($get_sentiment['sentences'] as $sentences) {

                    $data_sentences[] = [
                        'msg_magnitude' => (int) $sentences['magnitude'],
                        'msg_score' => (int) $sentences['score']
                    ];

                }

            }

            if (isset($get_sentiment['tokens']) && $get_sentiment['tokens'] !== false) {
                foreach ($get_sentiment['tokens'] as $data_token) {
                    $data_tokens[] = [
                        'token_uin' => $data_token['uin'],
                        'token_text' => $data_token['text'],
                        'token_part' => $data_token['part']
                    ];
                }
            }

            if (isset($get_sentiment['entities']) && $get_sentiment['entities'] !== false) {
                foreach ($get_sentiment['entities'] as $data_entitie) {
                    $data_entities[] = [
                        'entitie_uin' => $data_entitie['uin'],
                        'entitie_name' => strtolower($data_entitie['name']),
                        'entitie_type' => $data_entitie['type'],
                        'entitie_salience' => $data_entitie['salience']
                    ];
                }
            }

        }

        $response = [
            'perspective' => [
                'msg_toxicity' => (int) (isset($data_perspective['msg_toxicity'])) ? $data_perspective['msg_toxicity'] : 0,
                'msg_severe_toxicity' => (int) (isset($data_perspective['msg_severe_toxicity'])) ? $data_perspective['msg_severe_toxicity'] : 0
            ],
            'sentiment' => [
                'msg_magnitude' => (int) (isset($data_sentiment['msg_magnitude'])) ? $data_sentiment['msg_magnitude'] : 0,
                'msg_score' => (int) (isset($data_sentiment['msg_score'])) ? $data_sentiment['msg_score'] : 0
            ]
        ];

        if (isset($data_tokens)) {
            $response['tokens'] = $data_tokens;
        }

        if (isset($data_entities)) {
            $response['entities'] = $data_entities;
        }

    } catch (Exception $e) {
        $response = $e->getMessage();
    }

    return $response;

}

function get_analyze_tokens($chat_msg_id, $tokens)
{

    try {

        if (isset($tokens)) {

            foreach ($tokens as $token) {
                $tokens_data[] = (string) "'{$token}'";
            }

            $stop_words_compare = implode(",", $tokens_data);

            $sql_query_params = <<<EOT
            SELECT
                *
            FROM
                btw_training_tokens
            WHERE
                btw_training_tokens.token_text IN ({$stop_words_compare})
            EOT;

            $sql_connectionParams = get_connection_params();
            $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);
            $sql_query = $sql_query_params;
            $sql_execute = $sql_conn->query($sql_query);
            $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

            if ($sql_total > 0) {
                $response = $sql_total;
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

function get_analyze_training($text) {

    try {

        $get_sentiment = get_sentiment($text);

        if ($get_sentiment !== false) {

            if (isset($get_sentiment['tokens'])) {
                foreach ($get_sentiment['tokens'] as $data_token) {
                    $data_tokens[] = [
                        'token_uin' => $data_token['uin'],
                        'token_text' => $data_token['text'],
                        'token_part' => $data_token['part']
                    ];
                }
            }

        }

        $response = ($data_tokens) ? $data_tokens : false;

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}

function get_msg($data) {

    try {

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);

        if (isset($data['chat_username']) && isset($data['chat_msg'])) {

            #$get_analyze = get_analyze($data['chat_msg']);

            # btw_chats - begin
            $data_btw_chats = [
                'chat_msg_id' => (string) $data['chat_id'],
                'chat_date' => date('Y-m-d H:i:s', strtotime('-5 hours')),
                'chat_target' => (string) $data['chat_target'],
                'chat_username' => (string) $data['chat_username'],
                'chat_msg' => (string) $data['chat_msg']
            ];

            $sql_conn->executeStatement("INSERT IGNORE INTO `btw_chats` (`auto_id`, `chat_msg_id`, `chat_date`, `chat_target`, `chat_username`, `chat_msg`) VALUES (NULL, '{$data_btw_chats['chat_msg_id']}', '{$data_btw_chats['chat_date']}', '{$data_btw_chats['chat_target']}', '{$data_btw_chats['chat_username']}', '{$data_btw_chats['chat_msg']}')");
            # btw_chats - end

            $response = true;

        } else {
            $response = 2;
        }

    } catch (Exception $e) {
        $response = $e->getMessage();
    }

    return $response;

}

function get_msg_process() {

    try {

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);

        $sql_query = "SELECT * FROM btw_chats WHERE chat_analyze = 0 LIMIT 25";
        $sql_execute = $sql_conn->query($sql_query);
        $sql_total = count($sql_conn->fetchAllAssociativeIndexed($sql_query));

        if ($sql_total > 0) {

            while (($row = $sql_execute->fetchAssociative()) !== false) {

                $get_analyze = get_analyze($row['chat_msg']);

                # btw_msg_entities - begin
                if (isset($get_analyze['entities'])) {
                    foreach ($get_analyze['entities'] as $entitie) {
                        $data_btw_msg_entities = [
                            'chat_msg_id' => (string) $row['chat_msg_id'],
                            'entitie_uin' => (string) $entitie['entitie_uin'],
                            'entitie_name' => (string) $entitie['entitie_name'],
                            'entitie_type' => (string) $entitie['entitie_type'],
                            'entitie_salience' => (int) $entitie['entitie_salience']
                        ];
                        #$sql_conn->insert('btw_msg_entities', $data_btw_msg_entities);
                        $sql_conn->executeStatement("INSERT IGNORE INTO `btw_msg_entities` (`auto_id`, `chat_msg_id`, `entitie_uin`, `entitie_name`, `entitie_type`, `entitie_salience`) VALUES (NULL, '{$data_btw_msg_entities['chat_msg_id']}', '{$data_btw_msg_entities['entitie_uin']}', '{$data_btw_msg_entities['entitie_name']}', '{$data_btw_msg_entities['entitie_type']}', '{$data_btw_msg_entities['entitie_salience']}')");
                    }
                }
                # btw_msg_entities - end

                # btw_msg_perspective - begin
                if (isset($get_analyze['perspective'])) {
                    $data_btw_msg_perspective = [
                        'chat_msg_id' => (string) $row['chat_msg_id'],
                        'msg_toxicity' => (int) ($get_analyze['perspective']['msg_toxicity'] > 5) ? ($get_analyze['perspective']['msg_toxicity']*1.5) : $get_analyze['perspective']['msg_toxicity'],
                        'msg_severe_toxicity' => (int) ($get_analyze['perspective']['msg_severe_toxicity'] > 5) ? ($get_analyze['perspective']['msg_severe_toxicity']*1.5) : $get_analyze['perspective']['msg_severe_toxicity']
                    ];
                    #$sql_conn->insert('btw_msg_perspective', $data_btw_msg_perspective);
                    $sql_conn->executeStatement("INSERT IGNORE INTO `btw_msg_perspective` (`auto_id`, `chat_msg_id`, `msg_toxicity`, `msg_severe_toxicity`) VALUES (NULL, '{$data_btw_msg_perspective['chat_msg_id']}', '{$data_btw_msg_perspective['msg_toxicity']}', '{$data_btw_msg_perspective['msg_severe_toxicity']}')");
                }
                # btw_msg_perspective - end

                # btw_msg_sentiment - begin
                if (isset($get_analyze['sentiment'])) {
                    $data_btw_msg_sentiment = [
                        'chat_msg_id' => (string) $row['chat_msg_id'],
                        #'msg_magnitude' => (int) $get_analyze['sentiment']['msg_magnitude'],
                        'msg_magnitude' => (int) ($get_analyze['sentiment']['msg_magnitude'] < 0) ? (($get_analyze['sentiment']['msg_magnitude']*-1)*1.2) : ($get_analyze['sentiment']['msg_magnitude']*0.7),
                        'msg_score' => (int) ($get_analyze['sentiment']['msg_score'] < 0) ? (($get_analyze['sentiment']['msg_score']*-1)*1.2) : ($get_analyze['sentiment']['msg_score']*0.7)
                    ];
                    #$sql_conn->insert('btw_msg_sentiment', $data_btw_msg_sentiment);
                    $sql_conn->executeStatement("INSERT IGNORE INTO `btw_msg_sentiment` (`auto_id`, `chat_msg_id`, `msg_magnitude`, `msg_score`) VALUES (NULL, '{$data_btw_msg_sentiment['chat_msg_id']}', '{$data_btw_msg_sentiment['msg_magnitude']}', '{$data_btw_msg_sentiment['msg_score']}')");
                }
                # btw_msg_sentiment - end

                # btw_msg_tokens - begin
                if (isset($get_analyze['tokens'])) {
                    foreach ($get_analyze['tokens'] as $token) {
                        $data_btw_msg_tokens = [
                            'chat_msg_id' => (string) $row['chat_msg_id'],
                            'token_uin' => (string) $token['token_uin'],
                            'token_text' => (string) $token['token_text'],
                            'token_part' => (string) $token['token_part']
                        ];

                        $data_btw_msg_tokens_data[] = $token['token_text'];

                        #$sql_conn->insert('btw_msg_tokens', $data_btw_msg_tokens);
                        $sql_conn->executeStatement("INSERT IGNORE INTO `btw_msg_tokens` (`auto_id`, `chat_msg_id`, `token_uin`, `token_text`, `token_part`) VALUES (NULL, '{$data_btw_msg_tokens['chat_msg_id']}', '{$data_btw_msg_tokens['token_uin']}', '{$data_btw_msg_tokens['token_text']}', '{$data_btw_msg_tokens['token_part']}')");
                    }

                    if (isset($data_btw_msg_tokens_data)) {
                        $get_analyze_tokens = get_analyze_tokens($row['chat_msg_id'], $data_btw_msg_tokens_data);
                        $sql_conn->update('btw_chats', ['chat_stop_words' => $get_analyze_tokens], ['chat_msg_id' => "{$row['chat_msg_id']}"]);
                    }

                }
                # btw_msg_tokens - end

                $sql_conn->update('btw_chats', ['chat_analyze' => 1], ['chat_msg_id' => "{$row['chat_msg_id']}"]);

                unset($data_btw_msg_tokens_data);

            }

            $response = true;

        } else {
            $response = false;
        }

    } catch (Exception $e) {
        $response = $e->getMessage();
    }

    return $response;

}

function get_msg_training($data) {

    try {

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);

        if (isset($data['chat_msg'])) {

            $get_analyze = get_analyze_training($data['chat_msg']);

            # btw_msg_tokens - begin
            if (isset($get_analyze)) {
                foreach ($get_analyze as $token) {
                    $btw_training_tokens = $sql_conn->executeStatement("INSERT IGNORE INTO `btw_training_tokens` (`auto_id`, `token_uin`, `token_text`, `token_part`) VALUES (NULL, '{$token['token_uin']}', '{$token['token_text']}', '{$token['token_part']}')");
                }
            }
            # btw_msg_tokens - end

            $response = $get_analyze;

        } else {
            $response = false;
        }

    } catch (Exception $e) {
        $response = false;
    }

    return $response;

}