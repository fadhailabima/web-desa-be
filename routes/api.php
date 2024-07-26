<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\FacilitiesController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideosController;
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
    Route::post('/facilities/{id}', [FacilitiesController::class, 'updateFacilities']);
    Route::get('/facilities/{category_id}', [FacilitiesController::class, 'getAllFacilities']);
    Route::get('/facilities/{id}', [FacilitiesController::class, 'getFacById']);
    Route::delete('/facilities/{id}', [FacilitiesController::class, 'deleteFacilities']);

    // Location Session
    Route::post('/catlocs', [CategoriesController::class, 'tambahKategoriLokasi']);
    Route::get('/catlocs', [CategoriesController::class, 'getKategoriLokasi']);
    Route::delete('/catlocs/{id}', [CategoriesController::class, 'hapusKategoriLokasi']);
    Route::post('/locations/{catlocs_id}', [LocationsController::class, 'tambahLokasi']);
    Route::delete('/locations/{id}', [LocationsController::class, 'hapusLokasi']);
    Route::get('/locations', [LocationsController::class, 'tampilkanSemuaLokasi']);
    Route::get('/locations/{catlocs_id}', [LocationsController::class, 'tampilkanLokasiBerdasarkanCatlocsId']);

    // News
    Route::post('/news', [NewsController::class, 'createNews']);
    Route::post('/news/{id}', [NewsController::class, 'updateNews']);
    Route::get('/news', [NewsController::class, 'getAllNews']);
    Route::get('/news/{id}', [NewsController::class, 'getNewsById']);
    Route::delete('/news/{id}', [NewsController::class, 'deleteNews']);

    // Videos
    Route::post('/videos', [VideosController::class, 'createVideos']);
    Route::post('/videos/{id}', [VideosController::class, 'updateVideos']);
    Route::get('/videos', [VideosController::class, 'getAllVideos']);
    Route::get('/videos/{id}', [VideosController::class, 'getVideo']);
    Route::delete('/videos/{id}', [VideosController::class, 'deleteVideos']);

});