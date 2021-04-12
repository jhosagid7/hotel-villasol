<?php

namespace App\Http\Controllers;

use App\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class HorarioController extends Controller
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

        $horarios = Horario::get();

        return view('config.horarios.index', compact('horarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Crear Horarios para servicios';
        return view('config.horarios.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->is24Horas;
        if($request->is24Horas == 'on'){
            $desde      = null;
            $hasta      = null;
            $restringir = null;
        }else{
            $desde =date('H:i:s', strtotime( $request->get('desde')));
            $hasta =date('H:i:s', strtotime( $request->get('hasta')));
            $restringir =date('H:i:s', strtotime( $request->get('restringir')));
        }

        $horario = new Horario;
        $horario->tipo = $request->get('tipo');
        $horario->nombre = $request->get('nombre');
        $horario->desde = $desde;
        $horario->hasta = $hasta;
        $horario->restringir = $restringir;
        $horario->is24Horas = $request->is24Horas;
        $horario->save();

       return Redirect::to('config/horario')->with('status_success', 'El horario fue creado Exitosamente...!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function show(Horario $horario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function edit(Horario $horario)
    {
        $title = 'Editar horario';
        return view('config.horarios.edit', compact('horario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Horario $horario)
    {
        // return $request;
        if($request->is24Horas == 'on'){
            $desde      = null;
            $hasta      = null;
            $restringir = null;
        }else{
            $desde =date('H:i:s', strtotime( $request->get('desde')));
            $hasta =date('H:i:s', strtotime( $request->get('hasta')));
            $restringir =date('H:i:s', strtotime( $request->get('restringir')));
        }


        $horario->tipo = $request->get('tipo');
        $horario->nombre = $request->get('nombre');
        $horario->desde = $desde;
        $horario->hasta = $hasta;
        $horario->restringir = $restringir;
        $horario->is24Horas = $request->is24Horas;
        $horario->update();

        return redirect()
        ->route('horario.index')
        ->with('status_success', 'Horario actualizado exitosamente...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Horario $horario)
    {
        $horario->delete();

        return redirect()
        ->route('horario.index')
        ->with('status_success', 'Horario fue eliminado exitosamente...');
    }
}
