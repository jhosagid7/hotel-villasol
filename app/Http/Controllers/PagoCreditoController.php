<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Tasa;
use App\User;
use App\Credito;
use App\Persona;
use Carbon\Carbon;
use App\Sessioncaja;
use App\Pago_Credito;
use App\Credito_Pagado;
use App\Detalle_credito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PagoCreditoController extends Controller
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
        $mesActual = Carbon::now()->format('Y-m-d');
            $restaMes = Carbon::now()->subWeek(1);
            $restaMes = $restaMes->format('Y-m-d');
            $title='Creditos';
            $creditos = Credito::where('total_factura','>',0)->get();



//             min-devuelve la fecha mínima.
// max: devuelve la fecha máxima.
// eq - Determine si dos fechas son iguales.
// gt: determina si la primera fecha es mayor que la segunda.
// lt: determina si la primera fecha es menor que la segunda.
// gte-determinar si la primera fecha es mayor o igual que la segunda fecha.
// lte-determinar si la primera fecha es menor o igual que la segunda fecha.


//             $first = Carbon::create(2012, 9, 5, 23, 26, 11);
// $second = Carbon::create(2012, 9, 5, 20, 26, 11, 'America/Vancouver');

// echo $first->toDateTimeString();                   // 2012-09-05 23:26:11
// echo $first->tzName;                               // America/Toronto
// echo $second->toDateTimeString();                  // 2012-09-05 20:26:11
// echo $second->tzName;                              // America/Vancouver

// var_dump($first->eq($second));                     // bool(true)
// var_dump($first->ne($second));                     // bool(false)
// var_dump($first->gt($second));                     // bool(false)
// var_dump($first->gte($second));                    // bool(true)
// var_dump($first->lt($second));                     // bool(false)
// var_dump($first->lte($second));                    // bool(true)

// $now = Carbon::parse($date);
//                 $second = Carbon::parse('2021-04-06');
//                 // dd($now->gte($second));
//                 if ($second->gte($now)) {
//                     return 'tiene credito vigente';
//                     $credito_id = $fecha_limite->id;
//                     $upCredito = Credito::findOrFail($credito_id);
//                     if ($upCredito->total_deuda > 0) {
//                         $upCredito->estado_credito = 'Moroso';
//                         $upCredito->update();
//                     }else{
//                         $upCredito->estado_credito = 'Activo';
//                         $upCredito->update();
//                     }

//                 }else{
//                     return 'tiene credito vencido';
//                 }


            // return $ventas;

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // este codigo maneja las fechas de los creditos vencidos
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $date   = Carbon::now('America/Caracas');

            $creditos_clientes = Credito::get();
            if ($creditos_clientes) {


            foreach ($creditos_clientes as $fecha_limite) {

                $now = Carbon::parse($date);
                $second = Carbon::parse($fecha_limite->fecha_limite_pago);

                if ($second->gte($now)) {
                    // return 'tiene credito vigente';
                    $credito_id = $fecha_limite->id;
                    $upCredito = Credito::findOrFail($credito_id);
                    if ($upCredito->total_deuda > 0) {
                        $upCredito->estado_credito = 'Moroso';
                        $upCredito->update();
                    }else{
                        $upCredito->estado_credito = 'Activo';
                        $upCredito->update();
                    }

                    $upCredito->estado_credito = 'Activo';
                        $upCredito->update();

                }else{
                    // return 'tiene credito vencido';
                    $credito_id = $fecha_limite->id;
                    $upCredito = Credito::findOrFail($credito_id);

                    $upCredito->estado_credito = 'Moroso';
                    $upCredito->update();


                    if ($upCredito->total_deuda > 0) {
                        $upCredito->estado_credito = 'Moroso';
                        $upCredito->update();
                    }else{
                        $upCredito->estado_credito = 'Activo';
                        $upCredito->update();
                    }
                }

            }
        }
            $detalle_creditos = Detalle_credito::get();
            // return $detalle_credito;
            foreach ($detalle_creditos as $detalle_credito) {

                if ($date >= $detalle_credito->fecha_vencimiento) {

                    $detalle_credito_id = $detalle_credito->id;
                    $upDetalleCredito = Detalle_credito::findOrFail($detalle_credito_id);
                    if ($upDetalleCredito->estado_pago == 'Pendiente') {
                        $upDetalleCredito->estado_credito = 'Vencido';
                        $upDetalleCredito->update();
                    }


                }

        }
            // echo $difference = $date->diff($date2)->days;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




            return view('creditos.index', compact('title','creditos'));
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
        // return $request;

        $facturas_pagadas = $request->get('facturas_pagadas');
        $facturas_pagadas_id = $request->get('facturas_pagadas_id');
        // return $facturas_pagadas_id;
        $total_costo = $request->get('total_costo');
        $tipo_pago = $request->get('tipo_pago');
        $modo_pago = $request->get('modo_pago');
        $caja_id = $request->get('caja_id');
        $operador = $request->get('operador');

        $operador_id = Auth::id();
        $monto_dejado = $request->get('monto_dejado');
        // return $monto_dejado;
        $num_Punto = $request->get('num_Punto');
        $num_Trans = $request->get('num_Trans');






        $idFacturasPagadas = [];


        try{

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // este codigo maneja las fechas de los creditos vencidos
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $date   = Carbon::now('America/Caracas');

            $creditos_clientes = Credito::get();
            if ($creditos_clientes) {


            foreach ($creditos_clientes as $fecha_limite) {

                $now = Carbon::parse($date);
                $second = Carbon::parse($fecha_limite->fecha_limite_pago);

                if ($second->gte($now)) {
                    // return 'tiene credito vigente';
                    $credito_id = $fecha_limite->id;
                    $upCredito = Credito::findOrFail($credito_id);
                    if ($upCredito->total_deuda > 0) {
                        $upCredito->estado_credito = 'Moroso';
                        $upCredito->update();
                    }else{
                        $upCredito->estado_credito = 'Activo';
                        $upCredito->update();
                    }

                    $upCredito->estado_credito = 'Activo';
                        $upCredito->update();

                }else{
                    // return 'tiene credito vencido';
                    $credito_id = $fecha_limite->id;
                    $upCredito = Credito::findOrFail($credito_id);

                    $upCredito->estado_credito = 'Moroso';
                    $upCredito->update();


                    if ($upCredito->total_deuda > 0) {
                        $upCredito->estado_credito = 'Moroso';
                        $upCredito->update();
                    }else{
                        $upCredito->estado_credito = 'Activo';
                        $upCredito->update();
                    }
                }

            }
        }
            $detalle_creditos = Detalle_credito::get();
            // return $detalle_credito;
            foreach ($detalle_creditos as $detalle_credito) {

                if ($date >= $detalle_credito->fecha_vencimiento) {

                    $detalle_credito_id = $detalle_credito->id;
                    $upDetalleCredito = Detalle_credito::findOrFail($detalle_credito_id);
                    if ($upDetalleCredito->estado_pago == 'Pendiente') {
                        $upDetalleCredito->estado_credito = 'Vencido';
                        $upDetalleCredito->update();
                    }


                }

        }
            // echo $difference = $date->diff($date2)->days;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            DB::beginTransaction();
            $date   = Carbon::now('America/Caracas');
            $fecha  = $date->format('y-m-d');

        if($facturas_pagadas == 'una'){


            // Llenamos la tabla Creditos_pagados
            $detalle_credito_datos = Detalle_credito::findOrFail($facturas_pagadas_id);

            $monto_consumo = 0;
            $monto_servicio = 0;

            if($detalle_credito_datos->tipo_operacion == 'Consumo'){
                $monto_consumo = $monto_consumo + $detalle_credito_datos->monto;
            }

            if($detalle_credito_datos->tipo_operacion == 'Servicio'){
                $monto_servicio = $monto_servicio + $detalle_credito_datos->monto;
            }

            $credito_pagado = new Credito_Pagado();
            $credito_pagado->numero_factura = $detalle_credito_datos->numero_factura;
            $credito_pagado->tipo_operacion = $detalle_credito_datos->tipo_operacion;
            $credito_pagado->operacion_id = $detalle_credito_datos->operacion_id;
            $credito_pagado->monto = $detalle_credito_datos->monto;
            $credito_pagado->tipo_pago = $tipo_pago;
            $credito_pagado->fecha_emision = $detalle_credito_datos->fecha_emision;
            $credito_pagado->fecha_vencimiento = $detalle_credito_datos->fecha_vencimiento;
            $credito_pagado->fecha_pago = $fecha;
            $credito_pagado->estado_credito_al_pagar = $detalle_credito_datos->estado_credito;
            $credito_pagado->user_id = $operador_id;
            $credito_pagado->persona_id = $detalle_credito_datos->persona_id;
            $credito_pagado->detalle_credito_id = $detalle_credito_datos->id;
            $credito_pagado->credito_id = $detalle_credito_datos->credito_id;
            $credito_pagado->caja_id = $caja_id;
            $credito_pagado->save();


            // Actualizamos la tabla Detalle_credito

            $detalle_credito = Detalle_credito::findOrFail($facturas_pagadas_id);
            $detalle_credito->estado_pago = 'Pagado';
            $detalle_credito->estado_credito = 'Pagado';
            $detalle_credito->fecha_pago = $fecha;
            $detalle_credito->update();

            // Capturamos la nueva fecha de vencimiento
            $fecha_limite_pago = Detalle_credito::where('persona_id', $request->get('cliente_id'))->where('estado_pago','Pendiente')->first();

            // return $fecha_limite_pago;

            if(!$fecha_limite_pago){
                $date   = Carbon::now('America/Caracas');
                $fecha  = $date->format('y-m-d');

            }else{
                $fecha = $fecha_limite_pago->fecha_vencimiento;
            }

            // Actualizamos la tabla creditos
            $credito = Credito::findOrFail($detalle_credito_datos->credito_id);
            $ultima_factura = $credito->total_factura - 1;

            if ($ultima_factura == 0) {
                $credito->total_factura = 0;
                $credito->total_deuda = 0;
                $credito->fecha_limite_pago = null;
                $credito->update();
            }else{
                $credito->total_factura = $credito->total_factura - 1;
                $credito->total_deuda = $credito->total_deuda - $detalle_credito_datos->monto;
                $credito->fecha_limite_pago = $fecha;
                $credito->update();
            }
            // Llenamos la tabla Pagos_Creditos

            $MontoDivisaR = $request->get('MontoDivisa');
            $divisaR = $request->get('divisa');
            $TasaTikeR = $request->get('TasaTike');
            $MontoDolarR = $request->get('MontoDolar');
            $VeltosR = $request->get('Veltos');

            $MontoDivisaR = array_filter($MontoDivisaR);


            foreach($MontoDivisaR as $key => $val) {


                $divisa[]=$divisaR[$key];
                $MontoDivisa[]=$MontoDivisaR[$key];
                $TasaTiket[]=$TasaTikeR[$key];
                $MontoDolar[]=$MontoDolarR[$key];
                $Vueltos[]=$VeltosR[$key];

            }

            //Creamos un contador
            $cont = 0;

            //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
            while ($cont < count($MontoDolar)) {


                $Pago_Venta = new Pago_Credito();
                $Pago_Venta->Divisa = $divisa[$cont];
                $Pago_Venta->MontoDivisa = $MontoDivisa[$cont];
                $Pago_Venta->TasaTiket = $TasaTiket[$cont];
                $Pago_Venta->MontoDolar = $MontoDolar[$cont];
                $Pago_Venta->MontoConsumo = $monto_consumo;
                $Pago_Venta->MontoServicio = $monto_servicio;
                $Pago_Venta->Vueltos = $Vueltos[$cont];
                $Pago_Venta->detalle_credito_id = $detalle_credito->id;
                $Pago_Venta->caja_id = $caja_id;
                $Pago_Venta->save();

                $cont = $cont+1;
            }



        }


        if($facturas_pagadas == 'todas'){

            //Buscamos todos los ides de la tabla detalle_credito que pertenecen al la tabla credito por medio del id

            $creditos_ids = Detalle_credito::where('credito_id',$facturas_pagadas_id)->get();

            // return $creditos_ids;
            $monto_consumo = 0;
            $monto_servicio = 0;

            foreach($creditos_ids as $ids) {

                // return $ids;
                if ($ids->estado_pago == 'Pendiente') {
                    $idFacturasPagadas[] = $ids->id;

                    // Llenamos la tabla Creditos_pagados
                    $detalle_credito_datos = Detalle_credito::findOrFail($ids->id);

                    if($detalle_credito_datos->tipo_operacion == 'Consumo'){
                        $monto_consumo = $monto_consumo + $detalle_credito_datos->monto;
                    }

                    if($detalle_credito_datos->tipo_operacion == 'Servicio'){
                        $monto_servicio = $monto_servicio + $detalle_credito_datos->monto;
                    }

                    $credito_pagado = new Credito_Pagado();
                    $credito_pagado->numero_factura = $detalle_credito_datos->numero_factura;
                    $credito_pagado->tipo_operacion = $detalle_credito_datos->tipo_operacion;
                    $credito_pagado->operacion_id = $detalle_credito_datos->operacion_id;
                    $credito_pagado->monto = $detalle_credito_datos->monto;
                    $credito_pagado->tipo_pago = $tipo_pago;
                    $credito_pagado->fecha_emision = $detalle_credito_datos->fecha_emision;
                    $credito_pagado->fecha_vencimiento = $detalle_credito_datos->fecha_vencimiento;
                    $credito_pagado->fecha_pago = $fecha;
                    $credito_pagado->estado_credito_al_pagar = $detalle_credito_datos->estado_credito;
                    $credito_pagado->user_id = $operador_id;
                    $credito_pagado->persona_id = $detalle_credito_datos->persona_id;
                    $credito_pagado->detalle_credito_id = $detalle_credito_datos->id;
                    $credito_pagado->credito_id = $detalle_credito_datos->credito_id;
                    $credito_pagado->caja_id = $caja_id;
                    $credito_pagado->save();


                    // Actualizamos la tabla Detalle_credito

                    $detalle_credito = Detalle_credito::findOrFail($ids->id);
                    $detalle_credito->estado_pago = 'Pagado';
                    $detalle_credito->estado_credito = 'Pagado';
                    $detalle_credito->fecha_pago = $fecha;
                    $detalle_credito->update();

                    // Capturamos la nueva fecha de vencimiento
                    $fecha_limite_pago = Detalle_credito::where('persona_id', $request->get('cliente_id'))->where('estado_pago','Pendiente')->first();

                    // Actualizamos la tabla creditos
                    // $credito = Credito::findOrFail($detalle_credito_datos->credito_id);
                    // $credito->total_factura = $credito->total_factura - 1;
                    // $credito->total_deuda = $credito->total_deuda - $detalle_credito_datos->monto;
                    // $credito->fecha_limite_pago = $fecha_limite_pago->fecha_vencimiento;
                    // $credito->update();

                    $credito = Credito::findOrFail($detalle_credito_datos->credito_id);
                    $ultima_factura = $credito->total_factura - 1;

                    if ($ultima_factura == 0) {
                        $credito->total_factura = 0;
                        $credito->total_deuda = 0;
                        $credito->fecha_limite_pago = null;
                        $credito->update();
                    }else{
                        $credito->total_factura = $credito->total_factura - 1;
                        $credito->total_deuda = $credito->total_deuda - $detalle_credito_datos->monto;
                        $credito->fecha_limite_pago = $fecha;
                        $credito->update();
                    }
                }
            }

            // return $idFacturasPagadas;

            // DB::rollback();
            // Llenamos la tabla Pagos_Creditos

            $MontoDivisaR = $request->get('MontoDivisa');
            $divisaR = $request->get('divisa');
            $TasaTikeR = $request->get('TasaTike');
            $MontoDolarR = $request->get('MontoDolar');
            $VeltosR = $request->get('Veltos');

            $MontoDivisaR = array_filter($MontoDivisaR);


            foreach($MontoDivisaR as $key => $val) {


                $divisa[]=$divisaR[$key];
                $MontoDivisa[]=$MontoDivisaR[$key];
                $TasaTiket[]=$TasaTikeR[$key];
                $MontoDolar[]=$MontoDolarR[$key];
                $Vueltos[]=$VeltosR[$key];

            }

            //Creamos un contador
            $cont = 0;

            //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
            while ($cont < count($MontoDolar)) {


                $Pago_Credito = new Pago_Credito();
                $Pago_Credito->Divisa = $divisa[$cont];
                $Pago_Credito->MontoDivisa = $MontoDivisa[$cont];
                $Pago_Credito->TasaTiket = $TasaTiket[$cont];
                $Pago_Credito->MontoDolar = $MontoDolar[$cont];
                $Pago_Credito->MontoConsumo = $monto_consumo;
                $Pago_Credito->MontoServicio = $monto_servicio;
                $Pago_Credito->Vueltos = $Vueltos[$cont];
                $Pago_Credito->detalle_credito_id = $detalle_credito->id;
                $Pago_Credito->caja_id = $caja_id;
                $Pago_Credito->save();

                $cont = $cont+1;
            }

        }

        DB::commit();



        }catch(\Exception $e)
        {

            DB::rollback();
            dd($e);
            return redirect()
            ->route('creditos.index')
            ->with('status_danger', 'Error de Sistema! El pago no se proceso. Por favor llamar a Soporte tecnico...');
        }



        $nombreCliente = Persona::findOrfail($request->get('cliente_id'));

        $printer = new PrinterController;

        $printer->ticketCreditos('Pago Creditos', $facturas_pagadas_id, $nombreCliente, $modo_pago, $tipo_pago, $monto_dejado, $operador ,$facturas_pagadas, $idFacturasPagadas);

        return redirect()
            ->route('creditos.index')
            ->with('status_success', 'El pago se resgistro exitosamente...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pago_Credito  $pago_Credito
     * @return \Illuminate\Http\Response
     */
    public function show($cliente_id)
    {
        $title = 'Facturas por Cobrar';
        $detalle_creditos = Detalle_credito::where('persona_id',$cliente_id)->where('estado_pago','Pendiente')->get();
        // return $cliente_id;
        $credito = Credito::where('persona_id',$cliente_id)->first();

        $tasaDolarHabitacion = Tasa::where('nombre','=','DolarHabitacion')->first();
        // return $tasaDolarHabitacion->tasa;
        $tasaPesoHabitacion = Tasa::where('nombre','=','PesoHabitacion')->first();
        $tasaDolar = DB::table('tasas')->where('nombre', '=', 'Dolar')->first();
        $tasaPeso = DB::table('tasas')->where('nombre', '=', 'Peso')->first();
        $tasaTransferenciaPunto = DB::table('tasas')->where('nombre', '=', 'Transferencia_Punto')->first();
        $tasaMixto = DB::table('tasas')->where('nombre', '=', 'Mixto')->first();
        $tasaEfectivo = DB::table('tasas')->where('nombre', '=', 'Efectivo')->first();
        $users = User::with('roles')->orderBy('id','Desc')->get();
        $UserName = Auth::user()->name;
        $cajaSessionid =  Sessioncaja::where('estado', 'Abierta')->orderBy('id', 'desc')->first();
        $Cajas = Caja::where("estado","=",'Abierta')->where("sessioncaja_id","=", $cajaSessionid->id)->first();
        $caja = Caja::find($Cajas->id);

        // return $detalle_creditos;
        return view('creditos.show', compact('caja','title','credito','detalle_creditos','tasaDolarHabitacion','tasaPesoHabitacion','tasaDolar','tasaPeso','tasaTransferenciaPunto','tasaMixto','tasaEfectivo','users','UserName'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pago_Credito  $pago_Credito
     * @return \Illuminate\Http\Response
     */
    public function edit(Pago_Credito $pago_Credito)
    {
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pago_Credito  $pago_Credito
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pago_Credito $pago_Credito)
    {
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pago_Credito  $pago_Credito
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pago_Credito $pago_Credito)
    {
        return 'destroy';
    }
}
