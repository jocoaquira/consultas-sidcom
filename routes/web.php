<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActualizacionOperadorController;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\BloqueosOperadorController;
use App\Http\Controllers\OperadorMineroController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TomaMuestraController;
use Resend\Laravel\Facades\Resend;

// RUTA PRINCIPAL
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

// ==================== RUTAS OPERADORES MINEROS ====================
// LISTADO PRINCIPAL
Route::get('/operadores', [OperadorMineroController::class, 'index'])->name('operadores.index');

// NOTIFICACIONES - RUTAS NUEVAS Y SIMPLES
Route::post('/operadores/{id}/notificar-email', [OperadorMineroController::class, 'notificarEmail'])->name('operadores.notificarEmail');
Route::get(
    '/operadores/{id}/whatsapp-mensaje',
    [OperadorMineroController::class, 'obtenerMensajeWhatsApp']
)->name('operadores.whatsapp.mensaje');

Route::post(
    '/operadores/{id}/registrar-whatsapp',
    [OperadorMineroController::class, 'registrarWhatsAppEnvio']
)->name('operadores.whatsapp.registrar');

// RUTAS ANTIGUAS (para compatibilidad)
Route::delete('/operadores/notificar/{id}', [OperadorMineroController::class, 'notificacion'])->name('operadores.notificar');

// OTRAS RUTAS CRUD
Route::get('/operadores/create', [OperadorMineroController::class, 'create'])->name('operadores.create');
Route::post('/operadores', [OperadorMineroController::class, 'store'])->name('operadores.store');
Route::get('/operadores/{operador_minero}', [OperadorMineroController::class, 'show'])->name('operadores.show');
Route::get('/operadores/{operador_minero}/edit', [OperadorMineroController::class, 'edit'])->name('operadores.edit');
Route::put('/operadores/{operador_minero}', [OperadorMineroController::class, 'update'])->name('operadores.update');
Route::delete('/operadores/{operador_minero}', [OperadorMineroController::class, 'destroy'])->name('operadores.destroy');
// ==================== FIN RUTAS OPERADORES ====================

// OTRAS RUTAS DEL SISTEMA
Route::resource('usuarios', UsuarioController::class);
Route::resource('toma-muestra', TomaMuestraController::class);
Route::resource('actualizacion-operadors', ActualizacionOperadorController::class);
Route::resource('bloqueo-operadors', BloqueosOperadorController::class);

// RUTAS ADICIONALES
Route::post('actualizacion-operadors/importar', [ActualizacionOperadorController::class, 'importarDesdeOperadores'])->name('actualizacion-operadors.importar');
Route::get('actualizacion-operadors/por-operador/{operadorId}', [ActualizacionOperadorController::class, 'porOperador'])->name('actualizacion-operadors.por-operador');
Route::get('bloqueo-operadors/historial/{operadorId}', [BloqueosOperadorController::class, 'historial'])->name('bloqueo-operadors.historial');
Route::post('bloqueo-operadors/bloquear-rapido', [BloqueosOperadorController::class, 'bloquearRapido'])->name('bloqueo-operadors.bloquear-rapido');
Route::post('bloqueo-operadors/desbloquear-rapido', [BloqueosOperadorController::class, 'desbloquearRapido'])->name('bloqueo-operadors.desbloquear-rapido');


// Rutas para bloqueo de operadores
Route::get('bloqueo-operadors/historial/{operadorId}', [BloqueosOperadorController::class, 'historial'])->name('bloqueo-operadors.historial');
Route::post('bloqueo-operadors/bloquear-rapido', [BloqueosOperadorController::class, 'bloquearRapido'])->name('bloqueo-operadors.bloquear-rapido');
Route::post('bloqueo-operadors/desbloquear-rapido', [BloqueosOperadorController::class, 'desbloquearRapido'])->name('bloqueo-operadors.desbloquear-rapido');



// ESTADÍSTICAS
Route::prefix('estadisticas')->group(function () {
    Route::get('/', [EstadisticasController::class, 'index'])->name('estadisticas.index');
    Route::post('/consulta', [EstadisticasController::class, 'consultaPersonalizada'])->name('estadisticas.consulta');
    Route::get('/semana', [EstadisticasController::class, 'consultaSemana'])->name('estadisticas.semana');
});



// RUTA DEBUG
Route::get('/debug-operadores', function() {
    return response()->json([
        'rutas_operadores' => [
            'GET /operadores' => 'Listado',
            'POST /operadores/enviar-email/{id}' => 'Enviar email',
            'GET /operadores/mensaje-whatsapp/{id}' => 'Obtener mensaje WhatsApp',
            'POST /operadores/registrar-whatsapp/{id}' => 'Registrar WhatsApp'
        ]
    ]);
});
