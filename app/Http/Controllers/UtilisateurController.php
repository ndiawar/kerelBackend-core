<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Notification; // Importer la classe Notification
use App\Notifications\UserRegistered; // Importer la notification UserRegistered

class UtilisateurController extends Controller
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
        // $email = $user->email ?? 'asow19133@gmail.com';
        // Notification::route('mail', $email)->notify(new UserRegistered($user));


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
                'status' => 'string|in:active,inactive',
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

            // Générer un token JWT
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        } else {
            return response()->json(['message' => 'Code incorrect'], 404);
        }
    }

    public function loginByCard(Request $request)
    {
        $validatedData = $request->validate([
            'rfid_code' => 'required|string|max:50',
        ]);

        $user = User::where('rfid_code', $validatedData['rfid_code'])->first();

        if ($user) {
            if ($user->status !== 'active') {
                return response()->json(['message' => 'Utilisateur bloqué'], 403);
            }

            // Générer un token JWT
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        } else {
            return response()->json(['message' => 'Code RFID incorrect'], 404);
        }
    }

    // Logout
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::parseToken());
            return response()->json(['message' => 'Déconnexion réussie']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token invalide'], 401);
        }
    }

    // Bloquer un utilisateur
public function bloquer($id, Request $request)
{
    $user = User::find($id);
 
    if ($user) {
        $user->status = 'inactive';
        $user->save();
        return response()->json(['message' => 'Utilisateur bloqué']);
    } else {
        return response()->json(['message' => 'Utilisateur non trouvé'], 404);
    }
}

// Débloquer un utilisateur
public function debloquer($id, Request $request)
{
    $user = User::find($id);

    if ($user) {
        $user->status = 'active';
        $user->save();
        return response()->json(['message' => 'Utilisateur débloqué']);
    } else {
        return response()->json(['message' => 'Utilisateur non trouvé'], 404);
    }
}

// Assign RFID à un utilisateur
public function assign(Request $request, $id)
{
    $validatedData = $request->validate([
        'rfid_code' => 'required|string|max:50',
    ]);

    // Vérifier si le rfid_code est déjà utilisé par un autre utilisateur
    $existingUser = User::where('rfid_code', $validatedData['rfid_code'])->first();
    if ($existingUser) {
        return response()->json(['message' => 'Carte déjà assignée'], 400);
    }

    $user = User::find($id);

    if ($user) {
        $user->rfid_code = $validatedData['rfid_code'];
        $user->save();
        return response()->json(['message' => 'Code RFID assigné', 'user' => $user]);
    } else {
        return response()->json(['message' => 'Utilisateur non trouvé'], 404);
    }
}


    // Désassigner un code RFID d'un utilisateur
    public function desassign($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->rfid_code = null;
            $user->save();
            return response()->json(['message' => 'Code RFID désassigné', 'user' => $user]);
        } else {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
    }
}