<?php

$HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['PHP_CRON_HOME'];

class OZN_v2_PostingFbsActList {
    static $DB_NAME = 'OZN_v2_PostingFbsActList';

    static function recreateDatabaseAndInsertRows($array) {
        try {
            OZN_v2_PostingFbsActList::recreateDatabase();

            global $HOME;

            $pdo = new PDO("sqlite:$HOME/database.sqlite");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $DB_NAME = OZN_v2_PostingFbsActList::$DB_NAME;
            $sql = "INSERT INTO
                        $DB_NAME
                        (
                            id,
                            delivery_method_id,
                            delivery_method_name,
                            integration_type,
                            containers_count,
                            status,
                            departure_date,
                            created_at,
                            updated_at,
                            act_type,
                            _related_docs_act_of_acceptance_document_status,
                            _related_docs_act_of_acceptance_created_at,
                            _related_docs_act_of_mismatch_document_status,
                            _related_docs_act_of_mismatch_created_at,
                            _related_docs_act_of_excess_document_status,
                            _related_docs_act_of_excess_created_at,
                            is_partial,
                            has_postings_for_next_carriage,
                            partial_num,
                            _raw_json
                        )
                    VALUES
                    ";

            $length = count($array);
            $lastIndex = $length - 1;
            for ($i = 0; $i < $length; $i++) {
                $current = $array[$i];
                $DATA = OZN_v2_PostingFbsActList::getPhpObject_byJson($current);

                $sql .= "('"
                    . implode("','", [
                        $DATA['id'],
                        $DATA['delivery_method_id'],
                        $DATA['delivery_method_name'],
                        $DATA['integration_type'],
                        $DATA['containers_count'],
                        $DATA['status'],
                        $DATA['departure_date'],
                        $DATA['created_at'],
                        $DATA['updated_at'],
                        $DATA['act_type'],
                        $DATA['_related_docs_act_of_acceptance_document_status'],
                        $DATA['_related_docs_act_of_acceptance_created_at'],
                        $DATA['_related_docs_act_of_mismatch_document_status'],
                        $DATA['_related_docs_act_of_mismatch_created_at'],
                        $DATA['_related_docs_act_of_excess_document_status'],
                        $DATA['_related_docs_act_of_excess_created_at'],
                        $DATA['is_partial'],
                        $DATA['has_postings_for_next_carriage'],
                        $DATA['partial_num'],
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

        $DB_NAME = OZN_v2_PostingFbsActList::$DB_NAME;

        $sql = "DROP TABLE
                IF EXISTS
                    $DB_NAME
                ";

        // echo "\n$sql\n";
        $pdo->prepare($sql)->execute();

        $sql = "CREATE TABLE $DB_NAME (
                    id TEXT,
                    delivery_method_id TEXT,
                    delivery_method_name TEXT,
                    integration_type TEXT,
                    containers_count TEXT,
                    status TEXT,
                    departure_date TEXT,
                    created_at TEXT,
                    updated_at TEXT,
                    act_type TEXT,
                    _related_docs_act_of_acceptance_document_status TEXT,
                    _related_docs_act_of_acceptance_created_at TEXT,
                    _related_docs_act_of_mismatch_document_status TEXT,
                    _related_docs_act_of_mismatch_created_at TEXT,
                    _related_docs_act_of_excess_document_status TEXT,
                    _related_docs_act_of_excess_created_at TEXT,
                    is_partial TEXT,
                    has_postings_for_next_carriage TEXT,
                    partial_num TEXT,
                    _raw_json TEXT
                )
                ";

        // echo "\n$sql\n";
        $pdo->prepare($sql)->execute();
    }

    static function getPhpObject_byJson($DATA) {
        $RESULT_DATA = [
            'id' => $DATA['id'],
            'delivery_method_id' => $DATA['delivery_method_id'],
            'delivery_method_name' => $DATA['delivery_method_name'],
            'integration_type' => $DATA['integration_type'],
            'containers_count' => $DATA['containers_count'],
            'status' => $DATA['status'],
            'departure_date' => $DATA['departure_date'],
            'created_at' => $DATA['created_at'],
            'updated_at' => $DATA['updated_at'],
            'act_type' => $DATA['act_type'],
            '_related_docs_act_of_acceptance_document_status' => $DATA['related_docs']['act_of_acceptance']['document_status'] ?? '',
            '_related_docs_act_of_acceptance_created_at' => $DATA['related_docs']['act_of_acceptance']['created_at'] ?? '',
            '_related_docs_act_of_mismatch_document_status' => $DATA['related_docs']['act_of_mismatch']['document_status'] ?? '',
            '_related_docs_act_of_mismatch_created_at' => $DATA['related_docs']['act_of_mismatch']['created_at'] ?? '',
            '_related_docs_act_of_excess_document_status' => $DATA['related_docs']['act_of_excess']['document_status'] ?? '',
            '_related_docs_act_of_excess_created_at' => $DATA['related_docs']['act_of_excess']['created_at'] ?? '',
            'is_partial' => $DATA['is_partial'],
            'has_postings_for_next_carriage' => $DATA['has_postings_for_next_carriage'],
            'partial_num' => $DATA['partial_num'],
            '_raw_json' => json_encode($DATA, JSON_UNESCAPED_UNICODE),
        ];

        return $RESULT_DATA;
    }
}
