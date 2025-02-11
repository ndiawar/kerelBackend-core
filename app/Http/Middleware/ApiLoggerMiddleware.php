<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;


class ApiLoggerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Récupérer l'utilisateur authentifié
       $user = null;
          
            
        $user = JWTAuth::parseToken()->authenticate();

        $actions = [
            '#^api/utilisateurs$#' => 'a créé un utilisateur',
            '#^api/utilisateurs/\d+$#' => 'a modifié les données d\'un utilisateur',
            '#^api/utilisateurs/logout$#' => 'S\'est déconnecté',
            '#^api/utilisateurs/bloquer/\d+$#' => 'Blocage de l\'utilisateur',
            '#^api/utilisateurs/debloquer/\d+$#' => 'Déblocage de l\'utilisateur',
            '#^api/utilisateurs/assign/\d+$#' => 'A assigné une carte RFID',
            '#^api/utilisateurs/desassign/\d+$#' => 'A désassigné une carte RFID',
        ];
        

        // Déterminer l'action en fonction de l'URL
        $action = 'Action inconnue';
        foreach ($actions as $pattern => $description) {
            if (preg_match($pattern, $request->path())) {
                $action = $description;
                break;
            }
        }

        // Enregistrez dans la table api_logs
        DB::table('api_logs')->insert([
            'user_id'    => $user ? $user->id : null,
            'prenom'     => $user ? $user->prenom : null,
            'nom'     => $user ? $user->nom : null,
            'Action'   => $action,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $response;
    }
}
