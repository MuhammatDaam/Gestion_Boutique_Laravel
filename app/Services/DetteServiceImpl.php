<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Dette;
use App\Models\Paiement;
use App\Facade\DetteRepositoryFacade;
use App\Repository\DetteRepository;
use Illuminate\Support\Facades\DB;

class DetteServiceImpl implements DetteService
{
   
   public function createDette(array $data)
{
    // Démarrer une transaction pour garantir l'intégrité des données
    DB::beginTransaction();
    
    try {
        // Créer la dette
        $dette = DetteRepositoryFacade::create([
            'montant' => $data['montant'],
            'client_id' => $data['clientId'],
        ]);

        // Ajouter les articles associés à la dette et mettre à jour les stocks
        foreach ($data['articles'] as $articleData) {
            // Attacher les articles à la dette via la table pivot
            $dette->articles()->attach($articleData['articleId'], [
                'quantite' => $articleData['stock'],
                'prix_vente' => $articleData['price'],
            ]);

            // Récupérer l'article pour mettre à jour le stock
            $article = Article::find($articleData['articleId']);

            // Vérifier s'il y a suffisamment de stock
            if ($article->stock >= $articleData['stock']) {
                // Mettre à jour le stock de l'article
                $article->stock -= $articleData['stock'];
                $article->save();
            } else {
                throw new \Exception('Stock insuffisant pour l\'article: ' . $article->libelle);
            }
        }

        // Ajouter les informations de paiement si présentes
        if (isset($data['paiement']['montant'])) {
            Paiement::create([
                'dette_id' => $dette->id,
                'montant' => $data['paiement']['montant'],
            ]);
        }

        // Commit la transaction pour valider toutes les opérations
        DB::commit();

        return $dette;
    } catch (\Exception $e) {
        // En cas d'erreur, rollback la transaction
        DB::rollBack();
        throw $e;
    }
}

    public function createDebt(array $data)
    {
        return DetteRepositoryFacade::create($data);
    }

    public function updateDebt(int $id, array $data)
    {
        return DetteRepositoryFacade::update($id, $data);
    }

    public function deleteDebt(int $id)
    {
        return DetteRepositoryFacade::delete($id);
    }

    public function getDebtById(int $id)
    {
        return DetteRepositoryFacade::findById($id);
    }

    public function getDebtsByClient(int $clientId)
    {
        return DetteRepositoryFacade::findByClient($clientId);
    }

    // afficher toutes les dettes
    public function getDebts()
    {
        return DetteRepositoryFacade::all();
    }

    public function getDebtsByStatus($statut)
    {
        return DetteRepositoryFacade::findByStatus($statut);
    }
}
