<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;

class ClientServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ClientService';
    }
}
