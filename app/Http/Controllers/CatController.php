<?php

namespace App\Http\Controllers;


use App\Cat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CatController extends Controller
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

        $title = 'Lista de Categorías de Habitaciones';

        $cats = Cat::get();

        return view('config.cats.index', compact('cats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Crear Categorías para Habitaciones';
        return view('config.cats.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cat = new Cat;
        $cat->nombre = $request->get('nombre');
        $cat->descripcion = $request->get('descripcion');
        $cat->estado = 1;
        $cat->save();

       return Redirect::to('config/cat')->with('status_success', 'La categoría fue creada Exitosamente...!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cat  $cat
     * @return \Illuminate\Http\Response
     */
    public function show(Cat $cat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cat  $cat
     * @return \Illuminate\Http\Response
     */
    public function edit(Cat $cat)
    {
        $title = 'Editar categoría';
        return view('config.cats.edit', compact('cat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cat  $cat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cat $cat)
    {
        $cat->nombre = $request->get('nombre');
        $cat->descripcion = $request->get('descripcion');
        $cat->update();

        return redirect()
        ->route('cat.index')
        ->with('status_success', 'Categoría actualizada exitosamente...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cat  $cat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cat $cat)
    {
        $cat->estado = 2;
        $cat->update();

        return redirect()
        ->route('cat.index')
        ->with('status_success', 'Categoría fue eliminada exitosamente...');
    }
}
