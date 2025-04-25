<?php

$HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['PHP_CRON_HOME'];

class OZN_v1_ReturnsList {
    static $DB_NAME = 'OZN_v1_ReturnsList';

    static function recreateDatabaseAndInsertRows($array) {
        try {
            OZN_v1_ReturnsList::recreateDatabase();

            global $HOME;

            $pdo = new PDO("sqlite:$HOME/database.sqlite");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $DB_NAME = OZN_v1_ReturnsList::$DB_NAME;
            $sql = "INSERT INTO
                        $DB_NAME
                        (
                            id,
                            company_id,
                            return_reason_name,
                            type,
                            schema,
                            order_id,
                            order_number,
                            _place_id,
                            _place_name,
                            _place_address,
                            _target_place_id,
                            _target_place_name,
                            _target_place_address,
                            _storage_sum_currency_code,
                            _storage_sum_price,
                            _storage_tariffication_first_date,
                            _storage_tariffication_start_date,
                            _storage_arrived_moment,
                            _storage_days,
                            _storage_utilization_sum_currency_code,
                            _storage_utilization_sum_price,
                            _storage_utilization_forecast_date,
                            _product_sku,
                            _product_offer_id,
                            _product_name,
                            _product_price_currency_code,
                            _product_price_price,
                            _product_price_without_commission_currency_code,
                            _product_price_without_commission_price,
                            _product_commission_percent,
                            _product_commission_currency_code,
                            _product_commission_price,
                            _product_quantity,
                            _logistic_technical_return_moment,
                            _logistic_final_moment,
                            _logistic_cancelled_with_compensation_moment,
                            _logistic_return_date,
                            _logistic_barcode,
                            _visual_status_id,
                            _visual_status_display_name,
                            _visual_status_sys_name,
                            _visual_change_moment,
                            _exemplars,
                            _additional_info_is_opened,
                            _additional_info_is_super_econom,
                            clearing_id,
                            posting_number,
                            posting_number_p1,
                            posting_number_p2,
                            posting_number_p3,
                            return_clearing_id,
                            source_id,
                            _raw_json
                        )
                    VALUES
                    ";

            $length = count($array);
            $lastIndex = $length - 1;
            for ($i = 0; $i < $length; $i++) {
                $current = $array[$i];
                $DATA = OZN_v1_ReturnsList::getPhpObject_byJson($current);

                $sql .= "('"
                    . implode("','", [
                        $DATA['id'],
                        $DATA['company_id'],
                        $DATA['return_reason_name'],
                        $DATA['type'],
                        $DATA['schema'],
                        $DATA['order_id'],
                        $DATA['order_number'],
                        $DATA['_place_id'],
                        $DATA['_place_name'],
                        $DATA['_place_address'],
                        $DATA['_target_place_id'],
                        $DATA['_target_place_name'],
                        $DATA['_target_place_address'],
                        $DATA['_storage_sum_currency_code'],
                        $DATA['_storage_sum_price'],
                        $DATA['_storage_tariffication_first_date'],
                        $DATA['_storage_tariffication_start_date'],
                        $DATA['_storage_arrived_moment'],
                        $DATA['_storage_days'],
                        $DATA['_storage_utilization_sum_currency_code'],
                        $DATA['_storage_utilization_sum_price'],
                        $DATA['_storage_utilization_forecast_date'],
                        $DATA['_product_sku'],
                        $DATA['_product_offer_id'],
                        $DATA['_product_name'],
                        $DATA['_product_price_currency_code'],
                        $DATA['_product_price_price'],
                        $DATA['_product_price_without_commission_currency_code'],
                        $DATA['_product_price_without_commission_price'],
                        $DATA['_product_commission_percent'],
                        $DATA['_product_commission_currency_code'],
                        $DATA['_product_commission_price'],
                        $DATA['_product_quantity'],
                        $DATA['_logistic_technical_return_moment'],
                        $DATA['_logistic_final_moment'],
                        $DATA['_logistic_cancelled_with_compensation_moment'],
                        $DATA['_logistic_return_date'],
                        $DATA['_logistic_barcode'],
                        $DATA['_visual_status_id'],
                        $DATA['_visual_status_display_name'],
                        $DATA['_visual_status_sys_name'],
                        $DATA['_visual_change_moment'],
                        $DATA['_exemplars'],
                        $DATA['_additional_info_is_opened'],
                        $DATA['_additional_info_is_super_econom'],
                        $DATA['clearing_id'],
                        $DATA['posting_number'],
                        $DATA['posting_number_p1'],
                        $DATA['posting_number_p2'],
                        $DATA['posting_number_p3'],
                        $DATA['return_clearing_id'],
                        $DATA['source_id'],
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

        $DB_NAME = OZN_v1_ReturnsList::$DB_NAME;

        $sql = "DROP TABLE
                IF EXISTS
                    $DB_NAME
                ";

        // echo "\n$sql\n";
        $pdo->prepare($sql)->execute();

        $sql = "CREATE TABLE $DB_NAME (
                    id TEXT,
                    company_id TEXT,
                    return_reason_name TEXT,
                    type TEXT,
                    schema TEXT,
                    order_id TEXT,
                    order_number TEXT,
                    _place_id TEXT,
                    _place_name TEXT,
                    _place_address TEXT,
                    _target_place_id TEXT,
                    _target_place_name TEXT,
                    _target_place_address TEXT,
                    _storage_sum_currency_code TEXT,
                    _storage_sum_price TEXT,
                    _storage_tariffication_first_date TEXT,
                    _storage_tariffication_start_date TEXT,
                    _storage_arrived_moment TEXT,
                    _storage_days TEXT,
                    _storage_utilization_sum_currency_code TEXT,
                    _storage_utilization_sum_price TEXT,
                    _storage_utilization_forecast_date TEXT,
                    _product_sku TEXT,
                    _product_offer_id TEXT,
                    _product_name TEXT,
                    _product_price_currency_code TEXT,
                    _product_price_price TEXT,
                    _product_price_without_commission_currency_code TEXT,
                    _product_price_without_commission_price TEXT,
                    _product_commission_percent TEXT,
                    _product_commission_currency_code TEXT,
                    _product_commission_price TEXT,
                    _product_quantity TEXT,
                    _logistic_technical_return_moment TEXT,
                    _logistic_final_moment TEXT,
                    _logistic_cancelled_with_compensation_moment TEXT,
                    _logistic_return_date TEXT,
                    _logistic_barcode TEXT,
                    _visual_status_id TEXT,
                    _visual_status_display_name TEXT,
                    _visual_status_sys_name TEXT,
                    _visual_change_moment TEXT,
                    _exemplars TEXT,
                    _additional_info_is_opened TEXT,
                    _additional_info_is_super_econom TEXT,
                    clearing_id TEXT,
                    posting_number TEXT,
                    posting_number_p1 TEXT,
                    posting_number_p2 TEXT,
                    posting_number_p3 TEXT,
                    return_clearing_id TEXT,
                    source_id TEXT,
                    _raw_json TEXT
                )
                ";

        // echo "\n$sql\n";
        $pdo->prepare($sql)->execute();
    }

    static function getPhpObject_byJson($DATA) {
        $posting_number = $DATA['posting_number'];
        $posting_number_arr3 = explode('-', $posting_number);
        $posting_number_1 = count($posting_number_arr3) > 0 ? $posting_number_arr3[0] : '';
        $posting_number_2 = count($posting_number_arr3) > 1 ? $posting_number_arr3[1] : '';
        $posting_number_3 = count($posting_number_arr3) > 2 ? $posting_number_arr3[2] : '';

        $RESULT_DATA = [
            'id' => $DATA['id'],
            'company_id' => $DATA['company_id'],
            'return_reason_name' => $DATA['return_reason_name'],
            'type' => $DATA['type'],
            'schema' => $DATA['schema'],
            'order_id' => $DATA['order_id'],
            'order_number' => $DATA['order_number'],
            '_place_id' => $DATA['place']['id'],
            '_place_name' => $DATA['place']['name'],
            '_place_address' => $DATA['place']['address'],
            '_target_place_id' => $DATA['target_place']['id'],
            '_target_place_name' => $DATA['target_place']['name'],
            '_target_place_address' => $DATA['target_place']['address'],
            '_storage_sum_currency_code' => $DATA['storage']['sum']['currency_code'],
            '_storage_sum_price' => $DATA['storage']['sum']['price'],
            '_storage_tariffication_first_date' => $DATA['storage']['tariffication_first_date'],
            '_storage_tariffication_start_date' => $DATA['storage']['tariffication_start_date'],
            '_storage_arrived_moment' => $DATA['storage']['arrived_moment'],
            '_storage_days' => $DATA['storage']['days'],
            '_storage_utilization_sum_currency_code' => $DATA['storage']['utilization_sum']['currency_code'],
            '_storage_utilization_sum_price' => $DATA['storage']['utilization_sum']['price'],
            '_storage_utilization_forecast_date' => $DATA['storage']['utilization_forecast_date'],
            '_product_sku' => $DATA['product']['sku'],
            '_product_offer_id' => $DATA['product']['offer_id'],
            '_product_name' => $DATA['product']['name'],
            '_product_price_currency_code' => $DATA['product']['price']['currency_code'],
            '_product_price_price' => $DATA['product']['price']['price'],
            '_product_price_without_commission_currency_code' => $DATA['product']['price_without_commission']['currency_code'],
            '_product_price_without_commission_price' => $DATA['product']['price_without_commission']['price'],
            '_product_commission_percent' => $DATA['product']['commission_percent'],
            '_product_commission_currency_code' => $DATA['product']['commission']['currency_code'],
            '_product_commission_price' => $DATA['product']['commission']['price'],
            '_product_quantity' => $DATA['product']['quantity'],
            '_logistic_technical_return_moment' => $DATA['logistic']['technical_return_moment'],
            '_logistic_final_moment' => $DATA['logistic']['final_moment'],
            '_logistic_cancelled_with_compensation_moment' => $DATA['logistic']['cancelled_with_compensation_moment'],
            '_logistic_return_date' => $DATA['logistic']['return_date'],
            '_logistic_barcode' => $DATA['logistic']['barcode'],
            '_visual_status_id' => $DATA['visual']['status']['id'],
            '_visual_status_display_name' => $DATA['visual']['status']['display_name'],
            '_visual_status_sys_name' => $DATA['visual']['status']['sys_name'],
            '_visual_change_moment' => $DATA['visual']['change_moment'],
            '_exemplars' => json_encode($DATA['exemplars'], JSON_UNESCAPED_UNICODE),
            '_additional_info_is_opened' => $DATA['additional_info']['is_opened'],
            '_additional_info_is_super_econom' => $DATA['additional_info']['is_super_econom'],
            'clearing_id' => $DATA['clearing_id'],
            'posting_number' => $DATA['posting_number'],
            'posting_number_p1' => $posting_number_1,
            'posting_number_p2' => $posting_number_2,
            'posting_number_p3' => $posting_number_3,
            'return_clearing_id' => $DATA['return_clearing_id'],
            'source_id' => $DATA['source_id'],
            '_raw_json' => json_encode($DATA, JSON_UNESCAPED_UNICODE),
        ];

        return $RESULT_DATA;
    }
}
