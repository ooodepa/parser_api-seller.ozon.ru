<?php

$HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['PHP_CRON_HOME'];

class OZN_v3_ProductInfoList {
    static $DB_NAME = 'OZN_v3_ProductInfoList';

    static function recreateDatabaseAndInsertRows($array) {
        try {
            OZN_v3_ProductInfoList::recreateDatabase();

            global $HOME;

            $pdo = new PDO("sqlite:$HOME/database.sqlite");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $DB_NAME = OZN_v3_ProductInfoList::$DB_NAME;
            $sql = "INSERT INTO
                        $DB_NAME
                        (
                            id,
                            name,
                            offer_id,
                            is_archived,
                            is_autoarchived,
                            barcodes,
                            description_category_id,
                            type_id,
                            created_at,
                            images,
                            currency_code,
                            marketing_price,
                            min_price,
                            old_price,
                            price,
                            sources,
                            model_info,
                            commissions,
                            is_prepayment_allowed,
                            volume_weight,
                            has_discounted_fbo_item,
                            is_discounted,
                            discounted_fbo_stocks,
                            stocks,
                            errors,
                            updated_at,
                            vat,
                            visibility_details,
                            price_indexes,
                            images360,
                            is_kgt,
                            color_image,
                            primary_image,
                            statuses,
                            is_super,
                            is_seasonal,
                            _raw_json
                        )
                    VALUES
                    ";

            $length = count($array);
            $lastIndex = $length - 1;
            for ($i = 0; $i < $length; $i++) {
                $current = $array[$i];
                $DATA = OZN_v3_ProductInfoList::getPhpObject_byJson($current);

                $sql .= "('"
                    . implode("','", [
                        $DATA['id'],
                        $DATA['name'],
                        $DATA['offer_id'],
                        $DATA['is_archived'],
                        $DATA['is_autoarchived'],
                        $DATA['barcodes'],
                        $DATA['description_category_id'],
                        $DATA['type_id'],
                        $DATA['created_at'],
                        $DATA['images'],
                        $DATA['currency_code'],
                        $DATA['marketing_price'],
                        $DATA['min_price'],
                        $DATA['old_price'],
                        $DATA['price'],
                        $DATA['sources'],
                        $DATA['model_info'],
                        $DATA['commissions'],
                        $DATA['is_prepayment_allowed'],
                        $DATA['volume_weight'],
                        $DATA['has_discounted_fbo_item'],
                        $DATA['is_discounted'],
                        $DATA['discounted_fbo_stocks'],
                        $DATA['stocks'],
                        $DATA['errors'],
                        $DATA['updated_at'],
                        $DATA['vat'],
                        $DATA['visibility_details'],
                        $DATA['price_indexes'],
                        $DATA['images360'],
                        $DATA['is_kgt'],
                        $DATA['color_image'],
                        $DATA['primary_image'],
                        $DATA['statuses'],
                        $DATA['is_super'],
                        $DATA['is_seasonal'],
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

        $DB_NAME = OZN_v3_ProductInfoList::$DB_NAME;

        $sql = "DROP TABLE
                IF EXISTS
                    $DB_NAME
                ";

        // echo "\n$sql\n";
        $pdo->prepare($sql)->execute();

        $sql = "CREATE TABLE $DB_NAME (
                    id TEXT,
                    name TEXT,
                    offer_id TEXT,
                    is_archived TEXT,
                    is_autoarchived TEXT,
                    barcodes TEXT,
                    description_category_id TEXT,
                    type_id TEXT,
                    created_at TEXT,
                    images TEXT,
                    currency_code TEXT,
                    marketing_price TEXT,
                    min_price TEXT,
                    old_price TEXT,
                    price TEXT,
                    sources TEXT,
                    model_info TEXT,
                    commissions TEXT,
                    is_prepayment_allowed TEXT,
                    volume_weight TEXT,
                    has_discounted_fbo_item TEXT,
                    is_discounted TEXT,
                    discounted_fbo_stocks TEXT,
                    stocks TEXT,
                    errors TEXT,
                    updated_at TEXT,
                    vat TEXT,
                    visibility_details TEXT,
                    price_indexes TEXT,
                    images360 TEXT,
                    is_kgt TEXT,
                    color_image TEXT,
                    primary_image TEXT,
                    statuses TEXT,
                    is_super TEXT,
                    is_seasonal TEXT,
                    _raw_json TEXT
                )
                ";

        // echo "\n$sql\n";
        $pdo->prepare($sql)->execute();
    }

    static function getPhpObject_byJson($DATA) {
        $RESULT_DATA = [
            'id' => $DATA['id'],
            'name' => $DATA['name'],
            'offer_id' => $DATA['offer_id'],
            'is_archived' => $DATA['is_archived'] ? 1 : 0,
            'is_autoarchived' => $DATA['is_autoarchived'] ? 1 : 0,
            'barcodes' => $DATA['barcodes'] ? '' . implode(';', $DATA['barcodes']) : '',
            'description_category_id' => $DATA['description_category_id'],
            'type_id' => $DATA['type_id'],
            'created_at' => $DATA['created_at'],
            'images' => $DATA['images'] ? '' . implode(';', $DATA['images']) : '',
            'currency_code' => $DATA['currency_code'],
            'marketing_price' => $DATA['marketing_price'],
            'min_price' => $DATA['min_price'],
            'old_price' => $DATA['old_price'],
            'price' => $DATA['price'],
            'sources' => json_encode($DATA['sources'], JSON_UNESCAPED_UNICODE),
            'model_info' => json_encode($DATA['model_info'], JSON_UNESCAPED_UNICODE),
            'commissions' => json_encode($DATA['commissions'], JSON_UNESCAPED_UNICODE),
            'is_prepayment_allowed' => $DATA['is_prepayment_allowed'] ? 1 : 0,
            'volume_weight' => $DATA['volume_weight'],
            'has_discounted_fbo_item' => $DATA['has_discounted_fbo_item'] ? 1 : 0,
            'is_discounted' => $DATA['is_discounted'] ? 1 : 0,
            'discounted_fbo_stocks' => $DATA['discounted_fbo_stocks'],
            'stocks' => json_encode($DATA['stocks'], JSON_UNESCAPED_UNICODE),
            'errors' => json_encode($DATA['errors'], JSON_UNESCAPED_UNICODE),
            'updated_at' => $DATA['updated_at'],
            'vat' => $DATA['vat'],
            'visibility_details' => json_encode($DATA['visibility_details'], JSON_UNESCAPED_UNICODE),
            'price_indexes' => json_encode($DATA['price_indexes'], JSON_UNESCAPED_UNICODE),
            'images360' => $DATA['images360'] ? '' . implode(';', $DATA['images360']) : '',
            'is_kgt' => $DATA['is_kgt'] ? 1 : 0,
            'color_image' => $DATA['color_image'] ? '' . implode(';', $DATA['color_image']) : '',
            'primary_image' => $DATA['primary_image'] ? '' . implode(';', $DATA['primary_image']) : '',
            'statuses' => json_encode($DATA['id'], JSON_UNESCAPED_UNICODE),
            'is_super' => $DATA['is_super'] ? 1 : 0,
            'is_seasonal' => $DATA['is_seasonal'] ? 1 : 0,
            '_raw_json' => json_encode($DATA, JSON_UNESCAPED_UNICODE),
        ];

        return $RESULT_DATA;
    }
}
