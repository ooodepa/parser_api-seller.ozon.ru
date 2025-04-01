<?php

$HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['PHP_CRON_HOME'];

include_once "$HOME/env.php";
include_once "$HOME/models/OZN_v3_ProductList.php";

class v3_ProductListService {
    public function executeCron() {
        global $HOME;

        $data = v3_ProductListService::fetchJson__getAllProducts();

        $FILE_PATH = "$HOME/data/v3_ProductListService.json";

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

        OZN_v3_ProductList::recreateDatabaseAndInsertRows($data);
    }

    static function getProductIdArray() {
        $arr = v3_ProductListService::fetchJson__getAllProducts();
        $productIdArray = array_map(function($element) {
            return $element['product_id'];
        }, $arr);
    }

    static function fetchJson__getAllProducts() {
        return array_merge(
            v3_ProductListService::fetchJson__getNotArchivedProducts()['result']['items'],
            v3_ProductListService::fetchJson__getArchivedProducts()['result']['items']
        );
    }

    static function fetchJson__getNotArchivedProducts() {
        return v3_ProductListService::fetchJson([
            'limit' => 1000,
            'filter' => [
                'visibility' => 'ALL',
            ],
        ]);
    }

    static function fetchJson__getArchivedProducts() {
        return v3_ProductListService::fetchJson([
            'limit' => 1000,
            'filter' => [
                'visibility' => 'ARCHIVED',
            ],
        ]);
    }

    static function fetchJson($data) {
        global $env;

        $URI = "/v3/product/list";
        $FETCH_URL = "https://api-seller.ozon.ru$URI";

        $string_json = json_encode($data);
        $http_data = $string_json;

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
