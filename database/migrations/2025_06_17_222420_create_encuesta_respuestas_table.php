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
      Schema::create('encuesta_respuestas', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('user_id');
          $table->text('pregunta');
          $table->text('respuesta');
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encuesta_respuestas');
    }
};
