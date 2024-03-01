<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyCreateRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Services\Api\CompanyService;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * Создать новую компанию.
     *
     * @param CompanyRequest $request
     * @return JsonResponse
     */
    public function store(CompanyCreateRequest $request): JsonResponse
    {
        $company = $this->companyService->createCompany($request->validated());

        return response()->json($company, 201);
    }

    /**
     * Обновить информацию о компании.
     *
     * @param CompanyRequest $request
     * @param int $id Идентификатор компании.
     * @return JsonResponse
     */
    public function update(CompanyUpdateRequest $request, int $id): JsonResponse
    {
        $company = $this->companyService->updateCompany($id, $request->validated());

        return response()->json($company, 200);
    }

    /**
     * Удалить компанию.
     *
     * @param int $id Идентификатор компании.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->companyService->deleteCompany($id);

        return response()->json(['message' => 'Сompany deleted successfully'], 204);
    }
}
