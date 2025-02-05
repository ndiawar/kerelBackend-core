<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class Utilisateur extends Controller
{
    // Afficher la liste des utilisateurs
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Afficher un utilisateur spécifique
    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
    }

    // Créer un nouvel utilisateur
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'string|in:active,inactive',
            'role' => 'required|string|in:superadmin,user',
            'rfid_code' => 'nullable|string|max:50',
        ]);

        // Générer un code de 4 chiffres
        $validatedData['code'] = rand(1000, 9999);

        // Définir 'status' à 'active' par défaut si non fourni
        if (!isset($validatedData['status'])) {
            $validatedData['status'] = 'active';
        }

        $user = User::create($validatedData);
        return response()->json($user, 201);
    }

    // Mettre à jour un utilisateur existant
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $validatedData = $request->validate([
                'nom' => 'sometimes|required|string|max:255',
                'prenom' => 'sometimes|required|string|max:255',
                'telephone' => 'sometimes|required|string|max:20',
                'email' => 'nullable|email|max:255',
                'status' => 'required|string|in:active,inactive',
                'role' => 'sometimes|required|string|in:superadmin,user',
                'rfid_code' => 'nullable|string|max:50',
            ]);

            $user->update($validatedData);
            return response()->json($user);
        } else {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
    }

    // Supprimer un utilisateur
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['message' => 'Utilisateur supprimé']);
        } else {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
    }

    // Login par code
    public function loginByCode(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|integer|digits:4',
        ]);

        $user = User::where('code', $validatedData['code'])->first();

        if ($user) {

            if ($user->status !== 'active') {
                return response()->json(['message' => 'Utilisateur bloqué'], 403);
            }

            $token = $user->createToken('auth_token')->plainTextToken;
            $user->api_token = $token;
            $user->save();

            return response()->json([
                'token' => $token,
                'role' => $user->role,
            ]);
        } else {
            return response()->json(['message' => 'Code incorrect'], 404);
        }
    }

    // Logout
    public function logout(Request $request)
{
    $user = $request->user();
    $token = $request->bearerToken();

    if ($user && $user->api_token === $token) {
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        $user->api_token = null;
        $user->save();
        return response()->json(['message' => 'Déconnexion réussie']);
    } else {
        return response()->json(['message' => 'Token invalide'], 401);
    }
}

     // Bloquer un utilisateur
     public function bloquer(Request $request)
     {
         $validatedData = $request->validate([
             'telephone' => 'required|string|max:20',
         ]);
 
         $user = User::where('telephone', $validatedData['telephone'])->first();
 
         if ($user) {
             $user->status = 'inactive';
             $user->save();
             return response()->json(['message' => 'Utilisateur bloqué']);
         } else {
             return response()->json(['message' => 'Utilisateur non trouvé'], 404);
         }
     }

     // Débloquer un utilisateur
    public function debloquer(Request $request)
    {
        $validatedData = $request->validate([
            'telephone' => 'required|string|max:20',
        ]);

        $user = User::where('telephone', $validatedData['telephone'])->first();

        if ($user) {
            $user->status = 'active';
            $user->save();
            return response()->json(['message' => 'Utilisateur débloqué']);
        } else {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
    }
}