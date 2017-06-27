<?php

namespace Brightfox\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Validator::extend('require_one_correct', function ($attribute, $value, $parameters, $validator) {
            
            $is_correct_exists = array_filter($value, function($answer){
                return array_key_exists('is_correct', $answer);
            });

            return !empty($is_correct_exists);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
