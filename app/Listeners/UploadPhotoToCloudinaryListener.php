<?php

namespace App\Listeners;

use App\Events\ClientCreated;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class UploadPhotoToCloudinaryListener
{
    public function handle(ClientCreated $event)
    {
        $client = $event->client;
        Log::info($client);
        // Vérifiez si la propriété $photo est définie dans l'événement
        if (property_exists($event, 'tempPhotoPath')) {
            $photo = $event->tempPhotoPath ?? null;
            $originalName = explode('/', $photo)[1];
            $filePath = storage_path('app/' . $photo);
            $file = new UploadedFile($filePath, $originalName);
            Log::info($file);
        } else {
            // Si la propriété n'est pas définie, vous pouvez utiliser une autre propriété ou une valeur par défaut
            $photo = null;
        }

        // Charger l'image dans Cloudinary
        Log::info(1);
        $saveCloud = Cloudinary::upload($file, [
            'folder' => 'photos',
        ]);

        // Récupérer l'URL sécurisée de l'image
        $secureUrl = $saveCloud->getSecurePath();

        // Mettre à jour l'URL de la photo dans la base de données
        $client->user->photo = $secureUrl;
        $client->user->save();
    }
}
