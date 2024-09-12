<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Spécifier la table associée (facultatif si le nom de la table suit la convention)
    protected $table = 'roles';

    // Les champs pouvant être remplis en masse
    protected $fillable = ['libelle'];

    /**
     * Un rôle peut avoir plusieurs utilisateurs.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
