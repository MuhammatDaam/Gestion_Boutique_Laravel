<?php

namespace App\Services;

use App\Repository\ClientRepository;
use App\Models\Client;
use App\Facade\ClientRepositoryFacade;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Mail\Sendmail;
use Illuminate\Support\Facades\Mail;
use Cloudinary\Cloudinary;
use App\Events\ClientCreated;


class ClientServiceImpl implements ClientService
{
    public function getAllClients(): Collection
    {
        return ClientRepositoryFacade::getAllClients();
    }

    public function getClientById(string $id): ?Client
    {
        return Client::find($id);
    }

    public function findClientByTelephone(string $telephone): ?Client
    {
        $client = ClientRepositoryFacade::findByTelephone($telephone);
            
            
            if($client->user){
                $client->user->photo = $client->user->photo;
                $client->user->role = $client->user->role_id;
                $client->user->etat = $client->user->etat;
            }
            $imagePath = storage_path('app/photos/' . $client->user->photo); // Chemin vers l'image, ajustez selon votre configuration
            if (file_exists($imagePath)) {
            $imageData = file_get_contents($imagePath);
            $base64Image = base64_encode($imageData);
        $client->user->photo = 'data:image/jpeg;base64,' . $base64Image; // Assurez-vous de définir le bon type MIME
    } else {
        $client->user->photo = null;
    }
        return $client;
    }

    public function createNewClient(array $data): Client
    {
        $client = ClientRepositoryFacade::createClient($data);
        $login = $client->user->login;

        // Envoyer un email au client après sa création
        Mail::to($login)->send(new Sendmail($client, storage_path('app/' . $client->pdf_path)));

        return $client;
    }

    public function updateClient(array $data, string $id): bool
    {
        $client = Client::find($id);

        if ($client) {
            return ClientRepositoryFacade::updateClient($data, $id);
        }

        return false;
    }

    public function deleteClient(string $id): bool
    {
        $client = Client::find($id);

        if ($client) {
            return ClientRepositoryFacade::deleteClient($id);
        }

        return false;
    }


    protected $cloudinary;
    public function __construct(){
        $this->cloudinary = new cloudinary([
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
            'api_key' => env('CLOUDINARY_API_KEY'),
            'api_secret' => env('CLOUDINARY_API_SECRET'),
             'secure' => true,
        ]);
    }

    // public function storeClientWithUser(array $clientData, array $userData, $photo): Client
    // {
    //     try {
    //         DB::beginTransaction();

    //         // Créer le client
    //         $client = ClientRepositoryFacade::createClient($clientData);

    //         // Associer le compte utilisateur au client
    //         $user = ClientRepositoryFacade::createUserForClient($client, $userData);

    //         DB::commit();

    //         $user = $client->user;

    //         // Préparer les données du QR code
    //         $qrCodeData = json_encode([
    //             'id' => $client->id,
    //             'surname' => $client->surname,
    //             'mail' => $user->login,
    //             'phone' => $client->telephone,
    //         ]);

    //         // Définir le nom de fichier et le chemin du QR code
    //         $qrCodeFileName = 'client_' . $client->id . '.png';
    //         $qrCodeDirectory = public_path('qrcodes/');

    //         if (!file_exists($qrCodeDirectory)) {
    //             mkdir($qrCodeDirectory, 0755, true);
    //         }

    //         // Envoyer la photo au Cloudinary
    //         $saveCloud = $this->cloudinary->uploadApi()->upload($photo->getRealPath(), [
    //             'folder' => 'photos',
    //         ]);
    //         $userData['photo'] = $saveCloud['secure_url'];

    //         //save photo in base de données
    //         $client->user->photo = $userData['photo'];
    //         $client->user->save();

    //         // Générer le QR code et le sauvegarder
    //         $qrCodeFullPath = $qrCodeDirectory . $qrCodeFileName;
    //         app(QrCodeService::class)->generateQrCode($qrCodeData, $qrCodeFullPath);

    //         // Encoder le QR code en base64
    //         $qrCodeBase64 = base64_encode(file_get_contents($qrCodeFullPath));

    //         // Générer le PDF
    //         $pdfPath = storage_path('/public/pdfs/client_' . $client->id . '.pdf');
    //         app(PdfService::class)->generatePdf('pdf.client', [
    //             'client' => $client,
    //             'qrCodeBase64' => $qrCodeBase64,
    //         ], $pdfPath);

    //         // Envoyer un email au client après sa création avec le PDF attaché
    //         $login = $client->user->login;
    //         Mail::to($login)->send(new Sendmail($client, $pdfPath));
    //         $client->userdata = $userData;
    //         return $client;
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         throw $e;
    //     }
    // }

    
    // pour job events listener
    // public function storeClientWithUser(array $clientData, array $userData, $photo): Client
    // {
    //     try {
    //         DB::beginTransaction();
    
    //         // Créer le client
    //         $client = ClientRepositoryFacade::createClient($clientData);
    
    //         // Associer le compte utilisateur au client
    //         $user = ClientRepositoryFacade::createUserForClient($client, $userData);
    
    //         DB::commit();
    
    //          // Sauvegarder la photo temporairement
    //          $tempPhotoPath = $photo->store('temp');
    
    //         // Préparer les données du QR code
    //         $qrCodeData = json_encode([
    //             'id' => $client->id,
    //             'surname' => $client->surname,
    //             'mail' => $user->login,
    //             'phone' => $client->telephone,
    //         ]);
    
    //         // Définir le nom de fichier et le chemin du QR code
    //         $qrCodeFileName = 'client_' . $client->id . '.png';
    //         $qrCodeDirectory = public_path('qrcodes/');
    
    //         if (!file_exists($qrCodeDirectory)) {
    //             mkdir($qrCodeDirectory, 0755, true);
    //         }
    
    //         // Générer le QR code et le sauvegarder
    //         $qrCodeFullPath = $qrCodeDirectory . $qrCodeFileName;
    //         app(QrCodeService::class)->generateQrCode($qrCodeData, $qrCodeFullPath);
    
    //         // Encoder le QR code en base64
    //         $qrCodeBase64 = base64_encode(file_get_contents($qrCodeFullPath));
    
    //         // Générer le PDF
    //         $pdfPath = storage_path('/public/pdfs/client_' . $client->id . '.pdf');
    //         app(PdfService::class)->generatePdf('pdf.client', [
    //             'client' => $client,
    //             'qrCodeBase64' => $qrCodeBase64,
    //         ], $pdfPath);

    //          // Envoyer un événement avec le chemin temporaire de la photo et le chemin du PDF
    //         event(new ClientCreated($client, $tempPhotoPath, $pdfPath));

    //         return $client;
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         throw $e;
    //     }
    // }
    

    public function storeClientWithUser(array $clientData, ?array $userData = null, $photo = null): Client
{
    try {
        DB::beginTransaction();

        // Créer le client
        $client = ClientRepositoryFacade::createClient($clientData);

        // Si les données utilisateur sont fournies, créer l'utilisateur
        if ($userData) {
            // Vérification si un fichier 'photo' est présent dans les données utilisateur
            if (isset($userData['photo'])) {
                // Stockage de la photo dans le disque public
                $filePath = $userData['photo']->store('photos', 'public');
                $userData['photo'] = $filePath;
            }

            // Associer le compte utilisateur au client
            ClientRepositoryFacade::createUserForClient($client, $userData);
        }

        DB::commit();

        // Gestion de la photo, du QR code et du PDF
        if ($photo) {
            // Sauvegarder la photo temporairement
            $tempPhotoPath = $photo->store('temp');

            // Préparer les données du QR code
            $qrCodeData = json_encode([
                'id' => $client->id,
                'surname' => $client->surname,
                'mail' => $userData['login'] ?? null,
                'phone' => $client->telephone,
            ]);

            // Définir le nom de fichier et le chemin du QR code
            $qrCodeFileName = 'client_' . $client->id . '.png';
            $qrCodeDirectory = public_path('qrcodes/');

            if (!file_exists($qrCodeDirectory)) {
                mkdir($qrCodeDirectory, 0755, true);
            }

            // Générer le QR code et le sauvegarder
            $qrCodeFullPath = $qrCodeDirectory . $qrCodeFileName;
            app(QrCodeService::class)->generateQrCode($qrCodeData, $qrCodeFullPath);

            // Encoder le QR code en base64
            $qrCodeBase64 = base64_encode(file_get_contents($qrCodeFullPath));

            // Générer le PDF
            $pdfPath = storage_path('/public/pdfs/client_' . $client->id . '.pdf');
            app(PdfService::class)->generatePdf('pdf.client', [
                'client' => $client,
                'qrCodeBase64' => $qrCodeBase64,
            ], $pdfPath);

            // Envoyer un événement avec le chemin temporaire de la photo et le chemin du PDF
            event(new ClientCreated($client, $tempPhotoPath, $pdfPath));
        }

        return $client;
    } catch (Exception $e) {
        DB::rollBack();
        throw $e;
    }
}

    public function deleteClientWithUser(string $id): bool
    {
        return ClientRepositoryFacade::deleteClientWithUser($id);
    }

    public function updateClientWithUser(array $data, string $id): bool
    {
        return ClientRepositoryFacade::updateClientWithUser($data, $id);
    }

    public function createClientWithUser(array $data): Client
    {
        return ClientRepositoryFacade::createClientWithUser($data);
    }

    public function createUserForClient(array $data)
    {
        return ClientRepositoryFacade::createUserForClient($data);
    }
}
