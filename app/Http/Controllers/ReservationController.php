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
use App\Habitacione;
use App\Reservation;
use App\Sessioncaja;
use App\Pago_Servicio;
use App\Pago_Vuelto;
use Illuminate\Http\Request;
use App\DetallePagoReservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

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
        $horarios = Horario::select('id', 'tipo')->where('tipo', '24 HORAS')->get();
        $tasaDolar = Tasa::where('Nombre', 'Dolar')->first();
        $tasaPeso = Tasa::where('Nombre', 'Peso')->first();
        $tasaEfectivo = Tasa::where('Nombre', 'Efectivo')->first();

        // return $tipoServicio;
        return view('reservations.index', compact('tasaDolar', 'tasaPeso' , 'tasaEfectivo', 'tipoServicios', 'horarios', 'userRole', 'caja_id'));
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
        $datosReservasion = request()->except(['_token', '_method', '_processService', 'formaPago']);
        $formaPago = request()->input('formaPago');

        $datosReservasion['caja_id'] = $caja->id;
        $datosReservasion['created_at'] = now();
        $datosReservasion['updated_at'] = now();


        try {
            DB::beginTransaction();
            $id_persona =  $datosReservasion['persona_id'];

            if (!$datosReservasion['persona_id'] || $datosReservasion['persona_id'] = '') {
                $persona = new Persona;
                $persona->tipo_persona = 'Cliente';
                $persona->nombre = $datosReservasion['nombreCliente'];
                $persona->tipo_documento = 'CI.V';
                $persona->num_documento = $datosReservasion['cedulaCliente'];
                $persona->direccion = $datosReservasion['telefonoContacto'] ? $datosReservasion['telefonoContacto'] : null;
                $persona->save();
                $id_persona = $persona->id;
            }

            $persona_id = $datosReservasion['persona_id'] ? $datosReservasion['persona_id'] : $id_persona;

            $datosReservasion['persona_id'] = $persona_id;


            // $ifCliente = Excedente::where('persona_id', $persona_id)
            //     ->where('tipo', 'Pagar_por_oficina')
            //     ->first();

            // if ($ifCliente) {
            //     $upExcedente = Excedente::findOrFail($ifCliente->id);
            //     $upExcedente->excedente += $datosReservasion['montoPago'];
            //     // $upExcedente->update();
            // } else {
            //     $dexcedente = new Excedente();
            //     $dexcedente->tipo = 'Pagar_por_oficina';
            //     $dexcedente->nombre_cliente = $datosReservasion['nombreCliente'];
            //     $dexcedente->cedula_cliente = $datosReservasion['cedulaCliente'];
            //     $dexcedente->excedente = $datosReservasion['montoPago'];
            //     $dexcedente->isEfectivo = 1;
            //     $dexcedente->persona_id = $persona_id;
            //     // $dexcedente->save();
            // }

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
        $caja = Caja::where("estado", "=", 'Abierta')->first() ?? Caja::latest()->first();

        $nombreCliente = request()->input('nombreCliente');
        $cedulaCliente = request()->input('cedulaCliente');
        $procesed = request()->input('_processService');

        $datosReservasion = request()->except(['_token', '_method', '_processService', 'user_id', 'caja_id', 'numServicio', 'formaPago']);

        if ($nombreCliente) {
            $datosReservasion['nombreCliente'] = $nombreCliente;
        }
        if ($cedulaCliente) {
            $datosReservasion['cedulaCliente'] = $cedulaCliente;
        }

        $formaPago = request()->input('formaPago');

        try {
            DB::beginTransaction();

            if ($formaPago && is_array($formaPago)) {
                // Guardar los datos en la tabla detalle_pago_reservation
                foreach ($formaPago as $pago) {
                    if (isset($pago['id']) && $pago['id']) {
                        DetallePagoReservation::updateOrCreate([
                            'id' => $pago['id']
                        ], [
                            'tipoPago' => $pago['tipoPago'] ?? 'Dolar',
                            'montoPagado' => $pago['montoPagado'] ? $pago['montoPagado'] : 0,
                            'montoPagadoDolar' => $pago['montoPagadoDolar'] ? $pago['montoPagadoDolar'] : 0,
                            'vueltos' => $pago['vueltos'] ? $pago['vueltos'] : 0,
                            'vueltosDolar' => $pago['vueltosDolar'] ? $pago['vueltosDolar'] : 0,
                            'nombreBanco' => $pago['nombreBanco'] ?? '',
                            'referencia' => $pago['referencia'] ?? '',
                            'fechaPago' => $pago['fechaPago'] ?? now()->toDateString(),
                            'tasaDolar' => $pago['tasaDolar'] ?? 0,
                            'tasaPeso' => $pago['tasaPeso'] ?? 0,
                            'tasaBolivar' => $pago['tasaBolivar'] ?? 0,
                            'operadorNombre' => auth()->user()->name ?? 'Admin',
                            'reservation_id' => $id,
                            'caja_id' => $caja ? $caja->id : 1
                        ]);
                    }
                }
            }

            // Procesar servicio (Activar reservación)
            if ($procesed === 'true') {
                $habitacionId = $datosReservasion['habitacione_id'] ?? null;
                if (!$habitacionId) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'type' => 'danger',
                        'msg' => 'Debe seleccionar una habitación para procesar la reservación.'
                    ], 422);
                }

                $habitacion = Habitacione::where('id', $habitacionId)->lockForUpdate()->first();
                if (!$habitacion) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'type' => 'danger',
                        'msg' => 'La habitación seleccionada no existe.'
                    ], 422);
                }

                if ($habitacion->status === 'Ocupada') {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'type' => 'danger',
                        'msg' => "La habitación {$habitacion->nombre} se encuentra actualmente OCUPADA por otro huésped. Por favor seleccione otra habitación disponible antes de activar."
                    ], 422);
                }

                $activeService = Servicio::where('habitacion_id', $habitacionId)
                    ->where('status_servicio', 'Iniciado')
                    ->lockForUpdate()
                    ->first();

                if ($activeService) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'type' => 'danger',
                        'msg' => "La habitación {$habitacion->nombre} ya tiene un servicio activo en curso (Servicio #{$activeService->num_servicio}). Por favor seleccione otra habitación disponible."
                    ], 422);
                }

                $datosReservasion['status'] = 'Procesado';
                $datosReservasion['color'] = '#118F00';
                $datosReservasion['caja_pago_reservacion_id'] = $caja ? $caja->id : 1;

                $this->processServicesReservations($datosReservasion, $id);
            }

            $result = Reservation::where('id', $id)->update($datosReservasion);

            // Sincronizar datos del cliente en personas si aplica
            if (!empty($datosReservasion['persona_id'])) {
                $persona = Persona::find($datosReservasion['persona_id']);
                if ($persona) {
                    if (!empty($datosReservasion['nombreCliente'])) {
                        $persona->nombre = $datosReservasion['nombreCliente'];
                    }
                    if (!empty($datosReservasion['cedulaCliente'])) {
                        $persona->num_documento = $datosReservasion['cedulaCliente'];
                    }
                    if (!empty($datosReservasion['telefonoContacto'])) {
                        $persona->direccion = $datosReservasion['telefonoContacto'];
                    }
                    $persona->save();
                }
            }

            DB::commit();

            return response()->json([
                'result' => $result,
                'success' => true,
                'msg' => $procesed === 'true' ? 'Reservación procesada y activada con éxito' : 'Reservacion Actualizada',
                'type' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'type' => 'danger',
                'msg' => 'Error al actualizar la reservación: ' . $th->getMessage()
            ], 500);
        }
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
        $tasaDolar = $tasas[0]['tasa'];
        $tasaPeso = $tasas[1]['tasa'];
        $tasaTransPunto = $tasas[2]['tasa'];
        $tasaMixto = $tasas[3]['tasa'];
        $tasaEfectivo = $tasas[4]['tasa'];
        $tasaDolarHabitacion = $tasas[5]['tasa'];
        $tasaPesoHabitacion = $tasas[6]['tasa'];


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
        $servicio->tasaDolar = $tasaDolar;
        $servicio->porDolar = $tasas[0]['porcentaje_ganancia'];
        $servicio->tasaPeso = $tasaPeso;
        $servicio->porPeso = $tasas[1]['porcentaje_ganancia'];
        $servicio->tasaTransPunto = $tasaTransPunto;
        $servicio->porTransPunto = $tasas[2]['porcentaje_ganancia'];
        $servicio->tasaMixto = $tasaMixto;
        $servicio->porMixto = $tasas[3]['porcentaje_ganancia'];
        $servicio->tasaEfectivo = $tasaEfectivo;
        $servicio->porEfectivo = $tasas[4]['porcentaje_ganancia'];
        $servicio->tasaDolarHabitacion = $tasaDolarHabitacion;
        $servicio->porDolarHabitacion = $tasas[5]['porcentaje_ganancia'];
        $servicio->tasaPesoHabitacion = $tasaPesoHabitacion;
        $servicio->porPesoHabitacion = $tasas[6]['porcentaje_ganancia'];
        $servicio->num_Punto = null;
        $servicio->num_Trans = null;
        $servicio->modo_pago = 'Contado';
        $servicio->tipo_pago = 'Dolar';
        $servicio->is_cambio = 'No';
        $servicio->status = 'Pagado';
        $servicio->precio_costo = $datosReservasion['precio'];
        $servicio->cantidad = 1;
        $servicio->dinero_dejado = $datosReservasion['montoPago'];
        $servicio->excedente_nuevo = 0.00;
        $servicio->pago_con_excedente = null;
        $servicio->total_venta = $datosReservasion['montoPago'];
        $servicio->estado = 'Aceptada';
        $servicio->nombre_cliente = $datosReservasion['nombreCliente'];
        $servicio->cedula_cliente = $datosReservasion['cedulaCliente'];
        $servicio->direccion_cliente = 'S/D';
        $servicio->telefono_cliente = $datosReservasion['telefonoPago'];
        $servicio->limite_fecha = null;
        $servicio->limite_monto = null;
        $servicio->persona_id = $datosReservasion['persona_id'];
        $servicio->user_id = auth()->user()->id;
        $servicio->caja_id = $caja->id;
        $servicio->save();

        $habitacion = Habitacione::findOrFail($datosReservasion['habitacione_id']);
        $habitacion->status = 'Ocupada';
        $habitacion->update();

        $pago_reservacione = DetallePagoReservation::where('reservation_id', $datosReservasion['id'])->get();

        if($pago_reservacione->count() > 0) {
            $tasaPago = '';
            foreach ($pago_reservacione as $pago) {

                if ($pago->tipoPago == 'Dolar') {
                    $tasaPago = $pago->tasaDolar;
                }
                if ($pago->tipoPago == 'Peso') {
                    $tasaPago = $pago->tasaPeso;
                }
                if ($pago->tipoPago == 'Bolivar') {
                    $tasaPago = $pago->tasaBolivar;
                }
                if ($pago->tipoPago == 'Transferencia') {
                    $tasaPago = $pago->tasaBolivar;
                }
                if ($pago->tipoPago == 'Punto') {
                    $tasaPago = $pago->tasaBolivar;
                }

                $Pago_Servicio = new Pago_Servicio();
                $Pago_Servicio->Divisa = $pago->tipoPago;
                $Pago_Servicio->MontoDivisa = $pago->montoPagado;
                $Pago_Servicio->TasaTiket = $tasaPago;
                $Pago_Servicio->MontoDolar = floatval($pago->montoPagadoDolar);
                $Pago_Servicio->MontoDolarServicio = (floatval($pago->montoPagadoDolar) - floatval($pago->vueltosDolar));
                $Pago_Servicio->Excedente = 0;
                $Pago_Servicio->Vueltos = floatval($pago->vueltosDolar);
                $Pago_Servicio->servicio_id = $servicio->id;
                $Pago_Servicio->caja_id = $caja->id;
                $Pago_Servicio->save();

                if($pago->vueltosDolar > 0){
                    $Pago_Vueltos = new Pago_Vuelto();
                    $Pago_Vueltos->Tipo = 'Servicio';
                    $Pago_Vueltos->tipo_vuelto = 'Vueltos_Pago';
                    $Pago_Vueltos->Divisa = $pago->tipoPago;
                    $Pago_Vueltos->MontoDivisa =  $pago->vueltos;
                    $Pago_Vueltos->TasaTiket = $tasaPago;
                    $Pago_Vueltos->MontoDolar = $pago->vueltosDolar;
                    $Pago_Vueltos->servicio_id = $servicio->id;
                    $Pago_Vueltos->venta_id = 0;
                    $Pago_Vueltos->horas_extra_id = 0;
                    $Pago_Vueltos->detalle__creditos__pagado_id = 0;
                    $Pago_Vueltos->caja_id = $caja->id;
                    $Pago_Vueltos->save();
                }
            }
        }
        $upExcedente = Reservation::findOrFail($datosReservasion['id']);
        $upExcedente->numServicio = $servicio->num_servicio;
        $upExcedente->servicio_id = $servicio->id;
        $upExcedente->color = '#118F00';
        $upExcedente->update();



        //Primiminos el ticket
        $this->printTiketServicio($datosReservasion, $num_servicio);





    }

    public function printTiketServicio($datosReservasion, $num_servicio)
    {
        try {
            $printer = new PrinterController;
            $printer->ticketServicio(
                'Servicio', $num_servicio,
                $datosReservasion['numHabitacion'],
                $datosReservasion['tipoHabitacion'],
                'Contado',
                'Dolar',
                $datosReservasion['precio'],
                auth()->user()->name ?? 'Admin',
                $datosReservasion['tipoServicio']
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('No se pudo imprimir ticket de reservación: ' . $e->getMessage());
        }
    }



}
