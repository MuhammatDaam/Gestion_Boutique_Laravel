<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;

class DetteRepositoryFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'dette_repository';
    }
}