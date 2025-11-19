<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::delete('operadores/notificar/{id}',[App\Http\Controllers\OperadorMineroController::class, 'notificacion'])->name('operadores.notificar');
Route::resource("operadores", "App\Http\Controllers\OperadorMineroController");

Route::resource("usuarios", "App\Http\Controllers\UsuarioController");
Route::resource("toma-muestra", "App\Http\Controllers\TomaMuestraController");
