<?php


use App\Http\Controllers\City\CityController;
use App\Http\Controllers\Donation\DonationController;
use App\Http\Controllers\Medicine\MedicineController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Pharmacy\PharmacyController;
use App\Http\Controllers\Request\RequestController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/get_cities', [CityController::class, 'getCities']);

Route::controller(UserController::class)->group(function () {
    Route::post('/register_user', 'RegisterUser');
    Route::post('/login', 'Login');
    Route::post('/verify_email', 'VerifyEmail');
    Route::post('/resend_otp', 'ResendOtp');
    Route::post('/reset_password', 'ResetPassword');
});

Route::controller(PharmacyController::class)->group(function () {
    Route::get('/get_pharmacies', 'GetPharmacies')->name('GetPharmaciesUser');
});

Route::get('/get_ordered_medicines', [MedicineController::class, 'getOrderedMedicines']);

Route::middleware(['auth:User'])->group(function () {

    Route::controller(UserController::class)->group(function () {
        Route::post('/logout', 'Logout');
        Route::get('/get_profile_info', 'GetProfileInfo');
    });

    Route::controller(RequestController::class)->group(function () {
        Route::post('/add_request', 'AddRequest');
        Route::get('/get_requests', 'GetRequests');
        Route::get('/get_request_recipe', 'GetRequestRecipe');
    });

    Route::controller(DonationController::class)->group(function () {
        Route::post('/add_donation', 'AddDonation');
        Route::post('/verify_donation', 'VerifyDonation');
        Route::get('/get_my_donations', 'GetMyDonations');
    });

    Route::controller(NotificationController::class)->group(function () {
        Route::get('/get_my_notifications', 'GetMyNotification');
    });

});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return Artisan::output();
});
