<?php

$HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['PHP_CRON_HOME'];

include_once "$HOME/env.php";

class v3_PostingFbsGetService {
    public function executeCron() {
        global $HOME;

        $data = v3_PostingFbsGetService::getAll();

        $FILE_PATH = "$HOME/data/v3_PostingFbsGet.json";

        $FILE_TEXT = json_encode(
            [
                'cache' => [
                    'createdAt' => date('Y-m-d_H-i-s'),
                    'data' => $data,
                ],
            ],
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT,
        );

        file_put_contents($FILE_PATH, $FILE_TEXT);
    }

    static function getAll() {
        global $HOME;

        $pdo = new PDO("sqlite:$HOME/database.sqlite");

        $sql = "SELECT
                    p1,
                    p2,
                    p3
                FROM
                    OZN_my_PostingList
                ";

        $sth = $pdo->prepare($sql);
        $sth->execute();
        $array = $sth->fetchAll(PDO::FETCH_ASSOC);

        $result_array = [];

        $length = count($array);
        for ($i = 0; $i < $length; $i++) {

            $current = $array[$i];

            if (strlen($current['p1']) == 0) {
                continue;
            }

            if (strlen($current['p2']) == 0) {
                continue;
            }

            if (strlen($current['p3']) == 0) {
                continue;
            }

            $posting_number = $current['p1'] . '-' . $current['p2'] . '-' . $current['p3'];

            $datetime = date('Y-m-d H:i:s');

            $n = $i + 1;
            $procent = 100 * $n / $length;
            $rounded_procent = round($procent, 2, PHP_ROUND_HALF_UP);
            echo "[$datetime] $n/$length ($rounded_procent %) | $posting_number \n";

            $data = v3_PostingFbsGetService::fetchJson__byPostingNumber($posting_number);

            if ($data == null) {
                array_push($result_array, $data);
            }
        }
        echo "\n";

        return $result_array;
    }

    static function fetchJson__byPostingNumber($postingNumber) {
        $data = v3_PostingFbsGetService::fetchJson([
            "posting_number" => $postingNumber,
            "with" => [
                "analytics_data" => true,
                "barcodes" => true,
                "financial_data" => true,
                "product_exemplars" => true,
                "translit" => true
            ],
        ]);

        return $data['result'];
    }

    static function fetchJson($data) {
        global $env;

        $URI = "/v3/posting/fbs/get";
        $FETCH_URL = "https://api-seller.ozon.ru$URI";

        $json_string = json_encode($data);
        $http_data = $json_string;

        $ozonClientId = $env['ozon-client-id'];
        $ozonApiKey = $env['ozon-api-key'];

        $http_headers = [
            "Content-Type: application/json",
            "Client-Id: $ozonClientId",
            "Api-Key: $ozonApiKey",
        ];

        $http_cookie = implode("; ", [
            "Client-Id=$ozonClientId",
            "Api-Key=$ozonApiKey",
        ]);

        $ch = curl_init($FETCH_URL);                            // Инициализируем cURL сессии
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);                   // Устанавливаем метод POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $http_data);       // Тело запроса
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);    // Устанавливаем заголовки
        curl_setopt($ch, CURLOPT_COOKIE, $http_cookie);         // Передаем куки
        $response = curl_exec($ch);                             // Выполняем запрос и получаем ответ

        if (curl_errno($ch)) {                                  // Проверяем на наличие ошибок
            $err = curl_error($ch);                             // Получаем сообщение об ошибке
            curl_close($ch);                                    // Закрываем cURL сессию
            throw new Error("Fetch error: $err");
        }

        curl_close($ch);                                        // Закрываем cURL сессию
        $json_string = $response;
        $php_object = json_decode($json_string, true);
        return $php_object;
    }
}
