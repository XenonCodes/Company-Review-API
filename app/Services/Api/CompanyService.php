<?php

namespace App\Services\Api;

use App\Models\Comment;
use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class CompanyService
{
    protected $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * Получить список всех компаний.
     *
     * @return Collection
     */
    public function getAllCompanies(): Collection
    {
        return Company::all();
    }

    /**
     * Получить информацию о конкретной компании.
     *
     * @param int $сompanyId Идентификатор компании.
     * @return Company
     */
    public function getCompanyInfo(int $сompanyId): Company
    {
        $сompany = Company::findOrFail($сompanyId);

        return $сompany;
    }

    /**
     * Создать новую компанию.
     *
     * @param array $companyData Данные компании для создания.
     * @param ?UploadedFile $logo Файл логотипа компании.
     * @return Company
     */
    public function createCompany(array $companyData, ?UploadedFile $logo): Company
    {
        if ($logo) {
            // Загрузка и сохранение логотипа компании
            $logoPath = $this->imageUploadService->uploadImage($logo, 'logo');
            $companyData['logo'] = $logoPath; // Сохраняем путь в модели компании
        }

        return Company::create($companyData);
    }

    /**
     * Обновить информацию о компании.
     *
     * @param int $companyId Идентификатор компании.
     * @param array $companyData Данные компании для обновления.
     * @param ?UploadedFile $newLogo Новый файл логотипа компании.
     * @return Company
     */
    public function updateCompany(int $companyId, array $companyData, ?UploadedFile $newLogo): Company
    {
        $company = Company::findOrFail($companyId);

        if ($newLogo) {
            // Загрузка и сохранение новой логотипа
            $logoPath = $this->imageUploadService->uploadImage($newLogo, 'logo');
            $companyData['logo'] = $logoPath; // Сохраняем путь в модели компании
            // Удаление старого логотипа, если он существует
            if ($company->logo) {
                $this->imageUploadService->deleteImage($company->logo); // Путь к старому логотипу
            }
            // Сохранение пути к новому логотипу в модели компании
            $companyData['logo'] = $logoPath;
        }

        $company->update($companyData);
        
        return $company;
    }

    /**
     * Удалить компанию.
     *
     * @param int $companyId Идентификатор компании.
     * @return void
     */
    public function deleteCompany(int $companyId): void
    {
        $company = Company::findOrFail($companyId);

        // Удаление старого логотипа, если он существует
        if ($company->logo) {
            $this->imageUploadService->deleteImage($company->logo); // Путь к старому логотипу
        }

        $company->delete();
    }

    /**
     * Получить комментарии компании по ее ID.
     *
     * @param int $companyId Идентификатор компании.
     * @return Collection<Comment>
     */
    public function getCompanyComments(int $companyId): Collection
    {
        return Comment::where('company_id', $companyId)->get();
    }

    /**
     * Вычислить общую оценку компании.
     *
     * @param int $companyId Идентификатор компании.
     * @return float|null
     */
    public function calculateCompanyRating(int $companyId): ?float
    {
        return Comment::where('company_id', $companyId)->avg('rating');
    }

    /**
     * Получить топ-10 компаний по оценке.
     *
     * @return Collection<Company>
     */
    public function getTopRatedCompanies(): Collection
    {
        // Выбираем идентификатор компании, название компании и средний рейтинг из связанных комментариев.
        return Company::select('companies.id', 'companies.name', DB::raw('avg(comments.rating) as average_rating'))
            ->join('comments', 'companies.id', '=', 'comments.company_id') // Соединяем таблицы компаний и комментариев по идентификатору компании.
            ->groupBy('companies.id') // Группируем результаты по идентификатору компании.
            ->orderByDesc('average_rating') // Сортируем результаты по убыванию среднего рейтинга.
            ->limit(10) // Показать 10
            ->get();
    }
}
