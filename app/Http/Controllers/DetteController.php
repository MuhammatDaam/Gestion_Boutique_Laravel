<?php

namespace App\Http\Controllers;

use App\Models\Dette;
use Illuminate\Http\Request;
use App\Services\DetteService;
use App\Http\Requests\StoreDetteRequest;
use App\Facade\DetteServiceFacade;
use Illuminate\Support\Facades\DB;


class DetteController extends Controller
{
    public function store(StoreDetteRequest $request)
    {
        $validated = $request->validated();
        
        // Utiliser le service pour enregistrer la dette
        $debt = DetteServiceFacade::createDette($validated);
        
         return response()->json(['debt' => $debt], 201);
    }

    public function update(int $id, Request $request)
    {
        $validated = $request->validated();
        
        // Utiliser le service pour mettre à jour la dette
        $debt = DetteServiceFacade::updateDebt($id, $validated);
        
        return response()->json(['debt' => $debt], 200);
    }

    public function destroy(int $id)
    {
        // Utiliser le service pour supprimer la dette
        DetteServiceFacade::deleteDebt($id);
        
        return response()->json(null, 204);
    }

    public function show(int $id)
    {
        // Utiliser le service pour afficher la dette
        $debt = DetteServiceFacade::getDebtById($id);
        
        return response()->json(['debt' => $debt], 200);
    }

    public function index(Request $request)
    {
        // Utiliser le service pour afficher les dettes
        $debts = DetteServiceFacade::getDebtsByClient();

        // Récupérer le paramètre "statut" de la requête (solde ou nonsolde)
    $statut = $request->input('statut');

    // Filtrer les dettes en fonction du statut (soldée ou non soldée)
    $debts = Dette::with('client') // Inclure les informations du client
                    ->statut($statut) // Utiliser le scope pour filtrer
                    ->get();
        
        return response()->json(['debts' => $debts], 200);
    }

    public function searchByLibelle(Request $request)
    {
        $libelle = $request->input('libelle');
        
        // Utiliser le service pour afficher les dettes
        $debts = DetteServiceFacade::getDebtsByClient(1);
        
        return response()->json(['debts' => $debts], 200);
    }

    public function updateMultiple(Request $request)
    {
        $data = $request->input('data');
        
        // Utiliser le service pour mettre à jour plusieurs dettes
        $debts = DetteServiceFacade::updateMultipleDebts($data);
        
        return response()->json(['debts' => $debts], 200);
    }

    public function deleteMultiple(Request $request)
    {
        $data = $request->input('data');
        
        // Utiliser le service pour supprimer plusieurs dettes
        $debts = DetteServiceFacade::deleteMultipleDebts($data);
        
        return response()->json(['debts' => $debts], 200);
    }

    public function filterByStatus(Request $request)
    {
        $statut = $request->input('statut');
        
        // Utiliser le service pour filtrer les dettes par statut
        $debts = DetteServiceFacade::getDebtsByStatus($statut);
        
        return response()->json(['debts' => $debts], 200);
    }
    
}
