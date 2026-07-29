<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IntegrationController; // Tambahkan baris ini

// Rute bawaan Laravel
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rute baru untuk integrasi aplikasi Pintar
Route::get('/v1/integration/pintar/verified-students', [IntegrationController::class, 'exportToPintar'])
    ->middleware(['auth:sanctum', 'role:superadmin']);
