<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class operador_minero extends Model
{
    use HasFactory;
    use Notifiable;
    protected $table='operador_minero';
    public $timestamps='false';
    protected $primaryKey='id_operador_minero';
    protected $fillable =
    [
        "id_operador_minero",
        "razon_social",
        "nit",
        "nim",
        "fecha_exp_nim",
        "actor_minero",
        "nro_personalidad_juridica",
        "tipo_doc_cre_emp",
        "documento_creacion_empresa",
        "nro_matricula_funda",
        "fecha_exp_funda",
        "act_explotacion",
        "act_comercio_interno",
        "act_comercio_externo",
        "act_concentracion",
        "act_fundicion",
        "act_tostacion",
        "act_calcinacion",
        "act_refinacion",
        "tipo_act_mine_explo",
        "nro_cod_unico",
        "nro_cuadricula",
        "denominacion_area",
        "str_municipios",
        "direccion_oficina",
        "ofi_lat",
        "ofi_lon",
        "email_op_min",
        "tel_op_min",
        "fax_op_min",
        "cel_op_min",
        "estado_operador_minero",
        "nombre_rep_legal",
        "ci_rep_legal",
        "cel_rep_legal",
        "fecha_creacion",
        "fecha_actualizacion",
        "fecha_expiracion",
        "observaciones",
        "origen_mineral_defecto"
    ];
    public function usuario()
    {
        return $this->hasMany(usuario::class,'id_operador_minero');
    }
    public function formulario()
    {
        return $this->hasMany(Formulario::class,'id_operador_minero');
    }
    public function email()
    {
        return $this->hasMany(Email::class,'id_operador_minero');
    
    }

    public function routeNotificationForMail(Notification $notification): array|string
    {
        // Devolver solo la dirección de correo electrónico...
        return $this->email_op_min;
    }

}
