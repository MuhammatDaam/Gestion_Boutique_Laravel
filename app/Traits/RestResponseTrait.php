<?php

namespace App\Traits;

use App\Enums\StateEnum;

trait RestResponseTrait
{
    public function sendResponse($data, StateEnum $status = StateEnum::SUCCESS, $message = '', $codeStatut = 201)
    {
        // Gestion des erreurs
        if ($status === StateEnum::ECHEC) {
            $codeStatut = 411;
            if (empty($message)) {
                $message = 'Une erreur s\'est produite.';
            }
        }

        // Gestion du succès
        if ($status === StateEnum::SUCCESS && empty($message)) {
            $message = 'Opération réussie.';
        }

        return response()->json([
            'data' => $data,
            'status' => $status->value,
            'message' => $message,
        ], $codeStatut);
    }
}

