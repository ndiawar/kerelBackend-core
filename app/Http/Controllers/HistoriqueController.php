<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoriqueController extends Controller
{
    //
    public function getApiLogs()
    {
        $apiLogs = DB::table('api_logs')->get();
        return response()->json($apiLogs);
    }
}
