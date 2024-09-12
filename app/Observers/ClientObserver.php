<?php

namespace App\Observers;

use App\Models\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\Sendmail;
use Cloudinary\Cloudinary;
use App\Events\ClientCreated;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;



class ClientObserver
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
            'api_key' => env('CLOUDINARY_API_KEY'),
            'api_secret' => env('CLOUDINARY_API_SECRET'),
            'secure' => true,
        ]);
    }

    /**
     * Action à effectuer après la création d'un client.
     *
     * @param Client $client
     */
    public function created(Client $client)
    {
        try {
            // Sauvegarde de la photo dans Cloudinary si une photo est fournie
            if ($client->photo && Storage::disk('local')->exists('temp/' . $client->photo)) {
                $photoPath = storage_path('app/temp/' . $client->photo);

                // Upload de la photo dans Cloudinary
                $uploadResult = $this->cloudinary->uploadApi()->upload($photoPath, [
                    'folder' => 'clients',
                ]);

                // Mise à jour du chemin de la photo dans le modèle client
                $client->photo = $uploadResult['secure_url'];
                $client->save();

                // Suppression de la photo temporaire
                Storage::disk('local')->delete('temp/' . $client->photo);
            }

            // Envoi de l'email de bienvenue avec le PDF joint si nécessaire
            $login = $client->user->login;
            Mail::to($login)->send(new Sendmail($client, storage_path('app/' . $client->pdf_path)));

            // Lancer l'événement ClientCreated
            event(new ClientCreated($client, $client->pdf_path));

        } catch (Exception $e) {
            // Gestion des erreurs (logging, message d'erreur, etc.)
            \Illuminate\Support\Facades\Log::error('Erreur lors de la création du client : ' . $e->getMessage());
        }
    }

    /**
     * Action à effectuer après la mise à jour d'un client.
     *
     * @param Client $client
     */
    public function updated(Client $client)
    {
        // Vous pouvez ajouter ici une logique spécifique à la mise à jour d'un client
    }

    /**
     * Action à effectuer après la suppression d'un client.
     *
     * @param Client $client
     */
    public function deleted(Client $client)
    {
        // Par exemple, supprimer la photo de Cloudinary lors de la suppression du client
        if ($client->photo) {
            $this->deletePhotoFromCloudinary($client->photo);
        }
    }

    /**
     * Action à effectuer après la restauration d'un client (SoftDeletes).
     *
     * @param Client $client
     */
    public function restored(Client $client)
    {
        // Logique pour la restauration si besoin
    }

    /**
     * Supprime la photo de Cloudinary.
     *
     * @param string $photoUrl
     */
    protected function deletePhotoFromCloudinary(string $photoUrl)
    {
        try {
            // Extraire l'ID public de l'URL Cloudinary
            $publicId = pathinfo($photoUrl, PATHINFO_FILENAME);
            $this->cloudinary->uploadApi()->destroy($publicId);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur lors de la suppression de la photo de Cloudinary : ' . $e->getMessage());
        }
    }
}
