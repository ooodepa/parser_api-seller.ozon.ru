<?php

class PhpInfoHelper {
    static function getInfoAbout($key) {
        try {
            switch($key) {
                case "php_version":
                    return phpversion();

                case "apache_version":
                    return apache_get_version();

                case "os1":
                    return php_uname();

                case "os2":
                    return PHP_OS;

                case "timezone":
                    return date_default_timezone_get();

                case "ip":
                    return $_SERVER['SERVER_ADDR'];

                case "url":
                    return ($_SERVER['HTTPS'] ? 'https' : 'http')
                        . "://"
                        . $_SERVER['HTTP_HOST']
                        . $_SERVER['REQUEST_URI'];

                case "domain":
                    return $_SERVER['SERVER_NAME'];

                case "datetime":
                    return date('Y-m-d\TH:i:s');

                case "home":
                    $HOME = strlen($_SERVER['DOCUMENT_ROOT']) != 0 ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['ENV_HOME'];
                    return $HOME;

                default:
                    return "\"$key\" не предусмотрен";
            }
        }
        catch(Throwable $exception) {
            return "$exception";
        }
    }

    static function echoJsonAndExit() {
        http_response_code(200);
        header('Content-Type: application/json');

        $data = [
            'datetime' => PhpInfoHelper::getInfoAbout("datetime"),
            'php_version' => PhpInfoHelper::getInfoAbout("php_version"),
            'apache_version' => PhpInfoHelper::getInfoAbout("apache_version"),
            'os1' => PhpInfoHelper::getInfoAbout("os1"),
            'os2' => PhpInfoHelper::getInfoAbout("os2"),
            'timezone' => PhpInfoHelper::getInfoAbout("timezone"),
            'ip' => PhpInfoHelper::getInfoAbout("ip"),
            'url' => PhpInfoHelper::getInfoAbout("url"),
            'domain' => PhpInfoHelper::getInfoAbout("domain"),
            'home' => PhpInfoHelper::getInfoAbout("home"),
        ];

        echo json_encode($data);
        exit;
    }
}

(function() {
    try {
        $HTTP_METHOD = $_SERVER['REQUEST_METHOD'];

        switch($HTTP_METHOD) {
            case "GET":
                phpinfo();
                return;

            case "POST":
                PhpInfoHelper::echoJsonAndExit();
                return;

            default:
                echo "<p>HTTP метод $HTTP_METHOD не предусмотрен</p>";
                return;
        }
    }
    catch(Throwable $exception) {
        echo "<pre style='color: red;'>";
        print_r($exception);
        echo "</pre>";
    }
})();
