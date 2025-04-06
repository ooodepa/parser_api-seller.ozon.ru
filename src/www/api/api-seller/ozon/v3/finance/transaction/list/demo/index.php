<?php

$HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['ENV_HOME'];

include "$HOME/api/api-seller/_classes/AuthHelper.class.php";

AuthHelper::exit_ifNotAuth();

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Уникальный список заказов</title>
    <link rel="stylesheet" href="/api/api-seller/assets/npm/bootstrap/dist/css/bootstrap.min.css" />
</head>
<body>
    <div class="container mt-2 mb-2">
        <div id="root">
            Загрузка
        </div>
    </div>

    <script src="/api/api-seller/ozon/v3/finance/transaction/list/demo/js/FinanseTransactionList.class.js"></script>
    <script>
        try {
            FinanseTransactionList.innerHtml();
        }
        catch(exception) {
            console.error(exception);
            alert(exception);
        }
    </script>
</body>
</html>
