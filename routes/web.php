<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\CompanyLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Company\CampaignController;
use App\Http\Controllers\Company\ChannelsController;
use App\Http\Controllers\Company\EmployeeController;
use App\Http\Controllers\Company\MailtemplateController;
use App\Http\Controllers\Company\Notification;
use App\Http\Controllers\Company\PackageController as CompanyPackageController;
use App\Http\Controllers\Company\RolesController;
use App\Http\Controllers\Company\SettingController as CompanySettingController;
use App\Http\Controllers\Company\SmstemplateController;
use App\Http\Controllers\Company\SurveyController;
use App\Http\Controllers\Company\UserController;
use App\Http\Controllers\Front\CampaignController as FrontCampaignController;
use App\Http\Controllers\Front\CommunityController;
use App\Http\Controllers\Front\HomeController as ForntHomeController;
use App\Http\Controllers\Front\SurveyController as ForntSurveyController;
use App\Http\Controllers\User\CampaignController as UserCampaignController;
use App\Http\Controllers\User\UsrController;


use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\log;


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

Route::get('/clears', function () {
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    Artisan::call('route:clear');
    return "Done!";
});


Route::get('send-email-queue', function () {
    // Your code inside the try block
    $userName  = 'testing data';
    $to = 'vuxipy@mailinator.com';
    $subject = 'Welcome Mail'; // Set your subject here
    $message = 'thank you'; // Set your message here

    if ((config('app.sendmail') == 'true' && config('app.mailSystem') == 'local') || (config('app.mailSystem') == 'server')) {
        SendEmailJob::dispatch($to, $subject, $message, $userName);
        return response()->json(['message' => 'Mail Send Successfully!!']);
    } else {

        return response()->json(['message' => 'Mail not Successfully!!']);
    }
});


// Route::get('admin/login', [AdminController::class, 'index'])->name('admin');
// Route::get('/', [AdminController::class, 'index'])->name('admin');
// Route::get('user', [LoginController::class, 'form'])->middleware('checkNotLoggedIn');

// Routes that require session timeout checks


Route::get('/login', [AdminController::class, 'index'])->name('login');

Route::group(['prefix' => 'admin'], function () {
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
});
Auth::routes();
Route::get('/errors', function () {
    return view('error');
})->name('error');
Route::get('/', [UsrController::class, 'index'])->middleware('checkNotLoggedIn');

Route::group(['middleware' => 'check.session'], function () {

    // Admin Middleware
    Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
        // Route::get('/login', [AdminController::class, 'index'])->name('login');
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('company-revenue', [AdminController::class, 'CompanyRevenue'])->name('CompanyRevenue');

        // admin side edit admin edit Profile Route
        Route::get('/change/password', [AdminController::class, 'ChengPassword'])->name('ChengPassword');
        Route::post('/update/admin/password', [AdminController::class, 'UpdatePassword'])->name('UpdatePassword');

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
            Route::get('edit/{id}', [AdminCompanyController::class, 'edit'])->name('edit');
            Route::post('update_passsword/{id}', [AdminCompanyController::class, 'updatepassword'])->name('update_password');
            Route::post('update_profile/{id}', [AdminCompanyController::class, 'updateprofile'])->name('update_profile');
            Route::post('store/{id}', [AdminCompanyController::class, 'store'])->name('store');
            Route::post('add/packages/{id}', [AdminCompanyController::class, 'AddPackages'])->name('AddPackages');
            Route::post('/buy', [AdminCompanyController::class, 'buy'])->name('buy');
        });
        Route::get('change-password', [AdminController::class, 'change_password'])->name('change_password');
        Route::post('update-change-password', [AdminController::class, 'update_change_password'])->name('update_change_password');

        Route::prefix('location')->name('location.')->group(function () {

            // country

            Route::prefix('country')->name('country.')->group(function () {

                Route::get('', [CountryController::class, 'index'])->name('list');
                Route::post('list', [CountryController::class, 'dtList'])->name('dtlist');
                Route::get('create', [CountryController::class, 'create'])->name('create');
                Route::post('store', [CountryController::class, 'store'])->name('store');
                Route::get('edit/{country}', [CountryController::class, 'edit'])->name('edit');
                Route::put('update/{country}', [CountryController::class, 'update'])->name('update');
                Route::delete('delete/{country}', [CountryController::class, 'delete'])->name('delete');
            });

            // state

            Route::prefix('state')->name('state.')->group(function () {

                Route::get('', [StateController::class, 'index'])->name('list');
                Route::post('list', [StateController::class, 'dtList'])->name('dtlist');
                Route::get('create', [StateController::class, 'create'])->name('create');
                Route::post('store', [StateController::class, 'store'])->name('store');
                Route::get('edit/{state}', [StateController::class, 'edit'])->name('edit');
                Route::put('update/{state}', [StateController::class, 'update'])->name('update');
                Route::delete('delete/{state}', [StateController::class, 'delete'])->name('delete');
            });


            // city

            Route::prefix('city')->name('city.')->group(function () {

                Route::get('', [CityController::class, 'index'])->name('list');
                Route::post('list', [CityController::class, 'dtList'])->name('dtlist');
                Route::get('create', [CityController::class, 'create'])->name('create');
                Route::post('store', [CityController::class, 'store'])->name('store');
                Route::get('edit/{city}', [CityController::class, 'edit'])->name('edit');
                Route::put('update/{city}', [CityController::class, 'update'])->name('update');
                Route::delete('delete/{city}', [CityController::class, 'delete'])->name('delete');
            });
        });
        //Mail

        Route::prefix('mail')->name('mail.')->group(function () {
            Route::get('template', [TemplateController::class, 'index'])->name('index');
            Route::get('template/create', [TemplateController::class, 'create'])->name('create');
            Route::get('template/list', [TemplateController::class, 'list'])->name('template.list');
            Route::post('template/store', [TemplateController::class, 'store'])->name('template.store');
            Route::get('edit/{id}', [TemplateController::class, 'edit'])->name('template.edit');
            Route::delete('delete/{id}', [CompanySettingController::class, 'progressionDelete'])->name('delete');
        });
        Route::prefix('sms')->name('sms.')->group(function () {
            Route::get('template', [TemplateController::class, 'smsIndex'])->name('index');
            Route::get('template/create', [TemplateController::class, 'smsCreate'])->name('create');
            Route::get('template/list', [TemplateController::class, 'smsList'])->name('template.list');
            Route::post('template/store', [TemplateController::class, 'smsStore'])->name('template.store');
            Route::get('edit/{id}', [TemplateController::class, 'smsEdit'])->name('template.edit');
        });
    });

    Route::get('migrate', function () {
        Artisan::call('migrate');
        return 'Yup, migrations run successfully!';
    });
    Route::get('seeder', function () {
        Artisan::call('db:seed');
        return 'Yup, seeder run successfully!';
    });
    //domain
    Route::middleware(['domain'])->group(function () {

        Route::get('/expire', function () {
            Artisan::call('expire:notification');
            return "Done!";
        });
        // Route::prefix('front')->name('front.')->group(function () {
        Route::prefix('survey')->name('front.survey.')->group(function () {
            Route::get('/{survey_form:slug}', [ForntSurveyController::class, 'survey'])->name('form');
            Route::post('/store', [ForntSurveyController::class, 'store'])->name('store');
        });
        Route::prefix('campaign')->name('front.campaign.')->group(function () {
            Route::get('/', [FrontCampaignController::class, 'list'])->name('list');
            Route::get('detail/{id}', [FrontCampaignController::class, 'detail'])->name('detail');
            Route::post('/getStates', [FrontCampaignController::class, 'getStates'])->name('getStates');
            Route::post('/getCity', [FrontCampaignController::class, 'getCity'])->name('getCity');
            Route::post('/search', [FrontCampaignController::class, 'search'])->name('search');
        });
        Route::get('/join-now/{join_link}', [FrontCampaignController::class, 'joinNow'])->name('front.campaign.Join');

        Route::get('/success-202', [ForntHomeController::class, 'success'])->name('front.success.page');
        // });

        Route::get('community/{type?}', [CommunityController::class, 'community'])->name('community');

        Route::prefix('community')->name('community.')->group(function () {
            // Route::get('{type?}', [CommunityController::class, 'type'])->name('type');
            Route::get('ss', [CommunityController::class, 'index'])->name('index');
            Route::post('store', [CommunityController::class, 'store'])->name('store');
            Route::get('discuss', [CommunityController::class, 'discuss'])->name('discuss');
            Route::get('show/{id}', [CommunityController::class, 'show'])->name('show');
            Route::post('reply/{id}', [CommunityController::class, 'reply'])->name('reply.store');
            Route::prefix('questions')->name('questions.')->group(function () {
                Route::get('create', [CommunityController::class, 'create'])->name('create');
                Route::delete('delete/{answer}', [CommunityController::class, 'delete'])->name('delete');
            });
        });


        Route::prefix('user')->name('user.')->group(function () {

            Route::prefix('community')->name('community.')->group(function () {
                Route::get('/{survey}', [CommunityController::class, 'index'])->name('index');
                Route::get('/
                /{id}', [CommunityController::class, 'channel'])->name('channel');
            });
            // Route::get('/', [UsrController::class, 'index'])->name('login')->middleware('checkNotLoggedIn');
            Route::get('/login', [UsrController::class, 'index'])->name('login')->middleware('checkNotLoggedIn');
            Route::get('/signup/{referral_code?}', [UsrController::class, 'signup'])->name('signup')->middleware('checkNotLoggedIn');
            Route::post('/store', [UsrController::class, 'login'])->name('userLogin');
            Route::post('/signup-store', [UsrController::class, 'store'])->name('store');
            Route::get('/forget', [UsrController::class, 'forget'])->name('forgetpassword');
            Route::post('/forget-password', [UsrController::class, 'submitForgetPassword'])->name('forget-password');
            Route::get('/confirm/password/{token}', [UsrController::class, 'confirmPassword'])->name('confirmPassword');
            Route::post('/reset-password', [UsrController::class, 'submitResetPassword'])->name('reset-password');
            Route::get('/phone/code', [UsrController::class, 'phoneCode'])->name('phone.code');
            Route::post('/get_states', [UsrController::class, 'get_states'])->name('get_states');
            Route::post('/get_city', [UsrController::class, 'get_city'])->name('get_city');

            Route::get('/sendsms', [UsrController::class, 'sendSMS'])->name('sendSMS');


            Route::middleware(['user'])->group(function () {
                Route::get('/dashboard', [UsrController::class, 'dashboard'])->name('dashboard');
                Route::get('/campaign', [UsrController::class, 'campaign'])->name('campaign');
                Route::get('/campaigns/view', [UsrController::class, 'campaignview'])->name('campaign.view');
                Route::get('/edit_profile', [UsrController::class, 'editProfile'])->name('edit_profile');
                Route::post('/edit_profile_store', [UsrController::class, 'editProfileStore'])->name('editProfileStore');
                Route::get('profile', [UsrController::class, 'profile'])->name('profile');
                Route::get('my/reward', [UsrController::class, 'myreward'])->name('my.reward');
                Route::get('progress/reward', [UsrController::class, 'progressreward'])->name('progress.reward');
                Route::post('/user/progress/search', [UsrController::class, 'searchProgress'])->name('progress.search');
                Route::post('store/chat/{id}', [CampaignController::class, 'storeChat'])->name('storeChat');

                Route::post('/reopen/{reopen}', [UsrController::class, 'reopen'])->name('progress.reopen');

                Route::post('/claim-reward/{id}', [UsrController::class, 'claimReward'])->name('progress.claimReward');

                Route::get('/analytics', [UsrController::class, 'analytics'])->name('analytics');
                Route::get('/notification', [UsrController::class, 'notification'])->name('notification');
                Route::get('/changePassword', [UsrController::class, 'editProfile'])->name('changePassword');
                Route::post('/changePassword-store', [UsrController::class, 'changePasswordStore'])->name('changePasswordStore');
                Route::post('/social-account', [UsrController::class, 'socialAccount'])->name('socialAccount');
                Route::post('/bank-details', [UsrController::class, 'bankDetail'])->name('bankDetail');
                Route::get('/logout', [UsrController::class, 'Logout'])->name('logout');
                //Rating
                Route::post('/reivews/store', [UsrController::class, 'addTaskRating'])->name('store.reivews');
                //end Rating

                //Feedback
                Route::post('/feedback/store', [UsrController::class, 'addTaskFeedback'])->name('store.feedback.task');
                //end Feedback


            });
        });

        Route::get('/campaign/{referral_link}', [UserCampaignController::class, 'referral'])->name('campaign.referral');

        Route::post('request/referral-user-detail', [UserCampaignController::class, 'GetReferralUserDetail'])->name('GetReferralUserDetail');

        Route::prefix('user/campaign/')->name('user.campaign.')->group(function () {
            Route::middleware(['user'])->group(function () {
                Route::get('/', [UserCampaignController::class, 'campaign'])->name('list');
                Route::get('/list', [UserCampaignController::class, 'dtlist'])->name('dtlist');
                Route::get('/view/{id}', [UserCampaignController::class, 'campaignview'])->name('view');
                Route::post('/usercampaign/{id}', [UserCampaignController::class, 'getusercampaign'])->name('getusercampaign');
                Route::post('/requestSend/{id}', [UserCampaignController::class, 'requestSend'])->name('requestSend');
                Route::post('/reopenSend/{id}', [UserCampaignController::class, 'reopenSend'])->name('reopenSend');
                Route::get('/userlist', [UserCampaignController::class, 'userlist'])->name('userlist');
            });
        });

        Route::prefix('company')->name('company.')->group(function () {
            Route::get('/', [CompanyLoginController::class, 'index'])->name('login')->middleware('checkNotLoggedIn');
            Route::get('/companyLoginWithToken/{token?}', [CompanyLoginController::class, 'loginWithToken'])->name('loginWithToken')->middleware('checkNotLoggedIn');
            Route::get('/login', [CompanyLoginController::class, 'index'])->name('signin')->middleware('checkNotLoggedIn');
            Route::post('/store', [CompanyLoginController::class, 'login'])->name('login');
            Route::get('/signup', [CompanyLoginController::class, 'signup'])->name('signup')->middleware('checkNotLoggedIn');
            Route::post('/signup/store', [CompanyLoginController::class, 'signupStore'])->name('signup.store');

            Route::get('/forget', [CompanyLoginController::class, 'forget'])->name('forgetpassword')->middleware('checkNotLoggedIn');
            Route::post('/forget-password', [CompanyLoginController::class, 'submitForgetPassword'])->name('forget-password');
            Route::get('/confirm/password/{token}', [CompanyLoginController::class, 'confirmPassword'])->name('confirmPassword')->middleware('checkNotLoggedIn');
            Route::post('/reset-password', [CompanyLoginController::class, 'submitResetPassword'])->name('reset-password');

            Route::get('/chenge/password/{id}', [CompanyLoginController::class, 'confirmPassword'])->name('confirmPassword')->middleware('checkNotLoggedIn');
            Route::put('/changePassword/{id}', [CompanyLoginController::class, 'changePassword'])->name('change.password');
            Route::get('/get_states', [UserController::class, 'get_states'])->name('get_states');
            Route::get('/get_city', [UserController::class, 'get_city'])->name('get_city');
            Route::get('/phone/code', [UsrController::class, 'phoneCode'])->name('phone.code');
        });

        Route::get('verifyemail/{id}', [CompanyLoginController::class, 'verifyemail'])->name('user.verifyemail');
        Route::get('verifycontact/{id}', [CompanyLoginController::class, 'verifycontact'])->name('user.verifycontact');

        // {{-- Company Middleware --}}


        Route::prefix('company')->name('company.')->middleware(['company'])->group(function () {
            Route::post('logout', [CompanyLoginController::class, 'logout'])->name('logout');
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
                Route::post('/get_states', [UserController::class, 'get_states'])->name('get_states');
                Route::post('/get_city', [UserController::class, 'get_city'])->name('get_city');
                Route::get('/export', [UserController::class, 'export'])->name('export');
            });
            Route::prefix('package')->name('package.')->group(function () {
                Route::get('/{type}', [CompanyPackageController::class, 'index'])->name('list');
                Route::post('/buy', [CompanyPackageController::class, 'buy'])->name('buy');
                Route::post('stripe', [CompanyPackageController::class, 'stripePost'])->name('stripe.post');
            });
            Route::middleware('buy.package')->group(function () {
                Route::get('dashboard/{data?}', [CompanyLoginController::class, 'dashboard'])->name('dashboard');
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
                    Route::get('tdlist/{type}', [CampaignController::class, 'tdlist'])->name('tdlist');;
                    Route::post('statuswiselist/user', [CampaignController::class, 'statuswiselist'])->name('statuswiselist');

                    Route::get('request/user/{id}', [CampaignController::class, 'request'])->name('request');

                    Route::get('request/user/details/{t_id}', [CampaignController::class, 'userDetails'])->name('userDetails');
                    Route::post('store/chat/{id}', [CampaignController::class, 'storeChat'])->name('storeChat');
                    Route::post('company-custom', [CampaignController::class, 'CompanyCustom'])->name('custom');
                    Route::post('request/social-analytics', [CampaignController::class, 'getSocialAnalytics'])->name('getSocialAnalytics');



                    Route::get('/create/{type}', [CampaignController::class, 'create'])->name('create');
                    Route::post('/store', [CampaignController::class, 'store'])->name('store');
                    Route::get('/view/{type}/{id}', [CampaignController::class, 'view'])->name('view');
                    Route::get('/view/{type}/{id}', [CampaignController::class, 'view'])->name('view');


                    Route::get('/edit/{type}/{id}', [CampaignController::class, 'edit'])->name('edit');
                    Route::post('/update/{Campaign}', [CampaignController::class, 'update'])->name('update');
                    Route::delete('/delete/{id}', [CampaignController::class, 'delete'])->name('delete');
                    Route::get('/analytics', [CampaignController::class, 'analytics'])->name('analytics');
                    Route::post('/fetch-data-filter', [CampaignController::class, 'fetch_data'])->name('fetch_data');
                    Route::post('/action', [CampaignController::class, 'action'])->name('action');
                    Route::get('/export/{type}', [CampaignController::class, 'export'])->name('export');
                });
                Route::prefix('billing')->name('billing.')->group(function () {
                    // billing list Route
                    Route::get('', [CompanyPackageController::class, 'billing'])->name('billing');
                });

                Route::prefix('setting')->name('setting.')->group(function () {
                    Route::get('', [CompanySettingController::class, 'index'])->name('index');
                    Route::post('store', [CompanySettingController::class, 'store'])->name('store');
                });
                //Survey Start
                Route::prefix('survey')->name('survey.')->group(function () {
                    Route::get('form', [SurveyController::class, 'formIndex'])->name('form.index');
                    Route::get('form/list', [SurveyController::class, 'formList'])->name('form.list');
                    Route::get('form/create', [SurveyController::class, 'formCreate'])->name('form.create');
                    Route::post('form/store', [SurveyController::class, 'formStore'])->name('form.store');
                    Route::get('form/edit/{survey}', [SurveyController::class, 'formEdit'])->name('form.edit');
                    Route::get('form/edit_form/{survey}', [SurveyController::class, 'formEditFrom'])->name('form.edit_form');
                    Route::post('/slug/check', [SurveyController::class, 'checkSlug'])->name('checkSlug');


                    Route::post('form/update/{survey}', [SurveyController::class, 'formUpdate'])->name('form.update');
                    Route::post('form/update_form/', [SurveyController::class, 'formUpdateForm'])->name('form.updateform');
                    Route::get('form/view/{survey}', [SurveyController::class, 'formView'])->name('form.view');
                    Route::delete('form/delete/{survey}', [SurveyController::class, 'formDelete'])->name('form.delete');
                    Route::get('form/addfield', [SurveyController::class, 'getAdditionalFields'])->name('form.addfield');
                });
                //Survey end
                // Channels Controller Start
                Route::prefix('category')->name('channel.')->group(function () {
                    Route::get('', [ChannelsController::class, 'index'])->name('index');
                    Route::get('create', [ChannelsController::class, 'create'])->name('create');
                    Route::post('store', [ChannelsController::class, 'store'])->name('store');
                    Route::get('list', [ChannelsController::class, 'list'])->name('list');
                    Route::get('edit/{id}', [ChannelsController::class, 'edit'])->name('edit');
                    Route::delete('delete/{id}', [ChannelsController::class, 'delete'])->name('delete');
                });

                //End Category

                //Task Progression
                Route::prefix('progression')->name('progression.')->group(function () {
                    Route::get('progression', [CompanySettingController::class, 'progressionIndex'])->name('index');
                    Route::get('progression/list', [CompanySettingController::class, 'progressionList'])->name('list');
                    Route::get('create', [CompanySettingController::class, 'progressionCreate'])->name('create');
                    Route::post('progression', [CompanySettingController::class, 'progressionStore'])->name('store');
                    Route::get('edit/{id}', [CompanySettingController::class, 'progressionEdit'])->name('edit');
                    Route::delete('delete/{id}', [CompanySettingController::class, 'progressionDelete'])->name('delete');
                });
                Route::prefix('mail')->name('mail.')->group(function () {
                    Route::get('template', [MailtemplateController::class, 'index'])->name('index');
                    Route::get('template/create', [MailtemplateController::class, 'create'])->name('create');
                    Route::get('template/list', [MailtemplateController::class, 'list'])->name('template.list');
                    Route::post('template/store', [MailtemplateController::class, 'store'])->name('template.store');
                    Route::get('edit/{id}', [MailtemplateController::class, 'edit'])->name('template.edit');
                    Route::delete('delete/{id}', [CompanySettingController::class, 'progressionDelete'])->name('delete');
                });
                Route::prefix('sms')->name('sms.')->group(function () {
                    Route::get('template', [SmstemplateController::class, 'index'])->name('index');
                    Route::get('template/create', [SmstemplateController::class, 'create'])->name('create');
                    Route::get('template/list', [SmstemplateController::class, 'list'])->name('template.list');
                    Route::post('template/store', [SmstemplateController::class, 'store'])->name('template.store');
                    Route::get('edit/{id}', [SmstemplateController::class, 'edit'])->name('template.edit');
                });
                // Route::prefix('mail')->name('mail.')->group(function () {
                //     Route::get('template', [MailtemplateController::class, 'index'])->name('index');
                //     Route::get('template/create', [MailtemplateController::class, 'create'])->name('create');
                //     Route::get('template/list', [MailtemplateController::class, 'list'])->name('template.list');
                //     Route::post('template/store', [MailtemplateController::class, 'store'])->name('template.store');
                //     Route::get('edit/{id}', [MailtemplateController::class, 'edit'])->name('template.edit');
                //     Route::delete('delete/{id}', [CompanySettingController::class, 'progressionDelete'])->name('delete');
                // });
                // roles Route
                Route::prefix('role')->name('role.')->group(function () {
                    // roles list Route
                    Route::get('', [RolesController::class, 'rolelist'])->name('rolelist');
                    // roles create , store Route
                    Route::get('/create', [RolesController::class, 'rolecreate'])->name('rolecreate');
                    Route::post('/store', [RolesController::class, 'store'])->name('store');
                    // roles edit , update Route
                    Route::get('/edit/{id}', [RolesController::class, 'edit'])->name('edit');
                    Route::post('/update/{id}', [RolesController::class, 'update'])->name('update');
                    // roles view Route
                    Route::get('/view/{id}', [RolesController::class, 'roleview'])->name('roleview');
                    // roles destroy Route
                    Route::get('/destroy/{id}', [RolesController::class, 'destroy'])->name('destroy');
                });
                Route::prefix('employee')->name('employee.')->group(function () {
                    Route::get('', [EmployeeController::class, 'index'])->name('list');
                    Route::get('/create', [EmployeeController::class, 'create'])->name('create');
                    Route::post('/store', [EmployeeController::class, 'store'])->name('store');
                    Route::get('view', [RolesController::class, 'roleview'])->name('roleview');
                    Route::get('/list', [EmployeeController::class, 'elist'])->name('elist');
                    Route::get('edit/{id}', [EmployeeController::class, 'edit'])->name('edit');
                    Route::delete('delete/{id}', [EmployeeController::class, 'delete'])->name('delete');
                    Route::post('/update/{id}', [EmployeeController::class, 'update'])->name('update');
                    Route::get('export', [EmployeeController::class, 'export'])->name('export');
                });
                Route::prefix('notification')->name('notification.')->group(function () {
                    Route::get('', [Notification::class, 'index'])->name('list');
                    Route::post('/list', [Notification::class, 'dtlist'])->name('dtlist');
                });
            });
        });
    });
});