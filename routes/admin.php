<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Donation\DonationController;
use App\Http\Controllers\Medicine\MedicineController;
use App\Http\Controllers\Pharmacy\PharmacyController;
use App\Http\Controllers\PharmacyRequest\PharmacyRequestController;
use App\Http\Controllers\Recipe\RecipeController;
use App\Http\Controllers\Request\RequestController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AdminController::class, 'Login']);

Route::middleware(['auth:Admin'])->group(function () {

    Route::controller(AdminController::class)->group(function () {
        Route::post('/logout', 'Logout');
        Route::post('/add_admin', 'AddAdmin');
        Route::get('/get_admins', 'GetAdmins');
        Route::delete('/delete_admin/{admin_id}', 'DeleteAdmin');
        Route::post('/update_admin/{admin_id}', 'UpdateAdmin');
    });

    Route::controller(MedicineController::class)->group(function () {
        Route::post('/import_medicine_excel', 'ImportMedicineExcel');
        Route::post('/add_medicine', 'AddMedicine');
        Route::get('/get_medicines', 'GetMedicines');
        Route::post('/update_medicine/{medicine_id}', 'UpdateMedicine');
        Route::delete('/delete_medicine/{medicine_id}', 'DeleteMedicine');
        Route::get('/get_Medicines_selector', 'getAllSelector');
    });

    Route::controller(RecipeController::class)->group(function () {
        Route::post('/add_recipe', 'AddRecipe');
        Route::get('/get_recipe_by_request/{request_id}', 'GetRecipeByRequest');
    });

    Route::controller(RequestController::class)->group(function () {
        Route::post('/reject_request', 'RejectRequest');
        Route::get('/get_requests', 'GetRequests');
    });

    Route::controller(PharmacyController::class)->group(function () {
        Route::post('/add_pharmacy', 'AddPharmacy');
        Route::get('/get_pharmacies', 'GetPharmacies');
        Route::delete('/delete_pharmacy/{pharmacy_id}', 'DeletePharmacy');
        Route::post('/update_pharmacy/{pharmacy_id}', 'UpdatePharmacy');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/get_users', 'GetUsers');
        Route::delete('/delete_user/{user_id}', 'DeleteUser');
        Route::post('/get_one_user/{user_id}', 'GetOneUser');
        Route::post('/change_user_status/{user_id}', 'ChangeUserStatus');
    });

    Route::controller(PharmacyRequestController::class)->group(function () {
        Route::get('/get_pharmacy_requests', 'GetPharmacyRequestsAdmin');
    });

    Route::controller(DonationController::class)->group(function () {
        Route::get('/get_user_donations/{user_id}', 'GetUserDonations');
    });

});
