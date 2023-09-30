<?php

namespace App\Http\Controllers;

use App\Cat;
use App\Caja;
use App\User;
use App\Horario;
use App\Persona;
use App\Excedente;
use Carbon\Carbon;
use App\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ReservationController extends Controller
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
    public function index(Request $request)
    {
        $user = User::with('roles')->where('id', Auth::id())->first();
        $userRole = $user->roles[0]->name;
        // return $user->roles[0]->name;
        $tipoServicios = Cat::where('estado', 'Activa')->select('id', 'nombre')->get();
        $horarios = Horario::select('id', 'tipo')->get();

        // return $tipoServicio;
        return view('reservations.index', compact('tipoServicios', 'horarios', 'userRole'));
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
        $caja = Caja::where("estado", "=", 'Abierta')->first();

        $datosReservasion = request()->except(['_token', '_method']);
        $datosReservasion['caja_id'] = $caja->id;
        $datosReservasion['created_at'] = now();
        $datosReservasion['updated_at'] = now();
        try {

            DB::beginTransaction();

            if(!$datosReservasion['persona_id']){
                $persona = new Persona;
                $persona->tipo_persona = 'Cliente';
                $persona->nombre = $datosReservasion['nombreCliente'];
                $persona->tipo_documento = 'CI.V';
                $persona->num_documento = $datosReservasion['cedulaCliente'];
                $persona->direccion = $datosReservasion['telefonoContacto'] ? $datosReservasion['telefonoContacto'] : null;
                $persona->save();
            }

            $persona_id = $datosReservasion['persona_id'] ? $datosReservasion['persona_id'] :  $persona->id;

            $ifCliente = Excedente::where('persona_id', $persona_id)
            ->where('tipo', 'Pagar_por_oficina')
            ->first();

            if ($ifCliente) {
                $upExcedente = Excedente::findOrFail($ifCliente->id);
                $upExcedente->excedente += $datosReservasion['montoPago'];
                $upExcedente->update();
            } else {
                $dexcedente = new Excedente();
                $dexcedente->tipo = 'Pagar_por_oficina';
                $dexcedente->nombre_cliente = $datosReservasion['nombreCliente'];
                $dexcedente->cedula_cliente = $datosReservasion['cedulaCliente'];
                $dexcedente->excedente = $datosReservasion['montoPago'];
                $dexcedente->isEfectivo = 1;
                $dexcedente->persona_id = $persona_id;
                $dexcedente->save();
            }

            Reservation::insert($datosReservasion);

            DB::commit();

            return response()->json(['msg' => 'Reservacion agregada', 'type' => 'success']);
            print_r($datosReservasion);

        } catch (\Throwable $th) {
            DB::rollback();
            dd($th);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $data['reservasiones'] = Reservation::all();

        return response()->json($data['reservasiones']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $datosReservasion = request()->except(['_token', '_method', 'operadorNombre','user_id','caja_id', 'nombreCliente', 'cedulaCliente', 'montoPago', 'numServicio']);
        $result = Reservation::where('id', $id)->update($datosReservasion);

        return response()->json([$result,'msg' => 'Reservacion Actualizada', 'type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);


        $ifCliente = Excedente::where('persona_id', $reservation->persona_id)->first();

        if ($ifCliente) {

            $upExcedente = Excedente::findOrFail($ifCliente->id);
            $upExcedente->excedente -= $reservation->montoPago;
            $upExcedente->update();

            $is_money = Excedente::where('persona_id', $reservation->persona_id)->first();
            if ($is_money->excedente <= 0) {
                Excedente::destroy($is_money->id);
            }
        }

        $reservation = Reservation::findOrFail($id);
        if ($reservation) {
            $reservation->status = 'Cancelado';
            $reservation->color = '#FE0606';
            $reservation->update();
        }

        return response()->json(['msg' => 'Reservacion eliminada', 'type' => 'success', 'id' => $id]);



    }





}
