<?php

try {
    $data = [
        'message' => "It's work!",
    ];

    $jsonString = json_encode($data, JSON_UNESCAPED_UNICODE);

    http_response_code(200);
    header('Content-Type: application/json; charset=utf-8');
    echo $jsonString;
}
catch(Throwable $exception) {
    http_response_code(500);
    echo "<pre style='color: red;'>";
    print_r($exception);
    echo "</pre>";
}
