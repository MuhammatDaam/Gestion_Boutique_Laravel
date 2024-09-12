<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;

class DetteServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'dette_service';
    }
}
