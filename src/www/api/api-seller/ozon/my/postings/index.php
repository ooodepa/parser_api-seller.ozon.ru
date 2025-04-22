<?php

function main() {
    try {
        $HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['ENV_HOME'];

        include "$HOME/api/api-seller/env.php";
        include "$HOME/api/api-seller/_classes/AuthHelper.class.php";

        AuthHelper::exit_ifNotAuth();

        $pdo = new PDO("sqlite:$ENV_SQLITE_DATABASE");

        $sql = "SELECT
                    n,
                    posting_date,
                    p1,
                    p2,
                    p3,
                    fb_type,
                    '_' AS _
                FROM
                    OZN_my_PostingList
                ";

        $sth = $pdo->prepare($sql);
        $sth->execute();
        $array = $sth->fetchAll(PDO::FETCH_ASSOC);

        $response = [
            'data' => $array,
        ];

        $jsonString = json_encode($response, JSON_UNESCAPED_UNICODE);

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
}

main();
