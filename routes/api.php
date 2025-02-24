<?php

use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\TeamsController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\HeadingsController;
use App\Http\Controllers\Api\PageBannerController;
use App\Http\Controllers\Api\SeoController;
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
    Route::put('/partner', [PartnerController::class, 'updatePartner']);
    Route::delete('/partner', [PartnerController::class, 'deletePartner']);

    //service
    Route::post('/service', [ServiceController::class, 'createService']);
    Route::put('/service', [ServiceController::class, 'updateService']);
    Route::delete('/service', [ServiceController::class, 'deleteService']);

    //testimonial
    Route::post('/testimonial', [TestimonialController::class, 'createTestimonial']);
    Route::put('/testimonial', [TestimonialController::class, 'updateTestimonial']);
    Route::delete('/testimonial', [TestimonialController::class, 'deleteTestimonial']);

    //gallery
    Route::post('/gallery', [GalleryController::class, 'createGallery']);
    Route::put('/gallery', [GalleryController::class, 'updateGallery']);
    Route::delete('/gallery', [GalleryController::class, 'deleteGallery']);

    //teams
    Route::post('/teams', [TeamsController::class, 'createTeam']);
    Route::put('/teams', [TeamsController::class, 'updateTeam']);
    Route::delete('/teams', [TeamsController::class, 'deleteTeam']);

    //banner
    Route::post('/banner', [BannerController::class, 'createBanner']);
    Route::put('/banner', [BannerController::class, 'updateBanner']);
    Route::delete('/banner', [BannerController::class, 'deleteBanner']);

    //contact
    Route::post('/contact', [ContactController::class, 'createContact']);
    Route::put('/contact', [ContactController::class, 'updateContact']);

    //about
    Route::post('/about', [AboutController::class, 'createAbout']);
    Route::put('/about', [AboutController::class, 'updateAbout']);

    //heading
    Route::post('/heading', [HeadingsController::class, 'createHeading']);
    Route::put('/heading', [HeadingsController::class, 'updateHeading']);

    //pageBanner
    Route::post('/pagesBanner', [PageBannerController::class, 'createBanner']);
    Route::put('/pagesBanner', [PageBannerController::class, 'updateBanner']);

    //meta tags
    Route::post('/seoTags', [SeoController::class, 'createSeoTags']);
    Route::put('/seoTags', [SeoController::class, 'updateSeoTags']);
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::delete('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'user']);

Route::get('/partner', [PartnerController::class, 'getPartner']);
Route::get('/service', [ServiceController::class, 'getService']);
Route::get('/service/{id}', [ServiceController::class, 'getServiceDetail']);
Route::get('/testimonial', [TestimonialController::class, 'getTestimonial']);
Route::get('/gallery', [GalleryController::class, 'getGallery']);
Route::get('/teams', [TeamsController::class, 'getTeams']);
Route::get('/banner', [BannerController::class, 'getBanner']);
Route::get('/contact', [ContactController::class, 'getContact']);
Route::get('/about', [AboutController::class, 'getAbout']);
Route::get('/heading', [HeadingsController::class, 'getHeadings']);
Route::get('/pagesBanner', [PageBannerController::class, 'getBanner']);
Route::get('/seoTags', [SeoController::class, 'getTags']);
