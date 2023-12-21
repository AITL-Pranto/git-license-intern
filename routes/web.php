<?php

use App\Http\Controllers\Collector\Auth\AuthController;
use App\Http\Controllers\Collector\Dashboard\DashboardController;
use App\Http\Controllers\Language\SwitchLanguageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Data Entry Operator/Collector Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('frontend.auth.login');
});
Route::get('/login', function () {
    return view('frontend.auth.login');
});
Route::post('/collector/login-request', [AuthController::class, 'collectorLogin'])->name('collectorLogin');
Route::get('/collector/logout', [AuthController::class, 'collectorLogout'])->name('collectorLogout');
Route::get('/switch-language', [SwitchLanguageController::class, 'switchLanguage']);

Route::group(['prefix' => 'collector'], function () {
    //collector dashboard
    Route::get('dashboard', [DashboardController::class, 'viewCollectorDashboard'])->name('viewCollectorDashboard');
    //collector dashboard
});

