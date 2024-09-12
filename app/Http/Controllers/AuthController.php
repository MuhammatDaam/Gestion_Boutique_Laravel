<?php 
 
namespace App\Http\Controllers; 

use App\Models\User; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash; 
use App\Traits\RestResponseTrait; 
use App\Enums\StateEnum; 
use Illuminate\Support\Str; 
use App\Services\AuthenticationServiceInterface;


class AuthController extends Controller
{
    use RestResponseTrait;

    protected $authService;

    public function __construct(AuthenticationServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $result = $this->authService->authenticate($request->only('login', 'password'));
 
        // if (!$result['status']) {
        //     return $this->sendResponse(null, StateEnum::ECHEC, $result['message'], $result['code']);
        // }
 
        return $this->sendResponse([ 
            //'user' => $result['user'],//
            'token'=> $result['token'],
            // 'access_token' => $result['access_token'],
            // 'refresh_token' => $result['refresh_token'],
        ], StateEnum::SUCCESS, $result['message']);
    } 

    public function refresh(Request $request) 
    { 
        $request->validate([ 
            'refresh_token' => 'required', 
        ]); 
 
        $user = User::where('refresh_token', $request->refresh_token)->first(); 
 
        if (!$user) { 
            return $this->sendResponse(null, StateEnum::ECHEC, 'Refresh token invalide', 401); 
        } 
        // Révoquer tous les tokens existants 
        $user->tokens()->delete(); 
 
        $token = $user->createToken('auth_token')->plainTextToken; 
        $refreshToken = Str::random(60); 
 
        $user->update(['refresh_token' => $refreshToken]); 
 
        return $this->sendResponse([ 
            'access_token' => $token, 
            // 'refresh_token' => $refreshToken,
            'token_type' => 'Bearer', 
        ], StateEnum::SUCCESS, 'Token rafraîchi avec succès'); 
    }  

    public function logout()
    { 
        $this->authService->logout();
 
        return $this->sendResponse(null, StateEnum::SUCCESS, 'Déconnexion réussie'); 
    } 
} 
