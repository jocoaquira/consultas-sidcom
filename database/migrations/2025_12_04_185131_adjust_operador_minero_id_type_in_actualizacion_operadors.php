<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('actualizacion_operadors')) {
            // Cambiar el tipo según lo que veas en tinker
            Schema::table('actualizacion_operadors', function (Blueprint $table) {
                // Si id_operador_minero es INT(11)
                $table->unsignedInteger('operador_minero_id')->change();

                // O si es BIGINT(20)
                // $table->unsignedBigInteger('operador_minero_id')->change();
            });
        }
    }

    public function down(): void
    {
        // Revertir si es necesario
        Schema::table('actualizacion_operadors', function (Blueprint $table) {
            $table->unsignedBigInteger('operador_minero_id')->change();
        });
    }
};
