<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
   
    /** 
     * Determine whether the user can update the stock of the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return bool
     */ 
    public function access(User $user)
    { 
        // Vérifier si l'utilisateur a le rôle de boutiquier (role_id 1 et 2)
        return $user->role_id === 1 || $user->role_id === 2
            ? Response::allow()
            : Response::deny('Vous n\'avez pas la permission d\'accéder à cette ressource.');
    
    } 
    
}