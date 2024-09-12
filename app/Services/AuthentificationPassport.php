<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthentificationPassport implements AuthenticationServiceInterface
{
    public function authenticate(array $credentials)
    {
        // Valider les informations d'identification fournies
        $validator = Validator::make($credentials, [
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

        // Identifier si l'utilisateur se connecte avec un nom d'utilisateur ou un email
        $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'login' : 'username';

        // Tentative de connexion avec les informations d'identification fournies
        if (Auth::attempt([$loginType => $credentials['login'], 'password' => $credentials['password']])) {
            // Si l'authentification est réussie, récupérer l'utilisateur connecté
            $user = User::find(Auth::user()->id);
            // Créer un token si vous utilisez Laravel Passport
            $token = $user->createToken('LaravelPassportAuth')->accessToken;

            return [
                'success' => true,
                'user' => $user,
                'token' => $token,
                'message' => 'Connexion reussie',
            ];
        } else {
            // Si l'authentification échoue
            return [
                'success' => false,
                'message' => 'Informations d\'identification invalides'
            ];
        }
    }

    public function logout()
    {
        // Déconnecter l'utilisateur pour Passport
        Auth::guard('passport')->logout();
    }
}
