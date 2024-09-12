<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    // methode pour permission de créer un user
    public function access(User $user){
        // Vérifier si l'utilisateur a le rôle de superadmin (role_id 2)
        return $user->role_id === 2
            ? Response::allow()
            : Response::deny('Vous n\'avez pas la permission d\'accéder à cette ressource.');
    }
}
