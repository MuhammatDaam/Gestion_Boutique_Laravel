<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Traits\RestResponseTrait;
use App\Enums\StateEnum;
use App\Services\ArticleService;

class ArticleController extends Controller
{
    use RestResponseTrait;

    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['disponible', 'libelle', 'stock']);
        $articles = $this->articleService->filter($filters);

        if ($articles->isEmpty()) {
            $message = !empty($filters['libelle']) ? 
                'Aucun article correspondant au libellé fourni.' : 
                'Aucun article trouvé.';
            return $this->sendResponse([], StateEnum::ECHEC, $message);
        }

        return $this->sendResponse($articles, StateEnum::SUCCESS, 'Articles récupérés avec succès');
    }

    public function searchByLibelle(Request $request)
    {
        $libelle = $request->input('libelle');
        $articles = $this->articleService->findByLibelle($libelle);

        if ($articles->isEmpty()) {
            return $this->sendResponse([], StateEnum::ECHEC, 'Aucun article correspondant au libellé fourni.');
        }

        return $this->sendResponse($articles, StateEnum::SUCCESS, 'Articles récupérés avec succès');
    }

    public function store(StoreArticleRequest $request)
    {
        $validatedData = $request->validated();
        if (empty($validatedData)) {
            return $this->sendResponse(null, StateEnum::ECHEC, 'Au moins un article est requis', 422);
        }
        $article = $this->articleService->create($validatedData);
        return $this->sendResponse($article, StateEnum::SUCCESS, 'Article créé avec succès', 201);
    }

    public function show($id)
    {
        $article = $this->articleService->find($id);
        if (!$article) {
            return $this->sendResponse(null, StateEnum::ECHEC, 'Article non trouvé', 404);
        }
        return $this->sendResponse($article, StateEnum::SUCCESS, 'Article récupéré avec succès');
    }

    public function update(UpdateArticleRequest $request, $id)
    {
        $validatedData = $request->validated();
        if (empty($validatedData)) {
            return $this->sendResponse(null, StateEnum::ECHEC, 'Au moins un champ d\'article est requis pour la mise à jour', 422);
        }
        $article = $this->articleService->update($id, $validatedData);
        return $this->sendResponse($article, StateEnum::SUCCESS, 'Article mis à jour avec succès');
    }

    public function destroy($id)
    {
        $this->articleService->delete($id);
        return $this->sendResponse(null, StateEnum::SUCCESS, 'Article supprimé avec succès');
    }

    public function softDelete($id)
    {
        $result = $this->articleService->softDelete($id);
        if (!$result) {
            return $this->sendResponse(null, StateEnum::ECHEC, 'Article introuvable', 404);
        }
        return $this->sendResponse(null, StateEnum::SUCCESS, 'Article supprimé avec succès');
    }

    public function restore($id)
    {
        $article = $this->articleService->restore($id);
        if (!$article) {
            return $this->sendResponse(null, StateEnum::ECHEC, 'Article introuvable ou déjà restauré', 400);
        }
        return $this->sendResponse($article, StateEnum::SUCCESS, 'Article restauré avec succès');
    }

    public function forceDelete($id)
    {
        $result = $this->articleService->forceDelete($id);
        if (!$result) {
            return $this->sendResponse(null, StateEnum::ECHEC, 'Article introuvable', 404);
        }
        return $this->sendResponse(null, StateEnum::SUCCESS, 'Article supprimé définitivement');
    }

    public function updateMultiple(Request $request)
    {
        $result = $this->articleService->updateMultiple($request->articles);
        if (isset($result['failed'])) {
            return $this->sendResponse($result['failed'], StateEnum::ECHEC, 'Certaines mises à jour ont échoué', 422);
        }
        return $this->sendResponse($result['success'], StateEnum::SUCCESS, 'Articles mis à jour avec succès');
    }
}