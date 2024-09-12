<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthentificationSanctum implements AuthenticationServiceInterface
{
    public function authenticate(array $credentials)
    {
        // Utiliser Sanctum pour authentifier l'utilisateur
        if (Auth::guard('sanctum')->attempt($credentials)) {
            return Auth::guard('sanctum')->user();
        }

        return null;
    }

    public function logout()
    {
        // Déconnecter l'utilisateur pour Sanctum
        Auth::guard('sanctum')->logout();
    }
}
