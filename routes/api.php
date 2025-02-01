<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\TeamsController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\BannerController;
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

    //service
    Route::post('/service', [ServiceController::class, 'createService']);
    Route::delete('/service', [ServiceController::class, 'deleteService']);

    //testimonial
    Route::post('/testimonial', [TestimonialController::class, 'createTestimonial']);
    Route::delete('/testimonial', [TestimonialController::class, 'deleteTestimonial']);

    //gallery
    Route::post('/gallery', [GalleryController::class, 'createGallery']);
    Route::delete('/gallery', [GalleryController::class, 'deleteGallery']);

    //teams
    Route::post('/teams', [TeamsController::class, 'createTeam']);
    Route::delete('/teams', [TeamsController::class, 'deleteTeam']);

    //banner
    Route::post('/banner', [BannerController::class, 'createBanner']);
    Route::delete('/banner', [BannerController::class, 'deleteBanner']);
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::delete('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'user']);

Route::get('/partner', [PartnerController::class, 'getPartner']);
Route::get('/service', [ServiceController::class, 'getService']);
Route::get('/testimonial', [TestimonialController::class, 'getTestimonial']);
Route::get('/gallery', [GalleryController::class, 'getGallery']);
Route::get('/teams', [TeamsController::class, 'getTeams']);
Route::get('/banner', [BannerController::class, 'getBanner']);



// Route::post('/logout', [AuthController::class, 'logout']);
// Route::get('/getUser/{id}', [UserController::class, 'update']);

// Route::apiResource('/users', UserController::class);