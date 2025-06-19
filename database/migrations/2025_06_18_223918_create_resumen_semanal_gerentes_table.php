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
        Schema::create('resumen_semanal_gerentes', function (Blueprint $table) {
           $table->id();
           $table->unsignedBigInteger('user_id');
           $table->date('semana_inicio');
           $table->date('semana_fin');
           $table->json('contenido'); // JSON con informe + recomendaciones
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumen_semanal_gerentes');
    }
};
