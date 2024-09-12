<?php

namespace App\Jobs;

use App\Models\Client;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UploadPhotoToCloudinaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $client;
    protected $tempPhotoPath;

    /**
     * Create a new job instance.
     *
     * @param  Client  $client
     * @param  string  $tempPhotoPath
     */
    public function __construct(Client $client, $tempPhotoPath)
    {
        $this->client = $client;
        $this->tempPhotoPath = $tempPhotoPath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Récupérer le fichier depuis le stockage temporaire
        $filePath = storage_path('app/' . $this->tempPhotoPath);

        // Upload de la photo sur Cloudinary
        $saveCloud = Cloudinary::upload($filePath, [
            'folder' => 'photos',
        ]);

        // Sauvegarder l'URL de la photo dans la base de données
        $this->client->photo = $saveCloud->getSecurePath();
        $this->client->save();

        // Supprimer la photo temporaire
        Storage::delete($this->tempPhotoPath);
    }
}
