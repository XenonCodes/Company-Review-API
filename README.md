# Тестовое задание: Использование API-2

## Требования к системе

- PHP 8.3
- Laravel 10
- MySQL 8.0
- Composer
- Docker

## Установка

1. Клонировать этот репозиторий

```
git clone https://github.com/XenonCodes/test-task-API-2.git my-project
```

2. Создайте файл .env
```
cd my-project/
cp .env.example .env
```

3. Соберите образы и запустите все контейнеры
```
docker-compose up -d
```

3. Установить зависимости проекта
```
composer install
```

4. Создайте ключ проекта Laravel
```
php artisan key:generate
```

5. В открытой консоли директории проекта введите команду для генерации таблиц с фековыми данными
```
php artisan migrate -seed
```