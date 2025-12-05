<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActualizacionOperador extends Model
{
    use HasFactory;

    // Especificar tabla
    protected $table = 'actualizacion_operadors';

    // Campos asignables masivamente
    protected $fillable = [
        'operador_minero_id',
        'tipo_actualizacion',
        'fecha',
        'observaciones',
    ];

    // Casts de tipos de datos
    protected $casts = [
        'fecha' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * RELACIÓN CON OPERADOR MINERO
     */
    public function operadorMinero(): BelongsTo
    {
        return $this->belongsTo(
            operador_minero::class,
            'operador_minero_id',
            'id_operador_minero'
        );
    }

    /**
     * Accessor: Obtener tipos como array
     */
    public function getTiposArrayAttribute(): array
    {
        if (empty($this->tipo_actualizacion)) {
            return [];
        }
        return explode(',', $this->tipo_actualizacion);
    }

    /**
     * Mutator: Guardar tipos desde array
     */
    public function setTipoActualizacionAttribute($value): void
    {
        if (is_array($value)) {
            // Filtrar valores vacíos y convertir a string separado por comas
            $filtered = array_filter($value);
            $this->attributes['tipo_actualizacion'] = implode(',', $filtered);
        } else {
            $this->attributes['tipo_actualizacion'] = $value;
        }
    }

    /**
     * Verificar si tiene un tipo específico
     */
    public function tieneTipo(string $tipo): bool
    {
        if (empty($this->tipo_actualizacion)) {
            return false;
        }

        $tipos = $this->tipos_array;
        return in_array($tipo, $tipos);
    }

    /**
     * Obtener tipos como string formateado
     */
    public function getTiposFormateadosAttribute(): string
    {
        if (empty($this->tipo_actualizacion)) {
            return 'Sin tipo';
        }

        $tipos = $this->tipos_array;
        $formateados = [];

        foreach ($tipos as $tipo) {
            $formateados[] = $this->getEtiquetaTipo($tipo);
        }

        return implode(', ', $formateados);
    }

    /**
     * Obtener etiqueta para un tipo específico
     */
    public function getEtiquetaTipo(string $tipo): string
    {
        $tipos = self::getTiposActualizacion();
        return $tipos[$tipo] ?? $tipo;
    }

    /**
     * Scope para filtrar por tipo
     */
    public function scopePorTipo($query, string $tipo)
    {
        return $query->where('tipo_actualizacion', 'LIKE', "%{$tipo}%");
    }

    /**
     * Scope para filtrar por fecha desde
     */
    public function scopeFechaDesde($query, string $fecha)
    {
        return $query->where('fecha', '>=', $fecha);
    }

    /**
     * Scope para filtrar por fecha hasta
     */
    public function scopeFechaHasta($query, string $fecha)
    {
        return $query->where('fecha', '<=', $fecha);
    }

    /**
     * Scope para filtrar por operador
     */
    public function scopePorOperador($query, int $operadorId)
    {
        return $query->where('operador_minero_id', $operadorId);
    }

    /**
     * Obtener opciones de tipo de actualización
     */
    public static function getTiposActualizacion(): array
    {
        return [
            'SEPREC' => 'SEPREC',
            'RUEX' => 'RUEX',
            'NIM' => 'NIM',
            'CARTA_PODER' => 'Carta de Poder',
            'IDOM' => 'IDOM',
            'NIT' => 'NIT',
            'REPRESENTANTE_LEGAL' => 'Representante Legal',
            'DIRECCION' => 'Dirección',
            'CONTRATO_DE_ARRENDAMIENTO' => 'Contrato de Arrendamiento',
            'OTROS' => 'Otros',
        ];
    }

    /**
     * Obtener colores para cada tipo (para badges)
     */
    public static function getColoresTipos(): array
    {
        return [
            'SEPREC' => 'primary',
            'RUEX' => 'success',
            'NIM' => 'info',
            'CARTA_PODER' => 'warning',
            'IDENTIFICACION' => 'secondary',
            'NIT' => 'dark',
            'REPRESENTANTE_LEGAL' => 'warning',
            'DIRECCION' => 'secondary',
            'CONTRATO_DE_ARRENDAMIENTO' => 'dark',
            'OTROS' => 'light',
        ];
    }

    /**
     * Obtener color para un tipo específico
     */
    public function getColorTipo(string $tipo): string
    {
        $colores = self::getColoresTipos();
        return $colores[$tipo] ?? 'secondary';
    }

    /**
     * Obtener badges HTML para los tipos
     */
    public function getBadgesTiposAttribute(): string
    {
        if (empty($this->tipo_actualizacion)) {
            return '<span class="badge bg-secondary">Sin tipo</span>';
        }

        $badges = [];
        $tipos = $this->tipos_array;

        foreach ($tipos as $tipo) {
            $color = $this->getColorTipo($tipo);
            $etiqueta = $this->getEtiquetaTipo($tipo);
            $badges[] = "<span class='badge bg-{$color}'>{$etiqueta}</span>";
        }

        return implode(' ', $badges);
    }

    /**
     * Obtener operadores con actualizaciones
     */
    public static function getOperadoresConActualizaciones()
    {
        return self::select('operador_minero_id')
            ->with('operadorMinero')
            ->distinct()
            ->get()
            ->pluck('operadorMinero');
    }

    /**
     * Obtener estadísticas por tipo
     */
    public static function getEstadisticasPorTipo()
    {
        $actualizaciones = self::all();
        $estadisticas = [];

        foreach (self::getTiposActualizacion() as $key => $value) {
            $estadisticas[$key] = [
                'nombre' => $value,
                'count' => 0
            ];
        }

        foreach ($actualizaciones as $actualizacion) {
            $tipos = $actualizacion->tipos_array;
            foreach ($tipos as $tipo) {
                if (isset($estadisticas[$tipo])) {
                    $estadisticas[$tipo]['count']++;
                }
            }
        }

        return $estadisticas;
    }

    /**
     * Importar desde operador_minero (para IDOM/REGISTRO)
     */
    public static function importarDesdeOperadores(int $anio = 2025)
    {
        $contador = 0;

        // 1. Operadores con fecha_actualizacion en noviembre del año especificado (IDOM)
        $operadoresIDOM = operador_minero::whereNotNull('fecha_actualizacion')
            ->whereMonth('fecha_actualizacion', 11)
            ->whereYear('fecha_actualizacion', $anio)
            ->get();

        foreach ($operadoresIDOM as $operador) {
            $existe = self::where('operador_minero_id', $operador->id_operador_minero)
                ->whereDate('fecha', $operador->fecha_actualizacion)
                ->exists();

            if (!$existe) {
                self::create([
                    'operador_minero_id' => $operador->id_operador_minero,
                    'tipo_actualizacion' => 'SEPREC,RUEX,NIM',
                    'fecha' => $operador->fecha_actualizacion,
                    'observaciones' => "Importado automáticamente - IDOM Noviembre {$anio}",
                ]);
                $contador++;
            }
        }

        // 2. Operadores con fecha_creacion en noviembre del año especificado (REGISTRO)
        $operadoresRegistro = operador_minero::whereMonth('fecha_creacion', 11)
            ->whereYear('fecha_creacion', $anio)
            ->get();

        foreach ($operadoresRegistro as $operador) {
            $existe = self::where('operador_minero_id', $operador->id_operador_minero)
                ->whereDate('fecha', $operador->fecha_creacion)
                ->exists();

            if (!$existe) {
                self::create([
                    'operador_minero_id' => $operador->id_operador_minero,
                    'tipo_actualizacion' => 'SEPREC,NIM',
                    'fecha' => $operador->fecha_creacion,
                    'observaciones' => "Importado automáticamente - Registro Noviembre {$anio}",
                ]);
                $contador++;
            }
        }

        return $contador;
    }
}
