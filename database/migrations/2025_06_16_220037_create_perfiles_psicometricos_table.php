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
        Schema::create('perfiles_psicometricos', function (Blueprint $table) {
            $table->id();

            // Información del colaborador (puede estar relacionada a users si lo deseas)
            $table->unsignedBigInteger('user_id')->nullable(); // opcional
            $table->integer('edad');
            $table->enum('sexo', ['Masculino', 'Femenino', 'No binario', 'Otro']);
            $table->string('nivel_educativo');
            $table->integer('antiguedad_anios'); // puede ser en meses también
            $table->string('area');
            $table->string('estado_civil');

            // Resultados analizados
            $table->string('respuesta_mentalidad');      // Creativa, Zona de confort, Intermedia
            $table->string('respuesta_comunicacion');    // León, Pavo Real, Delfín, Búho


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfiles_psicometricos');
    }
};
