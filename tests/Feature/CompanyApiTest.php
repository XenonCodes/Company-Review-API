<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Company;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CompanyApiTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Тест проверки получение комментариев компании.
     *
     * @return void
     */
    public function testGetCompanyComments()
    {
        // Создаем компанию
        $company = Company::factory()->create();

        // Создаем несколько комментариев для этой компании
        $comments = Comment::factory()->count(3)->create([
            'company_id' => $company->id,
        ]);

        // Отправляем запрос на получение комментариев компании
        $response = $this->json('GET', "/api/companies/{$company->id}/comments");

        // Проверяем, что запрос успешно обработан (статус 200)
        $response->assertStatus(200);

        // Проверяем, что возвращаемые данные содержат ожидаемые комментарии
        $response->assertJsonCount(3, 'data.comments');
    }

    /**
     * Тест проверки когда компании нет в базе данных (404).
     *
     * @return void
     */
    public function testGetCompanyCommentsWhenCompanyNotFound()
    {
        // Проверяем, что получаем статус 404 (Компания не найдена)
        $response = $this->json('GET', '/api/companies/999/comments');
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Компания не найдена']);
    }

    /**
     * Тест для расчета рейтинга компании.
     *
     * @return void
     */
    public function testCalculateCompanyRating()
    {
        // Создадим тестовую компанию
        $company = Company::factory()->create();

        // Создаем несколько комментариев для этой компании
        Comment::factory()->create([
            'company_id' => $company->id,
            'rating' => 3,
        ]);
        Comment::factory()->create([
            'company_id' => $company->id,
            'rating' => 7,
        ]);
        // Средний рейтинг получится 5
        $expectedRating = 5;

        // Вызываем метод API для вычисления рейтинга компании
        $response = $this->get('/api/companies/' . $company->id . '/rating');

        // Проверяем, что статус ответа 200 (Успешно)
        $response->assertStatus(200);

        // Проверяем, что JSON-ответ содержит правильные данные
        $response->assertJson([
            'data' => [
                'company' => [
                    'id' => $company->id,
                    'name' => $company->name,
                    'description' => $company->description,
                    'logo' => $company->logo,
                    'created_at' => $company->created_at->toISOString(),
                ],
                'rating' => $expectedRating,
            ],
        ]);
    }

    /**
     * Тест получения топ-10 компаний по рейтингу.
     *
     * @return void
     */
    public function testGetTopCompaniesByRating()
    {
        // Создадим тестовые компании с комментариями для оценки
        $companies = Company::factory()->count(15)->create();
        foreach ($companies as $company) {
            Comment::factory()->count(rand(1,5))->create(['company_id' => $company->id]);
        }

        // Вызываем метод API для получения топ-10 компаний по оценке
        $response = $this->get('/api/companies/top');

        // Проверяем, что статус ответа 200 (Успешно)
        $response->assertStatus(200);

        // Проверяем, что JSON-ответ содержит данные о топ-10 компаниях
        $response->assertJsonStructure([
            'data' => [
                'top_companies' => [
                    '*' => [
                        'id',
                        'name',
                        'average_rating',
                    ],
                ],
            ],
        ]);

        // Проверяем, что в ответе содержится 10 компаний
        $response->assertJsonCount(10, 'data.top_companies');
    }
}
