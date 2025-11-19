<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TomaMuestra extends Model
{
    use HasFactory;
    use Notifiable;
    protected $table='toma_de_muestra';
    public $timestamps='false';
    protected $primaryKey='nro';
    protected $fillable=
    [
        "nro",
        "fecha",
        "operador_minero",
        "mineral_y_o_metal",
        "ley",
        "peso_neto",
        "departamento",
        "acta"
    ];
    public function formulario()
    {
        return $this->hasMany(formulario::class,'acta','acta');
    }
}
