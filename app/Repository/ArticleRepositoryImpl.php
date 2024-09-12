<?php

namespace App\Repository;

use App\Models\Article;


class ArticleRepositoryImpl implements ArticleRepository
{
    public function all()
    {
        return Article::all();
    }

    public function create(array $data)
    {
        return Article::create($data);
    }

    public function find($id)
    {
        return Article::find($id);
    }

    public function update($id, array $data)
    {
        $article = $this->find($id);
        if ($article) {
            $article->update($data);
        }
        return $article;
    }

    public function delete($id)
    {
        $article = $this->find($id);
        if ($article) {
            $article->delete();
        }
        return $article;
    }

    public function findByLibelle($libelle)
    {
        return Article::where('libelle', 'like', "%{$libelle}%")->get();
    }

    public function findByEtat($etat)
    {
        return Article::where('etat', $etat)->get();
    }

    public function findByFilters(array $filters)
    {
        $query = Article::query();
    
        if (!empty($filters['libelle'])) {
            $query->where('libelle', 'like', '%' . $filters['libelle'] . '%');
        }
    
        if (!empty($filters['disponible'])) {
            if ($filters['disponible'] === 'non') {
                $query->where('stock', '=', 0);
            } elseif ($filters['disponible'] === 'oui') {
                $query->where('stock', '>', 0);
            }
        }
    
        return $query->get(); // Retourne directement les résultats ici
    }
    
    public function softDelete($id)    // public function getByStock(?string $disponible)

    {
        $article = $this->find($id);
        if ($article) {
            $article->delete();
            return true;
        }
        return false;
    }

    public function restore($id)
    {
        $article = Article::withTrashed()->find($id);
        if ($article && $article->trashed()) {
            $article->restore();
            return $article;
        }
        return null;
    }

    public function forceDelete($id)
    {
        $article = Article::withTrashed()->find($id);
        if ($article) {
            $article->forceDelete();
            return true;
        }
        return false;
    }
}