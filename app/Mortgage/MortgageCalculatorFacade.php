<?php

namespace App\Mortgage;

use Illuminate\Support\Facades\Facade;

class MortgageCalculatorFacade extends Facade{
    protected static function getFacadeAccessor() { return 'mortgagecalculator'; }
}