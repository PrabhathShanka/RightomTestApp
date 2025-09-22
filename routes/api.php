<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\LoanPaymentController;
use App\Http\Controllers\Api\LoanStatementController;

Route::post('/loan/apply', [LoanController::class, 'apply']);
Route::get('/loan/{id}', [LoanController::class, 'show']);

Route::post('/loan/{id}/repay', [LoanPaymentController::class, 'repay']);
Route::get('/loan/{id}/schedule', [LoanController::class, 'emiSchedule']);



Route::post('/loan/{id}/generate-statement', [LoanStatementController::class, 'generate']);



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



