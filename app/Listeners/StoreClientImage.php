<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\UploadService;

class StoreClientImage implements ShouldQueue
{
    public function handle($event)
    {
        // dd($event);
        $client = $event->client;
        if (isset($client->photo)) {
            $filePath = $client->photo;
            // Logique pour uploader l'image dans le cloud ici
            $cloudService = new UploadService();

              $cloudService->upload($filePath, 'photos');

        }
    }
}
