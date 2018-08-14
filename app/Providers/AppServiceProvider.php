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

        Validator::extend('require_one_correct_for_multiple_choice', function ($attribute, $value, $parameters, $validator) {
            
            if($parameters[0] === '0' || $parameters[0] === '7'){
                $answers_with_is_correct = array_filter($value, function($answer){
                    return array_key_exists('is_correct', $answer);
                });

                $return = !empty($answers_with_is_correct);
            }else{
                $return = true;
            }
            
            return $return;
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
