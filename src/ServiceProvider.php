<?php
namespace Chendujin\IdCardNumber;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot(): void
    {
        $validator = $this->app['validator'];
        $validator->extend('id_card_number', function ($attribute, $value, $paramters, $validator) {
            return  (new IdCardNumber($value))->isValid();
        });
    }
}
