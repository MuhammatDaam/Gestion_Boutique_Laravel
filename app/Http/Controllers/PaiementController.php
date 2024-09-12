<?php

namespace App\Http\Controllers;

use App\Services\PaiementService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaiementController extends Controller
{
    protected $paiementService;

    public function __construct(PaiementService $paiementService)
    {
        $this->paiementService = $paiementService;
    }

    public function effectuerPaiement(Request $request)
    {
        // Validation des données
        $request->validate([
            'dette_id' => 'required|integer|exists:dettes,id',
            'montant' => 'required|numeric|min:0.01',
        ]);

        try {
            // Appeler le service pour effectuer le paiement
            $paiement = $this->paiementService->effectuerPaiement($request->all());

            // Réponse en cas de succès
            return response()->json([
                'success' => true,
                'message' => 'Paiement effectué avec succès.',
                'paiement' => $paiement
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            // Réponse en cas d'erreur
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
