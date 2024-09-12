<?php
namespace App\Repository;

use App\Models\Client;
use App\Models\User;
use Spatie\QueryBuilder\QueryBuilder;

class ClientRepositoryImpl implements ClientRepository
{
    public function getAllClients(array $filters = [])
    {
        $query = QueryBuilder::for(Client::class)
            ->allowedFilters(['surname'])
            ->allowedIncludes(['user']);

        if (isset($filters['comptes'])) {
            $query->whereNotNull('user_id');
        }

        if (isset($filters['active'])) {
            $query->whereHas('user', function ($query) use ($filters) {
                $query->where('active', $filters['active'] === 'oui' ? 1 : 0);
            });
        }

        return $query->get();
    }

    public function findByTelephone(string $telephone)
    {
        return Client::where('telephone', $telephone)->first();
    }


    public function createClient(array $data)
    {
        return Client::create($data);
    }

    public function updateClient(array $data, string $id)
    {
        $client = Client::find($id);
        if ($client) {
            $client->update($data);
            return true;
        }
        return false;
    }

    public function deleteClient(string $id)
    {
        $client = Client::find($id);
        if ($client) {
            $client->delete();
            return true;
        }
        return false;
    }

    public function storeClientWithUser(array $clientData, array $userData)
    {$client = $this->createClient($clientData);

        // Vérification si un fichier 'photo' est présent dans les données utilisateur
        if (array_key_exists('photo', $userData)) {
            // Stockage de la photo dans le disque public
            $filePath = $userData['photo']->store('photos', 'public');

            // Mise à jour des données utilisateur avec le chemin de la photo
            $userData['photo'] = $filePath;
        }
    
        // Création de l'utilisateur et association au client
        $client->user()->create($userData);
    
        return $client;
    }

    public function createUserForClient($client, array $data)
    {

        $user = User::create($data);
        $client->user_id = $user->id;
        $client->save();
        return $user;
    }
}
