<?php 
 
namespace App\Http\Resources; 
 
use Illuminate\Http\Request; 
use Illuminate\Http\Resources\Json\ResourceCollection; 
 
class ClientCollection extends ResourceCollection 
{ 
    /** 
     * Transform the resource collection into an array. 
     * 
     * @return array<int|string, mixed> 
     */ 
    public function toArray(Request $request): array 
    { 
        return [
            'data' => $this->collection->map(function ($client) {
                return [
                    'id' => $client->id,
                    'surname' => $client->surname,
                    'address' => $client->adresse,
                    'telephone' => $client->telephone,
                    'user' => [
                        'id' => $client->user->id ?? null,
                        'login' => $client->user->login ?? null,
                        'role_id' => $client->user->role_id ?? null,
                        'photo' => $client->user->photo ?? null,
                    ],
                ];
            }),
            'meta' => [
                'total_clients' => $this->collection->count(),
            ],
        ]; 
    } 
}
