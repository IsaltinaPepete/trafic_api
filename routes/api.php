<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
//
//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');



Route::get('reports/statistics', [ReportController::class, 'statistics']);
Route::apiResource('reports', ReportController::class);

