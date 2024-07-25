<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\FacilitiesController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User Session
    Route::post('/user', [UserController::class, 'tambahUser']);
    Route::delete('/user/{id}', [UserController::class, 'hapusUser']);

    // Facilities Session
    Route::post('/category', [CategoriesController::class, 'tambahKategoriFasilitas']);
    Route::get('/category', [CategoriesController::class, 'getKategoriFasilitas']);
    Route::delete('/category/{id}', [CategoriesController::class, 'hapusKategoriFasilitas']);
    Route::post('/facilities/{category_id}', [FacilitiesController::class, 'createFacilities']);
    Route::get('/facilities/{category_id}', [FacilitiesController::class, 'getAllFacilities']);

    // Location Session
    Route::post('/catlocs', [CategoriesController::class, 'tambahKategoriLokasi']);
    Route::get('/catlocs', [CategoriesController::class, 'getKategoriLokasi']);
    Route::delete('/catlocs/{id}', [CategoriesController::class, 'hapusKategoriLokasi']);

});