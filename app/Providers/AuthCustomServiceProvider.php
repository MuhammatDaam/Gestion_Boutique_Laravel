<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AuthenticationServiceInterface;
use App\Services\AuthentificationPassport;
use App\Services\AuthentificationSanctum;

class AuthCustomServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Lier l'interface à l'implémentation de Passport ou Sanctum
        $this->app->bind(AuthenticationServiceInterface::class, function ($app) {
            // Choisir l'implémentation ici (Passport ou Sanctum)
            return new AuthentificationPassport();
        });
    }

    public function boot()
    {
        // Rien de spécifique à démarrer pour l'instant
    }
}
