<?php

try {
    $HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['ENV_HOME'];

    include "$HOME/api/api-seller/env.php";

    $pdo = new PDO("sqlite:$ENV_SQLITE_DATABASE");

    $sql = "SELECT
                *
            FROM
                OZN_v3_ProductList
            ";

    $sth = $pdo->prepare($sql);
    $sth->execute();
    $array = $sth->fetchAll(PDO::FETCH_ASSOC);

    $jsonString = json_encode($array, JSON_UNESCAPED_UNICODE);

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
