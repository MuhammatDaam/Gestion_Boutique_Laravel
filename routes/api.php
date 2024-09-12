<?php 
 
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\ClientController; 
use App\Http\Controllers\ArticleController; 
use App\Http\Controllers\UserController; 
use App\Http\Controllers\DetteController;
use App\Http\Controllers\PaiementController;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Route; 
 
// Préfixe v1 pour les routes versionnées
Route::prefix('v1')->group(function () {
 
    // Routes d'authentification accessibles sans authentification
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/users', [UserController::class, 'store']); // Ajout d'un utilisateur
 
    // Routes protégées par auth:passport
    Route::middleware('auth:api')->group(function () {
        // Déconnexion de l'utilisateur
        Route::post('/logout', [AuthController::class, 'logout']);
 
        // Gestion des utilisateurs
        Route::get('/users', [UserController::class, 'index']); // Liste des utilisateurs
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
 
        // Gestion des clients (versionnée) 
        Route::apiResource('/clients', ClientController::class)->only(['index', 'store', 'show']); 
        //recherrche client par id
        Route::get('/clients/{id}', [ClientController::class, 'show'])->name('clients.show');
 
        // Route POST pour le filtrage par téléphone
        Route::post('/clients/telephone', [ClientController::class, 'telephone']);

        // Route pour filtrage des clients par comptes
        Route::get('/clients', [ClientController::class, 'index']);

        // Gestion des articles avec un sous-préfixe 
        Route::prefix('/articles')->group(function () { 
            Route::apiResource('', ArticleController::class); // CRUD standard
            Route::post('/', [ArticleController::class, 'store']); // Stockage des articles
            Route::get('/{id}', [ArticleController::class, 'show'])->name('articles.show'); // Affichage d'un article
            Route::delete('/{id}', [ArticleController::class, 'softDelete']); // Suppression douce
            Route::patch('/{id}/restore', [ArticleController::class, 'restore']); // Restauration d'un article
            Route::delete('/{id}/force-delete', [ArticleController::class, 'forceDelete']); // Suppression forcée

            // Route POST pour la recherche par libelle
            Route::post('/libelle', [ArticleController::class, 'searchByLibelle']); 

            // Route POST pour la mise à jour de plusieurs articles
            Route::post('/stock', [ArticleController::class, 'updateMultiple']); 
        }); 

        // Gestion des dettes (versionnée) 
        Route :: prefix('/dettes')->group(function () {
            
            Route::apiResource('', DetteController::class);
            Route::post('/', [DetteController::class, 'store']); // Stockage des dettes
            Route::get('/', [DetteController::class, 'index']);// Liste des dettes
            Route::get('', [DetteController::class, 'filterByStatus']); // Filtrer par statut
            
          // Route POST pour passer une dette en paiement
          Route::post('id/paiements', [PaiementController::class, 'effectuerPaiement']);

        });

    }); 
});
