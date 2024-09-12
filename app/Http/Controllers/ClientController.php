<?php 
 
namespace App\Http\Controllers; 
 
use App\Http\Requests\StoreClientRequest; 
use App\Http\Resources\ClientCollection;
use App\Http\Resources\ClientResource;
use App\Facade\ClientServiceFacade;
use App\Traits\RestResponseTrait;
use App\Enums\StateEnum;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB; 
use App\Http\Requests\UpdateClientRequest;
use App\Policies\ClientPolicy;
use App\Models\Client;
use Exception;
 
class ClientController extends Controller 
{ 
    use RestResponseTrait; 

    public function index(Request $request) 
    { 
        // // Autorisation basée sur la Policy ClientPolicy::access()
        // $this->authorize('access', Client::class);
        
        $clients = ClientServiceFacade::getAllClients();
        return new ClientCollection($clients); 
    } 

    public function show($id) 
    { 
        // // Autorisation basée sur la Policy ClientPolicy::access()
        // $this->authorize('access', Client::class);

        $client = ClientServiceFacade::getClientById($id);
        return new ClientResource($client);
    } 

    public function telephone(Request $request)
    { 
        // // Autorisation basée sur la Policy ClientPolicy::access()
        // $this->authorize('access', Client::class);

        $request->validate(['telephone' => 'required|string']);
        $clients = ClientServiceFacade::findClientByTelephone($request->input('telephone'));
        // return new ClientCollection($clients);
         return $clients;
    } 
 
    public function store(StoreClientRequest $request) 
{
    // // Autorisation basée sur la Policy ClientPolicy::access()
    //     $this->authorize('access', Client::class);

        $client = ClientServiceFacade::storeClientWithUser(
            $request->only('surname', 'adresse', 'telephone'),
            $request->input('user'), $request->file('user.photo')
        );
    
        return $this->sendResponse(new ClientResource($client));
    
} 
}
