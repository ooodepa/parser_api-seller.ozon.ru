<?php

function main() {
    try {
        $HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['ENV_HOME'];

        include "$HOME/api/api-seller/env.php";
        include "$HOME/api/api-seller/_classes/AuthHelper.class.php";

        AuthHelper::exit_ifNotAuth();

        $pdo = new PDO("sqlite:$ENV_SQLITE_DATABASE");

        $sql = "SELECT
                    (
                        round(
                            delivery_charge
                            + return_delivery_charge
                            + accruals_for_sale
                            + sale_commission
                            + _services_MarketplaceRedistributionOfAcquiringOperation
                            + _services_MarketplaceServiceItemDropoffPVZ
                            + _services_MarketplaceServiceItemDirectFlowTrans
                            + _services_MarketplaceServiceItemDelivToCustomer
                            + _services_MarketplaceServiceItemDirectFlowLogistic
                            + _services_MarketplaceServiceItemReturnAfterDelivToCustomer
                            + _services_MarketplaceServiceItemReturnFlowTrans
                            + _services_MarketplaceServiceItemReturnFlowLogistic
                            + _services_MarketplaceServiceItemRedistributionReturnsPVZ
                            + _services_MarketplaceServiceItemReturnNotDelivToCustomer
                            + _services_MarketplaceServiceItemReturnPartGoodsCustomer,
                            2
                        )
                        - amount
                    ) AS zero,
                    *
                FROM
                    OZN_v3_FinanceTransactionList
                ";

        $sth = $pdo->prepare($sql);
        $sth->execute();
        $array = $sth->fetchAll(PDO::FETCH_ASSOC);

        $result_array = [];

        array_push($result_array, [
            'n' => '',
            'i' => '',
            'prihod' => 0,
            'rashod' => 0,
            'itogo' => 0,
            'itogo_2' => '',
            'comment_kod' => '',
            'comment_ru' => '',
            'comment_operation_type_name' => '',
            'comment_operation_id' => '',
            'comment_operation_date' => '',
            'comment__posting_order_date' => '',
            'comment__posting_posting_number' => '',
            'comment__posting_posting_number_1' => '',
            'comment__posting_posting_number_2' => '',
            'comment__posting_posting_number_3' => '',
            'comment__items_sku' => '',
            '_' => '',
        ]);

        $n = 0;
        for ($i = 0; $i < count($array); $i++) {
            $current = $array[$i];
            $transaction_id = $current['operation_id'];
            $transaction_type = $current['operation_type'];
            $transaction_type_name = $current['operation_type_name'];
            $transaction_date = $current['operation_date'];
            $transaction_date = substr($transaction_date, 0, 9);

            if ($current['zero'] != 0) {
                $cost = (float) $current['amount'];

                $itogo_old = (int) $result_array[count($result_array) - 1]['itogo'];
                $itogo_new = $itogo_old + round($cost * 100);

                $n += 1;
                array_push($result_array, [
                    'n' => $n,
                    'i' => $i + 1,
                    'prihod' => $cost > 0 ? $cost : 0,
                    'rashod' => $cost < 0 ? $cost : 0,
                    'itogo' => $itogo_new,
                    'itogo_2' => $itogo_new / 100,
                    'comment_kod' => 'amount',
                    'comment_ru' => '? ? ? ? ? ? ? ?',
                    'comment_operation_type_name' => $current['operation_type_name'],
                    'comment_operation_id' => $current['operation_id'],
                    'comment_operation_date' => $current['operation_date'],
                    'comment__posting_order_date' => $current['_posting_order_date'],
                    'comment__posting_posting_number' => $current['_posting_posting_number'],
                    'comment__posting_posting_number_1' => $current['_posting_posting_number_1'],
                    'comment__posting_posting_number_2' => $current['_posting_posting_number_2'],
                    'comment__posting_posting_number_3' => $current['_posting_posting_number_3'],
                    'comment__items_sku' => $current['_items_sku'],
                    '_' => '',
                ]);
                continue;
            }

            $cost = (float) $current['delivery_charge'];
            if ($cost != 0) {
                $itogo_old = (int) $result_array[count($result_array) - 1]['itogo'];
                $itogo_new = $itogo_old + round($cost * 100);

                $n += 1;
                array_push($result_array, [
                    'n' => $n,
                    'i' => $i + 1,
                    'prihod' => $cost > 0 ? $cost : 0,
                    'rashod' => $cost < 0 ? $cost : 0,
                    'itogo' => $itogo_new,
                    'itogo_2' => $itogo_new / 100,
                    'comment_kod' => 'delivery_charge',
                    'comment_ru' => '',
                    'comment_operation_type_name' => $current['operation_type_name'],
                    'comment_operation_id' => $current['operation_id'],
                    'comment_operation_date' => $current['operation_date'],
                    'comment__posting_order_date' => $current['_posting_order_date'],
                    'comment__posting_posting_number' => $current['_posting_posting_number'],
                    'comment__posting_posting_number_1' => $current['_posting_posting_number_1'],
                    'comment__posting_posting_number_2' => $current['_posting_posting_number_2'],
                    'comment__posting_posting_number_3' => $current['_posting_posting_number_3'],
                    'comment__items_sku' => $current['_items_sku'],
                    '_' => '',
                ]);
            }

            $cost = (float) $current['return_delivery_charge'];
            if ($cost != 0) {
                $itogo_old = (int) $result_array[count($result_array) - 1]['itogo'];
                $itogo_new = $itogo_old + round($cost * 100);

                $n += 1;
                array_push($result_array, [
                    'n' => $n,
                    'i' => $i + 1,
                    'prihod' => $cost > 0 ? $cost : 0,
                    'rashod' => $cost < 0 ? $cost : 0,
                    'itogo' => $itogo_new,
                    'itogo_2' => $itogo_new / 100,
                    'comment_kod' => 'return_delivery_charge',
                    'comment_ru' => '',
                    'comment_operation_type_name' => $current['operation_type_name'],
                    'comment_operation_id' => $current['operation_id'],
                    'comment_operation_date' => $current['operation_date'],
                    'comment__posting_order_date' => $current['_posting_order_date'],
                    'comment__posting_posting_number' => $current['_posting_posting_number'],
                    'comment__posting_posting_number_1' => $current['_posting_posting_number_1'],
                    'comment__posting_posting_number_2' => $current['_posting_posting_number_2'],
                    'comment__posting_posting_number_3' => $current['_posting_posting_number_3'],
                    'comment__items_sku' => $current['_items_sku'],
                    '_' => '',
                ]);
            }

            $cost = (float) $current['accruals_for_sale'];
            if ($cost != 0) {
                $itogo_old = (int) $result_array[count($result_array) - 1]['itogo'];
                $itogo_new = $itogo_old + round($cost * 100);

                $n += 1;
                array_push($result_array, [
                    'n' => $n,
                    'i' => $i + 1,
                    'prihod' => $cost > 0 ? $cost : 0,
                    'rashod' => $cost < 0 ? $cost : 0,
                    'itogo' => $itogo_new,
                    'itogo_2' => $itogo_new / 100,
                    'comment_kod' => 'accruals_for_sale',
                    'comment_ru' => 'За продажу или возврат до вычета комиссий и услуг',
                    'comment_operation_type_name' => $current['operation_type_name'],
                    'comment_operation_id' => $current['operation_id'],
                    'comment_operation_date' => $current['operation_date'],
                    'comment__posting_order_date' => $current['_posting_order_date'],
                    'comment__posting_posting_number' => $current['_posting_posting_number'],
                    'comment__posting_posting_number_1' => $current['_posting_posting_number_1'],
                    'comment__posting_posting_number_2' => $current['_posting_posting_number_2'],
                    'comment__posting_posting_number_3' => $current['_posting_posting_number_3'],
                    'comment__items_sku' => $current['_items_sku'],
                    '_' => '',
                ]);
            }

            $cost = (float) $current['sale_commission'];
            if ($cost != 0) {
                $itogo_old = (int) $result_array[count($result_array) - 1]['itogo'];
                $itogo_new = $itogo_old + round($cost * 100);

                $n += 1;
                array_push($result_array, [
                    'n' => $n,
                    'i' => $i + 1,
                    'prihod' => $cost > 0 ? $cost : 0,
                    'rashod' => $cost < 0 ? $cost : 0,
                    'itogo' => $itogo_new,
                    'itogo_2' => $itogo_new / 100,
                    'comment_kod' => 'sale_commission',
                    'comment_ru' => 'Комиссия за продажу',
                    'comment_operation_type_name' => $current['operation_type_name'],
                    'comment_operation_id' => $current['operation_id'],
                    'comment_operation_date' => $current['operation_date'],
                    'comment__posting_order_date' => $current['_posting_order_date'],
                    'comment__posting_posting_number' => $current['_posting_posting_number'],
                    'comment__posting_posting_number_1' => $current['_posting_posting_number_1'],
                    'comment__posting_posting_number_2' => $current['_posting_posting_number_2'],
                    'comment__posting_posting_number_3' => $current['_posting_posting_number_3'],
                    'comment__items_sku' => $current['_items_sku'],
                    '_' => '',
                ]);
            }

            $cost = (float) $current['_services_MarketplaceRedistributionOfAcquiringOperation'];
            if ($cost != 0) {
                $itogo_old = (int) $result_array[count($result_array) - 1]['itogo'];
                $itogo_new = $itogo_old + round($cost * 100);

                $n += 1;
                array_push($result_array, [
                    'n' => $n,
                    'i' => $i + 1,
                    'prihod' => $cost > 0 ? $cost : 0,
                    'rashod' => $cost < 0 ? $cost : 0,
                    'itogo' => $itogo_new,
                    'itogo_2' => $itogo_new / 100,
                    'comment_kod' => 'MarketplaceRedistributionOfAcquiringOperation',
                    'comment_ru' => 'Эквайринг',
                    'comment_operation_type_name' => $current['operation_type_name'],
                    'comment_operation_id' => $current['operation_id'],
                    'comment_operation_date' => $current['operation_date'],
                    'comment__posting_order_date' => $current['_posting_order_date'],
                    'comment__posting_posting_number' => $current['_posting_posting_number'],
                    'comment__posting_posting_number_1' => $current['_posting_posting_number_1'],
                    'comment__posting_posting_number_2' => $current['_posting_posting_number_2'],
                    'comment__posting_posting_number_3' => $current['_posting_posting_number_3'],
                    'comment__items_sku' => $current['_items_sku'],
                    '_' => '',
                ]);
            }

            $cost = (float) $current['_services_MarketplaceServiceItemDropoffPVZ'];
            if ($cost != 0) {
                $itogo_old = (int) $result_array[count($result_array) - 1]['itogo'];
                $itogo_new = $itogo_old + round($cost * 100);

                $n += 1;
                array_push($result_array, [
                    'n' => $n,
                    'i' => $i + 1,
                    'prihod' => $cost > 0 ? $cost : 0,
                    'rashod' => $cost < 0 ? $cost : 0,
                    'itogo' => $itogo_new,
                    'itogo_2' => $itogo_new / 100,
                    'comment_kod' => 'MarketplaceServiceItemDropoffPVZ',
                    'comment_ru' => 'Обработка отправления (Drop-off/Pick-up)',
                    'comment_operation_type_name' => $current['operation_type_name'],
                    'comment_operation_id' => $current['operation_id'],
                    'comment_operation_date' => $current['operation_date'],
                    'comment__posting_order_date' => $current['_posting_order_date'],
                    'comment__posting_posting_number' => $current['_posting_posting_number'],
                    'comment__posting_posting_number_1' => $current['_posting_posting_number_1'],
                    'comment__posting_posting_number_2' => $current['_posting_posting_number_2'],
                    'comment__posting_posting_number_3' => $current['_posting_posting_number_3'],
                    'comment__items_sku' => $current['_items_sku'],
                    '_' => '',
                ]);
            }

            $cost = (float) $current['_services_MarketplaceServiceItemDirectFlowTrans'];
            if ($cost != 0) {
                $itogo_old = (int) $result_array[count($result_array) - 1]['itogo'];
                $itogo_new = $itogo_old + round($cost * 100);

                $n += 1;
                array_push($result_array, [
                    'n' => $n,
                    'i' => $i + 1,
                    'prihod' => $cost > 0 ? $cost : 0,
                    'rashod' => $cost < 0 ? $cost : 0,
                    'itogo' => $itogo_new,
                    'itogo_2' => $itogo_new / 100,
                    'comment_kod' => 'MarketplaceServiceItemDirectFlowTrans',
                    'comment_ru' => 'Обработка отправления (Drop-off/Pick-up)',
                    'comment_operation_type_name' => $current['operation_type_name'],
                    'comment_operation_id' => $current['operation_id'],
                    'comment_operation_date' => $current['operation_date'],
                    'comment__posting_order_date' => $current['_posting_order_date'],
                    'comment__posting_posting_number' => $current['_posting_posting_number'],
                    'comment__posting_posting_number_1' => $current['_posting_posting_number_1'],
                    'comment__posting_posting_number_2' => $current['_posting_posting_number_2'],
                    'comment__posting_posting_number_3' => $current['_posting_posting_number_3'],
                    'comment__items_sku' => $current['_items_sku'],
                    '_' => '',
                ]);
            }

            $cost = (float) $current['_services_MarketplaceServiceItemDelivToCustomer'];
            if ($cost != 0) {
                $itogo_old = (int) $result_array[count($result_array) - 1]['itogo'];
                $itogo_new = $itogo_old + round($cost * 100);

                $n += 1;
                array_push($result_array, [
                    'n' => $n,
                    'i' => $i + 1,
                    'prihod' => $cost > 0 ? $cost : 0,
                    'rashod' => $cost < 0 ? $cost : 0,
                    'itogo' => $itogo_new,
                    'itogo_2' => $itogo_new / 100,
                    'comment_kod' => 'MarketplaceServiceItemDelivToCustomer',
                    'comment_ru' => 'Последняя миля',
                    'comment_operation_type_name' => $current['operation_type_name'],
                    'comment_operation_id' => $current['operation_id'],
                    'comment_operation_date' => $current['operation_date'],
                    'comment__posting_order_date' => $current['_posting_order_date'],
                    'comment__posting_posting_number' => $current['_posting_posting_number'],
                    'comment__posting_posting_number_1' => $current['_posting_posting_number_1'],
                    'comment__posting_posting_number_2' => $current['_posting_posting_number_2'],
                    'comment__posting_posting_number_3' => $current['_posting_posting_number_3'],
                    'comment__items_sku' => $current['_items_sku'],
                    '_' => '',
                ]);
            }

            $cost = (float) $current['_services_MarketplaceServiceItemDirectFlowLogistic'];
            if ($cost != 0) {
                $itogo_old = (int) $result_array[count($result_array) - 1]['itogo'];
                $itogo_new = $itogo_old + round($cost * 100);

                $n += 1;
                array_push($result_array, [
                    'n' => $n,
                    'i' => $i + 1,
                    'prihod' => $cost > 0 ? $cost : 0,
                    'rashod' => $cost < 0 ? $cost : 0,
                    'itogo' => $itogo_new,
                    'itogo_2' => $itogo_new / 100,
                    'comment_kod' => 'MarketplaceServiceItemDirectFlowLogistic',
                    'comment_ru' => 'Логистика',
                    'comment_operation_type_name' => $current['operation_type_name'],
                    'comment_operation_id' => $current['operation_id'],
                    'comment_operation_date' => $current['operation_date'],
                    'comment__posting_order_date' => $current['_posting_order_date'],
                    'comment__posting_posting_number' => $current['_posting_posting_number'],
                    'comment__posting_posting_number_1' => $current['_posting_posting_number_1'],
                    'comment__posting_posting_number_2' => $current['_posting_posting_number_2'],
                    'comment__posting_posting_number_3' => $current['_posting_posting_number_3'],
                    'comment__items_sku' => $current['_items_sku'],
                    '_' => '',
                ]);
            }

            $cost = (float) $current['_services_MarketplaceServiceItemReturnAfterDelivToCustomer'];
            if ($cost != 0) {
                $itogo_old = (int) $result_array[count($result_array) - 1]['itogo'];
                $itogo_new = $itogo_old + round($cost * 100);

                $n += 1;
                array_push($result_array, [
                    'n' => $n,
                    'i' => $i + 1,
                    'prihod' => $cost > 0 ? $cost : 0,
                    'rashod' => $cost < 0 ? $cost : 0,
                    'itogo' => $itogo_new,
                    'itogo_2' => $itogo_new / 100,
                    'comment_kod' => 'MarketplaceServiceItemReturnAfterDelivToCustomer',
                    'comment_ru' => '',
                    'comment_operation_type_name' => $current['operation_type_name'],
                    'comment_operation_id' => $current['operation_id'],
                    'comment_operation_date' => $current['operation_date'],
                    'comment__posting_order_date' => $current['_posting_order_date'],
                    'comment__posting_posting_number' => $current['_posting_posting_number'],
                    'comment__posting_posting_number_1' => $current['_posting_posting_number_1'],
                    'comment__posting_posting_number_2' => $current['_posting_posting_number_2'],
                    'comment__posting_posting_number_3' => $current['_posting_posting_number_3'],
                    'comment__items_sku' => $current['_items_sku'],
                    '_' => '',
                ]);
            }

            $cost = (float) $current['_services_MarketplaceServiceItemReturnFlowTrans'];
            if ($cost != 0) {
                $itogo_old = (int) $result_array[count($result_array) - 1]['itogo'];
                $itogo_new = $itogo_old + round($cost * 100);

                $n += 1;
                array_push($result_array, [
                    'n' => $n,
                    'i' => $i + 1,
                    'prihod' => $cost > 0 ? $cost : 0,
                    'rashod' => $cost < 0 ? $cost : 0,
                    'itogo' => $itogo_new,
                    'itogo_2' => $itogo_new / 100,
                    'comment_kod' => 'MarketplaceServiceItemReturnFlowTrans',
                    'comment_ru' => '',
                    'comment_operation_type_name' => $current['operation_type_name'],
                    'comment_operation_id' => $current['operation_id'],
                    'comment_operation_date' => $current['operation_date'],
                    'comment__posting_order_date' => $current['_posting_order_date'],
                    'comment__posting_posting_number' => $current['_posting_posting_number'],
                    'comment__posting_posting_number_1' => $current['_posting_posting_number_1'],
                    'comment__posting_posting_number_2' => $current['_posting_posting_number_2'],
                    'comment__posting_posting_number_3' => $current['_posting_posting_number_3'],
                    'comment__items_sku' => $current['_items_sku'],
                    '_' => '',
                ]);
            }

            $cost = (float) $current['_services_MarketplaceServiceItemReturnFlowLogistic'];
            if ($cost != 0) {
                $itogo_old = (int) $result_array[count($result_array) - 1]['itogo'];
                $itogo_new = $itogo_old + round($cost * 100);

                $n += 1;
                array_push($result_array, [
                    'n' => $n,
                    'i' => $i + 1,
                    'prihod' => $cost > 0 ? $cost : 0,
                    'rashod' => $cost < 0 ? $cost : 0,
                    'itogo' => $itogo_new,
                    'itogo_2' => $itogo_new / 100,
                    'comment_kod' => 'MarketplaceServiceItemReturnFlowLogistic',
                    'comment_ru' => 'Обратная логистика',
                    'comment_operation_type_name' => $current['operation_type_name'],
                    'comment_operation_id' => $current['operation_id'],
                    'comment_operation_date' => $current['operation_date'],
                    'comment__posting_order_date' => $current['_posting_order_date'],
                    'comment__posting_posting_number' => $current['_posting_posting_number'],
                    'comment__posting_posting_number_1' => $current['_posting_posting_number_1'],
                    'comment__posting_posting_number_2' => $current['_posting_posting_number_2'],
                    'comment__posting_posting_number_3' => $current['_posting_posting_number_3'],
                    'comment__items_sku' => $current['_items_sku'],
                    '_' => '',
                ]);
            }

            $cost = (float) $current['_services_MarketplaceServiceItemRedistributionReturnsPVZ'];
            if ($cost != 0) {
                $itogo_old = (int) $result_array[count($result_array) - 1]['itogo'];
                $itogo_new = $itogo_old + round($cost * 100);

                $n += 1;
                array_push($result_array, [
                    'n' => $n,
                    'i' => $i + 1,
                    'prihod' => $cost > 0 ? $cost : 0,
                    'rashod' => $cost < 0 ? $cost : 0,
                    'itogo' => $itogo_new,
                    'itogo_2' => $itogo_new / 100,
                    'comment_kod' => 'MarketplaceServiceItemRedistributionReturnsPVZ',
                    'comment_ru' => 'Обработка возврата',
                    'comment_operation_type_name' => $current['operation_type_name'],
                    'comment_operation_id' => $current['operation_id'],
                    'comment_operation_date' => $current['operation_date'],
                    'comment__posting_order_date' => $current['_posting_order_date'],
                    'comment__posting_posting_number' => $current['_posting_posting_number'],
                    'comment__posting_posting_number_1' => $current['_posting_posting_number_1'],
                    'comment__posting_posting_number_2' => $current['_posting_posting_number_2'],
                    'comment__posting_posting_number_3' => $current['_posting_posting_number_3'],
                    'comment__items_sku' => $current['_items_sku'],
                    '_' => '',
                ]);
            }

            $cost = (float) $current['_services_MarketplaceServiceItemReturnNotDelivToCustomer'];
            if ($cost != 0) {
                $itogo_old = (int) $result_array[count($result_array) - 1]['itogo'];
                $itogo_new = $itogo_old + round($cost * 100);

                $n += 1;
                array_push($result_array, [
                    'n' => $n,
                    'i' => $i + 1,
                    'prihod' => $cost > 0 ? $cost : 0,
                    'rashod' => $cost < 0 ? $cost : 0,
                    'itogo' => $itogo_new,
                    'itogo_2' => $itogo_new / 100,
                    'comment_kod' => 'MarketplaceServiceItemReturnNotDelivToCustomer',
                    'comment_ru' => '',
                    'comment_operation_type_name' => $current['operation_type_name'],
                    'comment_operation_id' => $current['operation_id'],
                    'comment_operation_date' => $current['operation_date'],
                    'comment__posting_order_date' => $current['_posting_order_date'],
                    'comment__posting_posting_number' => $current['_posting_posting_number'],
                    'comment__posting_posting_number_1' => $current['_posting_posting_number_1'],
                    'comment__posting_posting_number_2' => $current['_posting_posting_number_2'],
                    'comment__posting_posting_number_3' => $current['_posting_posting_number_3'],
                    'comment__items_sku' => $current['_items_sku'],
                    '_' => '',
                ]);
            }

            $cost = (float) $current['_services_MarketplaceServiceItemReturnPartGoodsCustomer'];
            if ($cost != 0) {
                $itogo_old = (int) $result_array[count($result_array) - 1]['itogo'];
                $itogo_new = $itogo_old + round($cost * 100);

                $n += 1;
                array_push($result_array, [
                    'n' => $n,
                    'i' => $i + 1,
                    'prihod' => $cost > 0 ? $cost : 0,
                    'rashod' => $cost < 0 ? $cost : 0,
                    'itogo' => $itogo_new,
                    'itogo_2' => $itogo_new / 100,
                    'comment_kod' => 'MarketplaceServiceItemReturnPartGoodsCustomer',
                    'comment_ru' => '',
                    'comment_operation_type_name' => $current['operation_type_name'],
                    'comment_operation_id' => $current['operation_id'],
                    'comment_operation_date' => $current['operation_date'],
                    'comment__posting_order_date' => $current['_posting_order_date'],
                    'comment__posting_posting_number' => $current['_posting_posting_number'],
                    'comment__posting_posting_number_1' => $current['_posting_posting_number_1'],
                    'comment__posting_posting_number_2' => $current['_posting_posting_number_2'],
                    'comment__posting_posting_number_3' => $current['_posting_posting_number_3'],
                    'comment__items_sku' => $current['_items_sku'],
                    '_' => '',
                ]);
            }
        }

        $response = [
            'data' => $result_array,
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
