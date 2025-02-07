<?php

use App\Http\Controllers\UtilisateurController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Routes CRUD pour Utilisateur
Route::get('/utilisateurs', [UtilisateurController::class, 'index']);
Route::get('/utilisateurs/{id}', [UtilisateurController::class, 'show']);
Route::post('/utilisateurs', [UtilisateurController::class, 'store']);
Route::put('/utilisateurs/{id}', [UtilisateurController::class, 'update']);
Route::delete('/utilisateurs/{id}', [UtilisateurController::class, 'destroy']);

// Routes pour login et logout
Route::post('/utilisateurs/login', [UtilisateurController::class, 'loginByCode']);
Route::post('/utilisateurs/logout', [UtilisateurController::class, 'logout']);
Route::post('/utilisateurs/loginByCard', [UtilisateurController::class, 'loginByCard']);

// Routes pour bloquer et débloquer un utilisateur
Route::put('/utilisateurs/bloquer/{id}', [UtilisateurController::class, 'bloquer']);
Route::put('/utilisateurs/debloquer/{id}', [UtilisateurController::class, 'debloquer']);

// Routes pour assigner et désassigner un code RFID
Route::put('/utilisateurs/assign/{id}', [UtilisateurController::class, 'assign']);
Route::put('/utilisateurs/desassign/{id}', [UtilisateurController::class, 'desassign']);
