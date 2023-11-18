<?php

namespace App\Http\Controllers;

use App\Cat;
use App\Caja;
use App\Tasa;
use App\User;
use App\Horario;
use App\Persona;
use App\Servicio;
use App\Excedente;
use Carbon\Carbon;
use App\Reservation;
use App\Sessioncaja;
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

        $procesed = request()->input('_processService');
        $datosReservasion = request()->except(['_token', '_method', '_processService','user_id','caja_id', 'nombreCliente', 'cedulaCliente', 'numServicio', 'formaPago']);
        // dd($datosReservasion['precio']);

        $formaPago = request()->input('formaPago');



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

        //Todo creamos el servicio
        if($procesed) {
            $datosReservasion['status'] = 'Procesado';
            $this->processServicesReservations($datosReservasion);
        }

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

    public function processServicesReservations($datosReservasion) {

        //obtenemos el id del servicio
        $lastServicio = Servicio::latest()->first();
        $num_servicio = Sessioncaja::numCodigo('CS', auth()->user()->id, $lastServicio->id + 1);
        $caja = Caja::latest()->first();

        $tasas = Tasa::orderBy('id')->get();


        $servicio = new Servicio;
        $servicio->num_servicio = $num_servicio;
        $servicio->operador = auth()->user()->name;
        $servicio->status_servicio = 'Iniciado';
        $servicio->habitacion_id = $datosReservasion['habitacione_id'];
        $servicio->nombre_habitacion = $datosReservasion['numHabitacion'];
        $servicio->detalle_habitacion = $datosReservasion['tipoHabitacion'];
        $servicio->tipo_habitacion = $datosReservasion['tipoServicio'];
        $servicio->horario = $datosReservasion['tipoServicio'];
        $servicio->fecha_entrada = date("Y-m-d", strtotime($datosReservasion['start']));
        $servicio->hora_entrada = date("H:i", strtotime($datosReservasion['start']));
        $servicio->fecha_salida = date("Y-m-d", strtotime($datosReservasion['end']));
        $servicio->hora_salida = date("H:i", strtotime($datosReservasion['end']));
        $servicio->tasaDolar = $tasas[0]['tasa'];
        $servicio->porDolar = $tasas[0]['porcentaje_ganancia'];
        $servicio->tasaPeso = $tasas[1]['tasa'];
        $servicio->porPeso = $tasas[1]['porcentaje_ganancia'];
        $servicio->tasaTransPunto = $tasas[2]['tasa'];
        $servicio->porTransPunto = $tasas[2]['porcentaje_ganancia'];
        $servicio->tasaMixto = $tasas[3]['tasa'];
        $servicio->porMixto = $tasas[3]['porcentaje_ganancia'];
        $servicio->tasaEfectivo = $tasas[4]['tasa'];
        $servicio->porEfectivo = $tasas[4]['porcentaje_ganancia'];
        $servicio->tasaDolarHabitacion = $tasas[5]['tasa'];
        $servicio->porDolarHabitacion = $tasas[5]['porcentaje_ganancia'];
        $servicio->tasaPesoHabitacion = $tasas[6]['tasa'];
        $servicio->porPesoHabitacion = $tasas[6]['porcentaje_ganancia'];
        $servicio->num_Punto = null;
        $servicio->num_Trans = null;
        $servicio->modo_pago = 'Contado';
        $servicio->tipo_pago = 'Dolar';
        $servicio->is_cambio = 'No';
        $servicio->status = 'Pagado';
        $servicio->precio_costo = $datosReservasion['precio'];
        $servicio->cantidad = $datosReservasion['cantidad'];
        $servicio->dinero_dejado = $datosReservasion['montoPago'];
        $servicio->excedente_nuevo = 0.00;
        $servicio->pago_con_excedente = null;
        $servicio->total_venta = $datosReservasion['montoPago'];
        $servicio->estado = 'Aceptada';
        $servicio->nombre_cliente = $datosReservasion['nombreCliente'];
        $servicio->cedula_cliente = $datosReservasion['cedulaCliente'];
        $servicio->direccion_cliente = 'S/D';
        $servicio->telefono_cliente = $datosReservasion['0424-7665227'];
        $servicio->limite_fecha = null;
        $servicio->limite_monto = null;
        $servicio->persona_id = $datosReservasion['persona_id'];
        $servicio->user_id = auth()->user()->id;
        $servicio->caja_id = $caja->id;
        $servicio->save();
    }





}
