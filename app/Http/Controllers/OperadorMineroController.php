<?php

namespace App\Http\Controllers;

use App\Models\operador_minero;
use App\Http\Requests\Storeoperador_mineroRequest;
use App\Http\Requests\Updateoperador_mineroRequest;
use App\Notifications\NotificacionOperador;
use App\Models\Email;
use Request;

class OperadorMineroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $operador=operador_minero::get();
        return view("operador_minero.operador_minero", ["productos" => $operador]);
    }
    public function mensajeOperador(operador_minero $operador):string{
        $mensaje='';
        if($operador->fecha_expiracion<now()){
            $mensaje='IDOM vencido en fecha, '.date("d-m-Y", strtotime($operador->fecha_expiracion));
        }
        else{
            if($operador->fecha_exp_nim<now()){
                $mensaje=' NIM O NIAR vencido en fecha, '.date("d-m-Y", strtotime($operador->fecha_exp_nim)).', ';
            }
            if($operador->fecha_exp_funda<now() and $operador->nro_personalidad_juridica==''){
                $mensaje=$mensaje.' SEPREC vencido en fecha, '.date("d-m-Y", strtotime($operador->fecha_exp_funda));
            }
        }
        return $mensaje;
    }

    public function notificacion($id){
        try {
            $operador=operador_minero::find($id);
            //$operador->notify(new NotificacionOperador('DOCUMENTOS VENCIDOS',$operador));
            $email=new Email();
            $email->destino='wberlim91@gmail.com';//$operador->email_op_min;
            $email->asunto='DOCUMENTOS VENCIDOS';
            $email->detalle=$this->mensajeOperador($operador);
            $email->fecha_emision=now();
            $email->id_operador_minero=$operador->id_operador_minero;
            $email->estado=1;
            $email->save();

        /*
        "destino",
        "asunto",
        "detalle",
        "fecha_emision",
        "id_operador_minero",
        "estado"
        */
             return redirect()->route('operadores.index')->with('success', 'Enviado con exito.');
        } catch (\Exception $e) {
            //return 'error';
            return redirect()->route('operadores.index')->with('error', 'No se pudo enviar.');
        }
        
        //return redirect()->back();
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Storeoperador_mineroRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(operador_minero $operador_minero)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(operador_minero $operador_minero)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updateoperador_mineroRequest $request, operador_minero $operador_minero)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(operador_minero $operador_minero)
    {
        //
    }
}
