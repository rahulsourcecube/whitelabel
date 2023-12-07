<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\CompanyLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\company\CampaignController;
use App\Http\Controllers\company\PackageController as CompanyPackageController;
use App\Http\Controllers\company\SettingController as CompanySettingController;
use App\Http\Controllers\company\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('auth.login');
// });

// Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Auth::routes();

Route::get('/', [AdminController::class, 'index'])->name('admin');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('/login', [AdminController::class, 'index'])->name('admin.login');
});

Route::prefix('company')->name('company.')->group(function () {
    Route::get('/', [CompanyLoginController::class, 'index'])->name('login');
    Route::get('/login', [CompanyLoginController::class, 'index'])->name('login');
    Route::post('/store', [CompanyLoginController::class, 'login'])->name('login');
});
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {

    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::prefix('package')->name('package.')->group(function () {
        Route::get('', [PackageController::class, 'index'])->name('list');
        Route::post('list', [PackageController::class, 'dtList'])->name('dtlist');

        Route::get('create', [PackageController::class, 'create'])->name('create');
        Route::post('/store', [PackageController::class, 'store'])->name('store');
        Route::get('view/{package}', [PackageController::class, 'view'])->name('view');
        Route::delete('delete/{package}', [PackageController::class, 'delete'])->name('delete');
        Route::get('edit/{package}', [PackageController::class, 'edit'])->name('edit');
        Route::put('update/{package}', [PackageController::class, 'update'])->name('update');
    });

    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('', [SettingController::class, 'index'])->name('index');
        Route::post('store', [SettingController::class, 'store'])->name('store');
    });
    Route::prefix('company')->name('company.')->group(function () {
        Route::get('', [AdminCompanyController::class, 'index'])->name('list');
        Route::post('list', [AdminCompanyController::class, 'dtList'])->name('dtlist');
        Route::get('view/{id}', [AdminCompanyController::class, 'view'])->name('view');
    });
    Route::get('change-password', [AdminController::class, 'change_password'])->name('change_password');
    Route::post('update-change-password', [AdminController::class, 'update_change_password'])->name('update_change_password');
});

Route::prefix('company')->name('company.')->middleware(['company'])->group(function () {

    Route::get('dashboard', [CompanyLoginController::class, 'dashboard'])->name('dashboard');
    Route::get('edit_profile', [CompanyLoginController::class, 'editProfile'])->name('edit_profile');
    Route::get('profile', [CompanyLoginController::class, 'profile'])->name('profile');
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('', [UserController::class, 'index'])->name('list');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::get('/edit', [UserController::class, 'edit'])->name('edit');
        Route::post('list', [UserController::class, 'dtList'])->name('dtlist');
        Route::get('view', [UserController::class, 'view'])->name('view');
    });

    Route::prefix('campaign')->name('campaign.')->group(function () {
        Route::get('', [CampaignController::class, 'index'])->name('list');
        Route::get('/create', [CampaignController::class, 'create'])->name('create');
        Route::get('/analytics', [CampaignController::class, 'analytics'])->name('analytics');

        Route::get('/view', [CampaignController::class, 'view'])->name('view');
    });
    Route::prefix('package')->name('package.')->group(function () {
        Route::get('', [CompanyPackageController::class, 'index'])->name('list');

    });
    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('', [CompanySettingController::class, 'index'])->name('index');
        Route::post('store', [CompanySettingController::class, 'store'])->name('store');

    });

});
