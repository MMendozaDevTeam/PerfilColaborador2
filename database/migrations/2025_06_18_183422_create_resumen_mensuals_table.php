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
        Schema::create('resumen_mensuals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID del usuario o colaborador
            $table->text('contenido');             // Texto generado por OpenAI
            $table->string('mes');                 // Ej: "2025-06" para identificar el mes
            $table->timestamps();

            $table->unique(['user_id', 'mes']);    // Un resumen por mes por usuario
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumen_mensuals');
    }
};
