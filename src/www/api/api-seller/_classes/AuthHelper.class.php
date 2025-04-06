<?php

class AuthHelper {
    static function exit_ifNotAuth() {
        try {
            $HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['ENV_HOME'];

            include "$HOME/api/api-seller/env.php";

            if (!isset($_COOKIE['auth_id'])) {
                http_response_code(401);
                echo "<p style='color: red;'>Вы не авторизованы</p>";
                exit;
            }

            $auth_id_server = $ENV_AUTH_ID;
            $auth_id_client = $_COOKIE['auth_id'];

            if (strlen("$auth_id_server") == 0) {
                http_response_code(500);
                echo "<p style='color: red;'>Не задан секретный ключ на сервере</p>";
                exit;
            }

            if (strcmp("$auth_id_server", "$auth_id_client") != 0) {
                http_response_code(401);
                echo "<p style='color: red;'>Вы не авторизованы</p>";
                exit;
            }
        }
        catch(Throwable $exception) {
            echo "<pre>";
            print_r($exception);
            echo "</pre>";
            exit;
        }
    }
}
