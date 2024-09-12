<?php

namespace App\Services;

use App\Repository\ArticleRepository;
use Illuminate\Support\Facades\DB;

class ArticleServiceImpl implements ArticleService
{
    protected $repo;

    public function __construct(ArticleRepository $repo)
    {
        $this->repo = $repo;
    }

    public function all()
    {
        return $this->repo->all();
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function update($id, array $data)
    {
        $article = $this->repo->find($id);
        if (!$article) {
            return null;
        }

        if (isset($data['stock'])) {
            $newStock = $article->stock + $data['stock'];
            if ($newStock < 0) {
                throw new \Exception("Le stock ne peut pas être négatif");
            }
            $data['stock'] = $newStock;
        }

        return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    public function findByLibelle($libelle)
    {
        return $this->repo->findByLibelle($libelle);
    }

    public function findByEtat($etat)
    {
        return $this->repo->findByEtat($etat);
    }

    public function filter(array $filters)
    {
        return $this->repo->findByFilters($filters);
    }

    public function softDelete($id)
    {
        return $this->repo->softDelete($id);
    }

    public function restore($id)
    {
        return $this->repo->restore($id);
    }

    public function forceDelete($id)
    {
        return $this->repo->forceDelete($id);
    }

    public function updateMultiple(array $articles)
    {
        $updatedArticles = [];
        $failedUpdates = [];

        DB::beginTransaction();

        try {
            foreach ($articles as $articleData) {
                try {
                    if (!isset($articleData['id'])) {
                        throw new \Exception("L'ID de l'article est manquant");
                    }
                    $updatedArticle = $this->update($articleData['id'], $articleData);
                    if ($updatedArticle) {
                        $updatedArticles[] = $updatedArticle;
                    } else {
                        throw new \Exception("Article avec l'ID {$articleData['id']} introuvable");
                    }
                } catch (\Exception $e) {
                    $failedUpdates[] = [
                        'article_data' => $articleData,
                        'error_message' => $e->getMessage()
                    ];
                }
            }

            DB::commit();

            if (!empty($failedUpdates)) {
                return ['failed' => $failedUpdates];
            }

            return ['success' => $updatedArticles];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}