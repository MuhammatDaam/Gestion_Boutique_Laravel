<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;

interface ClientService
{
    public function getAllClients(): Collection;

    public function getClientById(string $id): ?Client;

    public function findClientByTelephone(string $telephone): ?Client;

    public function createNewClient(array $data): Client;

    public function updateClient(array $data, string $id);

    public function deleteClient(string $id);

    public function storeClientWithUser(array $clientData, array $userData, $photo): Client;

    public function createUserForClient(array $data);
    
}
