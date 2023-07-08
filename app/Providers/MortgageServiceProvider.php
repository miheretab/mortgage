<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Mortgage\MortgageCalculator;

class MortgageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('mortgagecalculator',function(){
            return new MortgageCalculator();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
