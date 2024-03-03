<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Проверка метода index: Получить список всех пользователей.
     */
    public function testGetAllUsers(): void
    {
        // Создаем тестовых пользователей
        User::factory()->count(5)->create();

        // Отправляем запрос на получение всех пользователей
        $response = $this->getJson('/api/users');

        // Проверяем, что запрос завершился успешно
        $response->assertStatus(200);

        // Проверяем, что данные пользователей вернулись в ожидаемом формате
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'first_name',
                    'last_name',
                    'phone_number',
                    'avatar',
                    'created_at',
                ]
            ]
        ]);

        // Проверяем, что данные не пустые
        $this->assertNotEmpty($response->json('data'));
    }

    /**
     * Проверка метода show: Получить информацию о пользователе.
     *
     * @return void
     */
    public function testGetUserInfo()
    {
        // Создаем пользователя для теста
        $user = User::factory()->create();

        // Проверяем успешное получение информации о пользователе
        $response = $this->get('/api/users/' . $user->id);
        $response->assertStatus(200);
        $response->assertJson(['data' => [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'phone_number' => $user->phone_number,
            'avatar' => $user->avatar,
            'created_at' => $user->created_at->toISOString(),
        ]]);
    }

    /**
     * Проверка обработку случая когда пользователя нет в базе данных (404).
     *
     * @return void
     */
    public function testUserNotFound()
    {
        // Создаем пользователя для теста
        $user = User::factory()->create();

        // Проверяем обработку случая, когда пользователя нет в базе данных (404)
        $response = $this->get('/api/users/999999');
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Пользователь не найден']);
    }

    /**
     * Тест создания нового пользователя.
     *
     * @return void
     */
    public function testCreateUser()
    {
        // Данные нового пользователя
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone_number' => '+70000000000',
        ];

        // Отправляем POST-запрос на эндпоинт с данными пользователя
        $response = $this->json('POST', '/api/users', $userData);

        // Проверяем, что статус ответа 201 (Создан)
        $response->assertStatus(201);

        // Проверяем структуру и данные ответа
        $response->assertJsonStructure([
            'data' => [
                'id',
                'first_name',
                'last_name',
                'phone_number',
                'created_at',
            ]
        ]);

        // Проверяем, что ответ содержит корректные данные пользователя
        $response->assertJson([
            'data' => [
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'phone_number' => $userData['phone_number'],
            ]
        ]);

        // Проверяем, что пользователь сохранен в базе данных
        $this->assertDatabaseHas('users', [
            'phone_number' => $userData['phone_number'],
        ]);
    }

    /**
     * Тест проверки валидации Имени и Фамилии пользователя.
     *
     * @return void
     */
    public function testUserNameValidation()
    {
        // Имя с менее чем 3 символами
        $userDataShortName = [
            'first_name' => 'Jo',
            'last_name' => 'Doe',
            'phone_number' => '+72345678900',
        ];

        $responseShortName = $this->json('POST', '/api/users', $userDataShortName);
        $responseShortName->assertStatus(422);
        $responseShortName->assertJson(['message' => 'Поле "Имя" должно содержать минимум 3 символов.']);

        // Имя с более чем 40 символами
        $userDataLongName = [
            'first_name' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'last_name' => 'Doe',
            'phone_number' => '+72345672900',
        ];

        $responseLongName = $this->json('POST', '/api/users', $userDataLongName);
        $responseLongName->assertStatus(422);
        $responseLongName->assertJson(['message' => 'Поле "Имя" должно содержать максимум 40 символов.']);

        // Фамилия с менее чем 3 символами
        $userDataShortName = [
            'first_name' => 'Joо',
            'last_name' => 'Do',
            'phone_number' => '+73345678900',
        ];

        $responseShortName = $this->json('POST', '/api/users', $userDataShortName);
        $responseShortName->assertStatus(422);
        $responseShortName->assertJson(['message' => 'Поле "Фамилия" должно содержать минимум 3 символов.']);

        // Фамилия с более чем 40 символами
        $userDataLongName = [
            'first_name' => 'Dilan',
            'last_name' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'phone_number' => '+72345672900',
        ];

        $responseLongName = $this->json('POST', '/api/users', $userDataLongName);
        $responseLongName->assertStatus(422);
        $responseLongName->assertJson(['message' => 'Поле "Фамилия" должно содержать максимум 40 символов.']);
    }

    /**
     * Тест проверки уникальности телефоного номера пользователя.
     *
     * @return void
     */
    public function testUniquenessPhoneUser()
    {
        // Данные нового пользователя
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone_number' => '+70000000000',
        ];

        // Отправляем POST-запрос на эндпоинт с данными пользователя два раза
        $response = $this->json('POST', '/api/users', $userData);
        $response = $this->json('POST', '/api/users', $userData);

        // Ожидаем ошибку уникальности номера телефона
        $response->assertStatus(422);
        $response->assertJson(['message' => 'Номер телефона уже используется.']);
    }

    /**
     * Тест проверки обновления пользователя.
     *
     * @return void
     */
    public function testUpdateUser()
    {
        // Создаем пользователя
        $user = User::factory()->create();

        // Подготавливаем данные для обновления
        $newUserData = [
            'first_name' => 'New First Name',
            'last_name' => 'New Last Name',
            'phone_number' => '+70009994422',
        ];

        // Отправляем запрос на обновление информации о пользователе
        $response = $this->json('PUT', '/api/users/' . $user->id, $newUserData);

        // Проверяем, что запрос успешно обработан (статус 200)
        $response->assertStatus(200);

        // Проверяем, что данные пользователя были обновлены
        $this->assertDatabaseHas('users', $newUserData);
    }

    /**
     * Тест проверки удаления пользователя.
     *
     * @return void
     */
    public function testDeleteUser()
    {
        // Создаем пользователя
        $user = User::factory()->create();

        // Отправляем запрос на удаление пользователя
        $response = $this->json('DELETE', '/api/users/' . $user->id);

        // Проверяем, что пользователь успешно удален (статус 204)
        $response->assertStatus(204);

        // Проверяем, что пользователь действительно удален из базы данных
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
