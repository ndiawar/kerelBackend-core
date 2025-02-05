<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Utilisateur;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Routes CRUD pour Utilisateur
Route::get('/utilisateurs', [Utilisateur::class, 'index']);
Route::get('/utilisateurs/{id}', [Utilisateur::class, 'show']);
Route::post('/utilisateurs', [Utilisateur::class, 'store']);
Route::put('/utilisateurs/{id}', [Utilisateur::class, 'update']);
Route::delete('/utilisateurs/{id}', [Utilisateur::class, 'destroy']);

// Routes pour login et logout
Route::post('/utilisateurs/login', [Utilisateur::class, 'loginByCode']);
Route::post('/utilisateurs/logout', [Utilisateur::class, 'logout']);

// Routes pour bloquer et débloquer un utilisateur
Route::put('/utilisateurs/bloquer', [Utilisateur::class, 'bloquer']);
Route::put('/utilisateurs/debloquer', [Utilisateur::class, 'debloquer']);