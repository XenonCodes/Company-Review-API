<?php

namespace App\Services\Api;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

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
    public function getAllCompanys(): Collection
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
}
