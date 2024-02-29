# Сгенерированная среда PHPDocker.io

## Добавление в ваш проект

Просто разархивируйте файл в свой проект. Это создаст `docker-compose.yml` в корне вашего проекта и папку с именем `phpdocker`, содержащую конфигурацию nginx и php-fpm для него.

Убедитесь, что конфигурация веб-сервера в файле `phpdocker/nginx/nginx.conf` корректна для вашего проекта. PHPDocker.io настроит этот файл в соответствии с местоположением контроллера, относительно файла docker-compose, который вы выбрали в генераторе (по умолчанию `public/index.php`).

**Примечание:** вы можете разместить файлы в другом месте в вашем проекте. Убедитесь, что вы измените местоположения для dockerfile php-fpm, переопределений php.ini и конфигурации nginx в `docker-compose.yml`, если вы это сделаете.

## Как запустить

### Зависимости:

- Docker. См. [инструкции по установке Docker](https://docs.docker.com/engine/installation)
- Docker-compose. См. [инструкции по установке Docker-compose](https://docs.docker.com/compose/install/)

После завершения, просто перейдите в свой проект с помощью `cd` и запустите `docker-compose up -d`. Это инициализирует и запустит все контейнеры, а затем оставит их работать в фоновом режиме.

## Сервисы, доступные за пределами вашей среды

Вы можете получить доступ к вашему приложению через **`localhost`**. Mailhog и nginx отвечают на любое имя хоста, в случае, если вы хотите добавить свое собственное имя хоста в ваш `/etc/hosts`.

| Сервис     | Адрес вне контейнеров      |
|------------|-----------------------------|
| Веб-сервер | [localhost:4444](http://localhost:4444) |
| MySQL      | **хост:** `localhost`; **порт:** `4446` |

## Хосты в вашей среде

Вам нужно настроить ваше приложение для использования любых включенных сервисов:

| Сервис  | Имя хоста | Номер порта |
|---------|-----------|-------------|
| php-fpm | php-fpm   | 9000        |
| MySQL   | mysql     | 3306 (по умолчанию) |

## Docker compose шпаргалка

**Примечание:** сначала вам нужно перейти в каталог, где находится ваш файл `docker-compose.yml`.

- Запустить контейнеры в фоновом режиме: `docker-compose up -d`
- Запустить контейнеры на переднем плане: `docker-compose up`. Вы увидите поток журналов для каждого запущенного контейнера. `ctrl+c` останавливает контейнеры.
- Остановить контейнеры: `docker-compose stop`
- Убить контейнеры: `docker-compose kill`
- Просмотреть журналы контейнера: `docker-compose logs` для всех контейнеров или `docker-compose logs SERVICE_NAME` для журналов всех контейнеров в `SERVICE_NAME`.
- Выполнить команду внутри контейнера: `docker-compose exec SERVICE_NAME COMMAND`, где `COMMAND` - то, что вы хотите выполнить. Примеры:
    - Войти в оболочку PHP, `docker-compose exec php-fpm bash`
    - Запустить консоль Symfony, `docker-compose exec php-fpm bin/console`
    - Открыть оболочку mysql, `docker-compose exec mysql mysql -uroot -pCHOSEN_ROOT_PASSWORD`

## Права доступа к файлам приложения

Как и во всех серверных средах, вашему приложению нужны правильные разрешения на файлы для правильной работы. Вы можете изменить файлы во всем контейнере, поэтому вам не стоит беспокоиться о том, существует ли пользователь или имеет ли тот же идентификатор на вашем хосте.

`docker-compose exec php-fpm chown -R www-data:www-data /application/public`

## Рекомендации

Избежать проблем с правами доступа к файлам в контейнерах достаточно сложно из-за того, что с точки зрения вашей операционной системы любые файлы, созданные в контейнере, принадлежат процессу, который запускает движок Docker (обычно это root). Разные операционные системы также могут сталкиваться с разными проблемами. Например, вы можете запускать команды в контейнерах, используя `docker exec -it -u $(id -u):$(id -g) CONTAINER_NAME COMMAND`, чтобы принудительно использовать текущий идентификатор пользователя в процессе. Однако это будет работать только в случае, если ваша основная операционная система - Linux, а не macOS. Следуйте нескольким простым правилам и избегайте проблем:

- Запускайте composer вне контейнера php, поскольку в противном случае все ваши зависимости будут установлены под управлением `root` в каталоге `vendor`.
- Выполняйте команды (например, консоль Symfony или artisan Laravel) непосредственно внутри вашего контейнера. Вы легко можете открыть оболочку, как описано выше, и выполнять нужные операции оттуда.

# Простая базовая конфигурация Xdebug с интеграцией в PHPStorm

## Xdebug 2

Для настройки **Xdebug 2** вам нужно добавить следующие строки в файл php-fpm/php-ini-overrides.ini:

### Для Linux:

```
xdebug.remote_enable = 1
xdebug.remote_connect_back = 1
xdebug.remote_autostart = 1
```

### Для macOS и Windows:

```
xdebug.remote_enable = 1
xdebug.remote_host = host.docker.internal
xdebug.remote_autostart = 1
```

## Xdebug 3

Для настройки **Xdebug 3** вам нужно добавить следующие строки в файл php-fpm/php-ini-overrides.ini:

### Для Linux:


```
xdebug.mode=debug
xdebug.discover_client_host=true
xdebug.start_with_request=yes
xdebug.client_port=9000
```

### Для macOS и Windows:

```
xdebug.mode = debug
xdebug.client_host = host.docker.internal
xdebug.start_with_request = yes
```

## Добавьте раздел "environment" в сервис php-fpm в файле docker-compose.yml:

```
environment:
  PHP_IDE_CONFIG: "serverName=Docker"
```

### Создайте конфигурацию сервера в PHPStorm:

* В PHPStorm откройте Preferences | Languages & Frameworks | PHP | Servers
* Добавьте новый сервер
* Поле "Name" должно совпадать с параметром "serverName" в "environment" в файле docker-compose.yml (например, *Docker* в приведенном выше примере)
* Значение поля "port" должно быть таким же, как первый порт (перед двоеточием) в сервисе "webserver" в файле docker-compose.yml
* Выберите "Use path mappings" и установите соответствия между путем к вашему проекту на хост-системе и контейнером Docker.
* Наконец, добавьте расширение "Xdebug helper" в ваш браузер, установите точки останова и начните отладку

### Создайте файл launch.json для Visual Studio Code
```
  {
      "version": "0.2.0",
      "configurations": [
          {
              "name": "Docker",
              "type": "php",
              "request": "launch",
              "port": 9000,
              // Отображение удаленного пути -> Локальный путь к проекту
              "pathMappings": {
                  "/application/public": "${workspaceRoot}/"
              },
          }
      ]
  }
```
