<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Vider la table utilisateur avant d'ajouter de nouvelles données
        User::truncate();

        // Instancier Faker pour générer des données aléatoires
        $faker = Faker::create('fr_FR');  // Nous utilisons la locale française pour générer des données pertinentes au Sénégal.

        // Liste de prénoms et noms typiques sénégalais
        $noms = ['Diop', 'Senghor', 'Diatta', 'Ba', 'Sy', 'Mbaye', 'Faye', 'Thiam', 'Gueye', 'Ndour'];
        $prenoms = ['Mamadou', 'Aissatou', 'Ousmane', 'Mariama', 'Ibrahime', 'Fatou', 'Cheikh', 'Ndiaye', 'Sadio', 'Adama'];

        // Ajouter un seul superadmin (s'il n'existe pas déjà)
        if (User::where('role', 'superadmin')->count() == 0) {
            User::create([
                'nom' => $faker->randomElement($noms), // Choisir un nom sénégalais au hasard
                'prenom' => $faker->randomElement($prenoms), // Choisir un prénom sénégalais au hasard
                'telephone' => '+221' . rand(700000000, 779999999), // Numéro de téléphone
                'email' => $faker->unique()->safeEmail, // Email unique
                'status' => 'active', // Statut actif
                'role' => 'superadmin', // Rôle superadmin
                'code' => str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT), // Code à 4 chiffres
                'rfid_code' => strtoupper(uniqid('RFID')), // Code RFID unique
                'api_token' => Str::random(60), // API token
            ]);
        }

        // Créer 29 autres utilisateurs avec des données aléatoires
        foreach (range(1, 29) as $index) {
            // Générer un code à 4 chiffres et un code RFID aléatoires
            $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT); // Code à 4 chiffres
            $rfid_code = strtoupper(uniqid('RFID')); // Code RFID (unique et aléatoire)

            // Générer un téléphone avec l'indicatif du Sénégal
            $telephone = '+221' . rand(700000000, 779999999);  // Téléphone valide avec l'indicatif du Sénégal (+221)

            // Insérer un utilisateur avec des données aléatoires
            User::create([
                'nom' => $faker->randomElement($noms), // Choisir un nom sénégalais au hasard
                'prenom' => $faker->randomElement($prenoms), // Choisir un prénom sénégalais au hasard
                'telephone' => $telephone, // Numéro de téléphone
                'email' => $faker->unique()->safeEmail, // Email unique
                'status' => 'active', // Statut actif
                'role' => $faker->randomElement(['superadmin', 'user']), // Rôle (superadmin ou user)
                'code' => $code, // Code à 4 chiffres
                'rfid_code' => $rfid_code, // Code RFID
                'api_token' => Str::random(60), // API token (généré aléatoirement)
            ]);
        }
    }
}
