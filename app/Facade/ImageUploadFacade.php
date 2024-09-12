<?php
namespace App\Facade;

use Illuminate\Support\Facades\Facade;

class ImageUploadFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'imageUploadService';
    }
}
