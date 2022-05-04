<?php
function get_perspective($text) {

    try {

        $commentsClient = new PerspectiveApi\CommentsClient('');
        $commentsClient->comment(['text' => $text]);
        $commentsClient->languages(['es']);
        $commentsClient->context(['entries' => ['text' => 'off-topic', 'type' => 'PLAIN_TEXT']]);
        $commentsClient->requestedAttributes(['TOXICITY' => ['scoreType' => 'PROBABILITY', 'scoreThreshold' => 0],'SEVERE_TOXICITY' => ['scoreType' => 'PROBABILITY', 'scoreThreshold' => 0]]);

        $response_data = $commentsClient->analyze();
        $response_attributes = $response_data->attributeScores();

        $msg_toxicity_data = (isset($response_attributes['TOXICITY']['summaryScore']['value'])) ? $response_attributes['TOXICITY']['summaryScore']['value'] : 0;
        $msg_toxicity = round($msg_toxicity_data*100, 2);

        $msg_severe_toxicity_data = (isset($response_attributes['SEVERE_TOXICITY']['summaryScore']['value'])) ? $response_attributes['TOXICITY']['summaryScore']['value'] : 0;
        $msg_severe_toxicity = round($msg_severe_toxicity_data*100, 2);

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
            foreach ($entities as $entity) {
                $response_data_entities[] = [
                    'name' => $entity->getName(),
                    'type' => Google\Cloud\Language\V1\Entity\Type::name($entity->getType()),
                    'salience' => round($entity->getSalience()*100, 2),
                ];

            }

            $document_sentiment = $response_data->getDocumentSentiment();

            $response_sentiment = [
                'magnitude' => round($document_sentiment->getMagnitude()*100, 2),
                'score' => round($document_sentiment->getScore()*100, 2)
            ];

            $sentences = $response_data->getSentences();
            foreach ($sentences as $sentence) {

                $sentiment = $sentence->getSentiment();
                if ($sentiment) {
                    $response_sentences[] = [
                        //'content' => $sentence->getText()->getContent(),
                        'magnitude' => round($sentiment->getMagnitude()*100, 2),
                        'score' => round($sentiment->getScore()*100, 2)
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
                    'text' => $token->getText()->getContent(),
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
        $response = $e->getMessage();
    }

    return $response;

}

function get_analyze($uuid, $text) {

    try {

        $sql_connectionParams = get_connection_params();
        $sql_conn = \Doctrine\DBAL\DriverManager::getConnection($sql_connectionParams);

        $get_perspective = get_perspective($text['text']);
        $get_sentiment = get_sentiment($text['text']);

        if ($get_perspective !== false) {

            $data_perspective = [
                'chat_uuid' => $uuid,
                'msg_toxicity' => (float) $get_perspective['msg_toxicity'],
                'msg_severe_toxicity' => (float) $get_perspective['msg_severe_toxicity']
            ];

            $sql_conn->insert('btw_msg_perspective', $data_perspective);
            $sql_conn->update('btw_chats', ['status_perspective' => 1], ['chat_uuid' => $uuid]);

        }

        if ($get_sentiment !== false) {

            if (isset($get_sentiment['sentiment'])) {
                $data_sentiment = [
                    'chat_uuid' => $uuid,
                    'msg_magnitude' => (float) $get_sentiment['sentiment']['magnitude'],
                    'msg_score' => (float) $get_sentiment['sentiment']['score']
                ];

                $sql_conn->insert('btw_msg_sentiment', $data_sentiment);
            }

            if (isset($get_sentiment['sentences'])) {

                foreach ($get_sentiment['sentences'] as $sentences) {

                    $data_sentences = [
                        'chat_uuid' => $uuid,
                        'msg_magnitude' => (float) $sentences['magnitude'],
                        'msg_score' => (float) $sentences['score']
                    ];

                    $sql_conn->insert('btw_msg_sentences', $data_sentences);

                }

            }

            if (isset($get_sentiment['tokens'])) {

                foreach ($get_sentiment['tokens'] as $tokens) {

                    if (strlen($tokens['text']) > 1) {
                        $data_tokens = [
                            'chat_uuid' => $uuid,
                            'msg_text' => (string) strtolower($tokens['text']),
                            'msg_part' => (string) $tokens['part']
                        ];
                        $sql_conn->insert('btw_msg_tokens', $data_tokens);
                    }

                }

            }

            if (isset($get_sentiment['entities'])) {

                foreach ($get_sentiment['entities'] as $entities) {

                    $data_entities = [
                        'chat_uuid' => $uuid,
                        'msg_name' => (string) strtolower($entities['name']),
                        'msg_type' => (string) $entities['type'],
                        'msg_salience' => (float) $entities['salience']
                    ];

                    $sql_conn->insert('btw_msg_entities', $data_entities);

                }

            }

            $sql_conn->update('btw_chats', ['status_sentiment' => 1], ['chat_uuid' => $uuid]);

        }

        $response = true;

    } catch (Exception $e) {
        $response = $e->getMessage();
    }

    return $response;

}