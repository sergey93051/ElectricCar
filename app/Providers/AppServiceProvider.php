<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

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
     * @return void
     */
    public function boot()
    {
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            $spaUrl = "http://localhost:8000?email_verify_url=".$url;

            return (new MailMessage)
                ->subject('Verify Email Address') 
                ->line('Click the button below to verify your email address.')
                ->action('Verify Email Address', $spaUrl);
        });
    }
}
