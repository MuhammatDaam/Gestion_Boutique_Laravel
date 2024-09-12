<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\UploadService;
use App\Models\Client;

class VerifyAndReuploadMissingImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function handle(UploadService $uploadService)
    {
        $photoPath = $this->client->photo;

        if (!$uploadService->getBase64($photoPath)) {
            // Logique pour réuploader l'image manquante
            $uploadService->upload($photoPath, $this->client);

            // Mettre à jour l'image dans la base de données
            $this->client->user->photo = $photoPath;
            $this->client->user->save();

            // Exemple : relancer un upload avec une logique prédéfinie
        }
    }
}
