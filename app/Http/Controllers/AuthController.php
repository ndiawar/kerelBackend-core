<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class AuthController extends Controller
{
    public function loginWithRFID(Request $request)
    {
        $request->validate([
            'rfid_code' => 'required|string'
        ]);

        $user = User::where('rfid_code', $request->rfid_code)->first();

        if (!$user) {
            return response()->json(['message' => 'Carte RFID non reconnue'], 401);
        }

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => $user
        ]);
    }
}


