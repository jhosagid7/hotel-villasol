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
use App\DetallePagoReservation;
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
        $caja = Caja::where("estado", "=", 'Abierta')->first();
        if($caja){
            $caja_id = $caja->id;
        }else{
            $caja_id = '';
        }

        $user = User::with('roles')->where('id', Auth::id())->first();
        $userRole = $user->roles[0]->name;
        // return $user->roles[0]->name;
        $tipoServicios = Cat::where('estado', 'Activa')->select('id', 'nombre')->get();
        $horarios = Horario::select('id', 'tipo')->where('tipo', '<>', 'DIURNO')->get();

        // return $tipoServicio;
        return view('reservations.index', compact('tipoServicios', 'horarios', 'userRole', 'caja_id'));
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
        $datosReservasion = request()->except(['_token', '_method', 'formaPago']);
        $formaPago = request()->input('formaPago');

        $datosReservasion['caja_id'] = $caja->id;
        $datosReservasion['created_at'] = now();
        $datosReservasion['updated_at'] = now();


        try {
            DB::beginTransaction();

            if (!$datosReservasion['persona_id']) {
                $persona = new Persona;
                $persona->tipo_persona = 'Cliente';
                $persona->nombre = $datosReservasion['nombreCliente'];
                $persona->tipo_documento = 'CI.V';
                $persona->num_documento = $datosReservasion['cedulaCliente'];
                $persona->direccion = $datosReservasion['telefonoContacto'] ? $datosReservasion['telefonoContacto'] : null;
                $persona->save();
            }

            $persona_id = $datosReservasion['persona_id'] ? $datosReservasion['persona_id'] : $persona->id;

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

            $reservationId = Reservation::insertGetId($datosReservasion);

            // Guardar los datos en la tabla detalle_pago_reservation
            foreach ($formaPago as $pago) {
                $detallePago = new DetallePagoReservation();
                $detallePago->tipoPago = $pago['tipoPago'];
                $detallePago->montoPagado = $pago['montoPagado'] ? $pago['montoPagado'] : 0;
                $detallePago->montoPagadoDolar = $pago['montoPagadoDolar'] ? $pago['montoPagadoDolar'] : 0;
                $detallePago->vueltos = $pago['vueltos'] ? $pago['vueltos'] : 0;
                $detallePago->vueltosDolar = $pago['vueltosDolar'] ? $pago['vueltosDolar'] : 0;
                $detallePago->nombreBanco = $pago['nombreBanco'];
                $detallePago->referencia = $pago['referencia'];
                $detallePago->fechaPago = $pago['fechaPago'];
                $detallePago->tasaDolar = $pago['tasaDolar'];
                $detallePago->tasaPeso = $pago['tasaPeso'];
                $detallePago->tasaBolivar = $pago['tasaBolivar'];
                $detallePago->operadorNombre = $pago['operadorNombre'];
                $detallePago->reservation_id = $reservationId;
                $detallePago->caja_id = $caja->id;
                $detallePago->save();
            }

            DB::commit();
            // print_r($datosReservasion);
            return response()->json(['msg' => 'Reservacion agregada', 'type' => 'success']);
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
        $data['reservasiones'] = Reservation::with('detalle_pago_reservaciones')->get();

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
        $caja = Caja::where("estado", "=", 'Abierta')->first();


        $datosReservasion = request()->except(['_token', '_method','user_id','caja_id', 'nombreCliente', 'cedulaCliente', 'numServicio', 'formaPago']);
        // dd($datosReservasion['precio']);

        $formaPago = request()->input('formaPago');

        $result = Reservation::where('id', $id)->update($datosReservasion);

        if($formaPago){
            // Guardar los datos en la tabla detalle_pago_reservation
            foreach ($formaPago as $pago) {
                DetallePagoReservation::updateOrCreate([
                    'id' => $pago['id']
                ], [
                    'tipoPago' => $pago['tipoPago'],
                    'montoPagado' => $pago['montoPagado'] ? $pago['montoPagado'] : 0,
                    'montoPagadoDolar' => $pago['montoPagadoDolar'] ? $pago['montoPagadoDolar'] : 0,
                    'vueltos' => $pago['vueltos'] ? $pago['vueltos'] : 0,
                    'vueltosDolar' => $pago['vueltosDolar'] ? $pago['vueltosDolar'] : 0,
                    'nombreBanco' => $pago['nombreBanco'],
                    'referencia' => $pago['referencia'],
                    'fechaPago' => $pago['fechaPago'],
                    'tasaDolar' => $pago['tasaDolar'],
                    'tasaPeso' => $pago['tasaPeso'],
                    'tasaBolivar' => $pago['tasaBolivar'],
                    'operadorNombre' => auth()->user()->name,
                    'reservation_id' => $id,
                    'caja_id' => $caja->id
                ]);
            }
        }

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
