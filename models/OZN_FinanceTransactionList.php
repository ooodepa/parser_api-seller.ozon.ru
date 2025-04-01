<?php

$HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['PHP_CRON_HOME'];

include_once "$HOME/api-seller/ozon/finance/v3_FinanceTransactionListService.php";

class OZN_FinanceTransactionList {
    static $DB_NAME = 'OZN_FinanceTransactionList';

    static function recreateDatabaseAndInsertRows($array) {
        try {
            OZN_FinanceTransactionList::recreateDatabase();

            global $HOME;

            $pdo = new PDO("sqlite:$HOME/database.sqlite");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $DB_NAME = OZN_FinanceTransactionList::$DB_NAME;
            $sql = "INSERT INTO
                        $DB_NAME
                        (
                            operation_id,
                            operation_type,
                            operation_date,
                            operation_type_name,
                            delivery_charge,
                            return_delivery_charge,
                            accruals_for_sale,
                            sale_commission,
                            amount,
                            type,
                            _posting_delivery_schema,
                            _posting_order_date,
                            _posting_posting_number,
                            _posting_warehouse_id,
                            _items_sku,
                            _services_MarketplaceRedistributionOfAcquiringOperation,
                            _services_MarketplaceServiceItemDropoffPVZ,
                            _services_MarketplaceServiceItemDirectFlowTrans,
                            _services_MarketplaceServiceItemDelivToCustomer,
                            _services_MarketplaceServiceItemDirectFlowLogistic,
                            _services_MarketplaceServiceItemReturnAfterDelivToCustomer,
                            _services_MarketplaceServiceItemReturnFlowTrans,
                            _services_MarketplaceServiceItemReturnFlowLogistic,
                            _services_MarketplaceServiceItemRedistributionReturnsPVZ,
                            _services_MarketplaceServiceItemReturnNotDelivToCustomer,
                            _services_MarketplaceServiceItemReturnPartGoodsCustomer,
                            _raw_json
                        )
                    VALUES
                    ";

            $length = count($array);
            $lastIndex = $length - 1;
            for ($i = 0; $i < $length; $i++) {
                $current = $array[$i];
                $DATA = OZN_FinanceTransactionList::getPhpObject_byJson($current);

                $sql .= "('"
                    . implode("','", [
                        $DATA['operation_id'],
                        $DATA['operation_type'],
                        $DATA['operation_date'],
                        $DATA['operation_type_name'],
                        $DATA['delivery_charge'],
                        $DATA['return_delivery_charge'],
                        $DATA['accruals_for_sale'],
                        $DATA['sale_commission'],
                        $DATA['amount'],
                        $DATA['type'],
                        $DATA['_posting_delivery_schema'],
                        $DATA['_posting_order_date'],
                        $DATA['_posting_posting_number'],
                        $DATA['_posting_warehouse_id'],
                        $DATA['_items_sku'],
                        $DATA['_services_MarketplaceRedistributionOfAcquiringOperation'],
                        $DATA['_services_MarketplaceServiceItemDropoffPVZ'],
                        $DATA['_services_MarketplaceServiceItemDirectFlowTrans'],
                        $DATA['_services_MarketplaceServiceItemDelivToCustomer'],
                        $DATA['_services_MarketplaceServiceItemDirectFlowLogistic'],
                        $DATA['_services_MarketplaceServiceItemReturnAfterDelivToCustomer'],
                        $DATA['_services_MarketplaceServiceItemReturnFlowTrans'],
                        $DATA['_services_MarketplaceServiceItemReturnFlowLogistic'],
                        $DATA['_services_MarketplaceServiceItemRedistributionReturnsPVZ'],
                        $DATA['_services_MarketplaceServiceItemReturnNotDelivToCustomer'],
                        $DATA['_services_MarketplaceServiceItemReturnPartGoodsCustomer'],
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

        $DB_NAME = OZN_FinanceTransactionList::$DB_NAME;

        $sql = "DROP TABLE
                IF EXISTS
                    $DB_NAME
                ";

        // echo "\n$sql\n";
        $pdo->prepare($sql)->execute();

        $sql = "CREATE TABLE $DB_NAME (
                    operation_id TEXT,
                    operation_type TEXT,
                    operation_date TEXT,
                    operation_type_name TEXT,
                    delivery_charge TEXT,
                    return_delivery_charge TEXT,
                    accruals_for_sale TEXT,
                    sale_commission TEXT,
                    amount TEXT,
                    type TEXT,
                    _posting_delivery_schema TEXT,
                    _posting_order_date TEXT,
                    _posting_posting_number TEXT,
                    _posting_warehouse_id TEXT,
                    _items_sku TEXT,
                    _services_MarketplaceRedistributionOfAcquiringOperation TEXT,
                    _services_MarketplaceServiceItemDropoffPVZ TEXT,
                    _services_MarketplaceServiceItemDirectFlowTrans TEXT,
                    _services_MarketplaceServiceItemDelivToCustomer TEXT,
                    _services_MarketplaceServiceItemDirectFlowLogistic TEXT,
                    _services_MarketplaceServiceItemReturnAfterDelivToCustomer TEXT,
                    _services_MarketplaceServiceItemReturnFlowTrans TEXT,
                    _services_MarketplaceServiceItemReturnFlowLogistic TEXT,
                    _services_MarketplaceServiceItemRedistributionReturnsPVZ TEXT,
                    _services_MarketplaceServiceItemReturnNotDelivToCustomer TEXT,
                    _services_MarketplaceServiceItemReturnPartGoodsCustomer TEXT,
                    _raw_json TEXT
                )
                ";

        // echo "\n$sql\n";
        $pdo->prepare($sql)->execute();
    }

    static function getPhpObject_byJson($DATA) {
        $posting_number = $DATA['posting']['posting_number'];
        $posting_number_arr3 = explode('-', $posting_number);
        $posting_number_1 = count($posting_number_arr3) > 0 ? $posting_number_arr3[0] : '';
        $posting_number_2 = count($posting_number_arr3) > 1 ? $posting_number_arr3[1] : '';
        $posting_number_3 = count($posting_number_arr3) > 2 ? $posting_number_arr3[2] : '';

        $RESULT_DATA = [
            "operation_id" => $DATA['operation_id'],
            "operation_type" => $DATA['operation_type'],
            "operation_date" => $DATA['operation_date'],
            "operation_type_name" => $DATA['operation_type_name'],
            "delivery_charge" => $DATA['delivery_charge'],
            "return_delivery_charge" => $DATA['return_delivery_charge'],
            "accruals_for_sale" => $DATA['accruals_for_sale'],
            "sale_commission" => $DATA['sale_commission'],
            "amount" => $DATA['amount'],
            "type" => $DATA['type'],
            "_posting_delivery_schema" => $DATA['posting']['delivery_schema'],
            "_posting_order_date" => $DATA['posting']['order_date'],
            "_posting_posting_number" => $DATA['posting']['posting_number'],
            "_posting_posting_number_1" => $posting_number_1,
            "_posting_posting_number_2" => $posting_number_2,
            "_posting_posting_number_3" => $posting_number_3,
            "_posting_warehouse_id" => $DATA['posting']['warehouse_id'],
            '_items_sku' => implode(
                ';',
                array_map(
                    function($element) {
                        return $element['sku'];
                    },
                    $DATA['items'],
                ),
            ),
            '_services_MarketplaceRedistributionOfAcquiringOperation' => 
                array_reduce(
                    array_filter(
                        $DATA['services'],
                        function ($element) {
                            $key = "MarketplaceRedistributionOfAcquiringOperation";
                            if (strcmp($element['name'], $key) == 0) {
                                return $element;
                            }
                        }
                    ),
                    function ($sum, $element) {
                        return $sum + $element['price'];
                    },
                    0
                ),
            '_services_MarketplaceServiceItemDropoffPVZ' => 
                array_reduce(
                    array_filter(
                        $DATA['services'],
                        function ($element) {
                            $key = "MarketplaceServiceItemDropoffPVZ";
                            if (strcmp($element['name'], $key) == 0) {
                                return $element;
                            }
                        }
                    ),
                    function ($sum, $element) {
                        return $sum + $element['price'];
                    },
                    0
                ),
            '_services_MarketplaceServiceItemDirectFlowTrans' => 
                array_reduce(
                    array_filter(
                        $DATA['services'],
                        function ($element) {
                            $key = "MarketplaceServiceItemDirectFlowTrans";
                            if (strcmp($element['name'], $key) == 0) {
                                return $element;
                            }
                        }
                    ),
                    function ($sum, $element) {
                        return $sum + $element['price'];
                    },
                    0
                ),
            '_services_MarketplaceServiceItemDelivToCustomer' => 
                array_reduce(
                    array_filter(
                        $DATA['services'],
                        function ($element) {
                            $key = "MarketplaceServiceItemDelivToCustomer";
                            if (strcmp($element['name'], $key) == 0) {
                                return $element;
                            }
                        }
                    ),
                    function ($sum, $element) {
                        return $sum + $element['price'];
                    },
                    0
                ),
            '_services_MarketplaceServiceItemDirectFlowLogistic' => 
                array_reduce(
                    array_filter(
                        $DATA['services'],
                        function ($element) {
                            $key = "MarketplaceServiceItemDirectFlowLogistic";
                            if (strcmp($element['name'], $key) == 0) {
                                return $element;
                            }
                        }
                    ),
                    function ($sum, $element) {
                        return $sum + $element['price'];
                    },
                    0
                ),
            '_services_MarketplaceServiceItemReturnAfterDelivToCustomer' => 
                array_reduce(
                    array_filter(
                        $DATA['services'],
                        function ($element) {
                            $key = "MarketplaceServiceItemReturnAfterDelivToCustomer";
                            if (strcmp($element['name'], $key) == 0) {
                                return $element;
                            }
                        }
                    ),
                    function ($sum, $element) {
                        return $sum + $element['price'];
                    },
                    0
                ),
            '_services_MarketplaceServiceItemReturnFlowTrans' => 
                array_reduce(
                    array_filter(
                        $DATA['services'],
                        function ($element) {
                            $key = "MarketplaceServiceItemReturnFlowTrans";
                            if (strcmp($element['name'], $key) == 0) {
                                return $element;
                            }
                        }
                    ),
                    function ($sum, $element) {
                        return $sum + $element['price'];
                    },
                    0
                ),
            '_services_MarketplaceServiceItemReturnFlowLogistic' => 
                array_reduce(
                    array_filter(
                        $DATA['services'],
                        function ($element) {
                            $key = "MarketplaceServiceItemReturnFlowLogistic";
                            if (strcmp($element['name'], $key) == 0) {
                                return $element;
                            }
                        }
                    ),
                    function ($sum, $element) {
                        return $sum + $element['price'];
                    },
                    0
                ),
            '_services_MarketplaceServiceItemRedistributionReturnsPVZ' => 
                array_reduce(
                    array_filter(
                        $DATA['services'],
                        function ($element) {
                            $key = "MarketplaceServiceItemRedistributionReturnsPVZ";
                            if (strcmp($element['name'], $key) == 0) {
                                return $element;
                            }
                        }
                    ),
                    function ($sum, $element) {
                        return $sum + $element['price'];
                    },
                    0
                ),
            '_services_MarketplaceServiceItemReturnNotDelivToCustomer' => 
                array_reduce(
                    array_filter(
                        $DATA['services'],
                        function ($element) {
                            $key = "MarketplaceServiceItemReturnNotDelivToCustomer";
                            if (strcmp($element['name'], $key) == 0) {
                                return $element;
                            }
                        }
                    ),
                    function ($sum, $element) {
                        return $sum + $element['price'];
                    },
                    0
                ),
            '_services_MarketplaceServiceItemReturnPartGoodsCustomer' => 
                array_reduce(
                    array_filter(
                        $DATA['services'],
                        function ($element) {
                            $key = "MarketplaceServiceItemReturnPartGoodsCustomer";
                            if (strcmp($element['name'], $key) == 0) {
                                return $element;
                            }
                        }
                    ),
                    function ($sum, $element) {
                        return $sum + $element['price'];
                    },
                    0
                ),
            '_raw_json' => json_encode($DATA, JSON_UNESCAPED_UNICODE),
        ];

        return $RESULT_DATA;
    }
}
