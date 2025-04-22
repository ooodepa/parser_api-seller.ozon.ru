<?php

try {
    $datetime = date('Y-m-d H:i:s');
    echo "[$datetime] = = = = = = = = НАЧАЛО ПРОГРАММЫ = = = = = = = = \n";

    $HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['PHP_CRON_HOME'];

    include_once "$HOME/api-seller/ozon/warehouse/v1_WarehouseListService.php";
    include_once "$HOME/api-seller/ozon/delivery-method/v1_DeliveryMethodListService.php";
    include_once "$HOME/api-seller/ozon/product/v3_ProductListService.php";
    include_once "$HOME/api-seller/ozon/product/v3_ProductInfoListService.php";
    include_once "$HOME/api-seller/ozon/product/v4_ProductInfoAttributesService.php";
    include_once "$HOME/api-seller/ozon/description-category/v1_DescriptionCategoryTreeService.php";
    include_once "$HOME/api-seller/ozon/finance/v3_FinanceTransactionListService.php";
    include_once "$HOME/api-seller/ozon/finance/my_PostingList.php";
    include_once "$HOME/api-seller/ozon/posting/v3_PostingFbsGetService.php";
    include_once "$HOME/api-seller/ozon/posting/v2_PostingFbsActListService.php";
    include_once "$HOME/api-seller/ozon/posting/v2_PostingFbsActGetPostingsService.php";

    $services = [
        new v1_WarehouseListService(),
        new v1_DeliveryMethodListService(), // ничего не возвращает
        new v3_ProductListService(),
        new v3_ProductInfoListService(),
        new v4_ProductInfoAttributesService(),
        new v1_DescriptionCategoryTreeService(),
        new v3_FinanceTransactionListService(),
        new my_PostingList(),
        new v3_PostingFbsGetService(),
        new v2_PostingFbsActListService(),
        new v2_PostingFbsActGetPostingsService(), // только с января 2025 года данные показывает
    ];

    $count = count($services);
    for ($i = 0; $i < $count; $i++) {
        try {
            ($services[$i])->executeCron();

            $datetime = date('Y-m-d H:i:s');

            $n = $i + 1;
            $procent = 100 * $n / $count;
            $rounded_procent = round($procent, 2, PHP_ROUND_HALF_UP);
            echo "[$datetime] $n/$count ($rounded_procent%) | Операция выполнена успешно\n";
        }
        catch(Throwable $exception) {
            $datetime = date('Y-m-d H:i:s');
            $n = $i + 1;
            $procent = 100 * $n / $count;
            $rounded_procent = round($procent, 2, PHP_ROUND_HALF_UP);
            echo "[$datetime] $n/$count ($rounded_procent%) | Произошло исключение\n";

            echo "< < < < < < < < (i = $i)";
            print_r($exception);
            echo "(i = $i) > > > > > > > >";
        }
    }

    $datetime = date('Y-m-d H:i:s');
    echo "[$datetime] = = = = = = = = КОНЕЦ ПРОГРАММЫ = = = = = = = = \n";
}
catch(Throwable $exception) {
    echo "< < < < < < < < EXCEPTION";
    print_r($exception);
    echo "> > > > > > > >";
}
