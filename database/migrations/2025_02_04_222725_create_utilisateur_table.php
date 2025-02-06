<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('utilisateur', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('telephone')->unique();
            $table->string('rfid_code')->unique()->nullable(); // Code RFID unique et optionnel
            $table->string('email')->unique()->nullable();     // Email unique et optionnel
            $table->enum('status', ['active', 'inactive'])->default('active'); // Statut par défaut "active"
            $table->enum('role', ['superadmin', 'user'])->default('user');     // Rôle par défaut "user"
            $table->string('code', 4)->unique(); // Code à 4 caractères généré automatiquement
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateur');
    }
};
