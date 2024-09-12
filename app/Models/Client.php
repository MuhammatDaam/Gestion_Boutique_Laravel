<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model; 
use App\Scopes\Filter;
use Illuminate\Database\Eloquent\SoftDeletes;
// use App\Observers\ClientObserver;
// use Illuminate\Database\Eloquent\Attributes\ObservedBy;
// #[ObservedBy([ClientObserver::class])]
    
class Client extends Model 
{ 
    use HasFactory, SoftDeletes; // Utilisation de SoftDeletes pour gérer la suppression en douceur
 
    protected $fillable = [ 
        'surname', 
        'adresse', 
        'telephone', 
        'user_id',
        'photo'  // Pour stocker l'URL ou le chemin de la photo
    ];

    protected $hidden = [ 
        'created_at', 
        'updated_at',
        'deleted_at' // Masquer les timestamps et champs de suppression douce
    ]; 

    // Appliquer un scope global si nécessaire, vous pouvez le décommenter si le besoin existe
    // protected static function booted()
    // {
    //     static::addGlobalScope(new Filter);
    // }

    /**
     * Relation avec le modèle User (un client appartient à un utilisateur).
     */
    public function user()
    {
        return $this->belongsTo(User::class); 
    } 

    // Enregistrement de l'observer sans passer par un service provider
    // protected static function booted()
    // {
    //     static::observe(ClientObserver::class);
    // }
    
} 

