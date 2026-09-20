<?php


use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware('api')
    ->group(base_path('routes/admin.php'));

Route::prefix('user')
    ->middleware('api')
    ->group(base_path('routes/user.php'));

Route::prefix('pharmacy')
    ->middleware('api')
    ->group(base_path('routes/pharmacy.php'));

