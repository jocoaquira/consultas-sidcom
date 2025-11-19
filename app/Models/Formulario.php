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

}
