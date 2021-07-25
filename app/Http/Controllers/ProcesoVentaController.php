<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Tasa;
use App\User;
use App\Venta;
use App\Precio;
use App\Credito;
use App\Persona;
use App\Articulo;
use App\Cortesia;
use App\Servicio;
use App\Excedente;
use Carbon\Carbon;
use App\Pago_Venta;
use App\Pago_Vuelto;
use App\Sessioncaja;
use App\Pago_Credito;
use App\Articulo_venta;
use App\Detalle_credito;
use App\Servicios_Ventas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Excedentes_Recibidos_Caja_Actual;

class ProcesoVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'Pre venta';
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
            $myTime = Carbon::now('America/Caracas');
            // number_format($número, 2, '.', '');




            $total_venta = $request->get('total_venta');
            $serie_comprobante = $request->get('serie_comprobante');
            $operador = $request->get('operador');



            $servicio_id = $request->get('servicio_id');

            $tipo_pago = $request->get('tipo_pago');

            if($tipo_pago == null){
                $tipo_pago = 'No pagado';
            }else{
                $tipo_pago = $request->get('tipo_pago');
            }
            $modo_pago = $request->get('modo_pago');
            $monto_dejado = $request->get('monto_dejado');
            // $monto_dejado = $monto_dejado;
            // return $monto_dejado;
            $total_costo = $request->get('total_costo');
            $status = '';
            $estado_pago = '';

            $base_vuelto_monto_dejado = $request->get('base_vuelto_monto_dejado');
            $monto_dejadoResta = $request->get('monto_dejadoResta');
            $isVueltos = $request->get('isVueltos');

            $VueltospagoConExcedente = $request->get('VueltospagoConExcedente');
            $VueltosdispExcedente = $request->get('VueltosdispExcedente');
                // return $request;

                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        // TODO creamos metodo para realizar el pago cuando se paga con dinero contable viene en la variable base_vuelto_monto_dejado
                        // primero validamos si exciste un pago hecho.

                        $montoBase = $request->get('base_vuelto_monto_dejado');
                        $montoResta = $request->get('monto_dejadoResta');
                        $montoPendiente = $request->get('VueltospagoConExcedente');
                        $total_venta = $request->get('total_venta');


                        $montoBase = floatval($montoBase);
                        $montoPendiente = floatval($montoPendiente);
                        $montoResta = floatval($montoResta);
                        $total_venta = floatval($total_venta);

                        $opS = $montoBase + $montoPendiente;

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // TODO metodo para pagar con vueltos pendientes
            // $VueltospagoConExcedente = $request->get('VueltospagoConExcedente');

            // if($VueltospagoConExcedente > 0){

            //     if($monto_dejado > 0){
            //         $modo_pago = 'contado';
            //         $status = 'Pagado';
            //         $monto_dejado = $VueltospagoConExcedente + $monto_dejado;
            //     }

            //     if($monto_dejado == 0){
            //         $modo_pago = 'contado';
            //         $status = 'Pagado';
            //         $monto_dejado = $VueltospagoConExcedente;
            //     }


            // }else{
            //     $status = 'Pagado';
            //     $modo_pago = $request->get('modo_pago');
            // }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    // return $monto_dejado;

                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    // TODO metodo para pagar con vueltos pendientes
                    // TODO Method to pay with pending returns

                    $VueltospagoConExcedente = $request->get('VueltospagoConExcedente');
                    // return $VueltospagoConExcedente;
                    if($VueltospagoConExcedente > 0){

                        // TODO Verificar que tengamos liquidez en esa divisa para dar vueltos y se procesa
                        //con este codigo buscamos los saldos disponibles en dolar, peso y bolivar
                        // el resultado son 3 variables de nombre $dolarDisponible, $pesoDisponible y $bolivarDisponible.
                        //para luego con esto poder dar los vueltos si hay desponibilidad.


                        $tasaDolar = DB::table('tasas')->where('nombre', '=', 'Dolar')->first();
                        $tasaPeso = DB::table('tasas')->where('nombre', '=', 'Peso')->first();
                        $tasaTransferenciaPunto = DB::table('tasas')->where('nombre', '=', 'Transferencia_Punto')->first();
                        $tasaMixto = DB::table('tasas')->where('nombre', '=', 'Mixto')->first();
                        $tasaEfectivo = DB::table('tasas')->where('nombre', '=', 'Efectivo')->first();

                        $cajas = Caja::find($request->get('caja_id'));
                        $cajas->user;
                        $cajas->ventas;
                        $cajas->pago_ventas;
                        $cajas->articulo_ventas;
                        $cajas->servicios;
                        $cajas->detalle_creditos;
                        // $cajas->credito;
                        $cajas->cortesias;
                        $cajas->pago_servicios;
                        $cajas->pago_creditos;
                        $cajas->creditos_pagados;
                        $cajas->excedente_actual;



                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        foreach ($cajas->creditos_pagados as $credPagados ) {

                            if ($credPagados->user_id == $cajas->user_id){

                                $validarPagosCreditos = Pago_Credito::where('detalle_credito_id',$credPagados->detalle_credito_id)->get();
                                if (count($validarPagosCreditos)) {
                                    foreach ($validarPagosCreditos as $credPagadosCaja ) {
                                        if ($credPagadosCaja->Divisa == 'Dolar') {
                                            $cajas->SumaTotalDolarCred = $cajas->SumaTotalDolarCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);
                                        }elseif ($credPagadosCaja->Divisa == 'Peso') {
                                            $cajas->SumaTotalPesoCred = $cajas->SumaTotalPesoCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);
                                        }elseif ($credPagadosCaja->Divisa == 'Bolivar') {
                                            $cajas->SumaTotalBolivarCred = $cajas->SumaTotalBolivarCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);
                                        }elseif ($credPagadosCaja->Divisa == 'Punto') {
                                            $cajas->SumaTotalPuntoCred = $cajas->SumaTotalPuntoCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);
                                        }elseif ($credPagadosCaja->Divisa == 'Transferencia') {
                                            $cajas->SumaTotalTransferenciaCred = $cajas->SumaTotalTransferenciaCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);
                                        }
                                    }
                                }

                                if ($credPagados->tipo_operacion == 'Consumo'){

                                    $validarPagosCreditosConsumo = Pago_Credito::where('detalle_credito_id',$credPagados->detalle_credito_id)->get();
                                    if (count($validarPagosCreditosConsumo)) {
                                        foreach ($validarPagosCreditosConsumo as $credPagadosCajaConsumo ) {
                                            if ($credPagadosCajaConsumo->Divisa == 'Dolar') {
                                                $cajas->SumaTotalDolarCredConsumo = $cajas->SumaTotalDolarCredConsumo + ($credPagadosCajaConsumo->MontoDivisa - $credPagadosCajaConsumo->Vueltos * -1);
                                            }elseif ($credPagadosCajaConsumo->Divisa == 'Peso') {
                                                $cajas->SumaTotalPesoCredConsumo = $cajas->SumaTotalPesoCredConsumo + ($credPagadosCajaConsumo->MontoDivisa - $credPagadosCajaConsumo->Vueltos * -1);
                                            }elseif ($credPagadosCajaConsumo->Divisa == 'Bolivar') {
                                                $cajas->SumaTotalBolivarCredConsumo = $cajas->SumaTotalBolivarCredConsumo + ($credPagadosCajaConsumo->MontoDivisa - $credPagadosCajaConsumo->Vueltos * -1);
                                            }elseif ($credPagadosCajaConsumo->Divisa == 'Punto') {
                                                $cajas->SumaTotalPuntoCredConsumo = $cajas->SumaTotalPuntoCredConsumo + ($credPagadosCajaConsumo->MontoDivisa - $credPagadosCajaConsumo->Vueltos * -1);
                                            }elseif ($credPagadosCajaConsumo->Divisa == 'Transferencia') {
                                                $cajas->SumaTotalTransferenciaCredConsumo = $cajas->SumaTotalTransferenciaCredConsumo + ($credPagadosCajaConsumo->MontoDivisa - $credPagadosCajaConsumo->Vueltos * -1);
                                            }
                                        }
                                    }

                                    $cajas->SumaTotalCreditosPagadosConsumoPorCaja = $cajas->SumaTotalCreditosPagadosConsumoPorCaja + $credPagados->monto;

                                }

                                if ($credPagados->tipo_operacion == 'Servicio'){

                                    $validarPagosCreditosServicio = Pago_Credito::where('detalle_credito_id',$credPagados->detalle_credito_id)->get();
                                    if (count($validarPagosCreditosServicio)) {
                                        foreach ($validarPagosCreditosServicio as $credPagadosCajaServicio ) {
                                            if ($credPagadosCajaServicio->Divisa == 'Dolar') {
                                                $cajas->SumaTotalDolarCredServicio = $cajas->SumaTotalDolarCredServicio + ($credPagadosCajaServicio->MontoDivisa - $credPagadosCajaServicio->Vueltos * -1);
                                            }elseif ($credPagadosCajaServicio->Divisa == 'Peso') {
                                                $cajas->SumaTotalPesoCredServicio = $cajas->SumaTotalPesoCredServicio + ($credPagadosCajaServicio->MontoDivisa - $credPagadosCajaServicio->Vueltos * -1);
                                            }elseif ($credPagadosCajaServicio->Divisa == 'Bolivar') {
                                                $cajas->SumaTotalBolivarCredServicio = $cajas->SumaTotalBolivarCredServicio + ($credPagadosCajaServicio->MontoDivisa - $credPagadosCajaServicio->Vueltos * -1);
                                            }elseif ($credPagadosCajaServicio->Divisa == 'Punto') {
                                                $cajas->SumaTotalPuntoCredServicio = $cajas->SumaTotalPuntoCredServicio + ($credPagadosCajaServicio->MontoDivisa - $credPagadosCajaServicio->Vueltos * -1);
                                            }elseif ($credPagadosCajaServicio->Divisa == 'Transferencia') {
                                                $cajas->SumaTotalTransferenciaCredServicio = $cajas->SumaTotalTransferenciaCredServicio + ($credPagadosCajaServicio->MontoDivisa - $credPagadosCajaServicio->Vueltos * -1);
                                            }
                                        }
                                    }

                                    $cajas->SumaTotalCreditosPagadosServicioPorCaja = $cajas->SumaTotalCreditosPagadosServicioPorCaja + $credPagados->monto;

                                }
                                    $cajas->SumaTotalCreditosPagadosTotalesPorCaja = $cajas->SumaTotalCreditosPagadosTotalesPorCaja + $credPagados->monto;

                            }else{

                                $validarPagosCreditos = Pago_Credito::where('detalle_credito_id',$credPagados->detalle_credito_id)->get();
                                if (count($validarPagosCreditos)) {
                                    foreach ($validarPagosCreditos as $credPagadosOficina ) {
                                        if ($credPagadosOficina->Divisa == 'Dolar') {
                                            $cajas->SumaTotalDolarCredPorOficina = $cajas->SumaTotalDolarCredPorOficina + ($credPagadosOficina->MontoDivisa - $credPagadosOficina->Vueltos * -1);
                                        }elseif ($credPagadosOficina->Divisa == 'Peso') {
                                            $cajas->SumaTotalPesoCredPorOficina = $cajas->SumaTotalPesoCredPorOficina + ($credPagadosOficina->MontoDivisa - $credPagadosOficina->Vueltos * -1);
                                        }elseif ($credPagadosOficina->Divisa == 'Bolivar') {
                                            $cajas->SumaTotalBolivarCredPorOficina = $cajas->SumaTotalBolivarCredPorOficina + ($credPagadosOficina->MontoDivisa - $credPagadosOficina->Vueltos * -1);
                                        }elseif ($credPagadosOficina->Divisa == 'Punto') {
                                            $cajas->SumaTotalPuntoCredPorOficina = $cajas->SumaTotalPuntoCredPorOficina + ($credPagadosOficina->MontoDivisa - $credPagadosOficina->Vueltos * -1);
                                        }elseif ($credPagadosOficina->Divisa == 'Transferencia') {
                                            $cajas->SumaTotalTransferenciaCredPorOficina = $cajas->SumaTotalTransferenciaCredPorOficina + ($credPagadosOficina->MontoDivisa - $credPagadosOficina->Vueltos * -1);
                                        }
                                    }
                                }

                                if ($credPagados->tipo_operacion == 'Consumo'){

                                    $cajas->SumaTotalCreditosPagadosConsumoPorOficina = $cajas->SumaTotalCreditosPagadosConsumoPorOficina + $credPagados->monto;


                                }

                                if ($credPagados->tipo_operacion == 'Servicio'){

                                    $cajas->SumaTotalCreditosPagadosServicioPorOficina = $cajas->SumaTotalCreditosPagadosServicioPorOficina + $credPagados->monto;


                                }

                                $cajas->SumaTotalCreditosPagadosTotalesPorOficina = $cajas->SumaTotalCreditosPagadosTotalesPorOficina + $credPagados->monto;

                            }


                            if ($credPagados->tipo_operacion == 'Servicio') {

                                // $cajas->SumaTotalCreditosPagadosServicio = $cajas->SumaTotalCreditosPagadosServicio + $credPagados->monto;
                                $cajas->SumaTotalCantidadCreditosPagadosServicio = $cajas->SumaTotalCantidadCreditosPagadosServicio + 1;

                            }

                            if ($credPagados->tipo_operacion == 'Consumo') {

                                // $cajas->SumaTotalCreditosPagadosConsumo = $cajas->SumaTotalCreditosPagadosConsumo + $credPagados->monto;
                                $cajas->SumaTotalCantidadCreditosPagadosConsumo =  $cajas->SumaTotalCantidadCreditosPagadosConsumo + 1;

                            }
                            // $cajas->SumaTotalCreditosPagadosTotales = $cajas->SumaTotalCreditosPagadosTotales + $credPagados->monto;
                            $cajas->SumaTotalCantidadCreditosPagadosTotales = $cajas->SumaTotalCantidadCreditosPagadosTotales + 1;

                        }
                        // return $cajas->SumaTotalCreditosPagados;
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        // TODO optenemos los datos de la tabla excedente actual

                        foreach ($cajas->excedente_actual as $excedenteActual) {

                            // TODO sacamos los totales devueltos recibidos por excedentes totales
                            if ($excedenteActual->Divisa == 'Dolar') {
                                $cajas->TotalSumaVueltosPendientesDolarDivisa = $cajas->TotalSumaVueltosPendientesDolarDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->TotalSumaVueltosPendientesDolarDolar = $cajas->TotalSumaVueltosPendientesDolarDolar  + $excedenteActual->MontoDolar;
                            }elseif ($excedenteActual->Divisa == 'Peso') {
                                $cajas->TotalSumaVueltosPendientesPesoDivisa = $cajas->TotalSumaVueltosPendientesPesoDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->TotalSumaVueltosPendientesPesoDolar = $cajas->TotalSumaVueltosPendientesPesoDolar  + $excedenteActual->MontoDolar;
                            }elseif ($excedenteActual->Divisa == 'Bolivar') {
                                $cajas->TotalSumaVueltosPendientesBolivarDivisa = $cajas->TotalSumaVueltosPendientesBolivarDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->TotalSumaVueltosPendientesBolivarDolar = $cajas->TotalSumaVueltosPendientesBolivarDolar  + $excedenteActual->MontoDolar;
                            }elseif ($excedenteActual->Divisa == 'Punto') {
                                $cajas->TotalSumaVueltosPendientesPuntoDivisa = $cajas->TotalSumaVueltosPendientesPuntoDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->TotalSumaVueltosPendientesPuntoDolar = $cajas->TotalSumaVueltosPendientesPuntoDolar  + $excedenteActual->MontoDolar;
                            }elseif ($excedenteActual->Divisa == 'Transferencia') {
                                $cajas->TotalSumaVueltosPendientesTransferenciaDivisa = $cajas->TotalSumaVueltosPendientesTransferenciaDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->TotalSumaVueltosPendientesTransferenciaDolar = $cajas->TotalSumaVueltosPendientesTransferenciaDolar  + $excedenteActual->MontoDolar;
                            }


                            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            // TODO hacemos subconsultas

                            if ($excedenteActual->Estado == 'Pendiente') {
                                if ($excedenteActual->Divisa == 'Dolar') {
                                    $cajas->SumaVueltosPendientesDolarDivisa = $cajas->SumaVueltosPendientesDolarDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPendientesDolarDolar = $cajas->SumaVueltosPendientesDolarDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Peso') {
                                    $cajas->SumaVueltosPendientesPesoDivisa = $cajas->SumaVueltosPendientesPesoDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPendientesPesoDolar = $cajas->SumaVueltosPendientesPesoDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Bolivar') {
                                    $cajas->SumaVueltosPendientesBolivarDivisa = $cajas->SumaVueltosPendientesBolivarDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPendientesBolivarDolar = $cajas->SumaVueltosPendientesBolivarDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Punto') {
                                    $cajas->SumaVueltosPendientesPuntoDivisa = $cajas->SumaVueltosPendientesPuntoDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPendientesPuntoDolar = $cajas->SumaVueltosPendientesPuntoDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Transferencia') {
                                    $cajas->SumaVueltosPendientesTransferenciaDivisa = $cajas->SumaVueltosPendientesTransferenciaDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPendientesTransferenciaDolar = $cajas->SumaVueltosPendientesTransferenciaDolar  + $excedenteActual->MontoDolar;
                                }
                            }elseif($excedenteActual->Estado == 'Devueltos'){
                                if ($excedenteActual->Divisa == 'Dolar') {
                                    $cajas->SumaVueltosDevueltosDolarDivisa = $cajas->SumaVueltosDevueltosDolarDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosDevueltosDolarDolar = $cajas->SumaVueltosDevueltosDolarDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Peso') {
                                    $cajas->SumaVueltosDevueltosPesoDivisa = $cajas->SumaVueltosDevueltosPesoDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosDevueltosPesoDolar = $cajas->SumaVueltosDevueltosPesoDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Bolivar') {
                                    $cajas->SumaVueltosDevueltosBolivarDivisa = $cajas->SumaVueltosDevueltosBolivarDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosDevueltosBolivarDolar = $cajas->SumaVueltosDevueltosBolivarDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Punto') {
                                    $cajas->SumaVueltosDevueltosPuntoDivisa = $cajas->SumaVueltosDevueltosPuntoDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosDevueltosPuntoDolar = $cajas->SumaVueltosDevueltosPuntoDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Transferencia') {
                                    $cajas->SumaVueltosDevueltosTransferenciaDivisa = $cajas->SumaVueltosDevueltosTransferenciaDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosDevueltosTransferenciaDolar = $cajas->SumaVueltosDevueltosTransferenciaDolar  + $excedenteActual->MontoDolar;
                                }
                            }elseif($excedenteActual->Estado == 'PagarOficina'){
                                if ($excedenteActual->Divisa == 'Dolar') {
                                    $cajas->SumaVueltosPagarOficinaDolarDivisa = $cajas->SumaVueltosPagarOficinaDolarDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPagarOficinaDolarDolar = $cajas->SumaVueltosPagarOficinaDolarDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Peso') {
                                    $cajas->SumaVueltosPagarOficinaPesoDivisa = $cajas->SumaVueltosPagarOficinaPesoDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPagarOficinaPesoDolar = $cajas->SumaVueltosPagarOficinaPesoDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Bolivar') {
                                    $cajas->SumaVueltosPagarOficinaBolivarDivisa = $cajas->SumaVueltosPagarOficinaBolivarDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPagarOficinaBolivarDolar = $cajas->SumaVueltosPagarOficinaBolivarDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Punto') {
                                    $cajas->SumaVueltosPagarOficinaPuntoDivisa = $cajas->SumaVueltosPagarOficinaPuntoDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPagarOficinaPuntoDolar = $cajas->SumaVueltosPagarOficinaPuntoDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Transferencia') {
                                    $cajas->SumaVueltosPagarOficinaTransferenciaDivisa = $cajas->SumaVueltosPagarOficinaTransferenciaDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPagarOficinaTransferenciaDolar = $cajas->SumaVueltosPagarOficinaTransferenciaDolar  + $excedenteActual->MontoDolar;
                                }
                            }elseif($excedenteActual->Estado == 'ExcedenteNuevo'){
                                if ($excedenteActual->Divisa == 'Dolar') {
                                    $cajas->SumaVueltosExcedenteNuevoDolarDivisa = $cajas->SumaVueltosExcedenteNuevoDolarDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosExcedenteNuevoDolarDolar = $cajas->SumaVueltosExcedenteNuevoDolarDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Peso') {
                                    $cajas->SumaVueltosExcedenteNuevoPesoDivisa = $cajas->SumaVueltosExcedenteNuevoPesoDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosExcedenteNuevoPesoDolar = $cajas->SumaVueltosExcedenteNuevoPesoDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Bolivar') {
                                    $cajas->SumaVueltosExcedenteNuevoBolivarDivisa = $cajas->SumaVueltosExcedenteNuevoBolivarDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosExcedenteNuevoBolivarDolar = $cajas->SumaVueltosExcedenteNuevoBolivarDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Punto') {
                                    $cajas->SumaVueltosExcedenteNuevoPuntoDivisa = $cajas->SumaVueltosExcedenteNuevoPuntoDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosExcedenteNuevoPuntoDolar = $cajas->SumaVueltosExcedenteNuevoPuntoDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Transferencia') {
                                    $cajas->SumaVueltosExcedenteNuevoTransferenciaDivisa = $cajas->SumaVueltosExcedenteNuevoTransferenciaDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosExcedenteNuevoTransferenciaDolar = $cajas->SumaVueltosExcedenteNuevoTransferenciaDolar  + $excedenteActual->MontoDolar;
                                }

                                if($excedenteActual->Tipo == 'Servicio'){
                                    $cajas->TotalSumaVueltosExcedenteNuevoServicioDolarToDolar = $excedenteActual->MontoDolar;
                                }
                                if($excedenteActual->Tipo == 'Consumo'){
                                    $cajas->TotalSumaVueltosExcedenteNuevoConsumoDolarToDolar = $excedenteActual->MontoDolar;
                                }

                                if($excedenteActual->Tipo == 'Otros'){
                                    $cajas->TotalSumaVueltosExcedenteNuevoOtrosDolarToDolar = $excedenteActual->MontoDolar;
                                }
                                $cajas->TotalSumaVueltosExcedenteNuevoDolarToDolar = $excedenteActual->MontoDolar;
                            }
                        }

                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        foreach ($cajas->pago_servicios as $pagoS ) {

                            $validarPagosServicios = Servicio::where('id',$pagoS->servicio_id)->first();
                            if ($validarPagosServicios) {
                                if ($pagoS->Divisa == 'Dolar') {
                                    if($pagoS->Vueltos > 0){
                                        $cajas->SumaTotalDolarServ = $cajas->SumaTotalDolarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - $validarPagosServicios->excedente_nuevo;
                                    }else{
                                        $cajas->SumaTotalDolarServDflotante = $cajas->SumaTotalDolarServDflotante + ($pagoS->Vueltos * -1);
                                        $cajas->SumaTotalDolarServ = $cajas->SumaTotalDolarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - $validarPagosServicios->excedente_nuevo;
                                    }
                                }elseif ($pagoS->Divisa == 'Peso') {
                                    if($pagoS->Vueltos > 0){
                                        $cajas->SumaTotalPesoServ = $cajas->SumaTotalPesoServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaPeso->tasa);
                                    }else{
                                        $cajas->SumaTotalPesoServDflotante = $cajas->SumaTotalPesoServDflotante + ( $pagoS->Vueltos * -1);
                                        $cajas->SumaTotalPesoServ = $cajas->SumaTotalPesoServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaPeso->tasa);
                                    }
                                }elseif ($pagoS->Divisa == 'Bolivar') {
                                    if($pagoS->Vueltos > 0){
                                        $cajas->SumaTotalBolivarServ = $cajas->SumaTotalBolivarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaEfectivo->tasa);
                                    }else{
                                        $cajas->SumaTotalBolivarServDflotante = $cajas->SumaTotalBolivarServDflotante + ($pagoS->Vueltos * -1) * $tasaEfectivo->tasa;
                                        $cajas->SumaTotalBolivarServ = $cajas->SumaTotalBolivarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaEfectivo->tasa);
                                    }
                                }elseif ($pagoS->Divisa == 'Punto') {
                                    if($pagoS->Vueltos > 0){
                                        $cajas->SumaTotalPuntoServ = $cajas->SumaTotalPuntoServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                                    }else{
                                        $cajas->SumaTotalPuntoServDflotante = $cajas->SumaTotalPuntoServDflotante + ($pagoS->Vueltos * -1) * $tasaTransferenciaPunto->tasa;
                                        $cajas->SumaTotalPuntoServ = $cajas->SumaTotalPuntoServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                                    }
                                }elseif ($pagoS->Divisa == 'Transferencia') {
                                    if($pagoS->Vueltos > 0){
                                        $cajas->SumaTotalTransferenciaServ = $cajas->SumaTotalTransferenciaServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                                    }else{
                                        $cajas->SumaTotalTransferenciaServDflotante = $cajas->SumaTotalTransferenciaServDflotante + ($pagoS->Vueltos * -1) * $tasaTransferenciaPunto->tasa;
                                        $cajas->SumaTotalTransferenciaServ = $cajas->SumaTotalTransferenciaServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                                    }
                                }
                            }

                        }

                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        foreach ($cajas->pago_ventas as $pago ) {

                            if ($pago->Divisa == 'Dolar') {
                                $cajas->SumaTotalDolar = $cajas->SumaTotalDolar + ($pago->MontoDivisa - $pago->Vueltos * -1);
                            }elseif ($pago->Divisa == 'Peso') {
                                $cajas->SumaTotalPeso = $cajas->SumaTotalPeso + ($pago->MontoDivisa - $pago->Vueltos * -1);
                            }elseif ($pago->Divisa == 'Bolivar') {
                                $cajas->SumaTotalBolivar = $cajas->SumaTotalBolivar + ($pago->MontoDivisa - $pago->Vueltos * -1);
                            }elseif ($pago->Divisa == 'Punto') {
                                $cajas->SumaTotalPunto = $cajas->SumaTotalPunto + ($pago->MontoDivisa - $pago->Vueltos * -1);
                            }elseif ($pago->Divisa == 'Transferencia') {
                                $cajas->SumaTotalTransferencia = $cajas->SumaTotalTransferencia + ($pago->MontoDivisa - $pago->Vueltos * -1);
                            }

                        }
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        foreach ($cajas->pago_extras as $pagoext ) {

                            if ($pagoext->Divisa == 'Dolar') {
                                $cajas->SumaTotalDolarPagoExtra = $cajas->SumaTotalDolarPagoExtra + ($pagoext->MontoDivisa - $pagoext->Vueltos * -1);
                            }elseif ($pagoext->Divisa == 'Peso') {
                                $cajas->SumaTotalPesoPagoExtra = $cajas->SumaTotalPesoPagoExtra + ($pagoext->MontoDivisa - $pagoext->Vueltos * -1);
                            }elseif ($pagoext->Divisa == 'Bolivar') {
                                $cajas->SumaTotalBolivarPagoExtra = $cajas->SumaTotalBolivarPagoExtra + ($pagoext->MontoDivisa - $pagoext->Vueltos * -1);
                            }elseif ($pagoext->Divisa == 'Punto') {
                                $cajas->SumaTotalPuntoPagoExtra = $cajas->SumaTotalPuntoPagoExtra + ($pagoext->MontoDivisa - $pagoext->Vueltos * -1);
                            }elseif ($pagoext->Divisa == 'Transferencia') {
                                $cajas->SumaTotalTransferenciaPagoExtra = $cajas->SumaTotalTransferenciaPagoExtra + ($pagoext->MontoDivisa - $pagoext->Vueltos * -1);
                            }

                        }
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        $excedentesPendientes = Excedentes_Recibidos_Caja_Actual::where('Estado','Pendiente')->get();
                        if ($excedentesPendientes) {


                            foreach ($excedentesPendientes as $excdPendientes ) {
                                // return $excdPendientes->Divisa;
                                if ($excdPendientes->Divisa == 'Dolar') {
                                    $cajas->SumaTotalDolarExcedentesPendientes = $cajas->SumaTotalDolarExcedentesPendientes + ($excdPendientes->MontoDivisa);
                                    // return $cajas->SumaTotalDolarExcedentesPendientes;
                                }elseif ($excdPendientes->Divisa == 'Peso') {
                                    $cajas->SumaTotalPesoExcedentesPendientes = $cajas->SumaTotalPesoExcedentesPendientes + ($excdPendientes->MontoDivisa);
                                }elseif ($excdPendientes->Divisa == 'Bolivar') {
                                    $cajas->SumaTotalBolivarExcedentesPendientes = $cajas->SumaTotalBolivarExcedentesPendientes + ($excdPendientes->MontoDivisa);
                                }elseif ($excdPendientes->Divisa == 'Punto') {
                                    $cajas->SumaTotalPuntoExcedentesPendientes = $cajas->SumaTotalPuntoExcedentesPendientes + ($excdPendientes->MontoDivisa);
                                }elseif ($excdPendientes->Divisa == 'Transferencia') {
                                    $cajas->SumaTotalTransferenciaExcedentesPendientes = $cajas->SumaTotalTransferenciaExcedentesPendientes + ($excdPendientes->MontoDivisa);
                                }

                            }
                        }
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        foreach ($cajas->pago_vueltos as $pagoV ) {

                            if ($pagoV->Divisa == 'Dolar') {

                                $cajas->SumaTotalDolarVueltos = $cajas->SumaTotalDolarVueltos + $pagoV->MontoDivisa;

                            }elseif ($pagoV->Divisa == 'Peso') {
                                $cajas->SumaTotalPesoVueltos = $cajas->SumaTotalPesoVueltos + $pagoV->MontoDivisa;
                            }elseif ($pagoV->Divisa == 'Bolivar') {
                                $cajas->SumaTotalBolivarVueltos = $cajas->SumaTotalBolivarVueltos + $pagoV->MontoDivisa;
                            }elseif ($pagoV->Divisa == 'Punto') {
                                $cajas->SumaTotalPuntoVueltos = $cajas->SumaTotalPuntoVueltos + $pagoV->MontoDivisa;
                            }elseif ($pagoV->Divisa == 'Transferencia') {
                                $cajas->SumaTotalTransferenciaVueltos = $cajas->SumaTotalTransferenciaVueltos + $pagoV->MontoDivisa;
                            }

                        }

                        // return $cajas->SumaTotalDolarExcedentesPendientes;


                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        // TODO Ahora le agregamos caja chica

                        // return 'algo';
                        $dolarDisponible = ($cajas->SumaTotalDolarExcedentesPendientes + $cajas->SumaTotalDolarPagoExtra + $cajas->monto_dolar + ($cajas->SumaTotalDolarCredConsumo + $cajas->SumaTotalDolarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoDolarDivisa) + ($cajas->SumaTotalDolarServ + $cajas->SumaTotalDolar) + ($cajas->SumaVueltosDevueltosDolarDivisa + $cajas->SumaTotalDolarServDflotante) - $cajas->SumaTotalDolarVueltos);
                        // return $dolarDisponible;
                        $pesoDisponible = ($cajas->SumaTotalPesoExcedentesPendientes + $cajas->SumaTotalPesoPagoExtra + $cajas->monto_peso + ($cajas->SumaTotalPesoCredConsumo + $cajas->SumaTotalPesoCredServicio) + ($cajas->SumaVueltosExcedenteNuevoPesoDivisa) + ($cajas->SumaTotalPesoServ + $cajas->SumaTotalPeso) + ($cajas->SumaVueltosDevueltosPesoDivisa + $cajas->SumaTotalPesoServDflotante) - $cajas->SumaTotalPesoVueltos);
                        // return $pesoDisponible;
                        $bolivarDisponible = ($cajas->SumaTotalBolivarExcedentesPendientes + $cajas->SumaTotalBolivarPagoExtra + $cajas->monto_bolivar + ($cajas->SumaTotalBolivarCredConsumo + $cajas->SumaTotalBolivarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoBolivarDivisa) + ($cajas->SumaTotalBolivarServ + $cajas->SumaTotalBolivar) + ($cajas->SumaVueltosDevueltosBolivarDivisa + $cajas->SumaTotalBolivarServDflotante) - $cajas->SumaTotalBolivarVueltos);
                        // return $bolivarDisponible;







                        // TODO Crear proceso que maneje el pago con vueltos pendiente

                        // TODO Consultamos la base de datos y sumamos el total de excedentes que tiene ese servicio
                                    $TotalExcedenteData = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$servicio_id)
                                    ->where('caja_id',$request->get('caja_id'))
                                    ->where('Estado','Pendiente')
                                    ->select(DB::raw('SUM(MontoDolar) as totalExcedente'))
                                    ->get();
                                    $TotalExcedenteSumado = floatval($TotalExcedenteData[0]->totalExcedente);

                                    // return $TotalExcedente;


                        // TODO Ir a la tabla Excedentes_Recibidos_Caja_Actual para actualizar el registro y restar los vueltos pendientes
                        // $RestarVtossPtesToVtosPtes = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$servicio_id)->first();

                        //realizamos la consulta en la base de datos y ordenamos los datos de menor a mayor sobre la columna MontoDolar
                        //para que luego reste el pago con vueltos pendientes
                         $RestarVtossPtesToVtosPtes = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$servicio_id)
                         ->where('caja_id',$request
                         ->get('caja_id'))
                         ->where('Estado','Pendiente')
                         ->orderBy('MontoDolar', 'ASC')
                         ->get();

                        // return $RestarVtossPtesToVtosPtes;


                        // return $TasaT;
                                    // $restk = $total_venta;

                                    // // return $restk;
                                    // $excedenteMonto = 0;
                                    // $x = $opS;
                                    // echo $restk. '<br> ';
                                    // foreach ($RestarVtossPtesToVtosPtes as $key) {

                                    //     // if($restk > 0){

                                    //     $restk = $restk - $key->MontoDolar;
                                    //     // return $restk;
                                    //     $x = floatval($x - $key->MontoDolar);
                                    //     $restk = floatval($restk);
                                    //     if($restk > 0){
                                    //         $excedenteMonto = 0;

                                    //         echo $restk.' menos: '.$key->MontoDolar. '<br> ';
                                            // echo  ' divisa: '.$p.' montoDivisa: '.$montoDiv[$p].' tasaTiket: '.$TasaT[$p].' montoDolar: '.floatval($value).' montoDolarConsumo: '.floatval($value).'  excedente:  '.$excedenteMonto.' <br> ';
                                            // echo $restk.' <br> ';

                                            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// return 'ya';
                                            // if ($RestarVtossPtesToVtosPtes) {
                                            //     $RestarVtossPtesToVtosPtes->MontoDivisa = $RestarVtossPtesToVtosPtes->MontoDivisa - ($key->MontoDolar * $$key->TasaTiket);
                                            //     $RestarVtossPtesToVtosPtes->MontoDolar = $RestarVtossPtesToVtosPtes->MontoDolar - $key->MontoDolar;
                                            //     $RestarVtossPtesToVtosPtes->update();


                                            //     // TODO Ir a la tabla Excedentes_Recibidos_Caja_Actual y crear un registro nuevo con el monto pagado pero con estatus Devueltos flotantes en la misma divisa

                                            //     $AgregarVtossPtesToVtosPtes = new Excedentes_Recibidos_Caja_Actual();
                                            //     $AgregarVtossPtesToVtosPtes->Tipo = 'Consumo';
                                            //     $AgregarVtossPtesToVtosPtes->Estado = 'Devueltos';
                                            //     $AgregarVtossPtesToVtosPtes->Divisa = $key->Divisa;
                                            //     $AgregarVtossPtesToVtosPtes->MontoDivisa = ($key->MontoDolar * $key->TasaTiket);
                                            //     $AgregarVtossPtesToVtosPtes->TasaTiket = $key->TasaTiket;
                                            //     $AgregarVtossPtesToVtosPtes->MontoDolar = $key->MontoDolar;
                                            //     $AgregarVtossPtesToVtosPtes->servicio_id = $servicio_id;
                                            //     $AgregarVtossPtesToVtosPtes->caja_id = $request->get('caja_id');
                                            //     $AgregarVtossPtesToVtosPtes->save();

                                            //     // return $AgregarVtossPtesToVtosPtes->id;

                                            //     // TODO verificar si despues de la actualizacion el registro que en 0 si es así procedemos a borrarlo de lo contrario se deja quieto
                                            //     if($RestarVtossPtesToVtosPtes->MontoDolar == 0){
                                            //         Excedentes_Recibidos_Caja_Actual::destroy($key->id);
                                            //     }
                                            // }
                                            // ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                            // ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                                            // $Pago_Venta = new Pago_Venta();
                                            // $Pago_Venta->Divisa = $p;
                                            // $Pago_Venta->MontoDivisa = $montoDiv[$p];
                                            // $Pago_Venta->TasaTiket = $TasaT[$p];
                                            // $Pago_Venta->MontoDolar = floatval($value);
                                            // $Pago_Venta->MontoDolarConsumo = floatval($value);
                                            // $Pago_Venta->Excedente = $excedenteMonto;
                                            // $Pago_Venta->Vueltos = 0;///////////////////////////////////////////ojo/////////////////////////////////////////
                                            // $Pago_Venta->servicio_id = $servicio_id;
                                            // $Pago_Venta->caja_id = $request->get('caja_id');
                                            // $Pago_Venta->venta_id = $venta->id;
                                            // $Pago_Venta->save();


                                        // }
//                                         if($restk <= 0){
// // return 'menor a 0';
//                                             if ($montoResta > 0) {
//                                                 $vv = ($restk * -1) - $montoResta;
//                                                 $vv7 = floatval($key->MontoDolar) - floatval($key->MontoDolar - ($restk * -1)) - $montoResta;
//                                             }else{
//                                                 $vv = $restk;
//                                                 $vv7 = floatval($key->MontoDolar) - floatval($key->MontoDolar - ($restk * -1));
//                                             }
//                                             echo $restk.' menosss: '.$key->MontoDolar. '<br> ';
                                            // $Pago_Venta = new Pago_Venta();
                                            // $Pago_Venta->Divisa = $p;
                                            // $Pago_Venta->MontoDivisa = $montoDiv[$p];
                                            // $Pago_Venta->TasaTiket = $TasaT[$p];
                                            // $Pago_Venta->MontoDolar = floatval($value);
                                            // $Pago_Venta->MontoDolarConsumo = floatval($value - ($restk * -1));
                                            // $Pago_Venta->Excedente = $vv7;
                                            // $Pago_Venta->Vueltos = $montoResta;
                                            // $Pago_Venta->servicio_id = $servicio_id;
                                            // $Pago_Venta->caja_id = $request->get('caja_id');
                                            // $Pago_Venta->venta_id = $venta->id;
                                            // $Pago_Venta->save();

                                            // if($vv7 > 0){
                                                // $excdtsRecibidosCaja = new Excedentes_Recibidos_Caja_Actual();
                                                // $excdtsRecibidosCaja->Tipo = 'Consumo';
                                                // $excdtsRecibidosCaja->Estado = 'Pendiente';
                                                // $excdtsRecibidosCaja->Divisa = $p;
                                                // $excdtsRecibidosCaja->MontoDivisa = floatval($vv7 * $TasaT[$p]);
                                                // $excdtsRecibidosCaja->TasaTiket = $TasaT[$p];
                                                // $excdtsRecibidosCaja->MontoDolar = floatval($vv7);
                                                // $excdtsRecibidosCaja->servicio_id = $servicio_id;
                                                // $excdtsRecibidosCaja->caja_id = $request->get('caja_id');;
                                                // $excdtsRecibidosCaja->save();
                                            // }

                                                    // $x = $x - floatval($value - ($restk * -1));

                                                // echo  ' divisa: '.$p.' = montoDivisa: '.$montoDiv[$p].' tasaTiket: '.$TasaT[$p].' montoDolar: '.floatval($value).' montoDolarConsumo: '.floatval($value - ($restk * -1)).'  excedentesss:  '.$vv7.' => '.$montoExcedente.' vueltos: '.$montoResta.'<br> ';
                                                // echo $restk.' <br> ';
                                            // }

                                                // unset($prueba2[$p]);
                                            // }

                                    // }
// return 'finalizo';
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        // if ($RestarVtossPtesToVtosPtes) {
                        //     $RestarVtossPtesToVtosPtes->MontoDivisa = $RestarVtossPtesToVtosPtes->MontoDivisa - ($VueltospagoConExcedente * $RestarVtossPtesToVtosPtes->TasaTiket);
                        //     $RestarVtossPtesToVtosPtes->MontoDolar = $RestarVtossPtesToVtosPtes->MontoDolar - $VueltospagoConExcedente;
                        //     // $RestarVtossPtesToVtosPtes->update();


                        //     // TODO Ir a la tabla Excedentes_Recibidos_Caja_Actual y crear un registro nuevo con el monto pagado pero con estatus Devueltos flotantes en la misma divisa

                        //     $AgregarVtossPtesToVtosPtes = new Excedentes_Recibidos_Caja_Actual();
                        //     $AgregarVtossPtesToVtosPtes->Tipo = 'Consumo';
                        //     $AgregarVtossPtesToVtosPtes->Estado = 'Devueltos';
                        //     $AgregarVtossPtesToVtosPtes->Divisa = $RestarVtossPtesToVtosPtes->Divisa;
                        //     $AgregarVtossPtesToVtosPtes->MontoDivisa = ($VueltospagoConExcedente * $RestarVtossPtesToVtosPtes->TasaTiket);
                        //     $AgregarVtossPtesToVtosPtes->TasaTiket = $RestarVtossPtesToVtosPtes->TasaTiket;
                        //     $AgregarVtossPtesToVtosPtes->MontoDolar = $VueltospagoConExcedente;
                        //     $AgregarVtossPtesToVtosPtes->servicio_id = $servicio_id;
                        //     $AgregarVtossPtesToVtosPtes->caja_id = $request->get('caja_id');
                        //     // $AgregarVtossPtesToVtosPtes->save();

                        //     // return $AgregarVtossPtesToVtosPtes->id;

                        //     // TODO verificar si despues de la actualizacion el registro que en 0 si es así procedemos a borrarlo de lo contrario se deja quieto
                        //     if($RestarVtossPtesToVtosPtes->MontoDolar == 0){
                        //         Excedentes_Recibidos_Caja_Actual::destroy($RestarVtossPtesToVtosPtes->id);
                        //     }
                        // }


                        // TODO Verificar que tengamos liquidez en esa divisa para dar vueltos y se procesa
                        // $tasaPeso
                        // $tasaTransferenciaPunto
                        // $tasaEfectivo

                        $Restardivisa = '';
                        $RestarMontoDivisa = 0;
                        $RestarTasaTiket = 0;
                        $RestarMontoDolar = 0;

                        // $RestarVtossPtesToVtosPtesDevueltos = Excedentes_Recibidos_Caja_Actual::findOrFail($AgregarVtossPtesToVtosPtes->id);
                        // // return $RestarVtossPtesToVtosPtesDevueltos;

                        // if ($RestarVtossPtesToVtosPtesDevueltos->Estado == 'Devueltos') {
                        //     // return $RestarVtossPtesToVtosPtesDevueltos->Estado;
                        //     if($RestarVtossPtesToVtosPtesDevueltos->Divisa == 'Dolar'){
                        //         // return $dolarDisponible . ' - ' .$VueltospagoConExcedente * 1;
                        //         if ($dolarDisponible >= ($VueltospagoConExcedente * 1)) {
                        //             $Restardivisa = 'Dolar';
                        //             $RestarMontoDivisa = $VueltospagoConExcedente * 1;
                        //             $RestarTasaTiket = $RestarVtossPtesToVtosPtes->TasaTiket;
                        //             $RestarMontoDolar = $VueltospagoConExcedente;

                        //         }else if ($pesoDisponible >= ($VueltospagoConExcedente * $tasaPeso->tasa)) {
                        //             $Restardivisa = 'Peso';
                        //             $RestarMontoDivisa = $VueltospagoConExcedente * $tasaPeso->tasa;
                        //             $RestarTasaTiket = $tasaPeso->tasa;
                        //             $RestarMontoDolar = $VueltospagoConExcedente;
                        //         }else if ($bolivarDisponible >= ($VueltospagoConExcedente * $tasaEfectivo->tasa)) {
                        //             $Restardivisa = 'Bolivar';
                        //             $RestarMontoDivisa = $VueltospagoConExcedente * $tasaEfectivo->tasa;
                        //             $RestarTasaTiket = $tasaEfectivo->tasa;
                        //             $RestarMontoDolar = $VueltospagoConExcedente;
                        //         }
                        //         // return $RestarMontoDivisa;


                        //     }else if($RestarVtossPtesToVtosPtesDevueltos->Divisa == 'Peso'){
                        //         if ($pesoDisponible >= ($VueltospagoConExcedente * $tasaPeso->tasa)) {
                        //             $Restardivisa = 'Peso';
                        //             $RestarMontoDivisa = $VueltospagoConExcedente * $tasaPeso->tasa;
                        //             $RestarTasaTiket = $tasaPeso->tasa;
                        //             $RestarMontoDolar = $VueltospagoConExcedente;
                        //         }else if ($dolarDisponible >= ($VueltospagoConExcedente * 1)) {
                        //             $Restardivisa = 'Dolar';
                        //             $RestarMontoDivisa = $VueltospagoConExcedente * 1;
                        //             $RestarTasaTiket = $RestarVtossPtesToVtosPtes->TasaTiket;
                        //             $RestarMontoDolar = $VueltospagoConExcedente;
                        //         }else if ($bolivarDisponible >= ($VueltospagoConExcedente * $tasaEfectivo->tasa)) {
                        //             $Restardivisa = 'Bolivar';
                        //             $RestarMontoDivisa = $VueltospagoConExcedente * $tasaEfectivo->tasa;
                        //             $RestarTasaTiket = $tasaEfectivo->tasa;
                        //             $RestarMontoDolar = $VueltospagoConExcedente;
                        //         }



                        //     }else if($RestarVtossPtesToVtosPtesDevueltos->Divisa == 'Bolivar'){
                        //         if ($bolivarDisponible >= ($VueltospagoConExcedente * $tasaEfectivo->tasa)) {
                        //             $Restardivisa = 'Bolivar';
                        //             $RestarMontoDivisa = $VueltospagoConExcedente * $tasaEfectivo->tasa;
                        //             $RestarTasaTiket = $tasaEfectivo->tasa;
                        //             $RestarMontoDolar = $VueltospagoConExcedente;
                        //         }else if ($pesoDisponible >= ($VueltospagoConExcedente * $tasaPeso->tasa)) {
                        //             $Restardivisa = 'Peso';
                        //             $RestarMontoDivisa = $VueltospagoConExcedente * $tasaPeso->tasa;
                        //             $RestarTasaTiket = $tasaPeso->tasa;
                        //             $RestarMontoDolar = $VueltospagoConExcedente;
                        //         }else if ($dolarDisponible >= ($VueltospagoConExcedente * 1)) {
                        //             $Restardivisa = 'Dolar';
                        //             $RestarMontoDivisa = $VueltospagoConExcedente * 1;
                        //             $RestarTasaTiket = $RestarVtossPtesToVtosPtes->TasaTiket;
                        //             $RestarMontoDolar = $VueltospagoConExcedente;
                        //         }


                        //     }
                        //     // return 'estoy en new Pago_Vuelto '.$RestarMontoDivisa;
                        //         $Pago_Consumo_Vueltos = new Pago_Vuelto();
                        //         $Pago_Consumo_Vueltos->Tipo = 'Consumo';
                        //         $Pago_Consumo_Vueltos->Divisa = $Restardivisa;
                        //         $Pago_Consumo_Vueltos->MontoDivisa = $RestarMontoDivisa;
                        //         $Pago_Consumo_Vueltos->TasaTiket = $RestarTasaTiket;
                        //         $Pago_Consumo_Vueltos->MontoDolar = $RestarMontoDolar;
                        //         $Pago_Consumo_Vueltos->servicio_id = $servicio_id;
                        //         $Pago_Consumo_Vueltos->caja_id = $request->get('caja_id');
                        //         // $Pago_Consumo_Vueltos->save();

                        //         $tipo_pago = $Restardivisa;
                        //  // REVIEW REVISAR
                        // }



                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


                        // return $dolarDisponible;
                        // return $pesoDisponible;
                        // return $bolivarDisponible;

                        // TODO Se procede a unir el vuelto pagado que ahora es dinero contado con el dinero_dejado si lo hubiera para procesar el pago
                        if($monto_dejado > 0){ // $opS > 0
                            $modo_pago = 'contado';
                            $status = 'Pagado';
                            $monto_dejado = $VueltospagoConExcedente + $monto_dejado;
                        }

                        if($monto_dejado == 0){
                            $modo_pago = 'contado';
                            $status = 'Pagado';
                            $monto_dejado = $VueltospagoConExcedente;
                        }

                        $monto_dejadoNegativo = Controller::is_negative_number($monto_dejado);

                        if($monto_dejadoNegativo){
                            if($VueltospagoConExcedente > $request->get('total_costo')){
                                $status = 'Pagado';
                                $monto_dejado = $request->get('total_costo');
                                $modo_pago == 'Contado-Excedente';


                            }
                        }

                    }else{
                        $status = 'Pagado';
                        $modo_pago = $request->get('modo_pago');
                    }

// return $request;

            if($modo_pago == 'cortesia'){
                $status = 'Exonerado';
                $estado = 'Aceptada';
                $estado_pago = 'Exonerado';
                $tipo_pago = 'Exonerado';


            }

            if($modo_pago == 'credito'){

                $status = 'Falta pagar';
                $estado_pago = 'Falta pagar';
                $tipo_pago = 'No pagado';
            }



            if($modo_pago == 'contado'){

                if($monto_dejado == $total_costo){// $opS == $total_venta
                    $status = 'Pagado';
                    $estado_pago = 'Pagado';
                }

                if($monto_dejado > $total_costo){// $opS > $total_costo
                    $status = 'Pagado';
                    $estado_pago = 'Pagado';
                }
                //     $exced = $montoExcedente; // $monto_dejado - $total_costo;
                //     $excedMontoDolar = $montoExcedente; // $monto_dejado - $total_costo;

                //     if ($tipo_pago == 'Dolar') {
                //         $tasaTiket = $request->get('tasaDolar');
                //         $exced = $exced * $tasaTiket;
                //         $divisaExced = 'Dolar';
                //     }

                //     if ($tipo_pago == 'Peso') {
                //         $tasaTiket = $request->get('tasaPeso');
                //         $exced = $exced * $tasaTiket;
                //         $divisaExced = 'Peso';
                //     }

                //     if ($tipo_pago == 'Trans/Punto') {
                //         // return 'Trans/Punto';
                //         $numPunto = $request->get('num_Punto');
                //         // return empty($numPunto);
                //         $numTrans = $request->get('num_Trans');
                //         // return $numTrans;
                //         $numPuntoTrans = '';
                //         if (empty($numPunto) && !empty($numTrans)) {
                //             // return 'p=null y t=si';
                //             $numPuntoTrans = $numTrans;
                //         }

                //         if (!empty($numPunto) && empty($numTrans)) {
                //             // return 'p=si y t=null';
                //             $numPuntoTrans = $numPunto;
                //         }

                //         if (!empty($numPunto) && !empty($numTrans)) {
                //             // return 'p=si y t=si';
                //             $numPuntoTrans = $numPunto. ' - ' .$numTrans;
                //         }

                //         if (empty($numPunto) && empty($numTrans)) {
                //             // return 'p=null y t=null';
                //             $numPuntoTrans = 'S/N - S/N';
                //         }
                //         // return $numPuntoTrans;

                //         $tasaTiket = $numPuntoTrans;
                //         $exced = $exced * $request->get('tasaTransPunto');
                //         $divisaExced = 'Trans/Punto';
                //     }
                //     // BUG   revisar el metodo mixto

                //     // if ($tipo_pago == 'Mixto') {

                //     //     // return 'MIxto';
                //     //     // $exced = $exced * $request->get('tasaMixto');
                //     //     // $divisaExced = 'Dolar';

                //     //     $MontoDivisaM = $request->get('MontoDivisa');
                //     //     $divisaM = $request->get('divisa');
                //     //     $TasaTikeM = $request->get('TasaTike');
                //     //     $MontoDolarM = $request->get('MontoDolar');
                //     //     $MontoDivisaM = array_filter($MontoDivisaM);

                //     //     // TODO Comvertimos la variable $MontoDolarM en un array y buscamos el valor mas alto

                //     //     $MontoDolarMax = array_filter($MontoDolarM);

                //     //     // return $MontoDolarM;
                //     //     $valorMax = max($MontoDolarMax);
                //     //     // return $valorMax . ' excedente es '. $exced;

                //     //     foreach($MontoDivisaM as $key => $val) {
                //     //         // return $valorMax . ' excedente es '. $exced . ' la divisa es ' .$divisaM[$key];
                //     //         if($MontoDolarM[$key] == $valorMax){
                //     //             // return 'MIxto';
                //     //             // return $valorMax . ' excedente es '. $exced . ' la divisa es ' .$divisaM[$key];

                //     //             $resta = $MontoDolarM[$key] - $exced;

                //     //             if ($resta >= 0) {
                //     //                 return $resta;
                //     //             }else{

                //     //                 $textos = $MontoDolarMax;
                //     //                 // return $textos;

                //     //                 if (($clave = array_search($valorMax, $textos)) !== false) {
                //     //                     unset($textos[$clave]);
                //     //                     return $textos;
                //     //                 }
                //     //             }

                //     //             $divisaExced=$divisaM[$key];
                //     //             $MontoDolar = $MontoDolarM[$key];
                //     //             $exced = $exced * $TasaTikeM;


                //     //         }
                //     //         $ValorMaximo = $MontoDolarM[$key];
                //     //     $divisa[]=$divisaM[$key];
                //     //     $MontoDolar[]=$MontoDolarM[$key];
                //     //     }
                //     // }

                //     if ($tipo_pago == 'Efectivo') {
                //         $tasaTiket = $request->get('tasaEfectivo');
                //         $exced = $exced * $tasaTiket;
                //         $divisaExced = 'Bolivar';
                //     }


                //     $excdtsRecibidosCaja = new Excedentes_Recibidos_Caja_Actual();
                //     $excdtsRecibidosCaja->Tipo = 'Consumo';
                //     $excdtsRecibidosCaja->Estado = 'Pendiente';
                //     $excdtsRecibidosCaja->Divisa = $divisaExced;
                //     $excdtsRecibidosCaja->TasaTiket = $tasaTiket;
                //     $excdtsRecibidosCaja->MontoDivisa = $exced;
                //     $excdtsRecibidosCaja->MontoDolar = $excedMontoDolar;
                //     $excdtsRecibidosCaja->servicio_id = $servicio_id;
                //     $excdtsRecibidosCaja->caja_id = $request->get('caja_id');;
                //     $excdtsRecibidosCaja->save();

                //     // $ifCliente = Excedente::where('persona_id',$request->get('cliente_id'))->first();

                // }

                // if($monto_dejado < $total_costo){
                //     $status = 'Falta pagar';
                //     $estado_pago = 'Falta pagar';
                // }
            }



            $articulo_id = $request->get('idarticulo');
            $cantidad = $request->get('cantidad');
            $precio_costo_unidad = $request->get('precio_costo_unidad');

            //creamos un contador
            $cont = 0;
            $precio_costo_final = 0;
            //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
            while ($cont < count($articulo_id)) {
                $precio_costo_final += $cantidad[$cont] * $precio_costo_unidad[$cont];

                $cont = $cont+1;
            }

            // return $precio_costo_final;
            $utilidad = $request->get('total_venta') - $precio_costo_final;



            $margen_gananacia = $utilidad / $request->get('total_venta');
            // dd($status);

            $venta = new Venta;
            $venta->tipo_comprobante = $request->get('tipo_comprobante');
            $venta->serie_comprobante = $request->get('serie_comprobante');
            $venta->num_comprobante = $request->get('num_comprobante');
            $venta->fecha_hora = $myTime->toDateTimeString();
            $venta->modo_pago = $modo_pago;
            $venta->tipo_pago = $tipo_pago;
            $venta->status = $status;
            $venta->tasaDolar = $request->get('tasaDolars');
            $venta->porDolar = $request->get('jmarjen_ganancia_dolar');
            $venta->tasaPeso = $request->get('tasaPesos');
            $venta->porPeso = $request->get('jmarjen_ganancia_peso');
            $venta->tasaTransPunto = $request->get('tasaTransPunto');
            $venta->porTransPunto = $request->get('jmarjen_ganancia_trans_punto');
            $venta->tasaMixto = $request->get('tasaMixto');
            $venta->porMixto = $request->get('jmarjen_ganancia_mixto');
            $venta->tasaEfectivo = $request->get('tasaEfectivo');
            $venta->porEfectivo = $request->get('jmarjen_ganancia_Efectivo');
            $venta->num_Punto = $request->get('num_Punto');
            $venta->num_Trans = $request->get('num_Trans');
            $venta->precio_costo = $precio_costo_final;
            $venta->margen_ganancia = $margen_gananacia;
            $venta->total_venta = $request->get('total_venta');
            $venta->ganancia_neta = $utilidad;
            $venta->estado = 'Aceptada';
            $venta->persona_id = $request->get('idcliente');
            $venta->servicio_id = $request->get('servicio_id');
            $venta->caja_id = $request->get('caja_id');
            $venta->save();



if($modo_pago == 'credito'){



    $fecha_vencimiento = Carbon::now();
    $fecha_vencimiento->addDays($request->get('limite_fecha'));
    $fecha_vencimiento->toDateString();


    $deuda_actual = Credito::where('persona_id',$request->get('cliente_id'))->first();

    if ($deuda_actual) {




        if ($deuda_actual->total_deuda == 0) {
            // return 'La deuda es menor a 0 '.$deuda_actual->total_deuda.'';

            $fecha_limite_pago = Detalle_credito::where('persona_id', $request->get('cliente_id'))->where('estado_pago','Pendiente')->first();
            // return $fecha_limite_pago;
            $upCredito = Credito::findOrFail($deuda_actual->id);

            $upCredito->total_factura = 1;
            $upCredito->total_deuda = $total_costo;
            $upCredito->fecha_limite_pago = $fecha_vencimiento;
            $upCredito->update();


            $detalleCredito = new Detalle_credito;
            $detalleCredito->numero_factura = $request->get('num_comprobante');
            $detalleCredito->tipo_operacion = 'Consumo';
            $detalleCredito->operacion_id = $venta->id;
            $detalleCredito->monto = $total_costo;
            $detalleCredito->estado_pago = 'Pendiente';
            $detalleCredito->estado_credito = 'Vigente';
            $detalleCredito->tipo_pago = 'Crédito';
            $detalleCredito->fecha_emision = $myTime->toDateString();
            $detalleCredito->fecha_vencimiento = $fecha_vencimiento;
            $detalleCredito->fecha_pago = null;
            $detalleCredito->persona_id = $request->get('cliente_id');
            $detalleCredito->credito_id = $upCredito->id ;
            $detalleCredito->caja_id = $request->get('caja_id');
            $detalleCredito->save();


        } else {
            // return 'La deuda es mayor a 0 '.$deuda_actual->total_deuda.'';

            $fecha_limite_pago = Detalle_credito::where('persona_id', $request->get('cliente_id'))->where('estado_pago','Pendiente')->first();
            // return $fecha_limite_pago;
            $upCredito = Credito::findOrFail($deuda_actual->id);

            $upCredito->total_factura = $upCredito->total_factura + 1;
            $upCredito->total_deuda = $upCredito->total_deuda + $total_costo;
            $upCredito->fecha_limite_pago = $fecha_limite_pago->fecha_vencimiento;
            $upCredito->update();


            $detalleCredito = new Detalle_credito;
            $detalleCredito->numero_factura = $request->get('num_comprobante');
            $detalleCredito->tipo_operacion = 'Consumo';
            $detalleCredito->operacion_id = $venta->id;
            $detalleCredito->monto = $total_costo;
            $detalleCredito->estado_pago = 'Pendiente';
            $detalleCredito->estado_credito = 'Vigente';
            $detalleCredito->tipo_pago = 'Crédito';
            $detalleCredito->fecha_emision = $myTime->toDateString();
            $detalleCredito->fecha_vencimiento = $fecha_vencimiento;
            $detalleCredito->fecha_pago = null;
            $detalleCredito->persona_id = $request->get('cliente_id');
            $detalleCredito->credito_id = $upCredito->id ;
            $detalleCredito->caja_id = $request->get('caja_id');
            $detalleCredito->save();
            }



    } else {
        $fecha_vencimiento_pago = Carbon::now();
        $fecha_vencimiento_pago->addDays($request->get('limite_fecha'));
        $fecha_vencimiento_pago->toDateString();

        $credito = new Credito;
        $credito->nombre_cliente = $request->get('nombre_cliente');
        $credito->cedula_cliente = $request->get('cedula_cliente');
        $credito->direccion_cliente = $request->get('direccion_cliente');
        $credito->telefono_cliente = $request->get('telefono_cliente');
        $credito->total_factura = 1;
        $credito->total_deuda = $total_costo;
        $credito->fecha_limite_pago = $fecha_vencimiento_pago;
        $credito->estado_credito = 'Activo';
        $credito->persona_id = $request->get('cliente_id');
        $credito->user_id = Auth::user()->id;
        $credito->save();


        $detalleCredito = new Detalle_credito;
        $detalleCredito->numero_factura = $request->get('num_comprobante');
        $detalleCredito->tipo_operacion = 'Consumo';
        $detalleCredito->operacion_id = $venta->id;
        $detalleCredito->monto = $total_costo;
        $detalleCredito->estado_pago = 'Pendiente';
        $detalleCredito->estado_credito = 'Vigente';
        $detalleCredito->tipo_pago = 'Credito';
        $detalleCredito->fecha_emision = $myTime->toDateTimeString();
        $detalleCredito->fecha_vencimiento = $fecha_vencimiento_pago;
        $detalleCredito->fecha_pago = null;
        $detalleCredito->persona_id = $request->get('cliente_id');
        $detalleCredito->credito_id = $credito->id ;
        $detalleCredito->caja_id = $request->get('caja_id');
        $detalleCredito->save();
    }




}


            if($modo_pago == 'cortesia'){

                $exon = $total_costo;

                    $cortesia = new Cortesia;
                    $cortesia->nombre_cliente = $request->get('nombre');
                    $cortesia->cedula_cliente = $request->get('num_documento');
                    $cortesia->direccion_cliente = $request->get('direccion');
                    $cortesia->telefono_cliente = $request->get('telefono');
                    $cortesia->exonerado = $exon;
                    $cortesia->persona_id = $request->get('cliente_id');
                    $cortesia->servicio_id = $servicio_id;
                    $cortesia->save();
            }

            //cargamos los datos del detalle del venta en la tabla articulo_venta en unas variables que reciven
            //un array



            if ($tipo_pago == 'Dolar') {
                $precio_venta_unidad = $request->get('precio_venta');
            }elseif ($tipo_pago == 'Peso') {
                $precio_venta_unidad = $request->get('precio_venta_p');
            }elseif ($tipo_pago == 'Trans/Punto') {
                $precio_venta_unidad = $request->get('precio_venta_tp');
            }elseif ($tipo_pago == 'Mixto') {
                $precio_venta_unidad = $request->get('precio_venta_m');
            }elseif ($tipo_pago == 'Efectivo') {
                $precio_venta_unidad = $request->get('precio_venta_e');
            }else{
                $precio_venta_unidad = $request->get('precio_venta');
            }


            $cantidad = $request->get('cantidad');
            $precio_costo_unidad = $request->get('precio_costo_unidad');
            $porEspecial = $request->get('porEspecial');

            $descuento = $request->get('descuento');
            $articulo_id = $request->get('idarticulo');

            //creamos un contador
            $cont = 0;

            //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
            while ($cont < count($articulo_id)) {

                $isDivisa = Articulo::find($articulo_id[$cont]);

                if($isDivisa->isDolar){
                    $activoD = $isDivisa->isDolar;
                }else{
                    $activoD = null;
                }
                if($isDivisa->isPeso){
                    $activoP = $isDivisa->isPeso;
                }else{
                    $activoP = null;
                }
                if($isDivisa->isTransPunto){
                    $activoT = $isDivisa->isTransPunto;
                }else{
                    $activoT = null;
                }
                if($isDivisa->isMixto){
                    $activoM = $isDivisa->isMixto;
                }else{
                    $activoM = null;
                }
                if($isDivisa->isEfectivo){
                    $activoE = $isDivisa->isEfectivo;
                }else{
                    $activoE = null;
                }

                $Servicios_venta = new Servicios_ventas();
                $Servicios_venta->cantidad = $cantidad[$cont];
                $Servicios_venta->precio_costo_unidad = $precio_costo_unidad[$cont];
                $Servicios_venta->precio_venta_unidad = $precio_venta_unidad[$cont];
                $Servicios_venta->porEspecial = $porEspecial[$cont];
                $Servicios_venta->isDolar = $activoD;
                $Servicios_venta->isPeso = $activoP;
                $Servicios_venta->isTransPunto = $activoT;
                $Servicios_venta->isMixto = $activoM;
                $Servicios_venta->isEfectivo = $activoE;
                $Servicios_venta->descuento = $descuento[$cont];
                $Servicios_venta->estado_pago = $estado_pago;
                $Servicios_venta->tipo_pago = $tipo_pago;
                $Servicios_venta->articulo_id = $articulo_id[$cont];
                $Servicios_venta->servicio_id =  $servicio_id;
                $Servicios_venta->venta_id =  $venta->id;
                $Servicios_venta->save();

                // dd($estado_pago);

                $Articulo_venta = new Articulo_venta();
                $Articulo_venta->cantidad = $cantidad[$cont];
                $Articulo_venta->precio_costo_unidad = $precio_costo_unidad[$cont];
                $Articulo_venta->precio_venta_unidad = $precio_venta_unidad[$cont];
                $Articulo_venta->porEspecial = $porEspecial[$cont];
                $Articulo_venta->isDolar = $activoD;
                $Articulo_venta->isPeso = $activoP;
                $Articulo_venta->isTransPunto = $activoT;
                $Articulo_venta->isMixto = $activoM;
                $Articulo_venta->isEfectivo = $activoE;
                $Articulo_venta->descuento = $descuento[$cont];
                $Articulo_venta->estado_pago = $estado_pago;
                $Articulo_venta->articulo_id = $articulo_id[$cont];
                $Articulo_venta->venta_id =  $venta->id;
                $Articulo_venta->save();


                $cont = $cont+1;
            }





            //////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////////////////////////////////////////////

            //validamos si el monto pagado es mayor a 0 sea que lo paguen con montoBase o con montoPendiente y que el tipo de pago sea contado
                        if($opS > 0 && $modo_pago == 'contado'){
                            // return $opS;
                            //validamos que el monto pagado sea mayor o igual al total de la venta
                            if($opS >= $total_venta){
                                // return 'si';
                                // calculamos excedente si el valor pagado es mayor a la venta
                                // $MontoDolarR = $request->get('MontoDolar');
                                // return $montoD;
                                //validamos si montobase es mayor y montopendiente es menor... lo que significa esto es que estamos reciviendo una moneda nueva
                                if($montoBase > 0 && $montoPendiente <= 0){
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

                                    foreach($MontoDolar as $key => $val) {

                                        $DivisaArray[$divisa[$key]] = $divisa[$key];
                                        $montoDivisaArray[$divisa[$key]] = $MontoDivisa[$key];
                                        $TasaTikeArray[$divisa[$key]]= $TasaTike[$key];
                                        $montoDolarArray[$divisa[$key]]= $MontoDolar[$key];
                                    }

                                    // return $request;
                                    $restk = $total_venta;
                                    $totalVuelto = $montoResta;
                                    // echo  'total vuelto '.$totalVuelto.'<br>';
                                    // return $opS;
                                    //  $re[] = '';
                                    asort($montoDolarArray);
                                    foreach ($montoDolarArray as $key => $val) {
                                        if($val > 0){
                                            $montoD[$key]= $val;
                                            $montoDiv[$key]= $montoDivisaArray[$key];
                                            $TasaT[$key]= $TasaTikeArray[$key];
                                        }
                                    }
                                    // return $montoDiv;
                                    $x = $opS;
                                    $residuo = 0;
                                    $exc = 0;
                                    foreach ($montoD as $p => $value) {

                                        if(round($value,6) == round($restk,6)){
                                            // echo 'igual <br> ';
                                            // echo 'value '.$value.' <br> ';
                                            // echo 'restk '.$restk.' <br> ';
                                            $restk = $restk - $value;
                                            $x = floatval($x - $value);
                                            $restk = floatval($restk);
                                            echo  ' divisa: '.$p.' montoDivisa: '.$montoDiv[$p].' tasaTiket: '.$TasaT[$p].' montoDolar: '.floatval($value).' montoDolarConsumo: '.floatval($value).'  excedente:  '. 0 .' vueltos: '. 0 .'<br> ';

                                            $Pago_Venta = new Pago_Venta();
                                            $Pago_Venta->Divisa = $p;
                                            $Pago_Venta->MontoDivisa = $montoDiv[$p];
                                            $Pago_Venta->TasaTiket = $TasaT[$p];
                                            $Pago_Venta->MontoDolar = floatval($value);
                                            $Pago_Venta->MontoDolarConsumo = floatval($value);
                                            $Pago_Venta->Excedente = 0;
                                            $Pago_Venta->Vueltos = 0;
                                            $Pago_Venta->servicio_id = $servicio_id;
                                            $Pago_Venta->caja_id = $request->get('caja_id');
                                            $Pago_Venta->venta_id = $venta->id;
                                            $Pago_Venta->save();

                                            // echo 'excd '. 0 .' <br> ';
                                            // echo 'vueltos '. 0 .' <br> ';
                                            // echo $restk.' <br> ';
                                            $residuo = $restk;


                                        }else if (round($value,6) < round($restk,6)){

                                            // echo 'value '.$value.' <br> ';
                                            // echo 'restk '.$restk.' <br> ';
                                            // echo 'menor <br> ';
                                            $restk = $restk - $value;
                                            echo  ' divisa: '.$p.' montoDivisa: '.$montoDiv[$p].' tasaTiket: '.$TasaT[$p].' montoDolar: '.floatval($value).' montoDolarConsumo: '.floatval($value).'  excedente:  '. 0 .' vueltos: '. 0 .'<br> ';

                                            $Pago_Venta = new Pago_Venta();
                                            $Pago_Venta->Divisa = $p;
                                            $Pago_Venta->MontoDivisa = $montoDiv[$p];
                                            $Pago_Venta->TasaTiket = $TasaT[$p];
                                            $Pago_Venta->MontoDolar = floatval($value);
                                            $Pago_Venta->MontoDolarConsumo = floatval($value);
                                            $Pago_Venta->Excedente = 0;
                                            $Pago_Venta->Vueltos = 0;
                                            $Pago_Venta->servicio_id = $servicio_id;
                                            $Pago_Venta->caja_id = $request->get('caja_id');
                                            $Pago_Venta->venta_id = $venta->id;
                                            $Pago_Venta->save();
                                            // echo 'excd '. 0 .' <br> ';
                                            // echo 'vueltos '. 0 .' <br> ';
                                            // echo $restk.' <br> ';
                                            $residuo = $restk;
                                        }else if (round($value,6) > round($restk,6)){
                                            // echo 'value '.$value.' <br> ';
                                            // echo 'restk '.$restk.' <br> ';
                                            $residuo = $restk;
                                            $restk =   $value - $restk;
                                            // $vuel = $totalVuelto;
                                            // return $restk;
                                            // if($totalVuelto > 0){
                                                if (round($totalVuelto,6) > round($restk,6)) {
                                                    // $totalVuelto = $totalVuelto - $restk;
                                                    $exc = $restk;
                                                    $vuel = 0;
                                                }
                                                if (round($totalVuelto,6) < round($restk,6)) {

                                                    echo '$totalVuelto: '.$totalVuelto;
                                                    $exc = $restk - $totalVuelto;
                                                    $vuel = $totalVuelto;
                                                    $totalVuelto = 0;
                                                    // $residuo = 0;
                                                }
                                                // }
                                                if (round($totalVuelto,6) == round($restk,6)) {
                                                    $exc = 0;
                                                    $vuel = $totalVuelto;
                                                    $totalVuelto = 0;
                                                }
                                            echo 'mayor <br> ';
                                            echo  ' divisa: '.$p.' montoDivisa: '.$montoDiv[$p].' tasaTiket: '.$TasaT[$p].' montoDolar: '.floatval($value).' montoDolarConsumo: '.floatval($residuo).'  excedente:  '.$exc.' vueltos: '.$vuel.'<br> ';
                                            $Pago_Venta = new Pago_Venta();
                                            $Pago_Venta->Divisa = $p;
                                            $Pago_Venta->MontoDivisa = $montoDiv[$p];
                                            $Pago_Venta->TasaTiket = $TasaT[$p];
                                            $Pago_Venta->MontoDolar = floatval($value);
                                            $Pago_Venta->MontoDolarConsumo = floatval($residuo);
                                            $Pago_Venta->Excedente = $exc;
                                            $Pago_Venta->Vueltos = $vuel;
                                            $Pago_Venta->servicio_id = $servicio_id;
                                            $Pago_Venta->caja_id = $request->get('caja_id');
                                            $Pago_Venta->venta_id = $venta->id;
                                            $Pago_Venta->save();

                                            if($exc > 0){
                                                $excdtsRecibidosCaja = new Excedentes_Recibidos_Caja_Actual();
                                                $excdtsRecibidosCaja->Tipo = 'Consumo';
                                                $excdtsRecibidosCaja->Estado = 'Pendiente';
                                                $excdtsRecibidosCaja->Divisa = $p;
                                                $excdtsRecibidosCaja->MontoDivisa = floatval($exc * $TasaT[$p]);
                                                $excdtsRecibidosCaja->TasaTiket = $TasaT[$p];
                                                $excdtsRecibidosCaja->MontoDolar = floatval($exc);
                                                $excdtsRecibidosCaja->servicio_id = $servicio_id;
                                                $excdtsRecibidosCaja->venta_id = $venta->id;
                                                $excdtsRecibidosCaja->caja_id = $request->get('caja_id');;
                                                $excdtsRecibidosCaja->save();
                                            }

                                            if($vuel > 0){
                                                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                                // En esta seccion trabajaremos la parte de vueltos llenamos la tabla pagos_vueltos

                                                // $isVuelos = $request->get('isVueltos');

                                                if($montoResta > 0 || $montoResta != null){
                                                    $MontoDivisaVueltos = $request->get('MontoDivisaV');
                                                    $divisaVueltos = $request->get('divisaV');
                                                    $TasaTikeVueltos = $request->get('TasaTikeV');
                                                    $MontoDolarVueltos = $request->get('MontoDolarV');

                                                    $MontoDivisaVueltos = array_filter($MontoDivisaVueltos);

                                                    foreach($MontoDivisaVueltos as $key => $val) {

                                                        $Vdivisa[]=$divisaVueltos[$key];
                                                        $VMontoDivisa[]=$MontoDivisaVueltos[$key];
                                                        $VTasaTiket[]=$TasaTikeVueltos[$key];
                                                        $VMontoDolar[]=$MontoDolarVueltos[$key];
                                                    }
                                                    // dd($divisa, $MontoDivisa,$TasaTike,$MontoDolar,$Veltos);
                                                    //creamos un contador
                                                    $cont = 0;

                                                    //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
                                                    while ($cont < count($VMontoDolar)) {
                                                        $Pago_Extras_Vueltos = new Pago_Vuelto();
                                                        $Pago_Extras_Vueltos->Tipo = 'Consumo';
                                                        $Pago_Extras_Vueltos->tipo_vuelto = 'Vueltos_Pago';
                                                        $Pago_Extras_Vueltos->Divisa = $Vdivisa[$cont];
                                                        $Pago_Extras_Vueltos->MontoDivisa = $VMontoDivisa[$cont];
                                                        $Pago_Extras_Vueltos->TasaTiket = $VTasaTiket[$cont];
                                                        $Pago_Extras_Vueltos->MontoDolar = $VMontoDolar[$cont];
                                                        $Pago_Extras_Vueltos->servicio_id = $servicio_id;
                                                        $Pago_Extras_Vueltos->venta_id = $venta->id;
                                                        $Pago_Extras_Vueltos->caja_id = $request->get('caja_id');
                                                        $Pago_Extras_Vueltos->save();

                                                        $cont = $cont+1;
                                                    }

                                                }
                                                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                            }
                                            echo 'excd '.$exc.' <br> ';
                                            echo 'vueltos '.$vuel.' <br> ';
                                            echo $restk.' <br> ';
                                        }

                                    }
                                // validamos  si montopendiente es mayor y montobase es menor... lo que significa esto es que estamos reciviendo una moneda pendiente
                                }else if($montoPendiente > 0 && $montoBase <= 0){
                                    // return 'pagado con plata pendiente = montoPendiente';
                                    //metodo para procesar pago con dinero con pendiente

                                    // TODO consultamos la tabla excedentes recibidos en caja para traer los excedentes que tenga asociados este servicio
                                    // y así porder realizar el pago del consumo con los vueltos pendientes

                                    // TODO Consultamos la base de datos y sumamos el total de excedentes que tiene ese servicio
                                    $TotalExcedenteData = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$servicio_id)
                                    ->where('caja_id',$request->get('caja_id'))
                                    ->where('Estado','Pendiente')
                                    ->select(DB::raw('SUM(MontoDolar) as totalExcedente'))
                                    ->get();
                                    $TotalExcedenteSumado = floatval($TotalExcedenteData[0]->totalExcedente);

                                    // return $TotalExcedente;

                                    //realizamos la consulta en la base de datos y ordenamos los datos de menor a mayor sobre la columna MontoDolar
                                    //para que luego reste el pago con vueltos pendientes
                                    $RestarVtossPtesToVtosPtes = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$servicio_id)
                                    ->where('caja_id',$request
                                    ->get('caja_id'))
                                    ->where('Estado','Pendiente')
                                    ->orderBy('MontoDolar', 'ASC')
                                    ->get();

                                    // return $request;
                                    $restk = $total_venta;
                                    $totalVuelto = $montoResta;

                                    $x = $opS;
                                    $residuo = 0;
                                    $exc = 0;
                                    foreach ($RestarVtossPtesToVtosPtes as $vueltosData => $val) {
                                        if(round($restk,6) > 0){


                                            $value = floatval($val->MontoDolar);
                                            $p = $val->Divisa;
                                            $montoDiv = $val->MontoDivisa;
                                            $TasaT = $val->TasaTiket;

                                            if (round($value,6) <= round($restk,6)){
                                                echo 'Resta: '. $restk . '<br>';
                                                $RestarVtossPtes = Excedentes_Recibidos_Caja_Actual::findOrFail($val->id);

                                                if ($RestarVtossPtes) {
                                                    $RestarVtossPtes->MontoDivisa = $RestarVtossPtes->MontoDivisa - ($value * $RestarVtossPtes->TasaTiket);
                                                    $RestarVtossPtes->MontoDolar = $RestarVtossPtes->MontoDolar - $value;
                                                    $RestarVtossPtes->update();

                                                echo  'Actualizar tabla Excedente_Recibidos => divisa: '.$RestarVtossPtes->Divisa.' montoDivisa: '.($RestarVtossPtes->MontoDivisa).' tasaTiket: '.$RestarVtossPtes->TasaTiket.' montoDolar: '.floatval($RestarVtossPtes->MontoDolar).'<br> ';

                                                    // TODO Ir a la tabla Excedentes_Recibidos_Caja_Actual y crear un registro nuevo con el monto pagado pero con estatus Devueltos flotantes en la misma divisa

                                                    $AgregarVtossPtesToVtosPtes = new Excedentes_Recibidos_Caja_Actual();
                                                    $AgregarVtossPtesToVtosPtes->Tipo = 'Consumo';
                                                    $AgregarVtossPtesToVtosPtes->Estado = 'Devueltos';
                                                    $AgregarVtossPtesToVtosPtes->Divisa = $RestarVtossPtes->Divisa;
                                                    $AgregarVtossPtesToVtosPtes->MontoDivisa = floatval($value * $RestarVtossPtes->TasaTiket);
                                                    $AgregarVtossPtesToVtosPtes->TasaTiket = $RestarVtossPtes->TasaTiket;
                                                    $AgregarVtossPtesToVtosPtes->MontoDolar = floatval($value);
                                                    $AgregarVtossPtesToVtosPtes->servicio_id = $servicio_id;
                                                    $AgregarVtossPtesToVtosPtes->venta_id = $venta->id;
                                                    $AgregarVtossPtesToVtosPtes->caja_id = $request->get('caja_id');
                                                    $AgregarVtossPtesToVtosPtes->save();

                                                    // return $AgregarVtossPtesToVtosPtes->id;

                                                    // TODO verificar si despues de la actualizacion el registro que en 0 si es así procedemos a borrarlo de lo contrario se deja quieto
                                                    if($RestarVtossPtes->MontoDolar == 0){
                                                        Excedentes_Recibidos_Caja_Actual::destroy($RestarVtossPtes->id);
                                                    }



                                                }
                                                echo 'menor o igual <br> ';
                                                echo  'Agretar registro nuevo en la tabla Excedente_Recibidos => Tipo: Consumo Estado: Devueltos divisa: '.$RestarVtossPtes->Divisa.' montoDivisa: '.floatval($value * $RestarVtossPtes->TasaTiket).' tasaTiket: '.$RestarVtossPtes->TasaTiket.' montoDolar: '.floatval($value).'<br> ';
                                                // return 'Finalizo...';
                                                // TODO Verificar que tengamos liquidez en esa divisa para dar vueltos y se procesa
                                                // $tasaPeso
                                                // $tasaTransferenciaPunto
                                                // $tasaEfectivo

                                                $Restardivisa = '';
                                                $RestarMontoDivisa = 0;
                                                $RestarTasaTiket = 0;
                                                $RestarMontoDolar = 0;

                                                $RestarVtossPtesToVtosPtesDevueltos = Excedentes_Recibidos_Caja_Actual::findOrFail($AgregarVtossPtesToVtosPtes->id);
                                                // return $RestarVtossPtesToVtosPtesDevueltos;

                                                if ($RestarVtossPtesToVtosPtesDevueltos->Estado == 'Devueltos') {
                                                    // return $RestarVtossPtesToVtosPtesDevueltos->Estado;
                                                    if($RestarVtossPtesToVtosPtesDevueltos->Divisa == 'Dolar'){
                                                        // return $dolarDisponible . ' - ' .$VueltospagoConExcedente * 1;
                                                        if ($dolarDisponible >=  $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaDolar->tasa) {
                                                            $Restardivisa = 'Dolar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaDolar->tasa;
                                                            $RestarTasaTiket = $tasaDolar->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);

                                                        }else if ($pesoDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa)) {
                                                            $Restardivisa = 'Peso';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa;
                                                            $RestarTasaTiket = $tasaPeso->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($bolivarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa)) {
                                                            $Restardivisa = 'Bolivar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa;
                                                            $RestarTasaTiket = $tasaEfectivo->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }
                                                        // return $RestarMontoDivisa;


                                                    }else if($RestarVtossPtesToVtosPtesDevueltos->Divisa == 'Peso'){
                                                        if ($pesoDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa)) {
                                                            $Restardivisa = 'Peso';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa;
                                                            $RestarTasaTiket = $tasaPeso->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($dolarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * 1)) {
                                                            $Restardivisa = 'Dolar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaDolar->tasa;
                                                            $RestarTasaTiket = $tasaDolar->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($bolivarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa)) {
                                                            $Restardivisa = 'Bolivar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa;
                                                            $RestarTasaTiket = $tasaEfectivo->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }



                                                    }else if($RestarVtossPtesToVtosPtesDevueltos->Divisa == 'Bolivar'){
                                                        if ($bolivarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa)) {
                                                            $Restardivisa = 'Bolivar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa;
                                                            $RestarTasaTiket = $tasaEfectivo->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($pesoDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa)) {
                                                            $Restardivisa = 'Peso';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa;
                                                            $RestarTasaTiket = $tasaPeso->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($dolarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * 1)) {
                                                            $Restardivisa = 'Dolar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaDolar->tasa;
                                                            $RestarTasaTiket = $tasaDolar->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }


                                                    }

                                                    echo  'Agregar registros en la tabla Pago_Vuelto => Tipo: Consumo divisa: '.$Restardivisa.' montoDivisa: '.($RestarMontoDivisa).' tasaTiket: '.$RestarTasaTiket.' montoDolar: '.floatval($RestarMontoDolar).'<br> ';
                                                    // return 'estoy en new Pago_Vuelto '.$RestarMontoDivisa;
                                                        $Pago_Consumo_Vueltos = new Pago_Vuelto();
                                                        $Pago_Consumo_Vueltos->Tipo = 'Consumo';
                                                        $Pago_Consumo_Vueltos->tipo_vuelto = 'Vueltos_Excedentes';
                                                        $Pago_Consumo_Vueltos->Divisa = $Restardivisa;
                                                        $Pago_Consumo_Vueltos->MontoDivisa = $RestarMontoDivisa;
                                                        $Pago_Consumo_Vueltos->TasaTiket = $RestarTasaTiket;
                                                        $Pago_Consumo_Vueltos->MontoDolar = $RestarMontoDolar;
                                                        $Pago_Consumo_Vueltos->servicio_id = $servicio_id;
                                                        $Pago_Consumo_Vueltos->venta_id = $venta->id;
                                                        $Pago_Consumo_Vueltos->caja_id = $request->get('caja_id');
                                                        $Pago_Consumo_Vueltos->save();

                                                        $tipo_pago = $Restardivisa;
                                                // REVIEW REVISAR

                                                $Pago_Venta = new Pago_Venta();
                                                    $Pago_Venta->Divisa = $Restardivisa;
                                                    $Pago_Venta->MontoDivisa = $RestarMontoDivisa;
                                                    $Pago_Venta->TasaTiket = $RestarTasaTiket;
                                                    $Pago_Venta->MontoDolar = floatval($RestarMontoDolar);
                                                    $Pago_Venta->MontoDolarConsumo = floatval($RestarMontoDolar);
                                                    $Pago_Venta->Excedente = 0;
                                                    $Pago_Venta->Vueltos = 0;
                                                    $Pago_Venta->servicio_id = $servicio_id;
                                                    $Pago_Venta->caja_id = $request->get('caja_id');
                                                    $Pago_Venta->venta_id = $venta->id;
                                                    $Pago_Venta->save();
                                                    // $restk = 0;
                                                    echo  'Agregar registros en la tabla Pago_Venta => divisa: '.$Restardivisa.' montoDivisa: '.($RestarMontoDivisa).' tasaTiket: '.$RestarTasaTiket.' montoDolar: '.floatval($RestarMontoDolar).' MontoDolarConsumo: '.floatval($RestarMontoDolar).' Excedente: '.floatval(0).' Vueltos: '.floatval(0).'<br> ';
                                                    $restk = $restk - $value;
                                                }
                                                echo 'Resta: ' . $restk . ' <br> ';
                                                // return 'Finalizo';
                                                // return '<';




                                            }else if (round($value,6) > round($restk,6)){
                                                echo 'Resta: '. $restk . '<br>';
                                                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                                                $RestarVtossPtes = Excedentes_Recibidos_Caja_Actual::findOrFail($val->id);

                                                if ($RestarVtossPtes) {
                                                    $RestarVtossPtes->MontoDivisa = $RestarVtossPtes->MontoDivisa - ($restk * $RestarVtossPtes->TasaTiket);
                                                    $RestarVtossPtes->MontoDolar = $RestarVtossPtes->MontoDolar - $restk;
                                                    $RestarVtossPtes->update();

                                                echo  'Actualizar tabla Excedente_Recibidos => divisa: '.$RestarVtossPtes->Divisa.' montoDivisa: '.($RestarVtossPtes->MontoDivisa).' tasaTiket: '.$RestarVtossPtes->TasaTiket.' montoDolar: '.floatval($RestarVtossPtes->MontoDolar).'<br> ';

                                                    // TODO Ir a la tabla Excedentes_Recibidos_Caja_Actual y crear un registro nuevo con el monto pagado pero con estatus Devueltos flotantes en la misma divisa

                                                    $AgregarVtossPtesToVtosPtes = new Excedentes_Recibidos_Caja_Actual();
                                                    $AgregarVtossPtesToVtosPtes->Tipo = 'Consumo';
                                                    $AgregarVtossPtesToVtosPtes->Estado = 'Devueltos';
                                                    $AgregarVtossPtesToVtosPtes->Divisa = $RestarVtossPtes->Divisa;
                                                    $AgregarVtossPtesToVtosPtes->MontoDivisa = floatval($restk * $RestarVtossPtes->TasaTiket);
                                                    $AgregarVtossPtesToVtosPtes->TasaTiket = $RestarVtossPtes->TasaTiket;
                                                    $AgregarVtossPtesToVtosPtes->MontoDolar = floatval($restk);
                                                    $AgregarVtossPtesToVtosPtes->servicio_id = $servicio_id;
                                                    $AgregarVtossPtesToVtosPtes->venta_id = $venta->id;
                                                    $AgregarVtossPtesToVtosPtes->caja_id = $request->get('caja_id');
                                                    $AgregarVtossPtesToVtosPtes->save();

                                                    // return $AgregarVtossPtesToVtosPtes->id;

                                                    // TODO verificar si despues de la actualizacion el registro que en 0 si es así procedemos a borrarlo de lo contrario se deja quieto
                                                    if($RestarVtossPtes->MontoDolar == 0){
                                                        Excedentes_Recibidos_Caja_Actual::destroy($RestarVtossPtes->id);
                                                    }



                                                }
                                                echo 'mayor <br> ';
                                                echo  'Agretar registro nuevo en la tabla Excedente_Recibidos => Tipo: Consumo Estado: Devueltos divisa: '.$RestarVtossPtes->Divisa.' montoDivisa: '.floatval($restk * $RestarVtossPtes->TasaTiket).' tasaTiket: '.$RestarVtossPtes->TasaTiket.' montoDolar: '.floatval($restk).'<br> ';
                                                // return 'Finalizo...';
                                                // TODO Verificar que tengamos liquidez en esa divisa para dar vueltos y se procesa
                                                // $tasaPeso
                                                // $tasaTransferenciaPunto
                                                // $tasaEfectivo

                                                $Restardivisa = '';
                                                $RestarMontoDivisa = 0;
                                                $RestarTasaTiket = 0;
                                                $RestarMontoDolar = 0;

                                                $RestarVtossPtesToVtosPtesDevueltos = Excedentes_Recibidos_Caja_Actual::findOrFail($AgregarVtossPtesToVtosPtes->id);
                                                // return $RestarVtossPtesToVtosPtesDevueltos;

                                                if ($RestarVtossPtesToVtosPtesDevueltos->Estado == 'Devueltos') {
                                                    // return $RestarVtossPtesToVtosPtesDevueltos->Estado;
                                                    if($RestarVtossPtesToVtosPtesDevueltos->Divisa == 'Dolar'){
                                                        // return $dolarDisponible . ' - ' .$VueltospagoConExcedente * 1;
                                                        if ($dolarDisponible >=  $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaDolar->tasa) {
                                                            $Restardivisa = 'Dolar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaDolar->tasa;
                                                            $RestarTasaTiket = $tasaDolar->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);

                                                        }else if ($pesoDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa)) {
                                                            $Restardivisa = 'Peso';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa;
                                                            $RestarTasaTiket = $tasaPeso->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($bolivarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa)) {
                                                            $Restardivisa = 'Bolivar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa;
                                                            $RestarTasaTiket = $tasaEfectivo->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }
                                                        // return $RestarMontoDivisa;


                                                    }else if($RestarVtossPtesToVtosPtesDevueltos->Divisa == 'Peso'){
                                                        if ($pesoDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa)) {
                                                            $Restardivisa = 'Peso';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa;
                                                            $RestarTasaTiket = $tasaPeso->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($dolarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * 1)) {
                                                            $Restardivisa = 'Dolar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaDolar->tasa;
                                                            $RestarTasaTiket = $tasaDolar->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($bolivarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa)) {
                                                            $Restardivisa = 'Bolivar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa;
                                                            $RestarTasaTiket = $tasaEfectivo->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }



                                                    }else if($RestarVtossPtesToVtosPtesDevueltos->Divisa == 'Bolivar'){
                                                        if ($bolivarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa)) {
                                                            $Restardivisa = 'Bolivar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa;
                                                            $RestarTasaTiket = $tasaEfectivo->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($pesoDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa)) {
                                                            $Restardivisa = 'Peso';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa;
                                                            $RestarTasaTiket = $tasaPeso->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($dolarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * 1)) {
                                                            $Restardivisa = 'Dolar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaDolar->tasa;
                                                            $RestarTasaTiket = $tasaDolar->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }


                                                    }

                                                    echo  'Agregar registros en la tabla Pago_Vuelto => Tipo: Consumo divisa: '.$Restardivisa.' montoDivisa: '.($RestarMontoDivisa).' tasaTiket: '.$RestarTasaTiket.' montoDolar: '.floatval($RestarMontoDolar).'<br> ';
                                                    // return 'estoy en new Pago_Vuelto '.$RestarMontoDivisa;
                                                        $Pago_Consumo_Vueltos = new Pago_Vuelto();
                                                        $Pago_Consumo_Vueltos->Tipo = 'Consumo';
                                                        $Pago_Consumo_Vueltos->tipo_vuelto = 'Vueltos_Excedentes';
                                                        $Pago_Consumo_Vueltos->Divisa = $Restardivisa;
                                                        $Pago_Consumo_Vueltos->MontoDivisa = $RestarMontoDivisa;
                                                        $Pago_Consumo_Vueltos->TasaTiket = $RestarTasaTiket;
                                                        $Pago_Consumo_Vueltos->MontoDolar = $RestarMontoDolar;
                                                        $Pago_Consumo_Vueltos->servicio_id = $servicio_id;
                                                        $Pago_Consumo_Vueltos->venta_id = $venta->id;
                                                        $Pago_Consumo_Vueltos->caja_id = $request->get('caja_id');
                                                        $Pago_Consumo_Vueltos->save();

                                                        $tipo_pago = $Restardivisa;
                                                // REVIEW REVISAR

                                                $Pago_Venta = new Pago_Venta();
                                                    $Pago_Venta->Divisa = $Restardivisa;
                                                    $Pago_Venta->MontoDivisa = $RestarMontoDivisa;
                                                    $Pago_Venta->TasaTiket = $RestarTasaTiket;
                                                    $Pago_Venta->MontoDolar = floatval($RestarMontoDolar);
                                                    $Pago_Venta->MontoDolarConsumo = floatval($RestarMontoDolar);
                                                    $Pago_Venta->Excedente = 0;
                                                    $Pago_Venta->Vueltos = 0;
                                                    $Pago_Venta->servicio_id = $servicio_id;
                                                    $Pago_Venta->caja_id = $request->get('caja_id');
                                                    $Pago_Venta->venta_id = $venta->id;
                                                    $Pago_Venta->save();
                                                    $restk = 0;
                                                    echo  'Agregar registros en la tabla Pago_Venta => divisa: '.$Restardivisa.' montoDivisa: '.($RestarMontoDivisa).' tasaTiket: '.$RestarTasaTiket.' montoDolar: '.floatval($RestarMontoDolar).' MontoDolarConsumo: '.floatval($RestarMontoDolar).' Excedente: '.floatval(0).' Vueltos: '.floatval(0).'<br> ';
                                                }
                                                echo 'Resta: ' . $restk . ' <br> ';
                                                // return 'Finalizo';
                                                // return '>';
                                                // echo 'excd '.$exc.' <br> ';
                                                // echo 'vueltos '.$vuel.' <br> ';
                                                // echo $restk.' <br> ';
                                            }
                                        }
                                    }

// return 'Finalizo';


                                }else if($montoBase > 0 && $montoPendiente > 0){
                                    // metodo para procesar la venta con dinero nuevo y pendiente
                                    // return 'pagado con plata nueva y pendiente = montoBase y montoPendiente';
                                    // metodo para procesar la venta con dinero nuevo y pendiente
                                    // TODO Consultamos la base de datos y sumamos el total de excedentes que tiene ese servicio
                                    $TotalExcedenteData = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$servicio_id)
                                    ->where('caja_id',$request->get('caja_id'))
                                    ->where('Estado','Pendiente')
                                    ->select(DB::raw('SUM(MontoDolar) as totalExcedente'))
                                    ->get();
                                    $TotalExcedenteSumado = floatval($TotalExcedenteData[0]->totalExcedente);

                                    // return $TotalExcedente;

                                    //realizamos la consulta en la base de datos y ordenamos los datos de menor a mayor sobre la columna MontoDolar
                                    //para que luego reste el pago con vueltos pendientes
                                    $RestarVtossPtesToVtosPtes = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$servicio_id)
                                    ->where('caja_id',$request
                                    ->get('caja_id'))
                                    ->where('Estado','Pendiente')
                                    ->orderBy('MontoDolar', 'ASC')
                                    ->get();

                                    // return $request;
                                    $restk = $total_venta;
                                    $totalVuelto = $montoResta;

                                    $x = $opS;
                                    $residuo = 0;
                                    $exc = 0;
                                    foreach ($RestarVtossPtesToVtosPtes as $vueltosData => $val) {
                                        if(round($restk,6) > 0){


                                            $value = floatval($val->MontoDolar);
                                            $p = $val->Divisa;
                                            $montoDiv = $val->MontoDivisa;
                                            $TasaT = $val->TasaTiket;

                                            if (round($value,6) <= round($restk,6)){
                                                echo 'Resta: '. $restk . '<br>';
                                                $RestarVtossPtes = Excedentes_Recibidos_Caja_Actual::findOrFail($val->id);

                                                if ($RestarVtossPtes) {
                                                    $RestarVtossPtes->MontoDivisa = $RestarVtossPtes->MontoDivisa - ($value * $RestarVtossPtes->TasaTiket);
                                                    $RestarVtossPtes->MontoDolar = $RestarVtossPtes->MontoDolar - $value;
                                                    $RestarVtossPtes->update();

                                                echo  'Actualizar tabla Excedente_Recibidos => divisa: '.$RestarVtossPtes->Divisa.' montoDivisa: '.($RestarVtossPtes->MontoDivisa).' tasaTiket: '.$RestarVtossPtes->TasaTiket.' montoDolar: '.floatval($RestarVtossPtes->MontoDolar).'<br> ';

                                                    // TODO Ir a la tabla Excedentes_Recibidos_Caja_Actual y crear un registro nuevo con el monto pagado pero con estatus Devueltos flotantes en la misma divisa

                                                    $AgregarVtossPtesToVtosPtes = new Excedentes_Recibidos_Caja_Actual();
                                                    $AgregarVtossPtesToVtosPtes->Tipo = 'Consumo';
                                                    $AgregarVtossPtesToVtosPtes->Estado = 'Devueltos';
                                                    $AgregarVtossPtesToVtosPtes->Divisa = $RestarVtossPtes->Divisa;
                                                    $AgregarVtossPtesToVtosPtes->MontoDivisa = floatval($value * $RestarVtossPtes->TasaTiket);
                                                    $AgregarVtossPtesToVtosPtes->TasaTiket = $RestarVtossPtes->TasaTiket;
                                                    $AgregarVtossPtesToVtosPtes->MontoDolar = floatval($value);
                                                    $AgregarVtossPtesToVtosPtes->servicio_id = $servicio_id;
                                                    $AgregarVtossPtesToVtosPtes->venta_id = $venta->id;
                                                    $AgregarVtossPtesToVtosPtes->caja_id = $request->get('caja_id');
                                                    $AgregarVtossPtesToVtosPtes->save();

                                                    // return $AgregarVtossPtesToVtosPtes->id;

                                                    // TODO verificar si despues de la actualizacion el registro que en 0 si es así procedemos a borrarlo de lo contrario se deja quieto
                                                    if($RestarVtossPtes->MontoDolar == 0){
                                                        Excedentes_Recibidos_Caja_Actual::destroy($RestarVtossPtes->id);
                                                    }



                                                }
                                                echo 'menor o igual <br> ';
                                                echo  'Agretar registro nuevo en la tabla Excedente_Recibidos => Tipo: Consumo Estado: Devueltos divisa: '.$RestarVtossPtes->Divisa.' montoDivisa: '.floatval($value * $RestarVtossPtes->TasaTiket).' tasaTiket: '.$RestarVtossPtes->TasaTiket.' montoDolar: '.floatval($value).'<br> ';
                                                // return 'Finalizo...';
                                                // TODO Verificar que tengamos liquidez en esa divisa para dar vueltos y se procesa
                                                // $tasaPeso
                                                // $tasaTransferenciaPunto
                                                // $tasaEfectivo

                                                $Restardivisa = '';
                                                $RestarMontoDivisa = 0;
                                                $RestarTasaTiket = 0;
                                                $RestarMontoDolar = 0;

                                                $RestarVtossPtesToVtosPtesDevueltos = Excedentes_Recibidos_Caja_Actual::findOrFail($AgregarVtossPtesToVtosPtes->id);
                                                // return $RestarVtossPtesToVtosPtesDevueltos;

                                                if ($RestarVtossPtesToVtosPtesDevueltos->Estado == 'Devueltos') {
                                                    // return $RestarVtossPtesToVtosPtesDevueltos->Estado;
                                                    if($RestarVtossPtesToVtosPtesDevueltos->Divisa == 'Dolar'){
                                                        // return $dolarDisponible . ' - ' .$VueltospagoConExcedente * 1;
                                                        if ($dolarDisponible >=  $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaDolar->tasa) {
                                                            $Restardivisa = 'Dolar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaDolar->tasa;
                                                            $RestarTasaTiket = $tasaDolar->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);

                                                        }else if ($pesoDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa)) {
                                                            $Restardivisa = 'Peso';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa;
                                                            $RestarTasaTiket = $tasaPeso->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($bolivarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa)) {
                                                            $Restardivisa = 'Bolivar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa;
                                                            $RestarTasaTiket = $tasaEfectivo->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }
                                                        // return $RestarMontoDivisa;


                                                    }else if($RestarVtossPtesToVtosPtesDevueltos->Divisa == 'Peso'){
                                                        if ($pesoDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa)) {
                                                            $Restardivisa = 'Peso';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa;
                                                            $RestarTasaTiket = $tasaPeso->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($dolarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * 1)) {
                                                            $Restardivisa = 'Dolar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaDolar->tasa;
                                                            $RestarTasaTiket = $tasaDolar->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($bolivarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa)) {
                                                            $Restardivisa = 'Bolivar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa;
                                                            $RestarTasaTiket = $tasaEfectivo->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }



                                                    }else if($RestarVtossPtesToVtosPtesDevueltos->Divisa == 'Bolivar'){
                                                        if ($bolivarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa)) {
                                                            $Restardivisa = 'Bolivar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa;
                                                            $RestarTasaTiket = $tasaEfectivo->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($pesoDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa)) {
                                                            $Restardivisa = 'Peso';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa;
                                                            $RestarTasaTiket = $tasaPeso->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($dolarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * 1)) {
                                                            $Restardivisa = 'Dolar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaDolar->tasa;
                                                            $RestarTasaTiket = $tasaDolar->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }


                                                    }

                                                    echo  'Agregar registros en la tabla Pago_Vuelto => Tipo: Consumo divisa: '.$Restardivisa.' montoDivisa: '.($RestarMontoDivisa).' tasaTiket: '.$RestarTasaTiket.' montoDolar: '.floatval($RestarMontoDolar).'<br> ';
                                                    // return 'estoy en new Pago_Vuelto '.$RestarMontoDivisa;
                                                        $Pago_Consumo_Vueltos = new Pago_Vuelto();
                                                        $Pago_Consumo_Vueltos->Tipo = 'Consumo';
                                                        $Pago_Consumo_Vueltos->tipo_vuelto = 'Vueltos_Excedentes';
                                                        $Pago_Consumo_Vueltos->Divisa = $Restardivisa;
                                                        $Pago_Consumo_Vueltos->MontoDivisa = $RestarMontoDivisa;
                                                        $Pago_Consumo_Vueltos->TasaTiket = $RestarTasaTiket;
                                                        $Pago_Consumo_Vueltos->MontoDolar = $RestarMontoDolar;
                                                        $Pago_Consumo_Vueltos->servicio_id = $servicio_id;
                                                        $Pago_Consumo_Vueltos->venta_id = $venta->id;
                                                        $Pago_Consumo_Vueltos->caja_id = $request->get('caja_id');
                                                        $Pago_Consumo_Vueltos->save();

                                                        $tipo_pago = $Restardivisa;
                                                // REVIEW REVISAR

                                                $Pago_Venta = new Pago_Venta();
                                                    $Pago_Venta->Divisa = $Restardivisa;
                                                    $Pago_Venta->MontoDivisa = $RestarMontoDivisa;
                                                    $Pago_Venta->TasaTiket = $RestarTasaTiket;
                                                    $Pago_Venta->MontoDolar = floatval($RestarMontoDolar);
                                                    $Pago_Venta->MontoDolarConsumo = floatval($RestarMontoDolar);
                                                    $Pago_Venta->Excedente = 0;
                                                    $Pago_Venta->Vueltos = 0;
                                                    $Pago_Venta->servicio_id = $servicio_id;
                                                    $Pago_Venta->caja_id = $request->get('caja_id');
                                                    $Pago_Venta->venta_id = $venta->id;
                                                    $Pago_Venta->save();
                                                    // $restk = 0;
                                                    echo  'Agregar registros en la tabla Pago_Venta => divisa: '.$Restardivisa.' montoDivisa: '.($RestarMontoDivisa).' tasaTiket: '.$RestarTasaTiket.' montoDolar: '.floatval($RestarMontoDolar).' MontoDolarConsumo: '.floatval($RestarMontoDolar).' Excedente: '.floatval(0).' Vueltos: '.floatval(0).'<br> ';
                                                    $restk = $restk - $value;
                                                }
                                                echo 'Resta: ' . $restk . ' <br> ';
                                                // return 'Finalizo';
                                                // return '<';




                                            }else if (round($value,6) > round($restk,6)){
                                                echo 'Resta: '. $restk . '<br>';
                                                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                                                $RestarVtossPtes = Excedentes_Recibidos_Caja_Actual::findOrFail($val->id);

                                                if ($RestarVtossPtes) {
                                                    $RestarVtossPtes->MontoDivisa = $RestarVtossPtes->MontoDivisa - ($restk * $RestarVtossPtes->TasaTiket);
                                                    $RestarVtossPtes->MontoDolar = $RestarVtossPtes->MontoDolar - $restk;
                                                    $RestarVtossPtes->update();

                                                echo  'Actualizar tabla Excedente_Recibidos => divisa: '.$RestarVtossPtes->Divisa.' montoDivisa: '.($RestarVtossPtes->MontoDivisa).' tasaTiket: '.$RestarVtossPtes->TasaTiket.' montoDolar: '.floatval($RestarVtossPtes->MontoDolar).'<br> ';

                                                    // TODO Ir a la tabla Excedentes_Recibidos_Caja_Actual y crear un registro nuevo con el monto pagado pero con estatus Devueltos flotantes en la misma divisa

                                                    $AgregarVtossPtesToVtosPtes = new Excedentes_Recibidos_Caja_Actual();
                                                    $AgregarVtossPtesToVtosPtes->Tipo = 'Consumo';
                                                    $AgregarVtossPtesToVtosPtes->Estado = 'Devueltos';
                                                    $AgregarVtossPtesToVtosPtes->Divisa = $RestarVtossPtes->Divisa;
                                                    $AgregarVtossPtesToVtosPtes->MontoDivisa = floatval($restk * $RestarVtossPtes->TasaTiket);
                                                    $AgregarVtossPtesToVtosPtes->TasaTiket = $RestarVtossPtes->TasaTiket;
                                                    $AgregarVtossPtesToVtosPtes->MontoDolar = floatval($restk);
                                                    $AgregarVtossPtesToVtosPtes->servicio_id = $servicio_id;
                                                    $AgregarVtossPtesToVtosPtes->venta_id = $venta->id;
                                                    $AgregarVtossPtesToVtosPtes->caja_id = $request->get('caja_id');
                                                    $AgregarVtossPtesToVtosPtes->save();

                                                    // return $AgregarVtossPtesToVtosPtes->id;

                                                    // TODO verificar si despues de la actualizacion el registro que en 0 si es así procedemos a borrarlo de lo contrario se deja quieto
                                                    if($RestarVtossPtes->MontoDolar == 0){
                                                        Excedentes_Recibidos_Caja_Actual::destroy($RestarVtossPtes->id);
                                                    }



                                                }
                                                echo 'mayor <br> ';
                                                echo  'Agretar registro nuevo en la tabla Excedente_Recibidos => Tipo: Consumo Estado: Devueltos divisa: '.$RestarVtossPtes->Divisa.' montoDivisa: '.floatval($restk * $RestarVtossPtes->TasaTiket).' tasaTiket: '.$RestarVtossPtes->TasaTiket.' montoDolar: '.floatval($restk).'<br> ';
                                                // return 'Finalizo...';
                                                // TODO Verificar que tengamos liquidez en esa divisa para dar vueltos y se procesa
                                                // $tasaPeso
                                                // $tasaTransferenciaPunto
                                                // $tasaEfectivo

                                                $Restardivisa = '';
                                                $RestarMontoDivisa = 0;
                                                $RestarTasaTiket = 0;
                                                $RestarMontoDolar = 0;

                                                $RestarVtossPtesToVtosPtesDevueltos = Excedentes_Recibidos_Caja_Actual::findOrFail($AgregarVtossPtesToVtosPtes->id);
                                                // return $RestarVtossPtesToVtosPtesDevueltos;

                                                if ($RestarVtossPtesToVtosPtesDevueltos->Estado == 'Devueltos') {
                                                    // return $RestarVtossPtesToVtosPtesDevueltos->Estado;
                                                    if($RestarVtossPtesToVtosPtesDevueltos->Divisa == 'Dolar'){
                                                        // return $dolarDisponible . ' - ' .$VueltospagoConExcedente * 1;
                                                        if ($dolarDisponible >=  $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaDolar->tasa) {
                                                            $Restardivisa = 'Dolar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaDolar->tasa;
                                                            $RestarTasaTiket = $tasaDolar->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);

                                                        }else if ($pesoDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa)) {
                                                            $Restardivisa = 'Peso';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa;
                                                            $RestarTasaTiket = $tasaPeso->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($bolivarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa)) {
                                                            $Restardivisa = 'Bolivar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa;
                                                            $RestarTasaTiket = $tasaEfectivo->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }
                                                        // return $RestarMontoDivisa;


                                                    }else if($RestarVtossPtesToVtosPtesDevueltos->Divisa == 'Peso'){
                                                        if ($pesoDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa)) {
                                                            $Restardivisa = 'Peso';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa;
                                                            $RestarTasaTiket = $tasaPeso->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($dolarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * 1)) {
                                                            $Restardivisa = 'Dolar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaDolar->tasa;
                                                            $RestarTasaTiket = $tasaDolar->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($bolivarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa)) {
                                                            $Restardivisa = 'Bolivar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa;
                                                            $RestarTasaTiket = $tasaEfectivo->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }



                                                    }else if($RestarVtossPtesToVtosPtesDevueltos->Divisa == 'Bolivar'){
                                                        if ($bolivarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa)) {
                                                            $Restardivisa = 'Bolivar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaEfectivo->tasa;
                                                            $RestarTasaTiket = $tasaEfectivo->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($pesoDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa)) {
                                                            $Restardivisa = 'Peso';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaPeso->tasa;
                                                            $RestarTasaTiket = $tasaPeso->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }else if ($dolarDisponible >= ($RestarVtossPtesToVtosPtesDevueltos->MontoDolar * 1)) {
                                                            $Restardivisa = 'Dolar';
                                                            $RestarMontoDivisa = $RestarVtossPtesToVtosPtesDevueltos->MontoDolar * $tasaDolar->tasa;
                                                            $RestarTasaTiket = $tasaDolar->tasa;
                                                            $RestarMontoDolar = floatval($RestarVtossPtesToVtosPtesDevueltos->MontoDolar);
                                                        }


                                                    }

                                                    echo  'Agregar registros en la tabla Pago_Vuelto => Tipo: Consumo divisa: '.$Restardivisa.' montoDivisa: '.($RestarMontoDivisa).' tasaTiket: '.$RestarTasaTiket.' montoDolar: '.floatval($RestarMontoDolar).'<br> ';
                                                    // return 'estoy en new Pago_Vuelto '.$RestarMontoDivisa;
                                                        $Pago_Consumo_Vueltos = new Pago_Vuelto();
                                                        $Pago_Consumo_Vueltos->Tipo = 'Consumo';
                                                        $Pago_Consumo_Vueltos->tipo_vuelto = 'Vueltos_Excedentes';
                                                        $Pago_Consumo_Vueltos->Divisa = $Restardivisa;
                                                        $Pago_Consumo_Vueltos->MontoDivisa = $RestarMontoDivisa;
                                                        $Pago_Consumo_Vueltos->TasaTiket = $RestarTasaTiket;
                                                        $Pago_Consumo_Vueltos->MontoDolar = $RestarMontoDolar;
                                                        $Pago_Consumo_Vueltos->servicio_id = $servicio_id;
                                                        $Pago_Consumo_Vueltos->venta_id = $venta->id;
                                                        $Pago_Consumo_Vueltos->caja_id = $request->get('caja_id');
                                                        $Pago_Consumo_Vueltos->save();

                                                        $tipo_pago = $Restardivisa;
                                                // REVIEW REVISAR

                                                $Pago_Venta = new Pago_Venta();
                                                    $Pago_Venta->Divisa = $Restardivisa;
                                                    $Pago_Venta->MontoDivisa = $RestarMontoDivisa;
                                                    $Pago_Venta->TasaTiket = $RestarTasaTiket;
                                                    $Pago_Venta->MontoDolar = floatval($RestarMontoDolar);
                                                    $Pago_Venta->MontoDolarConsumo = floatval($RestarMontoDolar);
                                                    $Pago_Venta->Excedente = 0;
                                                    $Pago_Venta->Vueltos = 0;
                                                    $Pago_Venta->servicio_id = $servicio_id;
                                                    $Pago_Venta->caja_id = $request->get('caja_id');
                                                    $Pago_Venta->venta_id = $venta->id;
                                                    $Pago_Venta->save();
                                                    $restk = 0;
                                                    echo  'Agregar registros en la tabla Pago_Venta => divisa: '.$Restardivisa.' montoDivisa: '.($RestarMontoDivisa).' tasaTiket: '.$RestarTasaTiket.' montoDolar: '.floatval($RestarMontoDolar).' MontoDolarConsumo: '.floatval($RestarMontoDolar).' Excedente: '.floatval(0).' Vueltos: '.floatval(0).'<br> ';
                                                }
                                                echo 'Resta: ' . $restk . ' <br> ';
                                                // return 'Finalizo';
                                                // return '>';
                                                // echo 'excd '.$exc.' <br> ';
                                                // echo 'vueltos '.$vuel.' <br> ';
                                                // echo $restk.' <br> ';
                                            }
                                        }
                                    }
echo 'pase a dinero efectivo <br>';
                                    if (round($restk,6) > 0 || round($montoBase,6) > 0) {
                                        // return $restk;
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

                                    foreach($MontoDolar as $key => $val) {

                                        $DivisaArray[$divisa[$key]] = $divisa[$key];
                                        $montoDivisaArray[$divisa[$key]] = $MontoDivisa[$key];
                                        $TasaTikeArray[$divisa[$key]]= $TasaTike[$key];
                                        $montoDolarArray[$divisa[$key]]= $MontoDolar[$key];
                                    }

                                    // return $montoDivisaArray;
                                    // $restk = $total_venta;
                                    $totalVuelto = $montoResta;
                                    // echo  'total vuelto '.$totalVuelto.'<br>';
                                    // return $opS;
                                    //  $re[] = '';
                                    asort($montoDolarArray);
                                    foreach ($montoDolarArray as $key => $val) {
                                        if($val > 0){
                                            $montoD[$key]= $val;
                                            $montoDiv[$key]= $montoDivisaArray[$key];
                                            $TasaT[$key]= $TasaTikeArray[$key];
                                        }
                                    }
                                    // return $montoDiv;
                                    // return $request;
                                    $x = $opS;
                                    $residuo = 0;
                                    $exc = 0;
                                    foreach ($montoD as $p => $value) {
                                        echo 'value del for '.$value.'<br>';
                                        if(round($value,6) == round($restk,6)){
                                            echo 'value =='.$value.' <br> ';
                                            // echo 'igual <br> ';
                                            // echo 'value '.$value.' <br> ';
                                            // echo 'restk '.$restk.' <br> ';
                                            $restk = $restk - $value;
                                            $x = floatval($x - $value);
                                            $restk = floatval($restk);
                                            echo  'pago con dinero divisa: '.$p.' montoDivisa: '.$montoDiv[$p].' tasaTiket: '.$TasaT[$p].' montoDolar: '.floatval($value).' montoDolarConsumo: '.floatval($value).'  excedente:  '. 0 .' vueltos: '. 0 .'<br> ';

                                            $Pago_Venta = new Pago_Venta();
                                            $Pago_Venta->Divisa = $p;
                                            $Pago_Venta->MontoDivisa = $montoDiv[$p];
                                            $Pago_Venta->TasaTiket = $TasaT[$p];
                                            $Pago_Venta->MontoDolar = floatval($value);
                                            $Pago_Venta->MontoDolarConsumo = floatval($value);
                                            $Pago_Venta->Excedente = 0;
                                            $Pago_Venta->Vueltos = 0;
                                            $Pago_Venta->servicio_id = $servicio_id;
                                            $Pago_Venta->caja_id = $request->get('caja_id');
                                            $Pago_Venta->venta_id = $venta->id;
                                            $Pago_Venta->save();

                                            // echo 'excd '. 0 .' <br> ';
                                            // echo 'vueltos '. 0 .' <br> ';
                                            // echo $restk.' <br> ';
                                            $residuo = $restk;


                                        }else if (round($value,6) < round($restk,6)){
echo 'value <'.$value.' - '.$restk.' <br> ';
                                            // echo 'value '.$value.' <br> ';
                                            // echo 'restk '.$restk.' <br> ';
                                            // echo 'menor <br> ';
                                            $restk = $restk - $value;
                                            echo  'pago con dinero  divisa: '.$p.' montoDivisa: '.$montoDiv[$p].' tasaTiket: '.$TasaT[$p].' montoDolar: '.floatval($value).' montoDolarConsumo: '.floatval($value).'  excedente:  '. 0 .' vueltos: '. 0 .'<br> ';

                                            $Pago_Venta = new Pago_Venta();
                                            $Pago_Venta->Divisa = $p;
                                            $Pago_Venta->MontoDivisa = $montoDiv[$p];
                                            $Pago_Venta->TasaTiket = $TasaT[$p];
                                            $Pago_Venta->MontoDolar = floatval($value);
                                            $Pago_Venta->MontoDolarConsumo = floatval($value);
                                            $Pago_Venta->Excedente = 0;
                                            $Pago_Venta->Vueltos = 0;
                                            $Pago_Venta->servicio_id = $servicio_id;
                                            $Pago_Venta->caja_id = $request->get('caja_id');
                                            $Pago_Venta->venta_id = $venta->id;
                                            $Pago_Venta->save();
                                            // echo 'excd '. 0 .' <br> ';
                                            // echo 'vueltos '. 0 .' <br> ';
                                            // echo $restk.' <br> ';
                                            $residuo = $restk;
                                        }else if (round($value,6) > round($restk,6)){

                                            echo 'value >'.$value.' <br> ';
                                            echo 'value '.$value.' <br> ';
                                            echo 'restk '.$restk.' <br> ';
                                            $residuo = $restk;

                                            if(round($restk,6) <= 0){
                                                $restk =   $value - $restk;
                                                $r = $restk;
                                                $restk = 0;
                                            }else{
                                                $restk =   $value - $restk;
                                                $r = $restk;
                                            }
                                            // $restk =   $value - $restk;
                                            // $vuel = $totalVuelto;
                                            // return $restk;
                                            // if($totalVuelto > 0){
                                                if (round($totalVuelto,6) > round($r,6)) {
                                                    // $totalVuelto = $totalVuelto - $restk;
                                                    $exc = $r;
                                                    $vuel = 0;
                                                }
                                                if (round($totalVuelto,6) < round($r,6)) {

                                                    echo '$totalVuelto: '.$totalVuelto;
                                                    $exc = $r - $totalVuelto;
                                                    $vuel = $totalVuelto;
                                                    $totalVuelto = 0;
                                                    // $residuo = 0;
                                                }
                                                // }
                                                if (round($totalVuelto,6) == round($r,6)) {
                                                    $exc = 0;
                                                    $vuel = $totalVuelto;
                                                    $totalVuelto = 0;
                                                }
                                            echo 'mayor <br> ';
                                            echo  'pago con dinero  divisa: '.$p.' montoDivisa: '.$montoDiv[$p].' tasaTiket: '.$TasaT[$p].' montoDolar: '.floatval($value).' montoDolarConsumo: '.floatval($residuo).'  excedente:  '.$exc.' vueltos: '.$vuel.'<br> ';
                                            $Pago_Venta = new Pago_Venta();
                                            $Pago_Venta->Divisa = $p;
                                            $Pago_Venta->MontoDivisa = $montoDiv[$p];
                                            $Pago_Venta->TasaTiket = $TasaT[$p];
                                            $Pago_Venta->MontoDolar = floatval($value);
                                            $Pago_Venta->MontoDolarConsumo = floatval($residuo);
                                            $Pago_Venta->Excedente = $exc;
                                            $Pago_Venta->Vueltos = $vuel;
                                            $Pago_Venta->servicio_id = $servicio_id;
                                            $Pago_Venta->caja_id = $request->get('caja_id');
                                            $Pago_Venta->venta_id = $venta->id;
                                            $Pago_Venta->save();

                                            if($exc > 0){
                                                $excdtsRecibidosCaja = new Excedentes_Recibidos_Caja_Actual();
                                                $excdtsRecibidosCaja->Tipo = 'Consumo';
                                                $excdtsRecibidosCaja->Estado = 'Pendiente';
                                                $excdtsRecibidosCaja->Divisa = $p;
                                                $excdtsRecibidosCaja->MontoDivisa = floatval($exc * $TasaT[$p]);
                                                $excdtsRecibidosCaja->TasaTiket = $TasaT[$p];
                                                $excdtsRecibidosCaja->MontoDolar = floatval($exc);
                                                $excdtsRecibidosCaja->servicio_id = $servicio_id;
                                                $excdtsRecibidosCaja->venta_id = $venta->id;
                                                $excdtsRecibidosCaja->caja_id = $request->get('caja_id');;
                                                $excdtsRecibidosCaja->save();
                                            }

                                            if($vuel > 0){
                                                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                                // En esta seccion trabajaremos la parte de vueltos llenamos la tabla pagos_vueltos

                                                // $isVuelos = $request->get('isVueltos');

                                                if($montoResta > 0 || $montoResta != null){
                                                    $MontoDivisaVueltos = $request->get('MontoDivisaV');
                                                    $divisaVueltos = $request->get('divisaV');
                                                    $TasaTikeVueltos = $request->get('TasaTikeV');
                                                    $MontoDolarVueltos = $request->get('MontoDolarV');

                                                    $MontoDivisaVueltos = array_filter($MontoDivisaVueltos);

                                                    foreach($MontoDivisaVueltos as $key => $val) {

                                                        $Vdivisa[]=$divisaVueltos[$key];
                                                        $VMontoDivisa[]=$MontoDivisaVueltos[$key];
                                                        $VTasaTiket[]=$TasaTikeVueltos[$key];
                                                        $VMontoDolar[]=$MontoDolarVueltos[$key];
                                                    }
                                                    // dd($divisa, $MontoDivisa,$TasaTike,$MontoDolar,$Veltos);
                                                    //creamos un contador
                                                    $cont = 0;

                                                    //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
                                                    while ($cont < count($VMontoDolar)) {
                                                        $Pago_Extras_Vueltos = new Pago_Vuelto();
                                                        $Pago_Extras_Vueltos->Tipo = 'Consumo';
                                                        $Pago_Extras_Vueltos->tipo_vuelto = 'Vueltos_Excedentes';
                                                        $Pago_Extras_Vueltos->Divisa = $Vdivisa[$cont];
                                                        $Pago_Extras_Vueltos->MontoDivisa = $VMontoDivisa[$cont];
                                                        $Pago_Extras_Vueltos->TasaTiket = $VTasaTiket[$cont];
                                                        $Pago_Extras_Vueltos->MontoDolar = $VMontoDolar[$cont];
                                                        $Pago_Extras_Vueltos->servicio_id = $servicio_id;
                                                        $Pago_Extras_Vueltos->venta_id = $venta->id;
                                                        $Pago_Extras_Vueltos->caja_id = $request->get('caja_id');
                                                        $Pago_Extras_Vueltos->save();

                                                        $cont = $cont+1;
                                                    }

                                                }
                                                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                            }
                                            // echo 'excd '.$exc.' <br> ';
                                            // echo 'vueltos '.$vuel.' <br> ';
                                            // echo $restk.' <br> ';
                                        }

                                    }
                                // validamos  si montopendiente es mayor y montobase es menor... lo que significa esto es que estamos reciviendo una moneda pendiente
                                    }




                                }

                                //verificamos cuantos pagos hay en la tabla pagos ventas para saber si el pago fue mixto de ser así actualizamos
                                //la tabla ventas y la tabla servicios venas a mixto

                                $cantidadPagos = Pago_Venta::where('venta_id', $venta->id)->get();

                                $cantVentas = count($cantidadPagos);

                                if($cantVentas >= 2){
                                    $actualizarVentasToMixto = Venta::findOrfail($venta->id);
                                    $actualizarVentasToMixto->tipo_pago = 'Mixto';
                                    $actualizarVentasToMixto->update();

                                    $actualizarServiciosVentasToMixto = Servicios_Ventas::findOrfail($venta->id);
                                    $actualizarServiciosVentasToMixto->tipo_pago = 'Mixto';
                                    $actualizarServiciosVentasToMixto->update();
                                }
// return 'Finalizo';

                            }else{
                                return Redirect::back()
                                ->with('status_danger', '¡Error Pago incompleto! Debe ingresar un monto para pagar y procesar el servicio... ');
                            }

                            // return 'no';
                        }else{


                            return Redirect::back()
                                ->with('status_danger', '¡Error Pago incompleto! Debe ingresar un monto para pagar y procesar el servicio... ');
                        }


                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// return 'Finalizo...';
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if($modo_pago == 'contado' || $modo_pago == 'Contado-Excedente'){
                // $MontoDivisaR = $request->get('MontoDivisa');
                // $divisaR = $request->get('divisa');
                // $TasaTikeR = $request->get('TasaTike');
                // $MontoDolarR = $request->get('MontoDolar');
                // $VeltosR = $request->get('Veltos');
                // // return 'estoy en contado';
                // $MontoDivisaR = array_filter($MontoDivisaR);

                // if(count($MontoDivisaR) > 0){
                //     // return 'estoy en MontoDivisaR';

                //     // return count($MontoDivisaR);

                //     foreach($MontoDivisaR as $key => $val) {


                //         $divisa[]=$divisaR[$key];
                //         $MontoDivisa[]=$MontoDivisaR[$key];
                //         $TasaTiket[]=$TasaTikeR[$key];
                //         $MontoDolar[]=$MontoDolarR[$key];
                //         $Vueltos[]=$VeltosR[$key];

                //         if($monto_dejado == $total_costo){
                //             $Vueltos[]=$VeltosR[$key];
                //             // return 'igual';

                //         }else{

                //             $Vueltos[]=$VeltosR[$key] - $VeltosR[$key];
                //             // return $Vueltos;
                //             $isVuelos = $request->get('isVueltos');


                //         if(!$isVuelos > 0 || !$isVuelos != null){
                //             $Vueltos[$key] = 0;
                //             // return $Vueltos;

                //         }

                //         }


                //     }
                //     // return $request;



                //     // dd($divisa, $MontoDivisa,$TasaTike,$MontoDolar,$Veltos);
                //     //creamos un contador
                //     $cont = 0;


                //     //realizamos el pago en la tabla Pagos Extras
                //     while ($cont < count($MontoDolar)) {
                //         // return 'estoy en MontoDolar '.$divisa[$cont];

                //         $Pago_Venta = new Pago_Venta();
                //         $Pago_Venta->Divisa = $divisa[$cont];
                //         $Pago_Venta->MontoDivisa = $MontoDivisa[$cont];
                //         $Pago_Venta->TasaTiket = $TasaTiket[$cont];
                //         $Pago_Venta->MontoDolar = $MontoDolar[$cont];
                //         $Pago_Venta->MontoDolarConsumo = $total_venta;
                //         $Pago_Venta->Excedente = $MontoDolar[$cont];/////////////////////////////////////////ojo//////////////////////////////////////
                //         $Pago_Venta->Vueltos = $Vueltos[$cont];///////////////////////////////////////////ojo/////////////////////////////////////////
                //         $Pago_Venta->servicio_id = $request->get('servicio_id');
                //         $Pago_Venta->caja_id = $request->get('caja_id');
                //         $Pago_Venta->venta_id = $venta->id;
                //         $Pago_Venta->save();

                //         $cont = $cont+1;
                //     }

                //     // return 'estoy en contado';

                //     ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //     ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //     // En esta seccion trabajaremos la parte de vueltos llenamos la tabla pagos_vueltos para eso usamos una bandera llamad isVuelto
                //     //que debemos pasar por la vista

                //     $isVuelos = $request->get('isVueltos');

                //     if($isVuelos > 0 || $isVuelos != null){




                //         $MontoDivisaVueltos = $request->get('MontoDivisaV');
                //         $divisaVueltos = $request->get('divisaV');
                //         $TasaTikeVueltos = $request->get('TasaTikeV');
                //         $MontoDolarVueltos = $request->get('MontoDolarV');


                //         $MontoDivisaVueltos = array_filter($MontoDivisaVueltos);


                //         foreach($MontoDivisaVueltos as $key => $val) {


                //             $Vdivisa[]=$divisaVueltos[$key];
                //             $VMontoDivisa[]=$MontoDivisaVueltos[$key];
                //             $VTasaTiket[]=$TasaTikeVueltos[$key];
                //             $VMontoDolar[]=$MontoDolarVueltos[$key];





                //         }




                //         // dd($divisa, $MontoDivisa,$TasaTike,$MontoDolar,$Veltos);
                //         //creamos un contador
                //         $cont = 0;


                //         //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
                //         while ($cont < count($VMontoDolar)) {


                //             $Pago_Consumo_Vueltos = new Pago_Vuelto();
                //             $Pago_Consumo_Vueltos->Tipo = 'Consumo';
                //             $Pago_Consumo_Vueltos->Divisa = $Vdivisa[$cont];
                //             $Pago_Consumo_Vueltos->MontoDivisa = $VMontoDivisa[$cont];
                //             $Pago_Consumo_Vueltos->TasaTiket = $VTasaTiket[$cont];
                //             $Pago_Consumo_Vueltos->MontoDolar = $VMontoDolar[$cont];
                //             $Pago_Consumo_Vueltos->servicio_id = $servicio_id;
                //             $Pago_Consumo_Vueltos->caja_id = $request->get('caja_id');
                //             $Pago_Consumo_Vueltos->save();

                //             $cont = $cont+1;
                //         }

                //     }
                //     ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //     ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                //     // BUG actualizar la tabla pago servicios cuando se paga con vueltos pendientes
                //     // de lo contrario solo registra el dinero dejado de contado.
                //     // REVIEW  resuelto (revizar si esta resuelto)

                //     if($VueltospagoConExcedente > 0){
                //         // return 'estoy en '.$VueltospagoConExcedente;
                //         // $Restardivisa = '';
                //         // $RestarMontoDivisa = 0;
                //         // $RestarTasaTiket = 0;
                //         // $RestarMontoDolar = 0;

                //         // TODO consultamos la tabla Pagos vueltos para descubrir con que moneda se dieron los vueltos
                //         //para sumarcelos a pago servicio si la divisa usada es igual a la de pagos servicios solo se
                //         // suma y es distinta se hace un nuevo registro en la tabla y quedaría como si se hubiece pagado
                //         //con dos divisas

                //         $pagoVueltos = Pago_Vuelto::findOrFail($Pago_Consumo_Vueltos->id);
                //         // return $pagoVueltos;

                //         if ($pagoVueltos) {

                //             // return $Pago_Extra->id;
                //             $UdatePagoConsumoConVtosPendientes = Pago_Venta::findOrFail($Pago_Venta->id);
                //             // return $UdatePagoServiciosConVtosPendientes;
                //             if ($UdatePagoConsumoConVtosPendientes->Divisa == $pagoVueltos->Divisa) {
                //                 $UdatePagoConsumoConVtosPendientes->MontoDivisa = $UdatePagoConsumoConVtosPendientes->MontoDivisa + $pagoVueltos->MontoDivisa;
                //                 $UdatePagoConsumoConVtosPendientes->MontoDolar = $UdatePagoConsumoConVtosPendientes->MontoDolar + $pagoVueltos->MontoDolar;
                //                 // return $UdatePagoConsumoConVtosPendientes->MontoDivisa;
                //                 $UdatePagoConsumoConVtosPendientes->update();
                //             }else{

                //                 $Pago_Venta = new Pago_Venta();
                //                 $Pago_Venta->Divisa = $pagoVueltos->Divisa;
                //                 $Pago_Venta->MontoDivisa = $pagoVueltos->MontoDivisa;
                //                 $Pago_Venta->TasaTiket = $pagoVueltos->TasaTiket;
                //                 $Pago_Venta->MontoDolar = $pagoVueltos->MontoDolar;
                //                 $Pago_Venta->MontoDolarConsumo = $total_venta;
                //                 $Pago_Venta->Excedente = $MontoDolar[$cont];/////////////////////////////////////////ojo//////////////////////////////////////
                //                 $Pago_Venta->Vueltos = 0;
                //                 $Pago_Venta->servicio_id = $request->get('servicio_id');
                //                 $Pago_Venta->caja_id = $request->get('caja_id');
                //                 $Pago_Venta->venta_id = $venta->id;
                //                 $Pago_Venta->save();


                //             }


                //         }

                //         // // TODO Ahora actualizamos la tabla Excedentes_Recibidos_Caja_Actual para pasar el dinero pendiente si lo hay al nuevo servicio
                //         // //que se creo porque de lo contrario se perderia el vuelto pendiente

                //         // // TODO verificamos si exciste un vuelto pendiente con el id del servicio que cerramos $id


                //         //     $PasarVtossPtesToNextServ = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$id)->where('Estado','Pendiente')->first();
                //         //     if ($PasarVtossPtesToNextServ) {
                //         //         $PasarVtossPtesToNextServ->servicio_id = $servicio->id;
                //         //         $PasarVtossPtesToNextServ->update();
                //         //     }




                //     }


                // }else{
                //     // BUG actualizar la tabla pago servicios cuando se paga con vueltos pendientes
                //     // de lo contrario solo registra el dinero dejado de contado.
                //     // return 'estoy en else';
                //     if($VueltospagoConExcedente > 0){


                //         ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //         ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //         // En esta seccion trabajaremos la parte de vueltos llenamos la tabla pagos_vueltos

                //         $isVuelos = $request->get('isVueltos');

                //         if($isVuelos > 0 || $isVuelos != null){




                //             $MontoDivisaVueltos = $request->get('MontoDivisaV');
                //             $divisaVueltos = $request->get('divisaV');
                //             $TasaTikeVueltos = $request->get('TasaTikeV');
                //             $MontoDolarVueltos = $request->get('MontoDolarV');


                //             $MontoDivisaVueltos = array_filter($MontoDivisaVueltos);


                //             foreach($MontoDivisaVueltos as $key => $val) {


                //                 $Vdivisa[]=$divisaVueltos[$key];
                //                 $VMontoDivisa[]=$MontoDivisaVueltos[$key];
                //                 $VTasaTiket[]=$TasaTikeVueltos[$key];
                //                 $VMontoDolar[]=$MontoDolarVueltos[$key];





                //             }




                //             // dd($divisa, $MontoDivisa,$TasaTike,$MontoDolar,$Veltos);
                //             //creamos un contador
                //             $cont = 0;


                //             //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
                //             while ($cont < count($VMontoDolar)) {


                //                 $Pago_Extras_Vueltos = new Pago_Vuelto();
                //                 $Pago_Extras_Vueltos->Tipo = 'Consumo';
                //                 $Pago_Extras_Vueltos->Divisa = $Vdivisa[$cont];
                //                 $Pago_Extras_Vueltos->MontoDivisa = $VMontoDivisa[$cont];
                //                 $Pago_Extras_Vueltos->TasaTiket = $VTasaTiket[$cont];
                //                 $Pago_Extras_Vueltos->MontoDolar = $VMontoDolar[$cont];
                //                 $Pago_Extras_Vueltos->servicio_id = $servicio_id;
                //                 $Pago_Extras_Vueltos->caja_id = $request->get('caja_id');
                //                 $Pago_Extras_Vueltos->save();

                //                 $cont = $cont+1;
                //             }

                //         }
                //         ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //         ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                //          // $Restardivisa = '';
                //         // $RestarMontoDivisa = 0;
                //         // $RestarTasaTiket = 0;
                //         // $RestarMontoDolar = 0;

                //         // TODO consultamos la tabla Pagos vueltos para descubrir con que moneda se dieron los vueltos
                //         //para sumarcelos a pago servicio si la divisa usada es igual a la de pagos servicios solo se
                //         // suma y es distinta se hace un nuevo registro en la tabla y quedaría como si se hubiece pagado
                //         //con dos divisas

                //         $pagoVueltos = Pago_Vuelto::findOrFail($Pago_Extras_Vueltos->id);
                //         // return $RestarVtossPtesToVtosPtesDevueltos;



                //         if ($pagoVueltos) {
                //             $isVuelos = $request->get('isVueltos');

                //             $Pago_Venta = new Pago_Venta();
                //             $Pago_Venta->Divisa = $pagoVueltos->Divisa;
                //             $Pago_Venta->MontoDivisa = $pagoVueltos->MontoDivisa;
                //             $Pago_Venta->TasaTiket = $pagoVueltos->TasaTiket;
                //             $Pago_Venta->MontoDolar = $pagoVueltos->MontoDolar;
                //             $Pago_Venta->MontoDolarConsumo = $total_venta;
                //             $Pago_Venta->Excedente = 0;/////////////////////////////////////////ojo//////////////////////////////////////
                //             $Pago_Venta->Vueltos = -$isVuelos;///////////////////////////////////////////ojo/////////////////////////////////////////
                //             $Pago_Venta->servicio_id = $request->get('servicio_id');
                //             $Pago_Venta->caja_id = $request->get('caja_id');
                //             $Pago_Venta->venta_id = $venta->id;
                //             $Pago_Venta->save();

                //         }





                //     }

                //     ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //     ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                // }


            }

            // if($modo_pago == 'contado'){

            //    /////////////////////////////////////////////////////////////////////////////////////////////////
            //     // TODO ingresamos los datos en la tabla Pago_Venta////////////////////////////////////////////

            //     $MontoDivisaR = $request->get('MontoDivisa');
            //     $divisaR = $request->get('divisa');
            //     $TasaTikeR = $request->get('TasaTike');
            //     $MontoDolarR = $request->get('MontoDolar');
            //     $VeltosR = $request->get('Veltos');

            //     $MontoDivisaR = array_filter($MontoDivisaR);


            //     foreach($MontoDivisaR as $key => $val) {


            //         $divisa[]=$divisaR[$key];
            //         $MontoDivisa[]=$MontoDivisaR[$key];
            //         $TasaTiket[]=$TasaTikeR[$key];
            //         $MontoDolar[]=$MontoDolarR[$key];
            //         $Vueltos[]=$VeltosR[$key];

            //     }




            //     // dd($divisa, $MontoDivisa,$TasaTike,$MontoDolar,$Veltos);
            //     //creamos un contador
            //     $cont = 0;

            //     //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
            //     while ($cont < count($MontoDolar)) {


            //         $Pago_Venta = new Pago_Venta();
            //         $Pago_Venta->Divisa = $divisa[$cont];
            //         $Pago_Venta->MontoDivisa = $MontoDivisa[$cont];
            //         $Pago_Venta->TasaTiket = $TasaTiket[$cont];
            //         $Pago_Venta->MontoDolar = $MontoDolar[$cont];
            //         $Pago_Venta->Vueltos = $Vueltos[$cont];
            //         $Pago_Venta->venta_id = $venta->id;
            //         $Pago_Venta->save();

            //         $cont = $cont+1;
            //     }

            //     // TODO Fin de ingreso en la tabla Pago_Venta //////////////////////////////////////////////////
            //     //////////////////////////////////////////////////////////////////////////////////////////////////

            //     ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //     ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //     // En esta seccion trabajaremos la parte de vueltos llenamos la tabla pagos_vueltos

            //     $isVuelos = $request->get('isVueltos');

            //     if($isVuelos > 0 || $isVuelos != null){




            //         $MontoDivisaVueltos = $request->get('MontoDivisaV');
            //         $divisaVueltos = $request->get('divisaV');
            //         $TasaTikeVueltos = $request->get('TasaTikeV');
            //         $MontoDolarVueltos = $request->get('MontoDolarV');


            //         $MontoDivisaVueltos = array_filter($MontoDivisaVueltos);


            //         foreach($MontoDivisaVueltos as $key => $val) {


            //             $Vdivisa[]=$divisaVueltos[$key];
            //             $VMontoDivisa[]=$MontoDivisaVueltos[$key];
            //             $VTasaTiket[]=$TasaTikeVueltos[$key];
            //             $VMontoDolar[]=$MontoDolarVueltos[$key];





            //         }




            //         // dd($divisa, $MontoDivisa,$TasaTike,$MontoDolar,$Veltos);
            //         //creamos un contador
            //         $cont = 0;


            //         //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
            //         while ($cont < count($VMontoDolar)) {


            //             $Pago_Servicio = new Pago_Vuelto();
            //             $Pago_Servicio->Tipo = 'Consumo';
            //             $Pago_Servicio->Divisa = $Vdivisa[$cont];
            //             $Pago_Servicio->MontoDivisa = $VMontoDivisa[$cont];
            //             $Pago_Servicio->TasaTiket = $VTasaTiket[$cont];
            //             $Pago_Servicio->MontoDolar = $VMontoDolar[$cont];
            //             $Pago_Servicio->servicio_id = $servicio_id;
            //             $Pago_Servicio->caja_id = $request->get('caja_id');
            //             $Pago_Servicio->save();

            //             $cont = $cont+1;
            //         }



            //         // // TODO Ahora vamos a actualizar la tabla Pago_Servicios en su campo vueltos para evitar el problema de registrar
            //         // //vueltos incorrectamente cuando se pagaba por medio del modo mixto

            //         // //consultamos la tabla pago_servicios donde el id se el mismo del servicio que registramos para actualizar el campo
            //         // //vueltos ejeplo: si pagan 10 en dolares la tabla servicio solo registra los 10 dolares pero el campo vueltos
            //         // // queda en 0 luego se consulta la tabla vueltos y si dieron vueltos en dolares actualiza el campo vueltos donde
            //         // //divisa sea igual a dolar esto se hace para corregir el error que presentava cuando se pagaba en modo mixto con
            //         // // varias modedas.

            //         // $revisarServicios = Pago_Servicio::where('servicio_id', $servicio->id)->get();
            //         // // return $revisarServicios;
            //         // if(count($revisarServicios)){
            //         //     // return $revisarServicios;

            //         //     foreach ($revisarServicios as $servicioReg) {
            //         //         // return $servicioReg;
            //         //         //Actializamos la tabla Pago_Servicios donde el id servicio sea igual al id recibido y el campo divisa
            //         //         //sea igual alcampo divisa de la tabla pagos vuelos para ello consultamos la tabla vueltos

            //         //         $vueltoReg = Pago_Vuelto::where('servicio_id', $servicio->id)->where('Divisa', $servicioReg->Divisa)->get();
            //         //         if(count($vueltoReg)){
            //         //             // return $vueltoReg[0]->MontoDivisa;
            //         //             $upDateVueltos = Pago_Servicio::findOrFail($servicioReg->id);
            //         //             $upDateVueltos->Vueltos = -$vueltoReg[0]->MontoDivisa;;
            //         //             $upDateVueltos->update();
            //         //         }

            //         //     }
            //         // }
            //     }

            //     ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //     ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // }
            DB::commit();

        }catch(\Exception $e)
        {

            DB::rollback();
            dd($e);
        }
        // return count($requestPrint->idarticulo);
        $printer = new PrinterController;

        $printer->ticketConsumo('Consumo', $articulo_id, $serie_comprobante, $precio_venta_unidad, $cantidad, $modo_pago, $tipo_pago, $total_venta, $operador);

        return Redirect::to('checkout')->with('success', 'La venta fué registrada exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $habitacion = Servicio::where('id','=',"$id")->get();
        $sevicioVenta = Servicios_Ventas::where('servicio_id', $habitacion[0]->id)->get();
        // return $sevicioVenta;
        $service = Servicio::where('id', $habitacion[0]->id)->first();
        $cliente = Persona::where('id',$habitacion[0]->persona_id)->first();
        $credito = Credito::where('persona_id',$habitacion[0]->persona_id)->first();
        // return $cliente;
////////////////////////////////////////////////////////////////////////////////////////////////////////
        $User = Auth::user();

        Sessioncaja::crearsession();
        $tasa = Tasa::find(1);
        // return $tasa;
        $tasa->updated_at;
        $fechaActual = Carbon::now();

        if ($tasa->tasa <= 0 || $tasa->updated_at->diffInHours($fechaActual) >= 6 ) {
            return redirect()
            ->route('tasa.index')
            ->with('status_danger', '¡Debes Actualizar el margen de gananacia para poder acceder!');
        }else{

            $cajaSessionid =  Sessioncaja::where('estado', 'Abierta')->orderBy('id', 'desc')->first();
            // dd($cajaSessionid);
            $Caja = Caja::where("estado","=",'Abierta')->where("sessioncaja_id","=", $cajaSessionid->id)->first();

            if ($Caja) {

                if($Caja->user_id == Auth::id()){
                    $title='Nueva venta';
                    $personas = DB::table('personas')->where('tipo_persona', '=', 'Cliente')->get();
                    $tasaDolar = DB::table('tasas')->where('nombre', '=', 'Dolar')->first();
                    $tasaPeso = DB::table('tasas')->where('nombre', '=', 'Peso')->first();
                    $tasaTransferenciaPunto = DB::table('tasas')->where('nombre', '=', 'Transferencia_Punto')->first();
                    $tasaMixto = DB::table('tasas')->where('nombre', '=', 'Mixto')->first();
                    $tasaEfectivo = DB::table('tasas')->where('nombre', '=', 'Efectivo')->first();
                    $articulos = DB::table('articulos as art')
                        ->select(DB::raw('CONCAT(art.codigo, " - ", art.nombre) AS articulo'), 'art.imagen', 'art.vender_al', 'art.nombre','art.id', 'precio_costo', 'porEspecial', 'isDolar', 'isPeso', 'isTransPunto', 'isMixto', 'isEfectivo', 'isKilo', 'stock', 'art.nombre')
                        ->where('art.estado', '=', 'Activo')
                        ->where('art.stock', '>', '0')
                        ->where('art.precio_costo', '>', '0')
                        ->get();


                    $UserId = Auth::id();
                    $caja = Caja::where("estado","=",'Abierta')->where("sessioncaja_id","=", $cajaSessionid->id)->first();
                    $ventaNum = Venta::latest('id')->first();

                    if (is_null($ventaNum)) {

                        $num_comprobante = Sessioncaja::numCodigo('C', $UserId, 1);
                        $serie_comprobante = Sessioncaja::numCodigo('N', $UserId, 1);

                    }else{
                        $num_comprobante = Sessioncaja::numCodigo('C', $UserId, $ventaNum->id+1);
                        $serie_comprobante = Sessioncaja::numCodigo('N', $UserId, $ventaNum->id+1);
                    }



                    $ventas = DB::table('ventas as v')
                        ->join('personas as p', 'v.persona_id', '=', 'p.id')
                        ->join('articulo_ventas as av', 'v.id', '=', 'av.venta_id')
                        ->join('cajas as c', 'v.caja_id', '=', 'c.sessioncaja_id')
                        ->join('users as u', 'u.id', '=', 'c.user_id')
                        ->select('u.name','c.user_id','v.id', 'v.fecha_hora','v.modo_pago','v.caja_id', 'c.sessioncaja_id', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.total_venta', 'v.estado')
                        ->where('c.user_id', '=', Auth::user()->id)
                        ->where('c.estado', '=', 'Abierta')
                        ->orderBy('v.id', 'desc')
                        ->groupBy('u.name','c.user_id','v.id', 'v.fecha_hora','v.modo_pago', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.total_venta', 'v.estado')
                        ->get();

                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    $cajas = Caja::find($caja->id);
                    $cajas->user;
                    $cajas->ventas;
                    $cajas->pago_ventas;
                    $cajas->articulo_ventas;
                    $cajas->servicios;
                    // $cajas->creditos;
                    $cajas->cortesias;
                    $cajas->pago_servicios;


                    // foreach ($cajas->pago_ventas as $pago ) {

                    //     if ($pago->Divisa == 'Dolar') {
                    //         $cajas->SumaTotalDolar = $cajas->SumaTotalDolar + ($pago->MontoDivisa - $pago->Vueltos * -1);
                    //     }elseif ($pago->Divisa == 'Peso') {
                    //         $cajas->SumaTotalPeso = $cajas->SumaTotalPeso + ($pago->MontoDivisa - $pago->Vueltos * -1);
                    //     }elseif ($pago->Divisa == 'Bolivar') {
                    //         $cajas->SumaTotalBolivar = $cajas->SumaTotalBolivar + ($pago->MontoDivisa - $pago->Vueltos * -1);
                    //     }elseif ($pago->Divisa == 'Punto') {
                    //         $cajas->SumaTotalPunto = $cajas->SumaTotalPunto + ($pago->MontoDivisa - $pago->Vueltos * -1);
                    //     }elseif ($pago->Divisa == 'Transferencia') {
                    //         $cajas->SumaTotalTransferencia = $cajas->SumaTotalTransferencia + ($pago->MontoDivisa - $pago->Vueltos * -1);
                    //     }

                    // }

                    foreach ($cajas->ventas as $vent ) {
                        if ($vent->estado == 'Aceptada' && $vent->status == 'Pagado') {
                        $cajas->SumaTotalVentas = $cajas->SumaTotalVentas + $vent->total_venta;

                        }

                        if ($vent->estado == 'Aceptada') {

                            $cajas->SumaTotalCantidadVentas = $cajas->SumaTotalCantidadVentas + 1;
                            }
                    }

                    foreach ($cajas->articulo_ventas as $art_vent ) {
                        $cajas->SumaArticulosVendidos = $cajas->SumaArticulosVendidos + $art_vent->cantidad;
                        if($art_vent->estado_pago == 'Falta pagar'){
                            // $cajas->SumaTotalVentas = $cajas->SumaTotalVentas - ($art_vent->cantidad * $art_vent->precio_venta_unidad);
                            $cajas->SumaTotalVentasPorCobrar = $cajas->SumaTotalVentasPorCobrar + ($art_vent->cantidad * $art_vent->precio_venta_unidad);
                        }

                    }

                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    // foreach ($cajas->pago_servicios as $pagoS ) {

                    //     if ($pagoS->Divisa == 'Dolar') {
                    //         if($pagoS->Vueltos > 0){
                    //             $cajas->SumaTotalDolarServ = $cajas->SumaTotalDolarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos);
                    //         }else{
                    //         $cajas->SumaTotalDolarServ = $cajas->SumaTotalDolarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1);
                    //         }
                    //     }elseif ($pagoS->Divisa == 'Peso') {
                    //         $cajas->SumaTotalPesoServ = $cajas->SumaTotalPesoServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1);
                    //     }elseif ($pagoS->Divisa == 'Bolivar') {
                    //         $cajas->SumaTotalBolivarServ = $cajas->SumaTotalBolivarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1);
                    //     }elseif ($pagoS->Divisa == 'Punto') {
                    //         $cajas->SumaTotalPuntoServ = $cajas->SumaTotalPuntoServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1);
                    //     }elseif ($pagoS->Divisa == 'Transferencia') {
                    //         $cajas->SumaTotalTransferenciaServ = $cajas->SumaTotalTransferenciaServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1);
                    //     }

                    // }



                    foreach ($cajas->servicios as $serv ) {
                        if ($serv->estado == 'Aceptada') {
                            if($serv->status == 'Pagado'){
                                $cajas->SumaTotalServicios = $cajas->SumaTotalServicios + $serv->total_venta;
                                $cajas->SumaTotalCantidadServicios = $cajas->SumaTotalCantidadServicios + 1;
                            }

                            if($serv->status == 'Falta pagar'){
                                $cajas->SumaTotalServiciosPorPagar = $cajas->SumaTotalServiciosPorPagar + $serv->total_venta;
                                $cajas->SumaTotalCantidadServiciosPorPagar = $cajas->SumaTotalCantidadServiciosPorPagar + 1;
                            }

                            if($serv->status == 'Exonerado'){
                                $cajas->SumaTotalServiciosCortesia = $cajas->SumaTotalServiciosCortesia + $serv->total_venta;
                                $cajas->SumaTotalCantidadServiciosCortesia = $cajas->SumaTotalCantidadServiciosCortesia + 1;
                            }

                        }
                    }

                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    // TODO Verificar que tengamos liquidez en esa divisa para dar vueltos y se procesa
                        //con este codigo buscamos los saldos disponibles en dolar, peso y bolivar
                        // el resultado son 3 variables de nombre $dolarDisponible, $pesoDisponible y $bolivarDisponible.
                        //para luego con esto poder dar los vueltos si hay desponibilidad.


                        $tasaDolar = DB::table('tasas')->where('nombre', '=', 'Dolar')->first();
                        $tasaPeso = DB::table('tasas')->where('nombre', '=', 'Peso')->first();
                        $tasaTransferenciaPunto = DB::table('tasas')->where('nombre', '=', 'Transferencia_Punto')->first();
                        $tasaMixto = DB::table('tasas')->where('nombre', '=', 'Mixto')->first();
                        $tasaEfectivo = DB::table('tasas')->where('nombre', '=', 'Efectivo')->first();


                        $cajas->user;
                        $cajas->ventas;
                        $cajas->pago_ventas;
                        $cajas->articulo_ventas;
                        $cajas->servicios;
                        $cajas->detalle_creditos;
                        // $cajas->credito;
                        $cajas->cortesias;
                        $cajas->pago_servicios;
                        $cajas->pago_creditos;
                        $cajas->creditos_pagados;
                        $cajas->excedente_actual;



                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        foreach ($cajas->creditos_pagados as $credPagados ) {

                            if ($credPagados->user_id == $cajas->user_id){

                                $validarPagosCreditos = Pago_Credito::where('detalle_credito_id',$credPagados->detalle_credito_id)->get();
                                if (count($validarPagosCreditos)) {
                                    foreach ($validarPagosCreditos as $credPagadosCaja ) {
                                        if ($credPagadosCaja->Divisa == 'Dolar') {
                                            $cajas->SumaTotalDolarCred = $cajas->SumaTotalDolarCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);
                                        }elseif ($credPagadosCaja->Divisa == 'Peso') {
                                            $cajas->SumaTotalPesoCred = $cajas->SumaTotalPesoCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);
                                        }elseif ($credPagadosCaja->Divisa == 'Bolivar') {
                                            $cajas->SumaTotalBolivarCred = $cajas->SumaTotalBolivarCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);
                                        }elseif ($credPagadosCaja->Divisa == 'Punto') {
                                            $cajas->SumaTotalPuntoCred = $cajas->SumaTotalPuntoCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);
                                        }elseif ($credPagadosCaja->Divisa == 'Transferencia') {
                                            $cajas->SumaTotalTransferenciaCred = $cajas->SumaTotalTransferenciaCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);
                                        }
                                    }
                                }

                                if ($credPagados->tipo_operacion == 'Consumo'){

                                    $validarPagosCreditosConsumo = Pago_Credito::where('detalle_credito_id',$credPagados->detalle_credito_id)->get();
                                    if (count($validarPagosCreditosConsumo)) {
                                        foreach ($validarPagosCreditosConsumo as $credPagadosCajaConsumo ) {
                                            if ($credPagadosCajaConsumo->Divisa == 'Dolar') {
                                                $cajas->SumaTotalDolarCredConsumo = $cajas->SumaTotalDolarCredConsumo + ($credPagadosCajaConsumo->MontoDivisa - $credPagadosCajaConsumo->Vueltos * -1);
                                            }elseif ($credPagadosCajaConsumo->Divisa == 'Peso') {
                                                $cajas->SumaTotalPesoCredConsumo = $cajas->SumaTotalPesoCredConsumo + ($credPagadosCajaConsumo->MontoDivisa - $credPagadosCajaConsumo->Vueltos * -1);
                                            }elseif ($credPagadosCajaConsumo->Divisa == 'Bolivar') {
                                                $cajas->SumaTotalBolivarCredConsumo = $cajas->SumaTotalBolivarCredConsumo + ($credPagadosCajaConsumo->MontoDivisa - $credPagadosCajaConsumo->Vueltos * -1);
                                            }elseif ($credPagadosCajaConsumo->Divisa == 'Punto') {
                                                $cajas->SumaTotalPuntoCredConsumo = $cajas->SumaTotalPuntoCredConsumo + ($credPagadosCajaConsumo->MontoDivisa - $credPagadosCajaConsumo->Vueltos * -1);
                                            }elseif ($credPagadosCajaConsumo->Divisa == 'Transferencia') {
                                                $cajas->SumaTotalTransferenciaCredConsumo = $cajas->SumaTotalTransferenciaCredConsumo + ($credPagadosCajaConsumo->MontoDivisa - $credPagadosCajaConsumo->Vueltos * -1);
                                            }
                                        }
                                    }

                                    $cajas->SumaTotalCreditosPagadosConsumoPorCaja = $cajas->SumaTotalCreditosPagadosConsumoPorCaja + $credPagados->monto;

                                }

                                if ($credPagados->tipo_operacion == 'Servicio'){

                                    $validarPagosCreditosServicio = Pago_Credito::where('detalle_credito_id',$credPagados->detalle_credito_id)->get();
                                    if (count($validarPagosCreditosServicio)) {
                                        foreach ($validarPagosCreditosServicio as $credPagadosCajaServicio ) {
                                            if ($credPagadosCajaServicio->Divisa == 'Dolar') {
                                                $cajas->SumaTotalDolarCredServicio = $cajas->SumaTotalDolarCredServicio + ($credPagadosCajaServicio->MontoDivisa - $credPagadosCajaServicio->Vueltos * -1);
                                            }elseif ($credPagadosCajaServicio->Divisa == 'Peso') {
                                                $cajas->SumaTotalPesoCredServicio = $cajas->SumaTotalPesoCredServicio + ($credPagadosCajaServicio->MontoDivisa - $credPagadosCajaServicio->Vueltos * -1);
                                            }elseif ($credPagadosCajaServicio->Divisa == 'Bolivar') {
                                                $cajas->SumaTotalBolivarCredServicio = $cajas->SumaTotalBolivarCredServicio + ($credPagadosCajaServicio->MontoDivisa - $credPagadosCajaServicio->Vueltos * -1);
                                            }elseif ($credPagadosCajaServicio->Divisa == 'Punto') {
                                                $cajas->SumaTotalPuntoCredServicio = $cajas->SumaTotalPuntoCredServicio + ($credPagadosCajaServicio->MontoDivisa - $credPagadosCajaServicio->Vueltos * -1);
                                            }elseif ($credPagadosCajaServicio->Divisa == 'Transferencia') {
                                                $cajas->SumaTotalTransferenciaCredServicio = $cajas->SumaTotalTransferenciaCredServicio + ($credPagadosCajaServicio->MontoDivisa - $credPagadosCajaServicio->Vueltos * -1);
                                            }
                                        }
                                    }

                                    $cajas->SumaTotalCreditosPagadosServicioPorCaja = $cajas->SumaTotalCreditosPagadosServicioPorCaja + $credPagados->monto;

                                }
                                    $cajas->SumaTotalCreditosPagadosTotalesPorCaja = $cajas->SumaTotalCreditosPagadosTotalesPorCaja + $credPagados->monto;

                            }else{

                                $validarPagosCreditos = Pago_Credito::where('detalle_credito_id',$credPagados->detalle_credito_id)->get();
                                if (count($validarPagosCreditos)) {
                                    foreach ($validarPagosCreditos as $credPagadosOficina ) {
                                        if ($credPagadosOficina->Divisa == 'Dolar') {
                                            $cajas->SumaTotalDolarCredPorOficina = $cajas->SumaTotalDolarCredPorOficina + ($credPagadosOficina->MontoDivisa - $credPagadosOficina->Vueltos * -1);
                                        }elseif ($credPagadosOficina->Divisa == 'Peso') {
                                            $cajas->SumaTotalPesoCredPorOficina = $cajas->SumaTotalPesoCredPorOficina + ($credPagadosOficina->MontoDivisa - $credPagadosOficina->Vueltos * -1);
                                        }elseif ($credPagadosOficina->Divisa == 'Bolivar') {
                                            $cajas->SumaTotalBolivarCredPorOficina = $cajas->SumaTotalBolivarCredPorOficina + ($credPagadosOficina->MontoDivisa - $credPagadosOficina->Vueltos * -1);
                                        }elseif ($credPagadosOficina->Divisa == 'Punto') {
                                            $cajas->SumaTotalPuntoCredPorOficina = $cajas->SumaTotalPuntoCredPorOficina + ($credPagadosOficina->MontoDivisa - $credPagadosOficina->Vueltos * -1);
                                        }elseif ($credPagadosOficina->Divisa == 'Transferencia') {
                                            $cajas->SumaTotalTransferenciaCredPorOficina = $cajas->SumaTotalTransferenciaCredPorOficina + ($credPagadosOficina->MontoDivisa - $credPagadosOficina->Vueltos * -1);
                                        }
                                    }
                                }

                                if ($credPagados->tipo_operacion == 'Consumo'){

                                    $cajas->SumaTotalCreditosPagadosConsumoPorOficina = $cajas->SumaTotalCreditosPagadosConsumoPorOficina + $credPagados->monto;


                                }

                                if ($credPagados->tipo_operacion == 'Servicio'){

                                    $cajas->SumaTotalCreditosPagadosServicioPorOficina = $cajas->SumaTotalCreditosPagadosServicioPorOficina + $credPagados->monto;


                                }

                                $cajas->SumaTotalCreditosPagadosTotalesPorOficina = $cajas->SumaTotalCreditosPagadosTotalesPorOficina + $credPagados->monto;

                            }


                            if ($credPagados->tipo_operacion == 'Servicio') {

                                // $cajas->SumaTotalCreditosPagadosServicio = $cajas->SumaTotalCreditosPagadosServicio + $credPagados->monto;
                                $cajas->SumaTotalCantidadCreditosPagadosServicio = $cajas->SumaTotalCantidadCreditosPagadosServicio + 1;

                            }

                            if ($credPagados->tipo_operacion == 'Consumo') {

                                // $cajas->SumaTotalCreditosPagadosConsumo = $cajas->SumaTotalCreditosPagadosConsumo + $credPagados->monto;
                                $cajas->SumaTotalCantidadCreditosPagadosConsumo =  $cajas->SumaTotalCantidadCreditosPagadosConsumo + 1;

                            }
                            // $cajas->SumaTotalCreditosPagadosTotales = $cajas->SumaTotalCreditosPagadosTotales + $credPagados->monto;
                            $cajas->SumaTotalCantidadCreditosPagadosTotales = $cajas->SumaTotalCantidadCreditosPagadosTotales + 1;

                        }
                        // return $cajas->SumaTotalCreditosPagados;
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        // TODO optenemos los datos de la tabla excedente actual

                        foreach ($cajas->excedente_actual as $excedenteActual) {

                            // TODO sacamos los totales devueltos recibidos por excedentes totales
                            if ($excedenteActual->Divisa == 'Dolar') {
                                $cajas->TotalSumaVueltosPendientesDolarDivisa = $cajas->TotalSumaVueltosPendientesDolarDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->TotalSumaVueltosPendientesDolarDolar = $cajas->TotalSumaVueltosPendientesDolarDolar  + $excedenteActual->MontoDolar;
                            }elseif ($excedenteActual->Divisa == 'Peso') {
                                $cajas->TotalSumaVueltosPendientesPesoDivisa = $cajas->TotalSumaVueltosPendientesPesoDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->TotalSumaVueltosPendientesPesoDolar = $cajas->TotalSumaVueltosPendientesPesoDolar  + $excedenteActual->MontoDolar;
                            }elseif ($excedenteActual->Divisa == 'Bolivar') {
                                $cajas->TotalSumaVueltosPendientesBolivarDivisa = $cajas->TotalSumaVueltosPendientesBolivarDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->TotalSumaVueltosPendientesBolivarDolar = $cajas->TotalSumaVueltosPendientesBolivarDolar  + $excedenteActual->MontoDolar;
                            }elseif ($excedenteActual->Divisa == 'Punto') {
                                $cajas->TotalSumaVueltosPendientesPuntoDivisa = $cajas->TotalSumaVueltosPendientesPuntoDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->TotalSumaVueltosPendientesPuntoDolar = $cajas->TotalSumaVueltosPendientesPuntoDolar  + $excedenteActual->MontoDolar;
                            }elseif ($excedenteActual->Divisa == 'Transferencia') {
                                $cajas->TotalSumaVueltosPendientesTransferenciaDivisa = $cajas->TotalSumaVueltosPendientesTransferenciaDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->TotalSumaVueltosPendientesTransferenciaDolar = $cajas->TotalSumaVueltosPendientesTransferenciaDolar  + $excedenteActual->MontoDolar;
                            }


                            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            // TODO hacemos subconsultas

                            if ($excedenteActual->Estado == 'Pendiente') {
                                if ($excedenteActual->Divisa == 'Dolar') {
                                    $cajas->SumaVueltosPendientesDolarDivisa = $cajas->SumaVueltosPendientesDolarDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPendientesDolarDolar = $cajas->SumaVueltosPendientesDolarDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Peso') {
                                    $cajas->SumaVueltosPendientesPesoDivisa = $cajas->SumaVueltosPendientesPesoDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPendientesPesoDolar = $cajas->SumaVueltosPendientesPesoDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Bolivar') {
                                    $cajas->SumaVueltosPendientesBolivarDivisa = $cajas->SumaVueltosPendientesBolivarDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPendientesBolivarDolar = $cajas->SumaVueltosPendientesBolivarDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Punto') {
                                    $cajas->SumaVueltosPendientesPuntoDivisa = $cajas->SumaVueltosPendientesPuntoDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPendientesPuntoDolar = $cajas->SumaVueltosPendientesPuntoDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Transferencia') {
                                    $cajas->SumaVueltosPendientesTransferenciaDivisa = $cajas->SumaVueltosPendientesTransferenciaDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPendientesTransferenciaDolar = $cajas->SumaVueltosPendientesTransferenciaDolar  + $excedenteActual->MontoDolar;
                                }
                            }elseif($excedenteActual->Estado == 'Devueltos'){
                                if ($excedenteActual->Divisa == 'Dolar') {
                                    $cajas->SumaVueltosDevueltosDolarDivisa = $cajas->SumaVueltosDevueltosDolarDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosDevueltosDolarDolar = $cajas->SumaVueltosDevueltosDolarDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Peso') {
                                    $cajas->SumaVueltosDevueltosPesoDivisa = $cajas->SumaVueltosDevueltosPesoDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosDevueltosPesoDolar = $cajas->SumaVueltosDevueltosPesoDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Bolivar') {
                                    $cajas->SumaVueltosDevueltosBolivarDivisa = $cajas->SumaVueltosDevueltosBolivarDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosDevueltosBolivarDolar = $cajas->SumaVueltosDevueltosBolivarDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Punto') {
                                    $cajas->SumaVueltosDevueltosPuntoDivisa = $cajas->SumaVueltosDevueltosPuntoDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosDevueltosPuntoDolar = $cajas->SumaVueltosDevueltosPuntoDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Transferencia') {
                                    $cajas->SumaVueltosDevueltosTransferenciaDivisa = $cajas->SumaVueltosDevueltosTransferenciaDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosDevueltosTransferenciaDolar = $cajas->SumaVueltosDevueltosTransferenciaDolar  + $excedenteActual->MontoDolar;
                                }
                            }elseif($excedenteActual->Estado == 'PagarOficina'){
                                if ($excedenteActual->Divisa == 'Dolar') {
                                    $cajas->SumaVueltosPagarOficinaDolarDivisa = $cajas->SumaVueltosPagarOficinaDolarDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPagarOficinaDolarDolar = $cajas->SumaVueltosPagarOficinaDolarDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Peso') {
                                    $cajas->SumaVueltosPagarOficinaPesoDivisa = $cajas->SumaVueltosPagarOficinaPesoDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPagarOficinaPesoDolar = $cajas->SumaVueltosPagarOficinaPesoDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Bolivar') {
                                    $cajas->SumaVueltosPagarOficinaBolivarDivisa = $cajas->SumaVueltosPagarOficinaBolivarDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPagarOficinaBolivarDolar = $cajas->SumaVueltosPagarOficinaBolivarDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Punto') {
                                    $cajas->SumaVueltosPagarOficinaPuntoDivisa = $cajas->SumaVueltosPagarOficinaPuntoDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPagarOficinaPuntoDolar = $cajas->SumaVueltosPagarOficinaPuntoDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Transferencia') {
                                    $cajas->SumaVueltosPagarOficinaTransferenciaDivisa = $cajas->SumaVueltosPagarOficinaTransferenciaDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosPagarOficinaTransferenciaDolar = $cajas->SumaVueltosPagarOficinaTransferenciaDolar  + $excedenteActual->MontoDolar;
                                }
                            }elseif($excedenteActual->Estado == 'ExcedenteNuevo'){
                                if ($excedenteActual->Divisa == 'Dolar') {
                                    $cajas->SumaVueltosExcedenteNuevoDolarDivisa = $cajas->SumaVueltosExcedenteNuevoDolarDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosExcedenteNuevoDolarDolar = $cajas->SumaVueltosExcedenteNuevoDolarDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Peso') {
                                    $cajas->SumaVueltosExcedenteNuevoPesoDivisa = $cajas->SumaVueltosExcedenteNuevoPesoDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosExcedenteNuevoPesoDolar = $cajas->SumaVueltosExcedenteNuevoPesoDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Bolivar') {
                                    $cajas->SumaVueltosExcedenteNuevoBolivarDivisa = $cajas->SumaVueltosExcedenteNuevoBolivarDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosExcedenteNuevoBolivarDolar = $cajas->SumaVueltosExcedenteNuevoBolivarDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Punto') {
                                    $cajas->SumaVueltosExcedenteNuevoPuntoDivisa = $cajas->SumaVueltosExcedenteNuevoPuntoDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosExcedenteNuevoPuntoDolar = $cajas->SumaVueltosExcedenteNuevoPuntoDolar  + $excedenteActual->MontoDolar;
                                }elseif ($excedenteActual->Divisa == 'Transferencia') {
                                    $cajas->SumaVueltosExcedenteNuevoTransferenciaDivisa = $cajas->SumaVueltosExcedenteNuevoTransferenciaDivisa  + $excedenteActual->MontoDivisa;
                                    $cajas->SumaVueltosExcedenteNuevoTransferenciaDolar = $cajas->SumaVueltosExcedenteNuevoTransferenciaDolar  + $excedenteActual->MontoDolar;
                                }

                                if($excedenteActual->Tipo == 'Servicio'){
                                    $cajas->TotalSumaVueltosExcedenteNuevoServicioDolarToDolar = $excedenteActual->MontoDolar;
                                }
                                if($excedenteActual->Tipo == 'Consumo'){
                                    $cajas->TotalSumaVueltosExcedenteNuevoConsumoDolarToDolar = $excedenteActual->MontoDolar;
                                }

                                if($excedenteActual->Tipo == 'Otros'){
                                    $cajas->TotalSumaVueltosExcedenteNuevoOtrosDolarToDolar = $excedenteActual->MontoDolar;
                                }
                                $cajas->TotalSumaVueltosExcedenteNuevoDolarToDolar = $excedenteActual->MontoDolar;
                            }
                        }

                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        foreach ($cajas->pago_servicios as $pagoS ) {

                            $validarPagosServicios = Servicio::where('id',$pagoS->servicio_id)->first();
                            if ($validarPagosServicios) {
                                if ($pagoS->Divisa == 'Dolar') {
                                    if($pagoS->Vueltos > 0){
                                        $cajas->SumaTotalDolarServ = $cajas->SumaTotalDolarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - $validarPagosServicios->excedente_nuevo;
                                    }else{
                                        $cajas->SumaTotalDolarServDflotante = $cajas->SumaTotalDolarServDflotante + ($pagoS->Vueltos * -1);
                                        $cajas->SumaTotalDolarServ = $cajas->SumaTotalDolarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - $validarPagosServicios->excedente_nuevo;
                                    }
                                }elseif ($pagoS->Divisa == 'Peso') {
                                    if($pagoS->Vueltos > 0){
                                        $cajas->SumaTotalPesoServ = $cajas->SumaTotalPesoServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaPeso->tasa);
                                    }else{
                                        $cajas->SumaTotalPesoServDflotante = $cajas->SumaTotalPesoServDflotante + ( $pagoS->Vueltos * -1);
                                        $cajas->SumaTotalPesoServ = $cajas->SumaTotalPesoServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaPeso->tasa);
                                    }
                                }elseif ($pagoS->Divisa == 'Bolivar') {
                                    if($pagoS->Vueltos > 0){
                                        $cajas->SumaTotalBolivarServ = $cajas->SumaTotalBolivarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaEfectivo->tasa);
                                    }else{
                                        $cajas->SumaTotalBolivarServDflotante = $cajas->SumaTotalBolivarServDflotante + ($pagoS->Vueltos * -1) * $tasaEfectivo->tasa;
                                        $cajas->SumaTotalBolivarServ = $cajas->SumaTotalBolivarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaEfectivo->tasa);
                                    }
                                }elseif ($pagoS->Divisa == 'Punto') {
                                    if($pagoS->Vueltos > 0){
                                        $cajas->SumaTotalPuntoServ = $cajas->SumaTotalPuntoServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                                    }else{
                                        $cajas->SumaTotalPuntoServDflotante = $cajas->SumaTotalPuntoServDflotante + ($pagoS->Vueltos * -1) * $tasaTransferenciaPunto->tasa;
                                        $cajas->SumaTotalPuntoServ = $cajas->SumaTotalPuntoServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                                    }
                                }elseif ($pagoS->Divisa == 'Transferencia') {
                                    if($pagoS->Vueltos > 0){
                                        $cajas->SumaTotalTransferenciaServ = $cajas->SumaTotalTransferenciaServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                                    }else{
                                        $cajas->SumaTotalTransferenciaServDflotante = $cajas->SumaTotalTransferenciaServDflotante + ($pagoS->Vueltos * -1) * $tasaTransferenciaPunto->tasa;
                                        $cajas->SumaTotalTransferenciaServ = $cajas->SumaTotalTransferenciaServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                                    }
                                }
                            }

                        }

                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        foreach ($cajas->pago_ventas as $pago ) {

                            if ($pago->Divisa == 'Dolar') {
                                $cajas->SumaTotalDolar = $cajas->SumaTotalDolar + ($pago->MontoDivisa - $pago->Vueltos * -1);
                            }elseif ($pago->Divisa == 'Peso') {
                                $cajas->SumaTotalPeso = $cajas->SumaTotalPeso + ($pago->MontoDivisa - $pago->Vueltos * -1);
                            }elseif ($pago->Divisa == 'Bolivar') {
                                $cajas->SumaTotalBolivar = $cajas->SumaTotalBolivar + ($pago->MontoDivisa - $pago->Vueltos * -1);
                            }elseif ($pago->Divisa == 'Punto') {
                                $cajas->SumaTotalPunto = $cajas->SumaTotalPunto + ($pago->MontoDivisa - $pago->Vueltos * -1);
                            }elseif ($pago->Divisa == 'Transferencia') {
                                $cajas->SumaTotalTransferencia = $cajas->SumaTotalTransferencia + ($pago->MontoDivisa - $pago->Vueltos * -1);
                            }

                        }
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        foreach ($cajas->pago_extras as $pagoext ) {

                            if ($pagoext->Divisa == 'Dolar') {
                                $cajas->SumaTotalDolarPagoExtra = $cajas->SumaTotalDolarPagoExtra + ($pagoext->MontoDivisa - $pagoext->Vueltos * -1);
                            }elseif ($pagoext->Divisa == 'Peso') {
                                $cajas->SumaTotalPesoPagoExtra = $cajas->SumaTotalPesoPagoExtra + ($pagoext->MontoDivisa - $pagoext->Vueltos * -1);
                            }elseif ($pagoext->Divisa == 'Bolivar') {
                                $cajas->SumaTotalBolivarPagoExtra = $cajas->SumaTotalBolivarPagoExtra + ($pagoext->MontoDivisa - $pagoext->Vueltos * -1);
                            }elseif ($pagoext->Divisa == 'Punto') {
                                $cajas->SumaTotalPuntoPagoExtra = $cajas->SumaTotalPuntoPagoExtra + ($pagoext->MontoDivisa - $pagoext->Vueltos * -1);
                            }elseif ($pagoext->Divisa == 'Transferencia') {
                                $cajas->SumaTotalTransferenciaPagoExtra = $cajas->SumaTotalTransferenciaPagoExtra + ($pagoext->MontoDivisa - $pagoext->Vueltos * -1);
                            }

                        }
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        $excedentesPendientes = Excedentes_Recibidos_Caja_Actual::where('Estado','Pendiente')->get();
                        if ($excedentesPendientes) {


                            foreach ($excedentesPendientes as $excdPendientes ) {
                                // return $excdPendientes->Divisa;
                                if ($excdPendientes->Divisa == 'Dolar') {
                                    $cajas->SumaTotalDolarExcedentesPendientes = $cajas->SumaTotalDolarExcedentesPendientes + ($excdPendientes->MontoDivisa);
                                    // return $cajas->SumaTotalDolarExcedentesPendientes;
                                }elseif ($excdPendientes->Divisa == 'Peso') {
                                    $cajas->SumaTotalPesoExcedentesPendientes = $cajas->SumaTotalPesoExcedentesPendientes + ($excdPendientes->MontoDivisa);
                                }elseif ($excdPendientes->Divisa == 'Bolivar') {
                                    $cajas->SumaTotalBolivarExcedentesPendientes = $cajas->SumaTotalBolivarExcedentesPendientes + ($excdPendientes->MontoDivisa);
                                }elseif ($excdPendientes->Divisa == 'Punto') {
                                    $cajas->SumaTotalPuntoExcedentesPendientes = $cajas->SumaTotalPuntoExcedentesPendientes + ($excdPendientes->MontoDivisa);
                                }elseif ($excdPendientes->Divisa == 'Transferencia') {
                                    $cajas->SumaTotalTransferenciaExcedentesPendientes = $cajas->SumaTotalTransferenciaExcedentesPendientes + ($excdPendientes->MontoDivisa);
                                }

                            }
                        }
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        foreach ($cajas->pago_vueltos as $pagoV ) {

                            if ($pagoV->Divisa == 'Dolar') {

                                $cajas->SumaTotalDolarVueltos = $cajas->SumaTotalDolarVueltos + $pagoV->MontoDivisa;

                            }elseif ($pagoV->Divisa == 'Peso') {
                                $cajas->SumaTotalPesoVueltos = $cajas->SumaTotalPesoVueltos + $pagoV->MontoDivisa;
                            }elseif ($pagoV->Divisa == 'Bolivar') {
                                $cajas->SumaTotalBolivarVueltos = $cajas->SumaTotalBolivarVueltos + $pagoV->MontoDivisa;
                            }elseif ($pagoV->Divisa == 'Punto') {
                                $cajas->SumaTotalPuntoVueltos = $cajas->SumaTotalPuntoVueltos + $pagoV->MontoDivisa;
                            }elseif ($pagoV->Divisa == 'Transferencia') {
                                $cajas->SumaTotalTransferenciaVueltos = $cajas->SumaTotalTransferenciaVueltos + $pagoV->MontoDivisa;
                            }

                        }

                        // return ($cajas->SumaTotalDolarExcedentesPendientes + $cajas->SumaTotalDolarPagoExtra + $cajas->monto_dolar + ($cajas->SumaTotalDolarCredConsumo + $cajas->SumaTotalDolarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoDolarDivisa) + ($cajas->SumaTotalDolarServ + $cajas->SumaTotalDolar) + ($cajas->SumaTotalDolarServDflotante));


                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        // TODO Ahora le agregamos caja chica
// return $cajas->SumaTotalDolarVueltos;
                        // return 'algo';
                        $dolarDisponible = ($cajas->SumaTotalDolarExcedentesPendientes + $cajas->SumaTotalDolarPagoExtra + $cajas->monto_dolar + ($cajas->SumaTotalDolarCredConsumo + $cajas->SumaTotalDolarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoDolarDivisa) + ($cajas->SumaTotalDolarServ + $cajas->SumaTotalDolar) + ($cajas->SumaTotalDolarServDflotante) - $cajas->SumaTotalDolarVueltos);
                        // return $dolarDisponible;
                        $pesoDisponible = ($cajas->SumaTotalPesoExcedentesPendientes + $cajas->SumaTotalPesoPagoExtra + $cajas->monto_peso + ($cajas->SumaTotalPesoCredConsumo + $cajas->SumaTotalPesoCredServicio) + ($cajas->SumaVueltosExcedenteNuevoPesoDivisa) + ($cajas->SumaTotalPesoServ + $cajas->SumaTotalPeso) + ($cajas->SumaTotalPesoServDflotante) - $cajas->SumaTotalPesoVueltos);
                        // return $pesoDisponible;
                        $bolivarDisponible = ($cajas->SumaTotalBolivarExcedentesPendientes + $cajas->SumaTotalBolivarPagoExtra + $cajas->monto_bolivar + ($cajas->SumaTotalBolivarCredConsumo + $cajas->SumaTotalBolivarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoBolivarDivisa) + ($cajas->SumaTotalBolivarServ + $cajas->SumaTotalBolivar) + ($cajas->SumaTotalBolivarServDflotante) - $cajas->SumaTotalBolivarVueltos);
                        // return $bolivarDisponible;
                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        // TODO Revisamos si el usuario tiene vueltos pendientes
                    // TODO pero tomando en cuenta que lo vamos a revisar por servicios no por la caja ?
                    $excedentesPendientesCajas = Excedentes_Recibidos_Caja_Actual::where('Estado', 'Pendiente')->get();
                    if($excedentesPendientesCajas){
                        foreach ($excedentesPendientesCajas as $exctePentesCajas) {
                            if ($exctePentesCajas->Estado == 'Pendiente' && $exctePentesCajas->servicio_id == $id) {
                                if ($exctePentesCajas->Divisa == 'Dolar') {
                                    $cajas->SumaVueltosPendientesDolarDivisa = $cajas->SumaVueltosPendientesDolarDivisa  + $exctePentesCajas->MontoDivisa;
                                    $cajas->SumaVueltosPendientesDolarDolar = $cajas->SumaVueltosPendientesDolarDolar  + $exctePentesCajas->MontoDolar;
                                }elseif ($exctePentesCajas->Divisa == 'Peso') {
                                    $cajas->SumaVueltosPendientesPesoDivisa = $cajas->SumaVueltosPendientesPesoDivisa  + $exctePentesCajas->MontoDivisa;
                                    $cajas->SumaVueltosPendientesPesoDolar = $cajas->SumaVueltosPendientesPesoDolar  + $exctePentesCajas->MontoDolar;
                                }elseif ($exctePentesCajas->Divisa == 'Bolivar') {
                                    $cajas->SumaVueltosPendientesBolivarDivisa = $cajas->SumaVueltosPendientesBolivarDivisa  + $exctePentesCajas->MontoDivisa;
                                    $cajas->SumaVueltosPendientesBolivarDolar = $cajas->SumaVueltosPendientesBolivarDolar  + $exctePentesCajas->MontoDolar;
                                }elseif ($exctePentesCajas->Divisa == 'Punto') {
                                    $cajas->SumaVueltosPendientesPuntoDivisa = $cajas->SumaVueltosPendientesPuntoDivisa  + $exctePentesCajas->MontoDivisa;
                                    $cajas->SumaVueltosPendientesPuntoDolar = $cajas->SumaVueltosPendientesPuntoDolar  + $exctePentesCajas->MontoDolar;
                                }elseif ($exctePentesCajas->Divisa == 'Transferencia') {
                                    $cajas->SumaVueltosPendientesTransferenciaDivisa = $cajas->SumaVueltosPendientesTransferenciaDivisa  + $exctePentesCajas->MontoDivisa;
                                    $cajas->SumaVueltosPendientesTransferenciaDolar = $cajas->SumaVueltosPendientesTransferenciaDolar  + $exctePentesCajas->MontoDolar;
                                }
                                $cajas->TotalSumaVueltosPendientesClienteDivisa = $cajas->TotalSumaVueltosPendientesClienteDivisa  + $exctePentesCajas->MontoDivisa;
                                $cajas->TotalSumaVueltosPendientesClienteDolar = $cajas->TotalSumaVueltosPendientesClienteDolar  + $exctePentesCajas->MontoDolar;
                            }
                        }
                    }


                    $tasaDolarHabitacion = Tasa::where('nombre','=','DolarHabitacion')->first();
                    $tasaPesoHabitacion = Tasa::where('nombre','=','PesoHabitacion')->first();
                    $UserName = Auth::user()->name;
                    $UserId = Auth::user()->id;


                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    //  return $cajas;
                    //dd($ventas);

                    return view('preventa.create', compact('cajas','dolarDisponible','pesoDisponible','bolivarDisponible','credito','cliente','UserName','UserId','tasaDolarHabitacion','tasaPesoHabitacion','cajas','habitacion','num_comprobante','serie_comprobante','caja', 'ventas','title','personas','tasaDolar','tasaPeso','tasaTransferenciaPunto','tasaMixto','tasaEfectivo','articulos'));
                }else{
                    return redirect()
                    ->route('caja.index')
                    ->with('status_danger', '¡Error de acceso! Solo se permite un usuario por caja para realizar ventas y ya se encuentra una caja abierta por otro usuario... ');
                }

            }
                return redirect()
                ->route('caja.index')
                ->with('status_danger', '¡Debes crear una caja para poder acceder!');


        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
