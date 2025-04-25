## Что делает этот код?

После выполнения задачи CRON происходит парсинг `https://api-seller.ozon.ru` себе в базу данных SQLite.
А веб-сайт возвращает данные в формате JSON из базы данных SQLite.

По сути у нас получилось кэширование данных.
Каждый заход по ссылке (API) мы не выполняем запрос на `https://api-seller.ozon.ru`,
а просто достаем из нашей базы данных,
которая обновляется каждый час.

При запуске скрипта `main_cron_start.php` в папке `data` создаются JSON файлы данных (с `https://api-seller.ozon.ru`)
и данные загружаются в базу данных database.sqlite.

Данные, которые парсятся:

```
.
`-- data
    |-- v1_DeliveryMethodListService.json       # POST https://api-seller.ozon.ru/v1/delivery-method/list/
    |-- v1_DescriptionCategoryTreeService.json  # POST https://api-seller.ozon.ru/v1/description-category/tree/
    |-- v1_RatingSummary.json                   # POST https://api-seller.ozon.ru/v1/rating/summary
    |-- v1_ReturnsList.json                     # POST https://api-seller.ozon.ru/v1/returns/list
    |-- v1_WarehouseListService.json            # POST https://api-seller.ozon.ru/v1/warehouse/list/
    |-- v2_PostingFbsActGetPostingsService.json # POST https://api-seller.ozon.ru/v2/posting/fbs/act/get-postings/list/
    |-- v2_PostingFbsActListService.json        # POST https://api-seller.ozon.ru/v2/posting/fbs/act/list/
    |-- v3_FinanceTransactionList.json          # POST https://api-seller.ozon.ru/v3/finance/transaction/list/
    |-- v3_ProductInfoListService.json          # POST https://api-seller.ozon.ru/v3/product/info/list/
    |-- v3_ProductListService.json              # POST https://api-seller.ozon.ru/v3/product/list/
    `-- v4_ProductInfoAttributesService.json    # POST https://api-seller.ozon.ru/v4/product/info/attributes/
```

## Дерево проекта

```
tree -d --charset=ascii
```

```
.
|-- README.md                                       # Инструкция репозитория
|-- example
|   |-- cpanel                                      # Корень хостинга cPanel
|   |   `-- home
|   |       `-- user                                # Имя пользователя cPanel
|   |           |-- _myProject
|   |           |   `-- parser_api-seller.ozon.ru   # Тут содержимое из папки src
|   |           |       |-- cron                    # Тут содержимое из папки src/cron
|   |           |       `-- www                     # Тут содержимое из папки src/www
|   |           `-- public_html                     # Тут веб-сайт, но можно оставить папку пустой
|   |               |-- api                         # symbollink.php создаст эту папку
|   |               |   `-- api-seller              # symbollink.php создаст символьную ссылку
|   |               `-- symbollink.php              # Открываем https://<имя-сайта>/symbollink.php
|   `-- d                                           # Диск D на Windows
|       `-- webbox                                  # Папка для Apache и PHP
|           `-- PHP
|               `-- php.ini                         # Пример конфига php.ini
`-- src                                             # Тут код
    |-- cron                                        # Тут код, который выполняется каждый час (CRON)
    `-- www                                         # Тут код API - веб-сайт возвращающий JSON
```

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
    1. Перед запуском убедитесь, что существует файл `/src/cron/env.php`. Если его нет скопируй содержимое `/src/cron/env.example.php`
    1. Для того, чтобы работали include в PHP, установите переменную среды (`PHP_CRON_HOME`) через свойства компьютера
        ```
        PHP_CRON_HOME=D:/_git/cron_api-seller.ozon.ru
        ```
    1. Запустите через cmd php скрипт
        ```
        php main_cron_start.php > main.log
        ```
    1. Если не хотите запустить сайт через Apache
        1. Запустите сайт командой
            ```
            cd src
            cd www
            php -S localhost:3000
            ```
        1. Перейдите по ссылке http://localhost:3000
    1. Если хотите запустить сайт через Apache
        1. Скачайте Apache 2.4
            1. Перейдите на оффициальный сайт: https://httpd.apache.org/
            1. Жмём "Download!" - и попадем на страницу https://httpd.apache.org/download.cgi
            1. Жмём "Files for Microsoft Windows" - и попадем на страницу https://httpd.apache.org/docs/current/platform/windows.html#down
            1. Жмём "Apache Lounge" - и попадем на страницу https://www.apachelounge.com/download/
            1. Скачиваем "httpd-2.4.63-250207-win64-VS17.zip"
        1. Настройте Apache
            1. Распакуйте "httpd-2.4.63-250207-win64-VS17.zip/Apache24" в папку `D:\webbox\Apache`
            1. Отредактируйте конфиг Apache/conf/httpd.conf
                ```
                ###Define SRVROOT "c:/Apache24"

                ### < < < < < < < <
                Define SRVROOT "D:/webbox/Apache"
                ### > > > > > > > >
                ```

                ```
                ### < < < < < < < <
                LoadModule php_module "D:/webbox/PHP/php8apache2_4.dll"
                PHPIniDir "D:/webbox/PHP"
                ### > > > > > > > >
                ```

                ```
                ### < < < < < < < <
                ServerName localhost:80
                ### > > > > > > > >
                ```

                ```
                <IfModule dir_module>
                    ## DirectoryIndex index.html
                    ### < < < < < < < <
                    DirectoryIndex index.html index.php
                    ### > > > > > > > >
                </IfModule>
                ```

                ```
                <IfModule mime_module>
                    ### < < < < < < < <
                    AddType application/x-httpd-php .php
                    AddType application/x-httpd-php-source .phps
                    ### > > > > > > > >
                ```

                ```
                ### < < < < < < < <
                Listen 58000
                <VirtualHost *:58000>
                    DocumentRoot "D:/_git/parser_api-seller.ozon.ru/src/www"
                    ErrorLog "logs/parser_api-seller.ozon.ru-error.log"
                    CustomLog "logs/parser_api-seller.ozon.ru-access.log" common
                    DirectoryIndex index.html index.php
                    <Directory "D:/_git/parser_api-seller.ozon.ru/src/www">
                        AllowOverride All
                        Require all granted
                    </Directory>
                </VirtualHost>
                ### > > > > > > > >
                ```

                Готовый файл [httpd.conf](/webbox/Apache/conf/httpd.conf) можно найти в этом репозитории: `/webbox/Apache/conf/httpd.conf`
            1. Запустите командную строку от имени администратора
            1. В командной строке от имени администратора установить Apache и запустите
                ```
                d:
                cd webbox
                cd Apache
                cd bin
                httpd -k install
                httpd -k start
                ```
            1. Перед заходом на сайт убедитесь в наличии `/src/www/api/api-seller/env.php`.
                Если его нет скопируй содержимое `/src/www/api/api-seller/env.example.php`
            1. Переходите на сайт http://localhost:58000/api/api-seller/

## Настройка cron на cPanel

Переходим в раздел `Расширенный` > `Задания cron`.

### Время cron

Например: `57 * * * *`

```
Минута: 57      # Каждую 57 минуту
Час: *          # Каждый час
День: *         # Каждый день
Месяц: *        # Кадый месяц
День недели: *  # Каждый день недели

```

### Команда cron

Пусть `<path>` = `/home/user/_myProjects/parser_api-seller.ozon.ru/src/cron`, тогда пример команды CRON:

```
export PHP_CRON_HOME=<path> && php <path>/main_cron_start.php
```

если нужно видеть результат выполнения скрипта, то его можно сохранить в текстовый файл:

```
export PHP_CRON_HOME=<path> && php <path>/main_cron_start.php > <path>/cpanel-cron-log.txt
```
