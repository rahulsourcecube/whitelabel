<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\CompanyLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Company\CampaignController;
use App\Http\Controllers\Company\EmployeeController;
use App\Http\Controllers\Company\PackageController as CompanyPackageController;
use App\Http\Controllers\Company\RolesController;
use App\Http\Controllers\Company\SettingController as CompanySettingController;
use App\Http\Controllers\Company\UserController;
use App\Http\Controllers\User\UsrController;
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
// Route::get('user', [LoginController::class, 'form']);
Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('/login', [AdminController::class, 'index'])->name('admin.login');
});
Route::prefix('user')->name('user.')->group(function () {
    Route::get('/', [UsrController::class, 'index'])->name('login');
    Route::get('/login', [UsrController::class, 'index'])->name('login');
    Route::get('/dashboard', [UsrController::class, 'dashboard'])->name('dashboard');
    Route::get('/campaign', [UsrController::class, 'campaign'])->name('campaign');
    Route::get('/campaigns/view', [UsrController::class, 'campaignview'])->name('campaign.view');
    Route::get('edit_profile', [UsrController::class, 'editProfile'])->name('edit_profile');
    Route::get('profile', [UsrController::class, 'profile'])->name('profile');
    Route::get('my/reward', [UsrController::class, 'myreward'])->name('my.reward');
    Route::get('progress/reward', [UsrController::class, 'progressreward'])->name('progress.reward');
    Route::get('/analytics', [UsrController::class, 'analytics'])->name('analytics');
    Route::get('/notification', [UsrController::class, 'notification'])->name('notification');
    Route::get('/changePassword', [UsrController::class, 'editProfile'])->name('changePassword');
    Route::get('/signup', [UsrController::class, 'signup'])->name('signup');
    Route::get('/forget', [UsrController::class, 'forget'])->name('forgetpassword');
    Route::get('/confirm/password', [UsrController::class, 'confirmPassword'])->name('confirmPassword');
});

Route::prefix('company')->name('company.')->group(function () {
    Route::get('/', [CompanyLoginController::class, 'index'])->name('login');
    Route::get('/login', [CompanyLoginController::class, 'index'])->name('signin');
    Route::post('/store', [CompanyLoginController::class, 'login'])->name('login');
    Route::get('/signup', [CompanyLoginController::class, 'signup'])->name('signup');
    Route::post('/signup/store', [CompanyLoginController::class, 'signupStore'])->name('signup.store');
    Route::get('/forget', [CompanyLoginController::class, 'forget'])->name('forgetpassword');
    Route::post('/forget/store', [CompanyLoginController::class, 'forgetPassSendmail'])->name('forgetPassSendmail');
    Route::get('/chenge/password/{id}', [CompanyLoginController::class, 'confirmPassword'])->name('confirmPassword');
    Route::put('/changePassword/{id}', [CompanyLoginController::class, 'changePassword'])->name('change.password');
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
    Route::post('update_profile/{id}', [CompanyLoginController::class, 'updateprofile'])->name('update_profile');
    Route::post('update_passsword', [CompanyLoginController::class, 'updatepassword'])->name('update_password');
    Route::get('profile', [CompanyLoginController::class, 'profile'])->name('profile');

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('', [UserController::class, 'index'])->name('list');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/email/check', [UserController::class, 'checkEmail'])->name('checkEmail');
        Route::post('/number/check', [UserController::class, 'checkContactNumber'])->name('checkContactNumber');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::get('view/{id}', [UserController::class, 'view'])->name('view');
        Route::delete('delete/{id}', [UserController::class, 'delete'])->name('delete');
        Route::get('/list', [UserController::class, 'dtList'])->name('dtlist');
    });
    Route::prefix('campaign')->name('campaign.')->group(function () {
        Route::get('list/{type}', [CampaignController::class, 'index'])->name('list');
        Route::get('tdlist/{type}', [CampaignController::class, 'tdlist'])->name('tdlist');
        Route::get('/create/{type}', [CampaignController::class, 'create'])->name('create');
        Route::post('/store', [CampaignController::class, 'store'])->name('store');
        Route::get('/view/{type}/{id}', [CampaignController::class, 'view'])->name('view');
        Route::get('/edit/{type}/{id}', [CampaignController::class, 'edit'])->name('edit');
        Route::post('/update/{Campaign}', [CampaignController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CampaignController::class, 'delete'])->name('delete');
        Route::get('/analytics', [CampaignController::class, 'analytics'])->name('analytics');
    });
    Route::prefix('package')->name('package.')->group(function () {
        Route::get('', [CompanyPackageController::class, 'index'])->name('list');
    });
    Route::prefix('billing')->name('billing.')->group(function () {
        Route::get('', [CompanyPackageController::class, 'billing'])->name('billing');
    });
    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('', [CompanySettingController::class, 'index'])->name('index');
        Route::post('store', [CompanySettingController::class, 'store'])->name('store');
    });
    Route::prefix('role')->name('role.')->group(function () {
        Route::get('', [RolesController::class, 'rolelist'])->name('rolelist');
        Route::get('/create', [RolesController::class, 'rolecreate'])->name('rolecreate');
        Route::post('/store', [RolesController::class, 'store'])->name('store');
        Route::get('/destroy/{id}', [RolesController::class, 'destroy'])->name('destroy');
        Route::get('/edit/{id}', [RolesController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [RolesController::class, 'update'])->name('update');
        Route::get('/view/{id}', [RolesController::class, 'roleview'])->name('roleview');
    });
    Route::prefix('employee')->name('employee.')->group(function () {

        Route::get('', [EmployeeController::class, 'index'])->name('list');
        Route::get('/create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/store', [EmployeeController::class, 'store'])->name('store');
        Route::get('view', [RolesController::class, 'roleview'])->name('roleview');
        Route::get('/lists', [EmployeeController::class, 'elist'])->name('elist');
        Route::get('edit/{id}', [EmployeeController::class, 'edit'])->name('edit');
        Route::delete('delete/{id}', [EmployeeController::class, 'delete'])->name('delete');
        Route::post('/update/{id}', [EmployeeController::class, 'update'])->name('update');
    });
});
