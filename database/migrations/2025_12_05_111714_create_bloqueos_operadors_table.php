<?php
// database/migrations/xxxx_xx_xx_create_bloqueo_operadors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bloqueo_operadors', function (Blueprint $table) {
            $table->id();

            // Relación con operador minero
            $table->unsignedBigInteger('operador_minero_id');

            // Campos principales
            $table->enum('estado', ['activo', 'bloqueado'])->default('activo');
            $table->text('motivo');
            $table->date('fecha');


            $table->timestamps();

            // Índices
            $table->index('operador_minero_id');
            $table->index('fecha');
            $table->index('estado');

        });
    }

    public function down(): void
    {
        //Schema::dropIfExists('bloqueo_operadors');
    }
};
