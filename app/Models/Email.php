<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Email extends Model
{
    use HasFactory;
    use Notifiable;
    protected $table='emails';
    public $timestamps = false;
    protected $primaryKey='id';
    protected $fillable=
    [
        "destino",
        "asunto",
        "detalle",
        "fecha_emision",
        "id_operador_minero",
        "estado"
    ];
    public function operadorMinero()
    {
        return $this->belongsTo(operador_minero::class,'id_operador_minero','id_operador_minero');
    }

}
