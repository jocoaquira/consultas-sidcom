<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    use HasFactory;
    protected $table='formulario';
    public $timestamps='false';
    protected $primaryKey='id_formulario';

  protected $fillable =
    [
        "id_formulario",
        "id_usuario",
        "id_operador_minero",
        "tipo_form_comercio",
        "lote",
        "tipo_presentacion",
        "ensacado",
        "peso_bruto",
        "tara",
        "humedad",
        "merma",
        "str_minerales",
        "str_ley",
        "str_unidad",
        "str_municipio_or",
        "tipo_destino",
        "comprador",
        "planta_tratamiento",
        "str_mun_desti",
        "tipo_transporte",
        "tara_volqueta",
        "placa",
        "color",
        "nombre_conductor",
        "licencia",
        "nro_vagon",
        "empresa_ferrea",
        "fecha_ferrea",
        "hr_ferrea",
        "estado_formulario",
        "fecha_creacion",
        "justificacion_act",
        "idm03",
        "nro_fact",
        "laboratorio",
        "cod_analisis",
        "acta",
        "aduana",
        "cod_aduana",
        "pais_destino",
        "observacion",
        "num_form",
        "fecha_vencimiento",
        "n_actualizacion",
        "justificacion_anul",
        "str_ley_reducida",
        "traslado_mineral",
        "nro_viajes",
    ];
    public function tomaMuestra()
    {
        return $this->belongsTo(TomaMuestra::class,'acta','acta');
    }
    public function operadorMinero()
    {
        return $this->belongsTo(operador_minero::class,'id_operador_minero','id_operador_minero');
    }
    /**
     * Scope para formularios entre fechas
     */
    public function scopeBetweenDates(Builder $query, $startDate, $endDate)
    {
        return $query->whereBetween('fecha_creacion', [$startDate, $endDate]);
    }

    /**
     * Scope para formularios con estados específicos
     */
    public function scopeWithEstados(Builder $query, array $estados = ['1', '2'])
    {
        return $query->whereIn('estado_formulario', $estados);
    }

    /**
     * Scope para tipo de formulario comercial
     */
    public function scopeTipoComercio(Builder $query, $tipo = 'I')
    {
        return $query->where('tipo_form_comercio', $tipo);
    }

     /**
     * Scope para formularios emitidos (estado 1)
     */
    public function scopeEmitidos($query)
    {
        return $query->where('estado_formulario', '1');
    }

    /**
     * Scope para formularios vencidos (estado 2)
     */
    public function scopeVencidos($query)
    {
        return $query->where('estado_formulario', '2');
    }

    /**
     * Scope para formularios activos (1 y 2)
     */
    public function scopeActivos($query)
    {
        return $query->whereIn('estado_formulario', ['1', '2']);
    }

    /**
     * Scope para comercio interno
     */
    public function scopeComercioInterno($query)
    {
        return $query->where('tipo_form_comercio', 'I');
    }

    /**
     * Scope para comercio externo
     */
    public function scopeComercioExterno($query)
    {
        return $query->where('tipo_form_comercio', 'E');
    }

    /**
     * Scope para la última semana (día anterior en adelante)
     */
public function scopeLastWeekFromYesterday($query)
{
    $ayer = now()->subDay();     // ya está en America/La_Paz
    $inicio = $ayer->copy()->subDays(6);

    logger()->info('FECHAS LOCAL', [
        'inicio' => $inicio->toDateTimeString(),
        'ayer'   => $ayer->toDateTimeString(),
    ]);

    return $query->whereBetween('fecha_creacion', [
        $inicio->format('Y-m-d 00:00:00'),
        $ayer->format('Y-m-d 23:59:59')
    ]);
}

}
