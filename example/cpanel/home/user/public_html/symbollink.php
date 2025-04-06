<?php

try {
    $USERNAME = 'user';
    echo "<p>USERNAME = $USERNAME</p>";

    $PUBLIC_HTML = 'public_html';
    echo "<p>PUBLIC_HTML = $PUBLIC_HTML</p>";

    echo "<p>Создание папок</p>";
    mkdir("/home/$USERNAME/$PUBLIC_HTML/api/", 0777, true);

    echo "<p>Создание символьной ссылки</p>";
    symlink(
        "/home/$USERNAME/_myProjects/parser_api-seller.ozon.ru/www/api/api-seller",
        "/home/$USERNAME/$PUBLIC_HTML/api/api-seller"
    );

    echo "<p>Конец выполнения скрипта</p>";
}
catch(Throwable $exception) {
    echo "<p>Исключение</p>";
    echo "<pre>";
    print_r($exception);
    echo "</pre>";
}
