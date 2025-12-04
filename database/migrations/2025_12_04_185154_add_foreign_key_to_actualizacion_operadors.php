<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('actualizacion_operadors') &&
            Schema::hasTable('operador_minero') &&
            Schema::hasColumn('operador_minero', 'id_operador_minero')) {

            // Primero eliminar índice existente si hay
            Schema::table('actualizacion_operadors', function (Blueprint $table) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableIndexes('actualizacion_operadors');

                if (isset($indexes['actualizacion_operadors_operador_minero_id_index'])) {
                    $table->dropIndex(['operador_minero_id']);
                }
            });

            // Agregar foreign key
            Schema::table('actualizacion_operadors', function (Blueprint $table) {
                $table->foreign('operador_minero_id')
                      ->references('id_operador_minero')
                      ->on('operador_minero')
                      ->onDelete('cascade')
                      ->onUpdate('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('actualizacion_operadors')) {
            Schema::table('actualizacion_operadors', function (Blueprint $table) {
                $table->dropForeign(['operador_minero_id']);
            });
        }
    }
};
