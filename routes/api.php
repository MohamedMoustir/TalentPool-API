<?php

use App\Http\Controllers\AdminControlller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidatureController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnnonceController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('refresh', [AuthController::class,'refresh']);
    Route::post('password/forgot', [AuthController::class, 'forgotPassword']);
    Route::post('password/reset', [AuthController::class, 'resetPassword']);
    Route::middleware('auth:api')->group(function () {
     Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:api')->group(function () {
    Route::get('/annonces', [AnnonceController::class, 'index']);
    Route::get('/annonces/{id}', [AnnonceController::class, 'show']);
});

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/candidatures', [CandidatureController::class, 'index']);
    Route::delete('/utilisateurs/profile/{id}', [CandidatureController::class, 'deleteProfile']);
    Route::get('/stats/globales', [AdminControlller::class, 'statsGlobales']);
});
Route::middleware(['auth:api', 'role:recruteur'])->group(function () {
    Route::post('/annonces', [AnnonceController::class, 'store']);
    Route::put('/annonces/{id}', [AnnonceController::class, 'update']);
    Route::delete('/annonces/{id}', [AnnonceController::class, 'destroy']);
    Route::put('/candidatures/{id}/statut', [CandidatureController::class, 'updateStatus']);
    Route::get('/candidatures', [CandidatureController::class, 'index']);
    Route::get('/candidatures/{id}', [CandidatureController::class, 'show']);
    Route::get('/stats/recruteur', [AdminControlller::class, 'statsRecruteur']);

});


Route::middleware(['auth:api', 'role:candidat'])->group(function () {
    Route::post('/candidatures', [CandidatureController::class, 'store']);
    Route::delete('/candidatures/{id}', [CandidatureController::class, 'destroy']);
    Route::get('/candidatures/miennes', [CandidatureController::class, 'myCandidatures']);
    Route::get('/candidatures/miennes/statut', [CandidatureController::class, 'getCandidaturesByStatus']);
    Route::get('/notifications/candidature/{id}', [CandidatureController::class, 'getNotifications']);
    Route::get('/utilisateurs/profile', [CandidatureController::class, 'getProfile']);
    Route::put('/utilisateurs/profile', [CandidatureController::class, 'updateProfile']);
});