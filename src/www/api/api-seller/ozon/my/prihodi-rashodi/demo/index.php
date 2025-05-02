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
    <title>Приход и расход</title>
    <!-- start css -->
    <link rel="stylesheet" href="/api/api-seller/assets/npm/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/assets/cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="/assets/cdn.datatables.net/buttons/3.2.2/css/buttons.dataTables.min.css" />
    <!-- end css -->
    <!-- start js -->
    <script src="/assets/code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="/assets/cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <script src="/assets/cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.min.js"></script>
    <script src="/assets/cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.min.js"></script>
    <script src="/assets/cdn.datatables.net/buttons/3.2.2/js/buttons.bootstrap5.min.js"></script>
    <script src="/assets/cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="/assets/cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="/assets/cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js"></script>
    <script src="/assets/cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>
    <script src="/assets/cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>
    <!-- end js -->
</head>
<body>
    <div class="container mt-2 mb-2">
        <div id="root">
            <table id="myTable1" class="table table-striped table-bordered myTable" cellspacing="0" width="100%">
            </table>
            <style>
                .myTable thead tr th {
                    text-align: center!important;
                    vertical-align: middle;
                }
            </style>
        </div>
    </div>
    <script src="js/datatable-init.js"></script>
    <script src="js/change_title.js"></script>
</body>
</html>
