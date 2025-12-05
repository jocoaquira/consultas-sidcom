<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActualizacionOperadorController;
use App\Http\Controllers\EstadisticasController;

Route::get('/', function () {
    return view('welcome');
});
Route::delete('operadores/notificar/{id}',[App\Http\Controllers\OperadorMineroController::class, 'notificacion'])->name('operadores.notificar');
Route::resource("operadores", "App\Http\Controllers\OperadorMineroController");

Route::resource("usuarios", "App\Http\Controllers\UsuarioController");
Route::resource("toma-muestra", "App\Http\Controllers\TomaMuestraController");

// Ruta de inicio (puedes cambiar '/' por lo que necesites)
Route::get('/', function () {
    return redirect()->route('actualizacion-operadors.index');
});

// Rutas CRUD para ActualizacionOperador (TODAS)
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
