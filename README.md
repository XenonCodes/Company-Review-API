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
php artisan migrate --seed
```

## Пользователи (Users)

1. Показать всех пользователей.

```
GET /api/users
```

2. Создать пользователя.

```
POST /api/users

Тело запроса:
{
  "first_name": "John",
  "last_name": "Doe",
  "phone_number": "+70000000000"
}
```

3. Обновить информацию о пользователе.

```
POST /api/users/{userId}?_method=PUT

Тело запроса:
{
  "first_name": "Michele",
  "last_name": "Brekke",
  "phone_number": "+70000000000",
  "avatar": "img.jpg"
}
```

4. Получить информацию о пользователе.

```
GET /api/users/{userId}
```

5. Удалить пользователя.

```
DELETE /api/users/{userId}
```

## Компании (Сompanies)

1. Показать все компании.

```
GET /api/companies
```

2. Создать компанию.

```
POST /api/companies

Тело запроса:
{
  "name": "McKenzie LLC",
  "description": "Quibusdam quia vel ipsam et et quia cum. Ab nesciunt officiis enim sit iusto. Facere hic occaecati ipsam suscipit laudantium beatae. Consequatur dolor alias corrupti officiis."
}
```

3. Обновить информацию о компании.

```
POST /api/companies/{companyId}?_method=PUT

Тело запроса:
{
  "name": "Hudson Inc",
  "description": "Et velit aut ut quibusdam saepe aperiam ullam cum. Quis nihil quae dolores. Impedit porro doloremque expedita. Illo aut hic maxime velit qui accusamus.",
  "logo": "img.png"
}
```

4. Получить информацию о компании.

```
GET /api/companies/{companyId}
```

5. Удалить компанию.

```
DELETE /api/companies/{companyId}
```

6. Получить комментарии компании по ID.

```
GET /api/companies/{companyId}/comments
```

7. Рассчитать общую оценку компании.

```
GET /api/companies/{companyId}/rating
```

8. Получить топ-10 компаний по оценке.

```
GET /api/companies/top
```

## Комментарии (Сomments)

1. Показать все комментарии.

```
GET /api/comments
```

2. Создать комментарий.

```
POST /api/comments

Тело запроса:
{
  "user_id": "1",
  "company_id": "1",
  "content": "Test officia voluptatem ipsum. Voluptatem voluptas suscipit quia rerum et sequi at. Eos dolores sint sed asperiores animi enim. Voluptatem voluptas suscipit quia rerum et sequi at. Maxime suscipit in ad tempore repellendus consequatur.",
  "rating": "5"
}
```

3. Обновить комментарий.

```
PUT /api/comments/{commentId}

Тело запроса:
{
  "content": "Test officia voluptatem ipsum. Eos dolores sint sed asperiores animi enim. Voluptatem voluptas suscipit quia rerum et sequi at. Maxime suscipit in ad tempore repellendus consequatur.",
  "rating": "2"
}
```

4. Получить комментарий.

```
GET /api/comments/{commentId}
```

5. Удалить комментарий.

```
DELETE /api/comments/{commentId}
```