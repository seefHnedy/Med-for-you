<?php


use App\Http\Controllers\Pharmacy\PharmacyController;
use App\Http\Controllers\PharmacyRequest\PharmacyRequestController;
use App\Http\Controllers\Recipe\RecipeController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [PharmacyController::class, 'Login']);

Route::middleware(['auth:Pharmacy'])->group(function () {

    Route::controller(PharmacyController::class)->group(function () {
        Route::post('/logout', 'Logout');
    });

    Route::controller(RecipeController::class)->group(function () {
        Route::get('/get_recipe', 'GetRecipe');
    });

    Route::controller(PharmacyRequestController::class)->group(function () {
        Route::post('/add_pharmacy_request', 'AddPharmacyRequest');
        Route::post('/verify_pharmacy_request', 'VerifyPharmacyRequest');
        Route::get('/get_pharmacy_requests', 'GetPharmacyRequests');
    });

});

