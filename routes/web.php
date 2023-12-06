<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\company\CampaignController;
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
        Route::get('', [CompanyController::class, 'index'])->name('list');
        Route::post('list', [CompanyController::class, 'dtList'])->name('dtlist');
        Route::get('view/{id}', [CompanyController::class, 'view'])->name('view');
    });
});

Route::prefix('company')->name('company.')->group(function () {
    Route::get('/login', [CompanyController::class, 'index'])->name('login');
    Route::post('/store', [CompanyController::class, 'login'])->name('login');

    Route::get('dashboard', [CompanyController::class, 'dashboard'])->name('dashboard');
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('', [UserController::class, 'index'])->name('list');
       
    });
    Route::prefix('campaign')->name('campaign.')->group(function () {
        Route::get('', [CampaignController::class, 'index'])->name('list');
       
    });
});