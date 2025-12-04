<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('actualizacion_operadors')) {
            Schema::create('actualizacion_operadors', function (Blueprint $table) {
                $table->id();

                // Usa el MISMO tipo que id_operador_minero (probablemente int)
                $table->unsignedInteger('operador_minero_id'); // Cambia esto según el tipo real

                $table->text('tipo_actualizacion')->nullable();
                $table->date('fecha');
                $table->text('observaciones')->nullable();
                $table->timestamps();

                // Solo índice por ahora
                $table->index('operador_minero_id');
                $table->index('fecha');
            });
        }
    }

    public function down(): void
    {
        // Schema::dropIfExists('actualizacion_operadors');
    }
};
