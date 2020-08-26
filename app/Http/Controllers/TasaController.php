<?php

namespace App\Http\Controllers;

use App\Tasa;
use Illuminate\Http\Request;

class TasaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title='Crear Tasa o Margen de ganancia';
        $tasas = Tasa::get();

        return view('ventas.tasa.index', compact('title','tasas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'                 => 'required|max:20',
            'tasa'                   => 'required',
            'porcentaje_ganancia'    => 'required',
            'estado'                 => 'required'
        ]);

        //llenamos la variable $role para luego guardarla
        $role = Tasa::create($request->all());


        return redirect()
        ->route('tasa.index')
        ->with('status_success', 'Tasa creada exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return 'este des show';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $this->authorize('update', [$user, ['user.edit', 'userown.edit']]);
        $title='Editar Tasa';
        $tasa = Tasa::find($id);
        // return $roles;
        return view('ventas.tasa.edit', compact('title', 'tasa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request;
        $title='Crear Tasa o Margen de ganancia';
        $tasa = Tasa::find($id);
        $tasa->tasa = $request->tasa;
        $tasa->porcentaje_ganancia = $request->porcentaje_ganancia;
        $tasa->estado = $request->estado;
        $tasa->caja = 'Abierta';
        $tasa->save();
        return redirect()
        ->route('tasa.index')
        ->with('status_success', 'Role saved successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    //Actualizar un solo registro
    // $app = ModelName::find($id);
    // $app->name = $request->name;
    // $app->email = $request->email;
    // $save();

    //Actualizar con base en una condición
    // $app = App\ModelName::find(1);
    // $app->where("status", 1)
    // ->update(["keyOne" => $valueOne, "keyTwo" => $valueTwo]);
}
