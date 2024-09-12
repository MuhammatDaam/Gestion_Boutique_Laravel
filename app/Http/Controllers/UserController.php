<?php

namespace App\Http\Controllers;
// use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
{
    //  // Autorisation basée sur la Policy UserPolicy::access()
    //  $this->authorize('access', User::class);

    // Récupération des paramètres 'role' et 'active' depuis la requête
    $role = $request->query('role');
    $active = $request->query('active');

    // Si un rôle est fourni, filtrer les utilisateurs par ce rôle
    if ($role) {
        // Vérification si le rôle existe
        $roleExists = Role::where('libelle', $role)->exists();

        if (!$roleExists) {
            return response()->json([
                'error' => 'Le rôle spécifié n\'existe pas.',
            ], 404);
        }

        // Si le rôle existe, récupérer les utilisateurs associés et filtrer par statut actif
        $users = User::whereHas('role', function ($query) use ($role) {
            $query->where('libelle', $role);
        })
        ->when($active, function ($query) use ($active) {
            $query->where('active', $active === 'oui' ? 1 : 0);
        })
        ->get();
    } else {
        // Sinon, retourner tous les utilisateurs, en filtrant par statut actif si nécessaire
        $users = User::when($active, function ($query) use ($active) {
            $query->where('active', $active === 'oui' ? 1 : 0);
        })
        ->get();
    }

    return response()->json([
        'data' => $users->isNotEmpty() ? $users : null,
    ]);
}

    
    public function store(Request $request)
    {
        //  // Autorisation basée sur la Policy UserPolicy::access()
        //  $this->authorize('access', User::class);

        // Identifiants des rôles admin et boutiquier
        $allowedRoles = [1, 2]; // Remplacez ces valeurs par les vrais role_id pour admin et boutiquier

        // Validation des données de la demande avec vérification du rôle
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255', 
            'prenom' => 'required|string|max:255',
            'login' => 'required|string|unique:users|max:255',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id|in:' . implode(',', $allowedRoles),
            'photo' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ], [
            'login.unique' => 'Ce login est déjà utilisé.',
            'role_id.in' => 'Le rôle spécifié doit être admin ou boutiquier.',
        ]);

        // Création de l'utilisateur
        $user = User::create([
            'nom' => $validatedData['nom'],
            'prenom' => $validatedData['prenom'],
            'login' => $validatedData['login'],
            'password' => Hash::make($validatedData['password']),
            'role_id' => $validatedData['role_id'],
            'photo' => $request->file('photo') ? $request->file('photo')->store('photos') : null,
        ]);

        // Retourne une réponse JSON avec le code de statut 201 (créé)
        return response()->json($user, 201);
    }


    public function show(User $user)
    { 
        //  // Autorisation basée sur la Policy UserPolicy::access()
        //  $this->authorize('access', User::class);

        return response()->json($user);
    } 
 
    public function destroy(User $user) 
    { 
        //  // Autorisation basée sur la Policy UserPolicy::access()
        //  $this->authorize('access', User::class);

        $user->delete(); 
        return response()->json(null, 204); 
    } 
}
