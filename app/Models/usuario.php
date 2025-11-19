<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class usuario extends Model
{
    use HasFactory;
    use Notifiable;
    protected $table='usuario';
    public $timestamps='false';
    protected $primaryKey='id_usuario';
    protected $fillable=
    [
        "id_usuario",
        "email",
        "password",
        "nivel",
        "nombres",
        "apellidos",
        "ci",
        "cel",
        "estado_usuario",
        "id_operador_minero",
        "pass_gen"
    ];
    public function operadorMinero()
    {
        return $this->belongsTo(operador_minero::class,'id_operador_minero','id_operador_minero');
    }
}
