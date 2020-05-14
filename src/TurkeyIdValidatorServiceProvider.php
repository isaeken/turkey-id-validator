<?php

namespace IsaEken;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use IsaEken\TurkeyIdValidator;

class TurkeyIdValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootValidator();
    }

    protected function bootValidator()
    {
        Validator::extend('turkeyid', function ($attribute, $value, $parameters, $validator) {
            return TurkeyIdValidator::VerifyId($value);
        });

        Validator::replacer('turkeyid', function ($message, $attribute, $rule, $parameters) {
            if ($message === 'validation.turkeyid') return 'Incorrect Republic of Turkey Identity ID';
            else return $message;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}