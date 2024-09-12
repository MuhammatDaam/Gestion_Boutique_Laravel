<?php
namespace App\Repository;

use App\Models\Client;

interface ClientRepository
{
    public function getAllClients();
    public function findByTelephone(string $telephone);
    public function createClient(array $data);
    public function updateClient(array $data, string $id);
    public function deleteClient(string $id);
    public function storeClientWithUser(array $clientData, array $userData);

}
