<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RFIDController extends Controller
{
    public function login(Request $request)
    {
        $rfid = $request->input('rfid');

        // Vérifiez si l'utilisateur existe avec ce RFID
        $user = User::where('rfid', $rfid)->first();

        if ($user) {
            // Connectez l'utilisateur
            Auth::login($user);

            return response()->json(['success' => true, 'message' => 'Connexion réussie']);
        } else {
            return response()->json(['success' => false, 'message' => 'Carte RFID non reconnue'], 401);
        }
    }
}