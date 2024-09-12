<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClientPolicy
{
       // methode pour les Clients 
       public function access(User $user){
        // Vérifier si l'utilisateur a le rôle de superadmin (role_id 1)
        return $user->role_id === 1 || $user->role_id === 2
            ? Response::allow()
            : Response::deny('Vous n\'avez pas la permission d\'accéder à cette ressource.');
    }
}