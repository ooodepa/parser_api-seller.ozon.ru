<?php

$HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['PHP_CRON_HOME'];

class OZN_v4_ProductInfoAttributes {
    static $DB_NAME = 'OZN_v4_ProductInfoAttributes';

    static function recreateDatabaseAndInsertRows($array) {
        try {
            OZN_v4_ProductInfoAttributes::recreateDatabase();

            global $HOME;

            $pdo = new PDO("sqlite:$HOME/database.sqlite");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $DB_NAME = OZN_v4_ProductInfoAttributes::$DB_NAME;
            $sql = "INSERT INTO
                        $DB_NAME
                        (
                            id,
                            barcode,
                            name,
                            offer_id,
                            height,
                            depth,
                            width,
                            dimension_unit,
                            weight,
                            weight_unit,
                            description_category_id,
                            type_id,
                            primary_image,
                            model_info,
                            images,
                            pdf_list,
                            attributes,
                            complex_attributes,
                            color_image,
                            sku,
                            barcodes,
                            _raw_json
                        )
                    VALUES
                    ";

            $length = count($array);
            $lastIndex = $length - 1;
            for ($i = 0; $i < $length; $i++) {
                $current = $array[$i];
                $DATA = OZN_v4_ProductInfoAttributes::getPhpObject_byJson($current);

                $sql .= "('"
                    . implode("','", [
                        $DATA['id'],
                        $DATA['barcode'],
                        $DATA['name'],
                        $DATA['offer_id'],
                        $DATA['height'],
                        $DATA['depth'],
                        $DATA['width'],
                        $DATA['dimension_unit'],
                        $DATA['weight'],
                        $DATA['weight_unit'],
                        $DATA['description_category_id'],
                        $DATA['type_id'],
                        $DATA['primary_image'],
                        $DATA['model_info'],
                        $DATA['images'],
                        $DATA['pdf_list'],
                        $DATA['attributes'],
                        $DATA['complex_attributes'],
                        $DATA['color_image'],
                        $DATA['sku'],
                        $DATA['barcodes'],
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

        $DB_NAME = OZN_v4_ProductInfoAttributes::$DB_NAME;

        $sql = "DROP TABLE
                IF EXISTS
                    $DB_NAME
                ";

        // echo "\n$sql\n";
        $pdo->prepare($sql)->execute();

        $sql = "CREATE TABLE $DB_NAME (
                    id TEXT,
                    barcode TEXT,
                    name TEXT,
                    offer_id TEXT,
                    height TEXT,
                    depth TEXT,
                    width TEXT,
                    dimension_unit TEXT,
                    weight TEXT,
                    weight_unit TEXT,
                    description_category_id TEXT,
                    type_id TEXT,
                    primary_image TEXT,
                    model_info TEXT,
                    images TEXT,
                    pdf_list TEXT,
                    attributes TEXT,
                    complex_attributes TEXT,
                    color_image TEXT,
                    sku TEXT,
                    barcodes TEXT,
                    _raw_json TEXT
                )
                ";

        // echo "\n$sql\n";
        $pdo->prepare($sql)->execute();
    }

    static function getPhpObject_byJson($DATA) {
        $RESULT_DATA = [
            "id" => $DATA['id'],
            "barcode" => $DATA['barcode'],
            "name" => $DATA['name'],
            "offer_id" => $DATA['offer_id'],
            "height" => $DATA['height'],
            "depth" => $DATA['depth'],
            "width" => $DATA['width'],
            "dimension_unit" => $DATA['dimension_unit'],
            "weight" => $DATA['weight'],
            "weight_unit" => $DATA['weight_unit'],
            "description_category_id" => $DATA['description_category_id'],
            "type_id" => $DATA['type_id'],
            "primary_image" => $DATA['primary_image'],
            "model_info" => json_encode($DATA['model_info'], JSON_UNESCAPED_UNICODE),
            "images" => implode(";", $DATA['images']),
            "pdf_list" => json_encode($DATA['pdf_list'], JSON_UNESCAPED_UNICODE),
            "attributes" => json_encode($DATA['attributes'], JSON_UNESCAPED_UNICODE),
            "complex_attributes" => json_encode($DATA['complex_attributes'], JSON_UNESCAPED_UNICODE),
            "color_image" => $DATA['color_image'],
            "sku" => $DATA['sku'],
            "barcodes" => implode(';', $DATA['barcodes']),
            '_raw_json' => json_encode($DATA, JSON_UNESCAPED_UNICODE),
        ];

        return $RESULT_DATA;
    }
}
