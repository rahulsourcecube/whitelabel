<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\MailConfiguration;
use App\Models\SettingModel;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Helpers\Helper;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     *
     * @return void
     */
    public function boot()
    { {
            try {
                Paginator::useBootstrap();

                Log::info('AppServiceProvider Check Mail Setting : ');

                $companyId = Helper::getCompanyId();
                if ($companyId) {
                    Log::info('AppServiceProvider Check Mail Setting Company: ' . $companyId);
                    $mailConfig = SettingModel::where('user_id', $companyId)->first();

                    Log::info('user admin: ' . $mailConfig->mail_username);
                    if (empty($mailConfig->mail_username) && empty($mailConfig->mail_password)) {
                        $user = User::where('user_type', '1')->first();
                        $mailConfig = SettingModel::where('user_id', $user->id)->first();
                    }
                } else {
                    $user = User::where('user_type', '1')->first();
                    $mailConfig = SettingModel::where('user_id', $user->id)->first();
                }


                if (!empty($mailConfig->mail_username) && !empty($mailConfig->mail_password)) {
                    Log::info('AppServiceProvider Mail : ' . json_encode($mailConfig));

                    Config::set('mail.driver', $mailConfig->mail_mailer);
                    Config::set('mail.host', $mailConfig->mail_host);
                    Config::set('mail.port', $mailConfig->mail_port);
                    Config::set('mail.username', $mailConfig->mail_username);
                    Config::set('mail.password', $mailConfig->mail_password);
                    Config::set('mail.from.name', $mailConfig->title);
                    // You can set other mail configuration values here as well
                }
                $stripe = Helper::stripeKey();

                Config::set('app.stripe_key', $stripe->stripe_key);
                Config::set('app.stripe_secret', $stripe->stripe_secret);
            } catch (\Throwable $th) {
                //throw $th;
                Log::error('Failed to send email to : ' . $th);
            }
        }
    }
}
