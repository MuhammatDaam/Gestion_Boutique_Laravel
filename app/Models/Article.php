<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['libelle', 'description', 'price', 'stock']; 

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    // Relation avec Dette
    public function dettes()
    {
        return $this->belongsToMany(Dette::class, 'dette_article')
                    ->withPivot('qte_vente', 'prix_vente')
                    ->withTimestamps();
    }
    /**
     * Filtre les articles selon des critères dynamiques.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, array $filters)
    {
        // Filtre par libelle
        if (!empty($filters['libelle'])) {
            $query->where('libelle', 'like', '%' . $filters['libelle'] . '%');
        }

        // Filtre par état (par exemple, active/inactive)
        if (!empty($filters['etat'])) {
            $query->where('etat', $filters['etat']);
        }

        // Filtre par prix minimum
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        // Filtre par prix maximum
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Filtre par stock minimum
        if (!empty($filters['min_stock'])) {
            $query->where('stock', '>=', $filters['min_stock']);
        }

        // Filtre par stock maximum
        if (!empty($filters['max_stock'])) {
            $query->where('stock', '<=', $filters['max_stock']);
        }

        // Filtre par disponibilité
        if (!empty($filters['disponible'])) {
            $query->where('stock', '>', 0);
        }

        // Filtre par disponibilité
        if (!empty($filters['non_disponible'])) {
            $query->where('stock', '=', 0);
        }

        return $query;
    }

}
