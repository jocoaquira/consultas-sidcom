<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActualizacionOperadorController;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\BloqueosOperadorController;
use App\Http\Controllers\OperadorMineroController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TomaMuestraController;

// RUTA PRINCIPAL ÚNICA - Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

// Rutas de Operadores Mineros
Route::delete('operadores/notificar/{id}', [OperadorMineroController::class, 'notificacion'])->name('operadores.notificar');
Route::resource('operadores', OperadorMineroController::class);

// Rutas de Usuarios
Route::resource('usuarios', UsuarioController::class);

// Rutas de Toma de Muestra
Route::resource('toma-muestra', TomaMuestraController::class);

// Rutas CRUD para ActualizacionOperador
Route::resource('actualizacion-operadors', ActualizacionOperadorController::class);

// Ruta adicional para importar desde operador_minero
Route::post('actualizacion-operadors/importar', [ActualizacionOperadorController::class, 'importarDesdeOperadores'])
     ->name('actualizacion-operadors.importar');

// Ruta para ver por operador específico
Route::get('actualizacion-operadors/por-operador/{operadorId}', [ActualizacionOperadorController::class, 'porOperador'])
     ->name('actualizacion-operadors.por-operador');

// Rutas de estadísticas
Route::prefix('estadisticas')->group(function () {
    Route::get('/', [EstadisticasController::class, 'index'])->name('estadisticas.index');
    Route::post('/consulta', [EstadisticasController::class, 'consultaPersonalizada'])->name('estadisticas.consulta');
    Route::get('/semana', [EstadisticasController::class, 'consultaSemana'])->name('estadisticas.semana');
});

// Ruta alternativa si quieres también poder acceder a actualizaciones desde la raíz
// Route::get('/actualizaciones', function () {
//     return redirect()->route('actualizacion-operadors.index');
// })->name('actualizaciones');

// Rutas para bloqueo de operadores
Route::resource('bloqueo-operadors', BloqueosOperadorController::class);

// Rutas adicionales
Route::get('bloqueo-operadors/historial/{operadorId}', [BloqueosOperadorController::class, 'historial'])
    ->name('bloqueo-operadors.historial');

Route::post('bloqueo-operadors/bloquear-rapido', [BloqueosOperadorController::class, 'bloquearRapido'])
    ->name('bloqueo-operadors.bloquear-rapido');

Route::post('bloqueo-operadors/desbloquear-rapido', [BloqueosOperadorController::class, 'desbloquearRapido'])
    ->name('bloqueo-operadors.desbloquear-rapido');
