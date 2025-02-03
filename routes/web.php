<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
 //routes pour la connexion par carte rfid
use App\Http\Controllers\AuthController;

Route::post('/login-rfid', [AuthController::class, 'loginWithRFID']);
