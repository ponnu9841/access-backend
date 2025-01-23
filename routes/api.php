<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PartnerController;
use Illuminate\Support\Facades\Route;

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

//admin routes
Route::middleware('admin')->group(function () {
    Route::post('/partner', [PartnerController::class, 'createPartner']);
    Route::delete('/partner', [PartnerController::class, 'deletePartner']);
});
Route::get('/partner', [PartnerController::class, 'getPartner']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::delete('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'user']);



// Route::post('/logout', [AuthController::class, 'logout']);
// Route::get('/getUser/{id}', [UserController::class, 'update']);

// Route::apiResource('/users', UserController::class);