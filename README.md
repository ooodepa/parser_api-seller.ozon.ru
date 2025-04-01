## Что делает этот код?

При запуске скрипта `main_cron_start.php` в папке `data` создаются JSON файлы данных (с api-seller.ozon.ru)
и данные загружаются в базу данных database.sqlite.

```
.
`-- data
    |-- v1_DeliveryMethodListService.json       # POST https://api-seller.ozon.ru/v1/delivery-method/list/
    |-- v1_DescriptionCategoryTreeService.json  # POST https://api-seller.ozon.ru/v1/description-category/tree/
    |-- v1_WarehouseListService.json            # POST https://api-seller.ozon.ru/v1/warehouse/list/
    |-- v2_PostingFbsActGetPostingsService.json # POST https://api-seller.ozon.ru/v2/posting/fbs/act/get-postings/list/
    |-- v2_PostingFbsActListService.json        # POST https://api-seller.ozon.ru/v2/posting/fbs/act/list/
    |-- v3_FinanceTransactionList.json          # POST https://api-seller.ozon.ru/v3/finance/transaction/list/
    |-- v3_ProductInfoListService.json          # POST https://api-seller.ozon.ru/v3/product/info/list/
    |-- v3_ProductListService.json              # POST https://api-seller.ozon.ru/v3/product/list/
    `-- v4_ProductInfoAttributesService.json    # POST https://api-seller.ozon.ru/v4/product/info/attributes/
```

Эти данные можно использовать, чтобы забрать их в свою базу sqlite, MySQL или для других целей.

## Как запустить

- Запуск в Windows 11 24H2
    1. Скачайте PHP 8.4
        1. Перейдите на оффициальный сайт: https://www.php.net/
        1. Жмём "Downloads" - и попадем на страницу https://www.php.net/downloads.php
        1. Жмём "Binaries are available for Microsoft Windows" - и попадаем на https://windows.php.net/download/
        1. Жмём "archives" - и попадаем на https://windows.php.net/downloads/releases/archives/
        1. Скачиваем "php-8.4.4-Win32-vs17-x86.zip"
        1. Распакуйте "php-8.4.4-Win32-vs17-x86.zip" в папку `D:\webbox\PHP`
        1. Добавляем PHP в переменные окружения: `D:\webbox\PHP`.
        1. После добавления в переменные окружения, можем проверить версию PHP, через командну строку: `php -v`
    1. В PHP нужно включить плагины `curl`, `pdo_sqlite` в `D:\webbox\PHP\php.ini`:

        ```ini
        extension_dir = "ext"
        extension=curl
        extension=pdo_sqlite
        ```

        Готовый файл [php.ini](/webbox/PHP/php.ini) можно найти в этом репозитории: `/webbox/PHP/php.ini`
    1. Перед запуском убедитесь, что существует файл `env.php`. Если его нет скопируй содержимое `env.example.php`
    1. Для того, чтобы работали include в PHP, установите переменную среды (`PHP_CRON_HOME`) через свойства компьютера
        ```
        PHP_CRON_HOME=D:/_git/cron_api-seller.ozon.ru
        ```
    1. Запустите через cmd php скрипт
        ```
        php main_cron_start.php > main.log
        ```

## Как запустить CRON в cPanel, если нельзя указать переменную окружения?

В cPanel установите переменную среды через команду export сразу в команде CRON

```
export PHP_CRON_HOME=/home/user/cron_api-seller.ozon.ru && php main_cron_start.php > main.log
```
