<?php

function main() {
    try {
        $HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['ENV_HOME'];

        include "$HOME/api/api-seller/env.php";
        include "$HOME/api/api-seller/_classes/AuthHelper.class.php";

        AuthHelper::exit_ifNotAuth();

        $pdo = new PDO("sqlite:$ENV_SQLITE_DATABASE");

        $sql = "SELECT
                    v3_ProdList.product_id AS код_продукта_ozon,
                    v3_ProdList.offer_id AS артикул_продавца,
                    v3_ProdInfoAttr.sku AS sku,
                    'https://ozon.ru/product/' || v3_ProdInfoAttr.sku AS ссылка_ozon,
                    v3_ProdInfoList.primary_image AS картинка,
                    v3_ProdList.has_fbo_stocks AS есть_остатки_FBO,
                    v3_ProdList.has_fbs_stocks AS есть_остатки_FBS,
                    v3_ProdList.archived AS в_архиве,
                    v3_ProdInfoAttr.barcode AS штрихкод,
                    v3_ProdInfoAttr.name AS наименование,
                    v3_ProdInfoAttr.depth AS X,
                    v3_ProdInfoAttr.width AS Y,
                    v3_ProdInfoAttr.height AS Z,
                    v3_ProdInfoAttr.dimension_unit AS единица_размера,
                    v3_ProdInfoAttr.weight AS G,
                    v3_ProdInfoAttr.weight_unit AS единица_веса,
                    v3_ProdInfoList.volume_weight,
                    v3_ProdInfoList.created_at AS создан,
                    v3_ProdInfoList.updated_at AS обновлен,
                    v3_ProdInfoList.vat AS НДС,
                    v3_ProdInfoList.marketing_price AS цена_маркетинговая,
                    v3_ProdInfoList.min_price AS цена_минимальная,
                    v3_ProdInfoList.old_price AS цена_зачеркнутая,
                    v3_ProdInfoList.price AS цена,
                    v3_ProdInfoList.currency_code AS код_валюты,
                    '_' AS _
                FROM
                    OZN_v3_ProductList AS v3_ProdList
                    LEFT JOIN OZN_v3_ProductInfoList AS v3_ProdInfoList ON
                        v3_ProdInfoList.id = v3_ProdList.product_id
                    LEFT JOIN OZN_v4_ProductInfoAttributes AS v3_ProdInfoAttr ON
                        v3_ProdInfoAttr.id  = v3_ProdList.product_id
                ORDER BY
                    v3_ProdList.offer_id ASC
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
