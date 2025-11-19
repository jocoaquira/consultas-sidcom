<?php

namespace App\Http\Controllers;

use App\Models\TomaMuestra;
use App\Http\Requests\StoreTomaMuestraRequest;
use App\Http\Requests\UpdateTomaMuestraRequest;

class TomaMuestraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $actas=TomaMuestra::all();
        return view("toma_muestra.intervalo_actas", ["tomaDeMuestras" => $actas]);
    }
    public function actasMes(Request $request){
        $actas=TomaMuestra::where('acta','>=',$request->inicio)->where('acta','<=',$request->fin)->get();
        return view("toma_muestra.intervalo_actas", ["tomaDeMuestras" => $actas]);
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
    public function store(StoreTomaMuestraRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TomaMuestra $tomaMuestra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TomaMuestra $tomaMuestra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTomaMuestraRequest $request, TomaMuestra $tomaMuestra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TomaMuestra $tomaMuestra)
    {
        //
    }
}
