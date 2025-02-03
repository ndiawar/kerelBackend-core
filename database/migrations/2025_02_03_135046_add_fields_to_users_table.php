<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Ajoute les nouveaux champs
            $table->string('email')->unique(); // Ajout de l'email
            $table->enum('status', ['active', 'inactive'])->default('active'); // Ajout du statut
            $table->enum('role', ['superadmin', 'user'])->default('user'); // Ajout du rôle
            $table->string('code')->unique(); // Ajout du code
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprime les nouveaux champs si la migration est annulée
            $table->dropColumn(['email', 'status', 'role', 'code']);
        });
    }
}
