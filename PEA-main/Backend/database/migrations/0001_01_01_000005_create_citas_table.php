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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mascota_id')->constrained('mascotas')->onDelete('cascade');
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('restrict');
            $table->foreignId('veterinario_id')->nullable()->constrained('veterinarios')->onDelete('set null');
            $table->date('fecha');
            $table->time('hora');
            $table->enum('estado', ['pendiente', 'confirmada', 'rechazada', 'completada'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->text('razon_rechazo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
