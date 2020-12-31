<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategorySpecController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Auth\AuthController;


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
Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found'
    ], 404);
});

Route::post('login', [AuthController::class, 'login']);
Route::delete('logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/authenticated', function () {
    return true;
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('collections',CollectionController::class);
    Route::resource('assets', AssetController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('requests', RequestController::class);
    Route::resource('users', UserController::class);
    Route::resource('assignments', AssignmentController::class);
    Route::get('users/{id}/history', [UserController::class, 'history']);
    Route::resource('locations', LocationController::class);
    Route::get('assets/{id}/history', [AssetController::class, 'history']);
    Route::patch('requests/{id}', [RequestController::class,'updateStatus']);
    Route::resource('cate-spec', CategorySpecController::class);
    Route::get('assignments/{id}/history', [AssignmentController::class, 'history']);
});

