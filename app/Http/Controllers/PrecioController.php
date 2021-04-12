<?php

namespace App\Http\Controllers;

use App\Cat;
use App\Tasa;
use App\Precio;
use App\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PrecioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Lista de Precios';

        $precios = Precio::get();
        $tasaDolarHabitacion = Tasa::where('nombre','=','DolarHabitacion')->first();
        $tasaPesoHabitacion = Tasa::where('nombre','=','PesoHabitacion')->first();

        return view('config.precios.index', compact('precios','tasaDolarHabitacion','tasaPesoHabitacion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Crear de Precios';
        $horarios = Horario::get();
        $categorias = Cat::where('estado','<>','Eliminada')->get();
        return view('config.precios.create', compact('horarios', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $precio = new Precio;
        $precio->horario_id = $request->get('horario_id');
        $precio->cat_id = $request->get('cat_id');
        $precio->precio = $request->get('precio');
        $precio->save();
        return redirect()
        ->route('precio.create')
        ->with('status_success', 'El Precio fue creado Exitosamente...!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Precio  $precio
     * @return \Illuminate\Http\Response
     */
    public function show(Precio $precio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Precio  $precio
     * @return \Illuminate\Http\Response
     */
    public function edit(Precio $precio)
    {
        $title = 'Editar habitación';
        $horarios = Horario::get();
        $categorias = Cat::where('estado','<>','Eliminada')->get();

        return view('config.precios.edit', compact('precio', 'categorias', 'horarios'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Precio  $precio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Precio $precio)
    {
        $precio->horario_id = $request->get('horario_id');
        $precio->cat_id = $request->get('cat_id');
        $precio->precio = $request->get('precio');
        $precio->update();

        return redirect()
        ->route('precio.index')
        ->with('status_success', 'Precio actualizado exitosamente...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Precio  $precio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Precio $precio)
    {
        $precio->delete();
        return redirect()
        ->route('precio.index')
        ->with('status_success', 'Precio fue eliminado exitosamente...');
    }
}
