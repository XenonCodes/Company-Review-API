<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyCreateRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Http\Resources\CompanyResource;
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
     * Получить список всех компаний.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $companys = $this->companyService->getAllCompanys();
        return response()->json(['data' =>CompanyResource::collection($companys)], 200);
    }

    /**
     * Получить информацию о компании.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $company = $this->companyService->getCompanyInfo($id);
        return response()->json(['data' => new CompanyResource($company)], 200);
    }

    /**
     * Создать новую компанию.
     *
     * @param CompanyCreateRequest $request
     * @return JsonResponse
     */
    public function store(CompanyCreateRequest $request): JsonResponse
    {
        $company = $this->companyService->createCompany($request->validated(), $request->file('logo'));

        return response()->json(['data' => new CompanyResource($company)], 201);
    }

    /**
     * Обновить информацию о компании.
     *
     * @param CompanyUpdateRequest $request
     * @param int $id Идентификатор компании.
     * @return JsonResponse
     */
    public function update(CompanyUpdateRequest $request, int $id): JsonResponse
    {
        $company = $this->companyService->updateCompany($id, $request->validated(), $request->file('logo'));

        return response()->json(['data' => new CompanyResource($company)], 200);
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
