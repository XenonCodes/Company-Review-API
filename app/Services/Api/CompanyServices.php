<?php

namespace App\Services\Api;

use App\Models\Company;

class CompanyService
{
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
