<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\Authority\ManageAuthorityController;
use App\Http\Controllers\Admin\Collector\ManageCollectorController;
use App\Http\Controllers\Admin\Ward\ManageWardController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\RolePermission\RolePermissionController;
use App\Http\Controllers\Admin\Setting\WebsiteSettingController;
use App\Http\Controllers\Language\SwitchLanguageController;
use App\Http\Controllers\Log\LogViewerController;
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

Route::group(['prefix' => 'admin'], function () {
    //login
    Route::get('/', function () {
        return view('backend.auth.login');
    });
    Route::get('/login', function () {
        return view('backend.auth.login');
    });
    Route::post('login-request', [AuthController::class, 'adminLogin'])->name('adminLogin');
    Route::get('logout', [AuthController::class, 'adminLogout'])->name('adminLogout');
    //login
    //log
    Route::get('logs', [LogViewerController::class, 'index']);
    //log

    Route::group(['middleware' => ['auth', 'userstatuscheck']], function () {

        //update profile
        Route::get('profile', [AuthController::class, 'viewAdminProfile'])->name('viewAdminProfile');
        Route::post('update-profile', [AuthController::class, 'updateAdminProfile'])->name('updateAdminProfile');
        //update profile

        //change password
        Route::get('change-password', [AuthController::class, 'changeAdminPassword'])->name('changeAdminPassword');
        Route::post('update-password', [AuthController::class, 'updateAdminPassword'])->name('updateAdminPassword');
        //change password

        //admin dashboard
        Route::get('dashboard', [DashboardController::class, 'viewAdminDashboard'])->name('viewAdminDashboard');
        //admin dashboard

        //manage admins
        Route::get('admins', [ManageAuthorityController::class, 'viewAdmins'])->name('viewAdmins');
        Route::post('/save-admin-user', [ManageAuthorityController::class, 'adminUserSave']);
        Route::get('/admin-user/inactive/{id}', [ManageAuthorityController::class, 'makeAdminUserInactive']);
        Route::get('/admin-user/active/{id}', [ManageAuthorityController::class, 'makeAdminUserActive']);
        //manage admins

        //manage collectors
        Route::get('collectors', [ManageCollectorController::class, 'viewCollectors'])->name('viewCollectors');
        Route::post('/save-collector-user', [ManageCollectorController::class, 'collectorUserSave']);
        Route::get('/collector-user/inactive/{id}', [ManageCollectorController::class, 'makeCollectorUserInactive']);
        Route::get('/collector-user/active/{id}', [ManageCollectorController::class, 'makeCollectorUserActive']);
        //manage collectors

        //manage wards
        Route::get('wards', [ManageWardController::class, 'viewWards'])->name('viewWards');
        Route::get('/manage-ward/delete/{id}', [ManageWardController::class, 'deleteWard']);
        Route::post('/save-ward', [ManageWardController::class, 'wardSave']);
        //manage wards


        /* role permissions */
        Route::get('/website-settings', [WebsiteSettingController::class, 'getWebsiteSettings'])->name('getWebsiteSettings');
        Route::post('/save-website-settings', [WebsiteSettingController::class, 'saveWebsiteSettings']);
        /* role permissions */

        /* role permissions */
        Route::get('/role-permission', [RolePermissionController::class, 'rolePermission'])->name('rolePermission');
        Route::post('/save-role-permission', [RolePermissionController::class, 'rolePermissionSave']);
        Route::get('/delete-role-permission/{id}', [RolePermissionController::class, 'rolePermissionDelete'])->name('deleteRole');
        /* role permissions */


    });
});

