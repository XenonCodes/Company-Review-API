<?php

namespace App\Services\Api;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;

class CompanyService
{
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
     * @return Company
     */
    public function createCompany(array $companyData): Company
    {
        return Company::create($companyData);
    }

    /**
     * Обновить информацию о компании.
     *
     * @param int $companyId Идентификатор компании.
     * @param array $companyData Данные компании для обновления.
     * @return Company
     */
    public function updateCompany(int $companyId, array $companyData): Company
    {
        $company = Company::findOrFail($companyId);
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
        $company->delete();
    }
}
