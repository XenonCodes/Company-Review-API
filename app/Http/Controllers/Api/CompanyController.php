<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyCreateRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Http\Resources\CommentResource;
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
        return response()->json(['data' => CompanyResource::collection($companys)], 200);
    }

    /**
     * Получить информацию о компании.
     *
     * @param int $companyId
     * @return JsonResponse
     */
    public function show(int $companyId): JsonResponse
    {
        $company = $this->companyService->getCompanyInfo($companyId);
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
     * @param int $companyId Идентификатор компании.
     * @return JsonResponse
     */
    public function update(CompanyUpdateRequest $request, int $companyId): JsonResponse
    {
        $company = $this->companyService->updateCompany($companyId, $request->validated(), $request->file('logo'));

        return response()->json(['data' => new CompanyResource($company)], 200);
    }

    /**
     * Удалить компанию.
     *
     * @param int $companyId Идентификатор компании.
     * @return JsonResponse
     */
    public function destroy(int $companyId): JsonResponse
    {
        $this->companyService->deleteCompany($companyId);

        return response()->json(['message' => 'Сompany deleted successfully'], 204);
    }

    /**
     * Получить комментарии компании по ее ID.
     *
     * @param int $companyId
     * @return JsonResponse
     */
    public function getCompanyComments(int $companyId): JsonResponse
    {
        $comments = $this->companyService->getCompanyComments($companyId);

        return response()->json(['data' => ['comments' => CommentResource::collection($comments)]], 200);
    }

    /**
     * Вычислить общую оценку компании.
     *
     * @param int $companyId
     * @return JsonResponse
     */
    public function calculateCompanyRating(int $companyId): JsonResponse
    {
        $rating = $this->companyService->calculateCompanyRating($companyId);
        $company = $this->companyService->getCompanyInfo($companyId);

        return response()->json(['data' => ['company' => new CompanyResource($company), 'rating' => $rating]], 200);
    }

    /**
     * Получить топ-10 компаний по оценке.
     *
     * @return JsonResponse
     */
    public function getTopCompaniesByRating(): JsonResponse
    {
        $topCompanies = $this->companyService->getTopRatedCompanies();

        return response()->json(['data' => ['top_companies' => CompanyResource::collection($topCompanies)]], 200);
    }
}
