<?php
// app/Models/BloqueoOperador.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloqueosOperador extends Model
{
    use HasFactory;

    protected $table = 'bloqueo_operadors';

    protected $fillable = [
        'operador_minero_id',
        'estado',
        'motivo',
        'fecha'
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relación con operador minero
    public function operador()
    {
        return $this->belongsTo(operador_minero::class, 'operador_minero_id', 'id_operador_minero');
    }

    // Scope para operadores bloqueados
    public function scopeBloqueados($query)
    {
        return $query->where('estado', 'bloqueado');
    }

    // Scope para operadores activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    // Accesor para estado formateado
    public function getEstadoFormateadoAttribute()
    {
        return $this->estado === 'bloqueado' ? 'BLOQUEADO' : 'ACTIVO';
    }

    // Accesor para color según estado
    public function getEstadoColorAttribute()
    {
        return $this->estado === 'bloqueado' ? 'danger' : 'success';
    }

    // Accesor para icono según estado
    public function getEstadoIconoAttribute()
    {
        return $this->estado === 'bloqueado' ? 'bi-lock-fill' : 'bi-unlock-fill';
    }

    // Accesor para fecha formateada
    public function getFechaFormateadaAttribute()
    {
        return $this->fecha ? $this->fecha->format('d/m/Y') : '';
    }
}
