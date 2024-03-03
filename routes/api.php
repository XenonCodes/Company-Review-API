<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Группа маршрутов для доп. управления компаниями
Route::prefix('companies')->group(function () {
    Route::get('/{companyId}/comments', [CompanyController::class, 'getCompanyComments']);
    Route::get('/{companyId}/rating', [CompanyController::class, 'calculateCompanyRating']);
    Route::get('/top', [CompanyController::class, 'getTopCompaniesByRating']);
});

// CRUD Маршруты для управления пользователями
Route::apiResource('users', UserController::class);
// CRUD Маршруты для управления пользователями
Route::apiResource('companies', CompanyController::class);
// CRUD Маршруты для управления комментариями
Route::apiResource('comments', CommentController::class);
