<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('annonce_id')->constrained()->onDelete('cascade');
            $table->string('cv_path');
            $table->string('lettre_motivation_path');
            $table->enum('statut', ['en_attente', 'en_cours', 'entretien', 'accepte', 'refuse'])->default('en_attente');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('candidatures');
    }

};
