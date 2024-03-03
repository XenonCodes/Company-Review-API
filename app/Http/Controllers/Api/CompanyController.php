<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyCreateRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\Api\CompanyService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        try {
            $companies = $this->companyService->getAllCompanies();
            return response()->json(['data' => CompanyResource::collection($companies)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не удалось получить список компаний'], 500);
        }
    }

    /**
     * Получить информацию о компании.
     *
     * @param int $companyId
     * @return JsonResponse
     */
    public function show(int $companyId): JsonResponse
    {
        try {
            $company = $this->companyService->getCompanyInfo($companyId);
            return response()->json(['data' => new CompanyResource($company)], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Компания не найдена'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не удалось получить информацию о компании'], 500);
        }
    }

    /**
     * Создать новую компанию.
     *
     * @param CompanyCreateRequest $request
     * @return JsonResponse
     */
    public function store(CompanyCreateRequest $request): JsonResponse
    {
        try {
            $company = $this->companyService->createCompany($request->validated(), $request->file('logo'));

            return response()->json(['data' => new CompanyResource($company)], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не удалось создать новую компанию'], 500);
        }
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
        try {
            $company = $this->companyService->updateCompany($companyId, $request->validated(), $request->file('logo'));

            return response()->json(['data' => new CompanyResource($company)], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Компания не найдена'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не удалось обновить информацию о компании'], 500);
        }
    }

    /**
     * Удалить компанию.
     *
     * @param int $companyId Идентификатор компании.
     * @return JsonResponse
     */
    public function destroy(int $companyId): JsonResponse
    {
        try {
            $this->companyService->deleteCompany($companyId);
            return response()->json(['message' => 'Компания успешно удалена'], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Компания не найдена'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не удалось удалить компанию'], 500);
        }
    }

    /**
     * Получить комментарии компании по ее ID.
     *
     * @param int $companyId
     * @return JsonResponse
     */
    public function getCompanyComments(int $companyId): JsonResponse
    {
        try {
            Company::findOrFail($companyId);// Проверяем есть ли такая компания
            $comments = $this->companyService->getCompanyComments($companyId);
            return response()->json(['data' => ['comments' => CommentResource::collection($comments)]], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Компания не найдена'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не удалось получить комментарии компании'], 500);
        }
    }

    /**
     * Вычислить общую оценку компании.
     *
     * @param int $companyId
     * @return JsonResponse
     */
    public function calculateCompanyRating(int $companyId): JsonResponse
    {
        try {
            $rating = $this->companyService->calculateCompanyRating($companyId);
            $company = $this->companyService->getCompanyInfo($companyId);
            return response()->json(['data' => ['company' => new CompanyResource($company), 'rating' => $rating]], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Компания не найдена'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не удалось вычислить общую оценку компании'], 500);
        }
    }

    /**
     * Получить топ-10 компаний по оценке.
     *
     * @return JsonResponse
     */
    public function getTopCompaniesByRating(): JsonResponse
    {
        try {
            $topCompanies = $this->companyService->getTopRatedCompanies();
            return response()->json(['data' => ['top_companies' => CompanyResource::collection($topCompanies)]], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не удалось получить топ-10 компаний'], 500);
        }
    }
}
