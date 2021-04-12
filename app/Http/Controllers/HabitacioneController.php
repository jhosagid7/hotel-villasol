<?php

namespace App\Http\Controllers;

use App\Cat;
use App\Level;
use App\Habitacione;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class HabitacioneController extends Controller
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
        $title = 'Lista de Habitaciones';

        $habitaciones = Habitacione::get();

        return view('config.habitacion.index', compact('habitaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Crear Habitaciones';
        $levels = Level::where('estado','<>','Eliminado')->get();
        $categorias = Cat::where('estado','<>','Eliminada')->get();
        return view('config.habitacion.create', compact('levels', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hab = new Habitacione;
        $hab->nombre = $request->get('nombre');
        $hab->cat_id = $request->get('cat_id');
        $hab->level_id = $request->get('level_id');
        $hab->estado = 1;
        $hab->status = 1;
        $hab->save();

       return Redirect::to('config/habitacion')->with('status_success', 'La Habitación fue creada Exitosamente...!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Habitacione  $habitacione
     * @return \Illuminate\Http\Response
     */
    public function show(Habitacione $habitacione)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Habitacione  $habitacione
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd($habitacione);
        $title = 'Editar habitación';
        $categorias = Cat::get();
        $levels = Level::get();
        $habitacione = Habitacione::findOrfail($id);
        return view('config.habitacion.edit', compact('habitacione', 'categorias', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Habitacione  $habitacione
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request;
        $habitacione = Habitacione::findOrFail($id);
        $habitacione->nombre = $request->get('nombre');
        $habitacione->cat_id = $request->get('cat_id');
        $habitacione->level_id = $request->get('level_id');
        $habitacione->update();
        return redirect()
        ->route('habitacion.index')
        ->with('status_success', 'La habitación fue actualizada exitosamente...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Habitacione  $habitacione
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hab = Habitacione::findOrFail($id);
        $hab->estado = 2;
        $hab->update();

        return redirect()
        ->route('habitacion.index')
        ->with('status_success', 'La habitación fue eliminada exitosamente...');
    }
}
