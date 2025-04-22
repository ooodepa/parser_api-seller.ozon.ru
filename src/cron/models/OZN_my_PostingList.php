<?php

$HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['PHP_CRON_HOME'];

class OZN_my_PostingList {
    static $DB_NAME = 'OZN_my_PostingList';

    static function recreateDatabaseAndInsertRows($array) {
        try {
            OZN_my_PostingList::recreateDatabase();

            global $HOME;

            $pdo = new PDO("sqlite:$HOME/database.sqlite");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $DB_NAME = OZN_my_PostingList::$DB_NAME;
            $sql = "INSERT INTO
                        $DB_NAME
                        (
                            n,
                            posting_date,
                            p1,
                            p2,
                            p3,
                            fb_type
                        )
                    VALUES
                    ";

            $length = count($array);
            $lastIndex = $length - 1;
            for ($i = 0; $i < $length; $i++) {
                $current = $array[$i];
                $DATA = OZN_my_PostingList::getPhpObject_byJson($current);

                $sql .= "('"
                    . implode("','", [
                        $DATA['n'],
                        $DATA['posting_date'],
                        $DATA['p1'],
                        $DATA['p2'],
                        $DATA['p3'],
                        $DATA['fb_type'],
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

        $DB_NAME = OZN_my_PostingList::$DB_NAME;

        $sql = "DROP TABLE
                IF EXISTS
                    $DB_NAME
                ";

        // echo "\n$sql\n";
        $pdo->prepare($sql)->execute();

        $sql = "CREATE TABLE $DB_NAME (
                    n TEXT,
                    posting_date TEXT,
                    p1 TEXT,
                    p2 TEXT,
                    p3 TEXT,
                    fb_type TEXT
                )
                ";

        // echo "\n$sql\n";
        $pdo->prepare($sql)->execute();
    }

    static function getPhpObject_byJson($DATA) {
        $RESULT_DATA = [
            "n" => $DATA['n'],
            "posting_date" => $DATA['posting_date'],
            "p1" => $DATA['P1'],
            "p2" => $DATA['P2'],
            "p3" => $DATA['P3'],
            "fb_type" => "" . $DATA['fb_type'],
        ];

        return $RESULT_DATA;
    }
}
