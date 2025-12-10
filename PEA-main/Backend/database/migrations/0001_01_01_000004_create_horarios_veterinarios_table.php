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
        Schema::create('horarios_veterinarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('veterinario_id')->constrained('veterinarios')->onDelete('cascade');
            $table->integer('dia_semana'); // 0-6 (Domingo a Sábado)
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->boolean('es_activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios_veterinarios');
    }
};
