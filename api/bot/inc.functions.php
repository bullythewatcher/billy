<?php
function get_data($data) {

    try {

        $data_comments = [
            'TOXICITY' => [
                'scoreType' => 'PROBABILITY', 'scoreThreshold' => 0
            ],
            'SEVERE_TOXICITY' => [
                'scoreType' => 'PROBABILITY', 'scoreThreshold' => 0
            ],
            'IDENTITY_ATTACK' => [
                'scoreType' => 'PROBABILITY', 'scoreThreshold' => 0
            ],
            'INSULT' => [
                'scoreType' => 'PROBABILITY', 'scoreThreshold' => 0
            ],
            'PROFANITY' => [
                'scoreType' => 'PROBABILITY', 'scoreThreshold' => 0
            ],
            'THREAT' => [
                'scoreType' => 'PROBABILITY', 'scoreThreshold' => 0
            ]
        ];

        $commentsClient = new PerspectiveApi\CommentsClient('token');
        $commentsClient->comment(['text' => $data]);
        $commentsClient->languages(['es']);
        $commentsClient->context(['entries' => ['text' => 'off-topic', 'type' => 'PLAIN_TEXT']]);
        $commentsClient->requestedAttributes(['TOXICITY' => ['scoreType' => 'PROBABILITY', 'scoreThreshold' => 0],'SEVERE_TOXICITY' => ['scoreType' => 'PROBABILITY', 'scoreThreshold' => 0]]);

        $response_data = $commentsClient->analyze();

        $response = [
            'text' => $data,
            'score' => $response_data->attributeScores()
        ];

    } catch (Exception $e) {
        $response = $e->getMessage();
    }

    return $response;

}