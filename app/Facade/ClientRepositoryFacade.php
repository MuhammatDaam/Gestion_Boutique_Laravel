<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;

class ClientRepositoryFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ClientRepository';
    }


}