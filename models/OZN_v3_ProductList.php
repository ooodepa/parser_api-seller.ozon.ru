<?php

$HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['PHP_CRON_HOME'];

class OZN_v3_ProductList {
    static $DB_NAME = 'OZN_v3_ProductList';

    static function recreateDatabaseAndInsertRows($array) {
        try {
            OZN_v3_ProductList::recreateDatabase();

            global $HOME;

            $pdo = new PDO("sqlite:$HOME/database.sqlite");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $DB_NAME = OZN_v3_ProductList::$DB_NAME;
            $sql = "INSERT INTO
                        $DB_NAME
                        (
                            product_id,
                            offer_id,
                            has_fbo_stocks,
                            has_fbs_stocks,
                            archived,
                            is_discounted,
                            quants,
                            _raw_json
                        )
                    VALUES
                    ";

            $length = count($array);
            $lastIndex = $length - 1;
            for ($i = 0; $i < $length; $i++) {
                $current = $array[$i];
                $DATA = OZN_v3_ProductList::getPhpObject_byJson($current);

                $sql .= "('"
                    . implode("','", [
                        $DATA['product_id'],
                        $DATA['offer_id'],
                        $DATA['has_fbo_stocks'],
                        $DATA['has_fbs_stocks'],
                        $DATA['archived'],
                        $DATA['is_discounted'],
                        $DATA['quants'],
                        $DATA['_raw_json'],
                    ]) .
                "')";

                if ($i != $lastIndex) {
                    $sql .= ",";
                }
            }

            // echo "\n$sql\n";
            $pdo->prepare($sql)->execute();
        }
        catch(Throwable $exception) {
            echo "< < < < < < < <";
            print_r($exception);
            echo "> > > > > > > >";
        }
    }

    static function recreateDatabase() {
        global $HOME;

        $pdo = new PDO("sqlite:$HOME/database.sqlite");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $DB_NAME = OZN_v3_ProductList::$DB_NAME;

        $sql = "DROP TABLE
                IF EXISTS
                    $DB_NAME
                ";

        // echo "\n$sql\n";
        $pdo->prepare($sql)->execute();

        $sql = "CREATE TABLE $DB_NAME (
                    product_id TEXT,
                    offer_id TEXT,
                    has_fbo_stocks TEXT,
                    has_fbs_stocks TEXT,
                    archived TEXT,
                    is_discounted TEXT,
                    quants TEXT,
                    _raw_json TEXT
                )
                ";

        // echo "\n$sql\n";
        $pdo->prepare($sql)->execute();
    }

    static function getPhpObject_byJson($DATA) {
        $RESULT_DATA = [
            "product_id" => $DATA['product_id'],
            "offer_id" => $DATA['offer_id'],
            "has_fbo_stocks" => $DATA['has_fbo_stocks'] ? '1' : '0',
            "has_fbs_stocks" => $DATA['has_fbs_stocks'] ? '1' : '0',
            "archived" => $DATA['archived'] ? '1' : '0',
            "is_discounted" => $DATA['is_discounted'] ? '1' : '0',
            "quants" => json_encode($DATA['quants'], JSON_UNESCAPED_UNICODE),
            '_raw_json' => json_encode($DATA, JSON_UNESCAPED_UNICODE),
        ];

        return $RESULT_DATA;
    }
}
