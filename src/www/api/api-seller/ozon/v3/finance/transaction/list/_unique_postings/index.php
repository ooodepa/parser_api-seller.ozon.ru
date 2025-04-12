<?php

function main() {
    try {
        $HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['ENV_HOME'];

        include "$HOME/api/api-seller/env.php";
        include "$HOME/api/api-seller/_classes/AuthHelper.class.php";

        AuthHelper::exit_ifNotAuth();

        $pdo = new PDO("sqlite:$ENV_SQLITE_DATABASE");

        $sql = "SELECT
                    _posting_order_date AS posting_date,
                    _posting_posting_number_1 AS P1,
                    _posting_posting_number_2 AS P2,
                    _posting_posting_number_3 AS P3,
                    _posting_posting_number AS posting_number
                FROM
                    OZN_v3_FinanceTransactionList
                WHERE
                    operation_type_name <> 'Прочие компенсации'
                ";

        $sth = $pdo->prepare($sql);
        $sth->execute();
        $array = $sth->fetchAll(PDO::FETCH_ASSOC);

        $result_array = [];

        // нахожу уникальные заказы
        for ($i = 0; $i < count($array); $i++) {
            $current_i = $array[$i];

            $isFound = false;
            for ($j = 0; $j < count($result_array); $j++) {
                $current_j = $result_array[$j];

                $json_1 = json_encode([
                    'posting_date' => $current_i['posting_date'],
                    'P1' => $current_i['P1'],
                    'P2' => $current_i['P2'],
                ]);

                $json_2 = json_encode([
                    'posting_date' => $current_j['posting_date'],
                    'P1' => $current_j['P1'],
                    'P2' => $current_j['P2'],
                ]);

                $isEquals = (strcmp($json_1, $json_2) == 0);

                if ($isEquals) {
                    $isFound = true;
                    break;
                }
            }

            if (!$isFound) {
                array_push($result_array, [
                    'posting_date' => $current_i['posting_date'],
                    'P1' => $current_i['P1'],
                    'P2' => $current_i['P2'],
                ]);
            }
        }

        // добавляю всем уникальным заказам поля
        for ($i = 0; $i < count($result_array); $i++) {
            $result_array[$i]['P3'] = "";
            $result_array[$i]['n'] = $i + 1;
            $result_array[$i]['repeat_orders'] = [];
        }

        // добавляю уникальным заказам третью часть (P3)
        for ($i = 0; $i < count($result_array); $i++) {
            $current_i = $result_array[$i];
            for ($j = 0; $j < count($array); $j++) {
                $current_j = $array[$j];
                if (strlen($current_j['P3']) == 0) {
                    continue;
                }

                $json_1 = json_encode([
                    'posting_date' => $current_i['posting_date'],
                    'P1' => $current_i['P1'],
                    'P2' => $current_i['P2'],
                ]);

                $json_2 = json_encode([
                    'posting_date' => $current_j['posting_date'],
                    'P1' => $current_j['P1'],
                    'P2' => $current_j['P2'],
                ]);

                $isEquals = (strcmp($json_1, $json_2) == 0);

                if (!$isEquals) {
                    continue;
                }

                $result_array[$i]['P3'] = $current_j['P3'];
            }
        }

        // добавляю уникальным заказам список других заказов этого клиента
        for ($i = 0; $i < count($result_array); $i++) {
            $current_i = $result_array[$i];
            for ($j = 0; $j < count($result_array); $j++) {
                $current_j = $result_array[$j];

                $isEquals = (strcmp($current_i['P1'], $current_j['P1']) == 0)
                    && (strcmp($current_i['P2'], $current_j['P2']) != 0);

                if (!$isEquals) {
                    continue;
                }

                array_push($result_array[$i]['repeat_orders'], [
                    'posting_date' => $current_j['posting_date'],
                    'P1' => $current_j['P1'],
                    'P2' => $current_j['P2'],
                    'P3' => $current_j['P3'],
                    'n' => $current_j['n'],
                ]);
            }
        }

        $jsonString = json_encode($result_array, JSON_UNESCAPED_UNICODE);

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
