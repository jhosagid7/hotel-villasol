<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Tasa;
use App\User;
use App\Credito;
use App\Persona;
use Carbon\Carbon;
use App\Pago_Vuelto;
use App\Sessioncaja;
use App\Pago_Credito;
use App\Credito_Pagado;
use App\Detalle_credito;
use App\Detalle_Creditos_Pagado;
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
        $title = 'Creditos';
        $creditos = Credito::where('total_factura', '>', 0)->get();



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
                    } else {
                        $upCredito->estado_credito = 'Activo';
                        $upCredito->update();
                    }

                    $upCredito->estado_credito = 'Activo';
                    $upCredito->update();
                } else {
                    // return 'tiene credito vencido';
                    $credito_id = $fecha_limite->id;
                    $upCredito = Credito::findOrFail($credito_id);

                    $upCredito->estado_credito = 'Moroso';
                    $upCredito->update();


                    if ($upCredito->total_deuda > 0) {
                        $upCredito->estado_credito = 'Moroso';
                        $upCredito->update();
                    } else {
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




        return view('creditos.index', compact('title', 'creditos'));
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

        // $base_vuelto_monto_dejado_abono = $request->get('base_vuelto_monto_dejado');
        $monto_dejado_abono = $request->get('monto_dejado');
        $resta_costo_abono = 0;





        $idFacturasPagadas = [];


        try {

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
                        } else {
                            $upCredito->estado_credito = 'Activo';
                            $upCredito->update();
                        }

                        $upCredito->estado_credito = 'Activo';
                        $upCredito->update();
                    } else {
                        // return 'tiene credito vencido';
                        $credito_id = $fecha_limite->id;
                        $upCredito = Credito::findOrFail($credito_id);

                        $upCredito->estado_credito = 'Moroso';
                        $upCredito->update();


                        if ($upCredito->total_deuda > 0) {
                            $upCredito->estado_credito = 'Moroso';
                            $upCredito->update();
                        } else {
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

            if ($facturas_pagadas == 'una' || $facturas_pagadas == 'todas' || $facturas_pagadas == 'abonar') {


                //Buscamos todos los ides de la tabla detalle_credito que pertenecen al la tabla credito por medio del id

                if ($facturas_pagadas == 'una' || $facturas_pagadas == 'abonar') {
                    $creditos_ids = Detalle_credito::where('id', $facturas_pagadas_id)->where('estado_pago', 'pendiente')->get();
                } else {
                    $creditos_ids = Detalle_credito::where('credito_id', $facturas_pagadas_id)->where('estado_pago', 'pendiente')->get();
                }

                // return $creditos_ids;



                // return $facturas_pagadas_id;
                $id_cliente = $creditos_ids[0]->credito_id;
                $total_factura = count($creditos_ids);
                $monto_consumo = 0;
                $monto_servicio = 0;


                foreach ($creditos_ids as $monto) {
                    if ($monto->estado_pago == 'Pendiente') {
                        $credito_datos = Detalle_credito::findOrFail($monto->id);

                        if ($credito_datos->tipo_operacion == 'Consumo') {
                            $monto_consumo = $monto_consumo + $credito_datos->monto;
                        }

                        if ($credito_datos->tipo_operacion == 'Servicio') {
                            $monto_servicio = $monto_servicio + $credito_datos->monto;
                        }
                    }
                }

                //consultamos la tabla creditos para traer los datos de nombre,cedula entre otros.

                $cliente =  Credito::findOrFail($id_cliente);
                // return $cliente;

                if ($facturas_pagadas == 'una' || $facturas_pagadas == 'todas' || $facturas_pagadas == 'abonar') {
                    // return 'abonar';
                    if ($monto_dejado_abono >= $total_costo) {
                        // return $total_costo . ' - ' . $monto_dejado_abono;
                        // return 'factura ==';
                        $resta_costo_abono = 0;
                    } else {
                        // return 'factura <';
                        $resta_costo_abono = $monto_dejado_abono;
                    }
                }
                // return $total_costo . ' - ' . $monto_dejado_abono;
                $detalle_creditos_pagados = new Detalle_Creditos_Pagado();
                $detalle_creditos_pagados->nombre_cliente = $cliente->nombre_cliente;
                $detalle_creditos_pagados->cedula_cliente = $cliente->cedula_cliente;
                $detalle_creditos_pagados->direccion_cliente = $cliente->direccion_cliente;
                $detalle_creditos_pagados->telefono_cliente = $cliente->telefono_cliente;
                $detalle_creditos_pagados->tipo_pago = $tipo_pago;
                $detalle_creditos_pagados->total_factura = $total_factura;
                $detalle_creditos_pagados->total_Consumo = $monto_consumo;
                $detalle_creditos_pagados->total_Servicio = $monto_servicio;
                $detalle_creditos_pagados->total_deuda = $total_costo - $resta_costo_abono;
                $detalle_creditos_pagados->fecha_pago = $fecha;
                $detalle_creditos_pagados->estado_credito = $cliente->estado_credito;;
                $detalle_creditos_pagados->persona_id = $cliente->persona_id;
                $detalle_creditos_pagados->user_id = $operador_id;
                $detalle_creditos_pagados->caja_id = $caja_id;
                $detalle_creditos_pagados->save();
                // return $detalle_creditos_pagados->id;

                foreach ($creditos_ids as $ids) {

                    // return $ids;
                    if ($ids->estado_pago == 'Pendiente') {
                        $idFacturasPagadas[] = $ids->id;

                        // Llenamos la tabla Creditos_pagados
                        $detalle_credito_datos = Detalle_credito::findOrFail($ids->id);

                        if ($facturas_pagadas == 'una' || $facturas_pagadas == 'todas' || $facturas_pagadas == 'abonar') {

                            if ($monto_dejado_abono >= $total_costo) {
                                $detalle_credito = Detalle_credito::findOrFail($ids->id);
                                $detalle_credito_datos->monto = $detalle_credito_datos->monto -  $detalle_credito->abono;
                            } else {
                                $detalle_credito_datos->monto = $resta_costo_abono;
                            }
                        }
                        // $detalle_credito_datos->monto = $resta_costo_abono;

                        $credito_pagado = new Credito_Pagado();
                        $credito_pagado->numero_factura = $detalle_credito_datos->numero_factura;
                        $credito_pagado->tipo_operacion = $detalle_credito_datos->tipo_operacion;
                        $credito_pagado->operacion_id = $detalle_credito_datos->operacion_id;
                        $credito_pagado->monto = $detalle_credito_datos->monto;
                        $credito_pagado->fecha_emision = $detalle_credito_datos->fecha_emision;
                        $credito_pagado->fecha_vencimiento = $detalle_credito_datos->fecha_vencimiento;
                        $credito_pagado->fecha_pago = $fecha;
                        $credito_pagado->estado_credito_al_pagar = $detalle_credito_datos->estado_credito;
                        $credito_pagado->persona_id = $detalle_credito_datos->persona_id;
                        $credito_pagado->user_id = $operador_id;
                        $credito_pagado->detalle__creditos__pagado_id = $detalle_creditos_pagados->id;
                        $credito_pagado->caja_id = $caja_id;
                        $credito_pagado->save();




                        // Actualizamos la tabla Detalle_credito

                        if ($facturas_pagadas == 'una' || $facturas_pagadas == 'todas' || $facturas_pagadas == 'abonar') {

                            if ($monto_dejado_abono >= $total_costo) {
                                $detalle_credito = Detalle_credito::findOrFail($ids->id);
                                $detalle_credito->estado_pago = 'Pagado';
                                $detalle_credito->estado_credito = 'Pagado';
                                $detalle_credito->abono = $detalle_credito->monto;
                                $detalle_credito->fecha_pago = $fecha;
                                $detalle_credito->update();

                                $restar_factura = 1;
                            } else {
                                $detalle_credito = Detalle_credito::findOrFail($ids->id);
                                $detalle_credito->estado_pago = 'Pendiente';
                                $detalle_credito->abono = $detalle_credito->abono + $resta_costo_abono;
                                $detalle_credito->fecha_pago = $fecha;
                                $detalle_credito->update();

                                $restar_factura = 0;
                            }
                        } else {
                            $detalle_credito = Detalle_credito::findOrFail($ids->id);
                            $detalle_credito->estado_pago = 'Pagado';
                            $detalle_credito->estado_credito = 'Pagado';
                            $detalle_credito->abono = $detalle_credito->monto;
                            $detalle_credito->fecha_pago = $fecha;
                            $detalle_credito->update();

                            $restar_factura = 1;
                        }



                        // * Capturamos la nueva fecha de vencimiento
                        $fecha_limite_pago = Detalle_credito::where('persona_id', $request->get('cliente_id'))->where('estado_pago', 'Pendiente')->first();

                        if (!$fecha_limite_pago) {
                            $date   = Carbon::now('America/Caracas');
                            $fecha  = $date->format('y-m-d');
                        } else {
                            $fecha = $fecha_limite_pago->fecha_vencimiento;
                        }

                        $credito = Credito::findOrFail($detalle_credito_datos->credito_id);
                        $ultima_factura = $credito->total_factura - $restar_factura;

                        if ($ultima_factura == 0) {
                            $credito->total_factura = 0;
                            $credito->total_deuda = 0;
                            $credito->fecha_limite_pago = null;
                            $credito->update();
                        } else {
                            if ($facturas_pagadas == 'una' || $facturas_pagadas == 'todas' || $facturas_pagadas == 'abonar') {



                                if ($monto_dejado_abono >= $total_costo) {
                                    $credito->total_factura = $credito->total_factura - $restar_factura;
                                    $credito->total_deuda = $credito->total_deuda - $detalle_credito_datos->monto;
                                    $credito->fecha_limite_pago = $fecha;
                                    $credito->update();
                                } else {
                                    $detalle_credito_datos->monto = $monto_dejado_abono;
                                    $credito->total_factura = $credito->total_factura - $restar_factura;
                                    $credito->total_deuda = $credito->total_deuda - $detalle_credito_datos->monto;
                                    $credito->fecha_limite_pago = $fecha;
                                    $credito->update();
                                }
                            } else {

                                $credito->total_factura = $credito->total_factura - $restar_factura;
                                $credito->total_deuda = $credito->total_deuda - $detalle_credito_datos->monto;
                                $credito->fecha_limite_pago = $fecha;
                                $credito->update();
                            }
                        }
                    }
                }



                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                // TODO creamos metodo para realizar el pago cuando se paga con dinero contable viene en la variable base_vuelto_monto_dejado
                // primero validamos si exciste un pago hecho.

                $montoBase = $request->get('base_vuelto_monto_dejado');
                $montoResta = $request->get('monto_dejadoResta');
                $total_venta = $total_costo;


                $montoBase = floatval($montoBase);
                $montoResta = floatval($montoResta);
                $total_venta = floatval($total_venta);

                // $servicio_id = $servicio->id;
                $caja_id = $request->get('caja_id');

                $opS = $montoBase;

                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                // return $modo_pago;
                //validamos si el monto pagado es mayor a 0 sea que lo paguen con montoBase o con montoPendiente y que el tipo de pago sea contado
                if ($opS > 0 && $modo_pago == 'Contado') {
                    // return $opS;
                    //validamos que el monto pagado sea mayor o igual al total de la venta
                    if ($opS) {
                        // return 'si';
                        // calculamos excedente si el valor pagado es mayor a la venta
                        // $MontoDolarR = $request->get('MontoDolar');
                        // return $montoD;
                        //validamos si montobase es mayor y montopendiente es menor... lo que significa esto es que estamos reciviendo una moneda nueva
                        if ($montoBase > 0) {
                            // return 'pagado con plata nueva = montoBase';
                            //metodo para procesar pago con dinero nuevo
                            $montoD = [];
                            $montoDiv = [];
                            $TasaT = [];

                            // $prueba[]= ['Divisa' => $divisaVueltos[$key], 'MontoDivisa' => $MontoDivisaVueltos[$key], 'TasaTiket' => $TasaTikeVueltos[$key], 'MontoDolar' => $MontoDolarVueltos[$key]];
                            $MontoDivisa = $request->get('MontoDivisa');
                            $divisa = $request->get('divisa');
                            $TasaTike = $request->get('TasaTike');
                            $MontoDolar = $request->get('MontoDolar');

                            $MontoDolar = array_filter($MontoDolar);

                            foreach ($MontoDolar as $key => $val) {

                                $DivisaArray[$divisa[$key]] = $divisa[$key];
                                $montoDivisaArray[$divisa[$key]] = $MontoDivisa[$key];
                                $TasaTikeArray[$divisa[$key]] = $TasaTike[$key];
                                $montoDolarArray[$divisa[$key]] = $MontoDolar[$key];
                            }

                            // return $request;
                            $restk = $total_venta;
                            $totalVuelto = $montoResta;
                            // echo  'total vuelto '.$totalVuelto.'<br>';
                            // return $opS;
                            //  $re[] = '';
                            asort($montoDolarArray);
                            foreach ($montoDolarArray as $key => $val) {
                                if ($val > 0) {
                                    $montoD[$key] = $val;
                                    $montoDiv[$key] = $montoDivisaArray[$key];
                                    $TasaT[$key] = $TasaTikeArray[$key];
                                }
                            }
                            // return $montoDiv;
                            $x = $opS;
                            $residuo = 0;
                            $exc = 0;
                            foreach ($montoD as $p => $value) {

                                if (round($value, 6) == round($restk, 6)) {
                                    echo 'igual <br> ';
                                    echo 'value ' . $value . ' <br> ';
                                    echo 'restk ' . $restk . ' <br> ';
                                    $restk = $restk - $value;
                                    $x = floatval($x - $value);
                                    $restk = floatval($restk);
                                    echo  ' divisa: ' . $p . ' montoDivisa: ' . $montoDiv[$p] . ' tasaTiket: ' . $TasaT[$p] . ' montoDolar: ' . floatval($value) . ' montoDolarConsumo: ' . floatval($value) . '  excedente:  ' . 0 . ' vueltos: ' . 0 . '<br> ';


                                    $Pago_Credito = new Pago_Credito();
                                    $Pago_Credito->Divisa = $p;
                                    $Pago_Credito->MontoDivisa = $montoDiv[$p];
                                    $Pago_Credito->TasaTiket = $TasaT[$p];
                                    $Pago_Credito->MontoDolar = floatval($value);
                                    $Pago_Credito->MontoCredito = $monto_consumo > 0 || $monto_servicio > 0 ? floatval($value) : $monto_consumo + $monto_servicio;
                                    $Pago_Credito->Vueltos = 0;
                                    $Pago_Credito->detalle__creditos__pagado_id = $detalle_creditos_pagados->id;
                                    $Pago_Credito->caja_id = $caja_id;
                                    $Pago_Credito->save();

                                    echo 'excd ' . 0 . ' <br> ';
                                    echo 'vueltos ' . 0 . ' <br> ';
                                    echo $restk . ' <br> ';
                                    $residuo = $restk;
                                } else if (round($value, 6) < round($restk, 6)) {

                                    echo 'value ' . $value . ' <br> ';
                                    echo 'restk ' . $restk . ' <br> ';
                                    echo 'menor <br> ';
                                    $restk = $restk - $value;
                                    echo  ' divisa: ' . $p . ' montoDivisa: ' . $montoDiv[$p] . ' tasaTiket: ' . $TasaT[$p] . ' montoDolar: ' . floatval($value) . ' montoDolarConsumo: ' . floatval($value) . '  excedente:  ' . 0 . ' vueltos: ' . 0 . '<br> ';

                                    $Pago_Credito = new Pago_Credito();
                                    $Pago_Credito->Divisa = $p;
                                    $Pago_Credito->MontoDivisa = $montoDiv[$p];
                                    $Pago_Credito->TasaTiket = $TasaT[$p];
                                    $Pago_Credito->MontoDolar = floatval($value);
                                    $Pago_Credito->MontoCredito = $monto_consumo > 0 || $monto_servicio > 0 ? floatval($value) : $monto_consumo + $monto_servicio;
                                    $Pago_Credito->Vueltos = 0;
                                    $Pago_Credito->detalle__creditos__pagado_id = $detalle_creditos_pagados->id;
                                    $Pago_Credito->caja_id = $caja_id;
                                    $Pago_Credito->save();

                                    echo 'excd ' . 0 . ' <br> ';
                                    echo 'vueltos ' . 0 . ' <br> ';
                                    echo $restk . ' <br> ';
                                    $residuo = $restk;
                                } else if (round($value, 6) > round($restk, 6)) {
                                    // echo 'value '.$value.' <br> ';
                                    // echo 'restk '.$restk.' <br> ';
                                    $residuo = $restk;
                                    $restk =   $value - $restk;
                                    // $vuel = $totalVuelto;
                                    // return $restk;
                                    // if($totalVuelto > 0){
                                    if (round($totalVuelto, 6) > round($restk, 6)) {
                                        // $totalVuelto = $totalVuelto - $restk;
                                        $exc = $restk;
                                        $vuel = 0;
                                    }
                                    if (round($totalVuelto, 6) < round($restk, 6)) {

                                        echo '$totalVuelto: ' . $totalVuelto;
                                        $exc = $restk - $totalVuelto;
                                        $vuel = $totalVuelto;
                                        $totalVuelto = 0;
                                        // $residuo = 0;
                                    }
                                    // }
                                    if (round($totalVuelto, 6) == round($restk, 6)) {
                                        $exc = 0;
                                        $vuel = $totalVuelto;
                                        $totalVuelto = 0;
                                    }
                                    echo 'mayor <br> ';
                                    echo  ' divisa: ' . $p . ' montoDivisa: ' . $montoDiv[$p] . ' tasaTiket: ' . $TasaT[$p] . ' montoDolar: ' . floatval($value) . ' montoDolarConsumo: ' . floatval($residuo) . '  excedente:  ' . $exc . ' vueltos: ' . $vuel . '<br> ';

                                    $Pago_Credito = new Pago_Credito();
                                    $Pago_Credito->Divisa = $p;
                                    $Pago_Credito->MontoDivisa = $montoDiv[$p];
                                    $Pago_Credito->TasaTiket = $TasaT[$p];
                                    $Pago_Credito->MontoDolar = floatval($value);
                                    $Pago_Credito->MontoCredito = $monto_consumo > 0 || $monto_servicio > 0 ? floatval($residuo) : $monto_consumo + $monto_servicio;
                                    $Pago_Credito->Vueltos = $vuel;
                                    $Pago_Credito->detalle__creditos__pagado_id = $detalle_creditos_pagados->id;
                                    $Pago_Credito->caja_id = $caja_id;
                                    $Pago_Credito->save();


                                    if ($vuel > 0) {
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        // En esta seccion trabajaremos la parte de vueltos llenamos la tabla pagos_vueltos

                                        // $isVuelos = $request->get('isVueltos');

                                        if ($montoResta > 0 || $montoResta != null) {
                                            $MontoDivisaVueltos = $request->get('MontoDivisaV');
                                            $divisaVueltos = $request->get('divisaV');
                                            $TasaTikeVueltos = $request->get('TasaTikeV');
                                            $MontoDolarVueltos = $request->get('MontoDolarV');

                                            $MontoDivisaVueltos = array_filter($MontoDivisaVueltos);

                                            foreach ($MontoDivisaVueltos as $key => $val) {

                                                $Vdivisa[] = $divisaVueltos[$key];
                                                $VMontoDivisa[] = $MontoDivisaVueltos[$key];
                                                $VTasaTiket[] = $TasaTikeVueltos[$key];
                                                $VMontoDolar[] = $MontoDolarVueltos[$key];
                                            }
                                            // dd($divisa, $MontoDivisa,$TasaTike,$MontoDolar,$Veltos);
                                            //creamos un contador
                                            $cont = 0;

                                            //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
                                            while ($cont < count($VMontoDolar)) {
                                                $Pago_Extras_Vueltos = new Pago_Vuelto();
                                                $Pago_Extras_Vueltos->Tipo = 'Creditos';
                                                $Pago_Extras_Vueltos->tipo_vuelto = 'Vueltos_Pago';
                                                $Pago_Extras_Vueltos->Divisa = $Vdivisa[$cont];
                                                $Pago_Extras_Vueltos->MontoDivisa = $VMontoDivisa[$cont];
                                                $Pago_Extras_Vueltos->TasaTiket = $VTasaTiket[$cont];
                                                $Pago_Extras_Vueltos->MontoDolar = floatval($VMontoDolar[$cont]);
                                                $Pago_Extras_Vueltos->servicio_id = 1;
                                                $Pago_Extras_Vueltos->venta_id = 0;
                                                $Pago_Extras_Vueltos->horas_extra_id = 0;
                                                $Pago_Extras_Vueltos->detalle__creditos__pagado_id = $detalle_creditos_pagados->id;
                                                $Pago_Extras_Vueltos->caja_id = $caja_id;
                                                $Pago_Extras_Vueltos->save();

                                                echo  'Pago_Vuelto Tipo: Consumo  Divisa: ' . $Vdivisa[$cont] . ' MontoDivisa: ' . $VMontoDivisa[$cont] . ' TasaTiket: ' . $VTasaTiket[$cont] . ' MontoDolar: ' . floatval($VMontoDolar[$cont]) . '<br> ';
                                                $cont = $cont + 1;
                                            }
                                        }
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }
                                    echo 'excd ' . $exc . ' <br> ';
                                    echo 'vueltos ' . $vuel . ' <br> ';
                                    echo $restk . ' <br> ';
                                }
                            }
                            // validamos  si montopendiente es mayor y montobase es menor... lo que significa esto es que estamos reciviendo una moneda pendiente
                        }
                        // return 'Finalizo';

                    } else {
                        return Redirect::back()
                            ->with('status_danger', '¡Error Pago incompleto! Debe ingresar un monto para pagar y procesar el servicio... ');
                    }

                    // return 'no';
                }
            }


            DB::commit();
        } catch (\Exception $e) {

            DB::rollback();
            dd($e);
            return redirect()
                ->route('creditos.index')
                ->with('status_danger', 'Error de Sistema! El pago no se proceso. Por favor llamar a Soporte tecnico...');
        }



        $nombreCliente = Persona::findOrfail($request->get('cliente_id'));

        $printer = new PrinterController;

        $printer->ticketCreditos('Pago Creditos', $facturas_pagadas_id, $nombreCliente, $modo_pago, $tipo_pago, $monto_dejado, $operador, $facturas_pagadas, $idFacturasPagadas);

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
        $detalle_creditos = Detalle_credito::where('persona_id', $cliente_id)->where('estado_pago', 'Pendiente')->get();
        // return $cliente_id;
        $credito = Credito::where('persona_id', $cliente_id)->first();

        $tasaDolarHabitacion = Tasa::where('nombre', '=', 'DolarHabitacion')->first();
        // return $tasaDolarHabitacion->tasa;
        $tasaPesoHabitacion = Tasa::where('nombre', '=', 'PesoHabitacion')->first();
        $tasaDolar = DB::table('tasas')->where('nombre', '=', 'Dolar')->first();
        $tasaPeso = DB::table('tasas')->where('nombre', '=', 'Peso')->first();
        $tasaTransferenciaPunto = DB::table('tasas')->where('nombre', '=', 'Transferencia_Punto')->first();
        $tasaMixto = DB::table('tasas')->where('nombre', '=', 'Mixto')->first();
        $tasaEfectivo = DB::table('tasas')->where('nombre', '=', 'Efectivo')->first();
        $users = User::with('roles')->orderBy('id', 'Desc')->get();
        $UserName = Auth::user()->name;
        $cajaSessionid =  Sessioncaja::where('estado', 'Abierta')->orderBy('id', 'desc')->first();
        $Cajas = Caja::where("estado", "=", 'Abierta')->where("sessioncaja_id", "=", $cajaSessionid->id)->first();
        $caja = Caja::find($Cajas->id);

        // return $detalle_creditos;
        return view('creditos.show', compact('caja', 'title', 'credito', 'detalle_creditos', 'tasaDolarHabitacion', 'tasaPesoHabitacion', 'tasaDolar', 'tasaPeso', 'tasaTransferenciaPunto', 'tasaMixto', 'tasaEfectivo', 'users', 'UserName'));
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
