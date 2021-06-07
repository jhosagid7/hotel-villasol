<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Tasa;
use App\Venta;
use App\Credito;
use App\Articulo;
use App\Servicio;
use Carbon\Carbon;
use App\Sessioncaja;
use App\Contabilidad;
use App\Denominacion;
use App\Pago_Credito;
use App\Pago_Servicio;
use App\Detalle_credito;
use App\Excedentes_Pendientes_Caja_Anterior;
use App\Excedentes_Recibidos_Caja_Actual;
use App\Historial_Vueltos_Pendiente;
use App\Horas_extra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CajaController extends Controller
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

        Sessioncaja::crearsession();
        $mesActual = Carbon::now();
        $restaMes = Carbon::now()->subWeek(3);
        $restaMes = $restaMes->format('Y-m-d');
        $cajas = Caja::where("created_at",">=",$restaMes)
        ->where("fecha","<=",$mesActual)->orderBy('id', 'asc')->get();
        // return $cajas;
        $denominacion_dolar = Denominacion::where('moneda', 'Dolar')->orderBy('id', 'desc')->get();
        $denominacion_peso = Denominacion::where('moneda', 'Pesos')->orderBy('id', 'desc')->get();
        $denominacion_bolivar = Denominacion::where('moneda', 'Bolivares')->orderBy('id', 'desc')->get();
        $cajaSessionid =  Sessioncaja::where('estado', 'Abierta')->orderBy('id', 'desc')->first();
        // dd($cajaSessionid);
        $Caja = Caja::where("estado","=",'Abierta')->where("sessioncaja_id","=", $cajaSessionid->id)->first();
        // dd($Caja);
        if (is_null($Caja)) {
            $mostrarNuvaVenta = 1;
        } else {
            $mostrarNuvaVenta = 0;
        }

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
    // return $cajas;
    return view('cajas.caja.index', compact('mostrarNuvaVenta','cajas', 'denominacion_dolar', 'denominacion_peso', 'denominacion_bolivar'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Crear caja';
        $caja =  Sessioncaja::where('estado', 'Abierta')->orderBy('id', 'desc')->first();
        // dd($caja);
        // $verificar_caja =  Caja::where('sessioncaja_id',$caja->id )->orderBy('id', 'desc')->first();
        $denominacion_dolar = Denominacion::where('moneda', 'Dolar')->orderBy('id', 'desc')->get();
        $denominacion_peso = Denominacion::where('moneda', 'Pesos')->orderBy('id', 'desc')->get();
        $denominacion_bolivar = Denominacion::where('moneda', 'Bolivares')->orderBy('id', 'desc')->get();

        return view('cajas.caja.create', compact('caja','title','denominacion_dolar','denominacion_peso','denominacion_bolivar'));
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
            DB::beginTransaction();
            $date              = Carbon::now('America/Caracas');
            $fecha             = $date->format('d-m-Y');
            $year              = $date->format('Y');
            $mes               = $date->format('m');
            $hora              = $date->format('h:i:s A');
            $idUsuario         = $request->get('idusuario');
            $session_id        = $request->get('session_id');
            $estatus_caja      = 'Apertura';

            $tasaVentaEfectivo = Tasa::where('nombre', 'efectivoVenta')->first();


                $Caja = new Caja;
                $Caja->codigo               = '';
                $Caja->fecha                = $fecha;
                $Caja->hora_cierre          = 'Sin cerrar';
                $Caja->hora                 = $hora;
                $Caja->mes                  = $mes;
                $Caja->year                 = $year;
                $Caja->monto_dolar          = $request->get('total_dolar');
                $Caja->monto_peso           = $request->get('total_peso');
                $Caja->monto_bolivar        = $request->get('total_bolivar');
                $Caja->monto_dolar_cierre   = 0.00;
                $Caja->monto_peso_cierre    = 0.00;
                $Caja->monto_bolivar_cierre = 0.00;
                $Caja->estado               = 'Abierta';
                $Caja->caja                 = $request->get('caja');
                $Caja->tasaActualVenta      = $tasaVentaEfectivo->tasa;
                $Caja->margenActualVenta    = $tasaVentaEfectivo->porcentaje_ganancia;
                $Caja->user_id              =  $idUsuario;
                $Caja->sucursal_id          = 1;
                $Caja->sessioncaja_id       = $request->get('session_id');
                $Caja->save();

                $codigo = Sessioncaja::numCodigo('CA', $idUsuario, $Caja->id);

                $CajaUp = Caja::find($Caja->id)->where("estado", 'Abierta')
                ->update(["codigo" => $codigo]);






//////////////////////////////////////////////////////////////////////////////////////////////////// BsubTotald
            $bcantidadR = $request->get('bcantidad');
            $BsubTotaldR = $request->get('BsubTotald');
            $bvalorR = $request->get('bvalor');
            $btipoR = $request->get('btipo');
            $bdenominacionR = $request->get('bdenominacion');

            $BsubTotaldR = array_filter($BsubTotaldR);


            if ($BsubTotaldR) {
                foreach($BsubTotaldR as $key => $val) {

                    $bcantidad[]=$bcantidadR[$key];
                    $BsubTotald[]=$BsubTotaldR[$key];
                    $bvalor[]=$bvalorR[$key];
                    $btipo[]=$btipoR[$key];
                    $bdenominacion[]=$bdenominacionR[$key];
                }

                // dd($bcantidadR, $BsubTotaldR,$bvalorR,$btipoR,$bdenominacionR);
                //creamos un contador
                $cont = 0;

                //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
                while ($cont < count($BsubTotald)) {

                    $detalleBolso = new Contabilidad();
                    $detalleBolso->denominacion = $bdenominacion[$cont];//este idingreso se autogenera cuando se crea el objeto en la parte superior (*)
                    $detalleBolso->valor = $bvalor[$cont];
                    $detalleBolso->cantidad = $bcantidad[$cont];
                    $detalleBolso->subtotal = $BsubTotald[$cont];
                    $detalleBolso->tipo = $btipo[$cont];
                    $detalleBolso->modo = $estatus_caja;
                    $detalleBolso->caja_id = $Caja->id;
                    $detalleBolso->save();

                    $cont = $cont+1;
                }
            }



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $pcantidadR = $request->get('pcantidad');
            $PsubTotaldR = $request->get('PsubTotald');
            $pvalorR = $request->get('pvalor');
            $ptipoR = $request->get('ptipo');
            $pdenominacionR = $request->get('pdenominacion');

            $PsubTotaldR = array_filter($PsubTotaldR);

            if ($PsubTotaldR) {
                foreach($PsubTotaldR as $key => $val) {

                    $pcantidad[]=$pcantidadR[$key];
                    $PsubTotald[]=$PsubTotaldR[$key];
                    $pvalor[]=$pvalorR[$key];
                    $ptipo[]=$ptipoR[$key];
                    $pdenominacion[]=$pdenominacionR[$key];
                }
                // dd($divisa, $MontoDivisa,$TasaTike,$MontoDolar,$Veltos);
                //creamos un contador
                $cont = 0;

                //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
                while ($cont < count($PsubTotald)) {

                    $detalleBolsoP = new Contabilidad();
                    $detalleBolsoP->denominacion = $pdenominacion[$cont];//este idingreso se autogenera cuando se crea el objeto en la parte superior (*)
                    $detalleBolsoP->valor = $pvalor[$cont];
                    $detalleBolsoP->cantidad = $pcantidad[$cont];
                    $detalleBolsoP->subtotal = $PsubTotald[$cont];
                    $detalleBolsoP->tipo = $ptipo[$cont];
                    $detalleBolsoP->modo = $estatus_caja;
                    $detalleBolsoP->caja_id = $Caja->id;
                    $detalleBolsoP->save();

                    $cont = $cont+1;
                }
            }



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $dcantidadR = $request->get('dcantidad');
            $DsubTotaldR = $request->get('DsubTotald');
            $dvalorR = $request->get('dvalor');
            $dtipoR = $request->get('dtipo');
            $ddenominacionR = $request->get('ddenominacion');

            $DsubTotaldR = array_filter($DsubTotaldR);

            if ($DsubTotaldR) {
                foreach($DsubTotaldR as $key => $val) {

                    $dcantidad[]=$dcantidadR[$key];
                    $DsubTotald[]=$DsubTotaldR[$key];
                    $dvalor[]=$dvalorR[$key];
                    $dtipo[]=$dtipoR[$key];
                    $ddenominacion[]=$ddenominacionR[$key];
                }
                // dd($divisa, $MontoDivisa,$TasaTike,$MontoDolar,$Veltos);
                //creamos un contador
                $cont = 0;

                //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
                while ($cont < count($DsubTotald)) {

                    $detalleBolsoD = new Contabilidad();
                    $detalleBolsoD->denominacion = $ddenominacion[$cont];//este idingreso se autogenera cuando se crea el objeto en la parte superior (*)
                    $detalleBolsoD->valor = $dvalor[$cont];
                    $detalleBolsoD->cantidad = $dcantidad[$cont];
                    $detalleBolsoD->subtotal = $DsubTotald[$cont];
                    $detalleBolsoD->tipo = $dtipo[$cont];
                    $detalleBolsoD->modo = $estatus_caja;
                    $detalleBolsoD->caja_id = $Caja->id;
                    $detalleBolsoD->save();

                    $cont = $cont+1;
                }
            }

            $UserName = $request->user();

            //hola


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                // TODO Ahora que lla guardamos los datos en la tabla historial
                //procedemos a borrar los registros donde el estado no sea pendiente
                // y a su vez vamos a cambiar el id colocandole el nuevo ide  de la caja
                //recien abierta.

                // TODO Creamos consulta para buscar registros y filtrarlos
                $filtrarReg = Excedentes_Recibidos_Caja_Actual::get();
                // return $filtrarReg;

                if($filtrarReg){
                    foreach ($filtrarReg as $fReg) {
                        if($fReg->Estado == 'Pendiente'){
                            $upReg = Excedentes_Recibidos_Caja_Actual::findOrFail($fReg->id);
                            $upReg->caja_id = $Caja->id;
                            $upReg->update();
                        }else{
                            Excedentes_Recibidos_Caja_Actual::destroy($fReg->id);
                        }
                    }
                }


                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            DB::commit();

        }catch(\Exception $e)
        {

            DB::rollback();
            dd($e);
        }
        $caja =  Sessioncaja::where('estado', 'Abierta')->orderBy('id', 'desc')->first();
        // dd($caja);
        $verificar_caja =  Caja::where('sessioncaja_id',$caja->id )->orderBy('id', 'desc')->first();
        $mensaje = $UserName->name.'  La Caja fué Abierta exitosamente. ¡Que tengas una hermosa jornada!';
        // $mostrar = self::show($verificar_caja->id,$mensaje);



        // return $mostrar;
        return redirect()
        ->route('caja.show',$verificar_caja->id)
        ->with('status_success',  $UserName->name.'  La Caja fué Abierta exitosamente. ¡Que tengas una exitosa jornada!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $mensaje = '')
    {

        // return $id;
        $title = 'Resumen de Caja';
        $caja = Caja::find($id);
        $denominacion_dolar = Denominacion::where('moneda', 'Dolar')->orderBy('id', 'desc')->get();
        $denominacion_peso = Denominacion::where('moneda', 'Pesos')->orderBy('id', 'desc')->get();
        $denominacion_bolivar = Denominacion::where('moneda', 'Bolivares')->orderBy('id', 'desc')->get();
        $tasaDolar = DB::table('tasas')->where('nombre', '=', 'Dolar')->first();
        $tasaPeso = DB::table('tasas')->where('nombre', '=', 'Peso')->first();
        $tasaTransferenciaPunto = DB::table('tasas')->where('nombre', '=', 'Transferencia_Punto')->first();
        $tasaMixto = DB::table('tasas')->where('nombre', '=', 'Mixto')->first();
        $tasaEfectivo = DB::table('tasas')->where('nombre', '=', 'Efectivo')->first();


        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $cajas = Caja::find($caja->id);
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
        $cajas->horas_extras;
        $cajas->pagos_vueltos_extra;
        $cajas->pago_extras;
        // return $cajas->pago_extras;


        // $cajas->excedente_actual;


//         // $creditos = Credito::where('caja_id',$caja->id)->get();

//         $cajas->creditos = $creditos;


// return $cajas;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//                    GESTIONAR PAGOS DE CREDITOS Y CREDITOS PAGADOS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// foreach ($cajas->pago_creditos as $pagoC ) {

//     if ($pagoC->Divisa == 'Dolar') {
//         $cajas->SumaTotalDolarCred = $cajas->SumaTotalDolarCred + ($pagoC->MontoDivisa - $pagoC->Vueltos * -1);
//     }elseif ($pagoC->Divisa == 'Peso') {
//         $cajas->SumaTotalPesoCred = $cajas->SumaTotalPesoCred + ($pagoC->MontoDivisa - $pagoC->Vueltos * -1);
//     }elseif ($pagoC->Divisa == 'Bolivar') {
//         $cajas->SumaTotalBolivarCred = $cajas->SumaTotalBolivarCred + ($pagoC->MontoDivisa - $pagoC->Vueltos * -1);
//     }elseif ($pagoC->Divisa == 'Punto') {
//         $cajas->SumaTotalPuntoCred = $cajas->SumaTotalPuntoCred + ($pagoC->MontoDivisa - $pagoC->Vueltos * -1);
//     }elseif ($pagoC->Divisa == 'Transferencia') {
//         $cajas->SumaTotalTransferenciaCred = $cajas->SumaTotalTransferenciaCred + ($pagoC->MontoDivisa - $pagoC->Vueltos * -1);
//     }

// }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // TODO Traer los vueltos pendientes de las cajas ateriores


    // $vueltosPendientesCajaAnterior = Excedentes_Pendientes_Caja_Anterior::where('Registrado_por','Sistema')->where('caja_id', '<>',$cajas->id)->latest('id')->first();
    // // return $vueltosPendientesCajaAnterior;

    //     if ($vueltosPendientesCajaAnterior) {
    //         $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisa = $vueltosPendientesCajaAnterior->Dolar;
    //         $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolar = $vueltosPendientesCajaAnterior->Dolar_To_Dolar;
    //         $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisa = $vueltosPendientesCajaAnterior->Peso;
    //         $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDolar = $vueltosPendientesCajaAnterior->Peso_To_Dolar;
    //         $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisa = $vueltosPendientesCajaAnterior->Punto;
    //         $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDolar = $vueltosPendientesCajaAnterior->Punto_To_Dolar;
    //         $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisa = $vueltosPendientesCajaAnterior->Transferencia;
    //         $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDolar = $vueltosPendientesCajaAnterior->Trans_To_Dolar;
    //         $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisa = $vueltosPendientesCajaAnterior->Bolivar;
    //         $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDolar = $vueltosPendientesCajaAnterior->Bolivar_To_Dolar;
    //         $cajas->VueltosCajaAnteriorPendientesOperador = $vueltosPendientesCajaAnterior->Operador;
    //         $cajas->VueltosCajaAnteriorPendientesOperadorId = $vueltosPendientesCajaAnterior->Operador_id;
    //     }





///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //TODO Traer los vueltos pendientes de las cajas ateriores
// return $cajas->id;
        if ($cajas->estado == 'Cerrada') {
            $vueltosPendientesCajaAnterior = Historial_Vueltos_Pendiente::where('caja_id', $cajas->id)->get();
            // return $cajas->id;
        }
        if ($cajas->estado == 'Abierta'){
            $vueltosPendientesCajaAnterior = Historial_Vueltos_Pendiente::where('caja_id', $cajas->id - 1)->get();
            // return $vueltosPendientesCajaAnterior;
        }



        foreach ($vueltosPendientesCajaAnterior as $TotalVuetosPendientesCajaAnterior) {
            if ($TotalVuetosPendientesCajaAnterior->Estado == 'Pendiente') {
                if ($TotalVuetosPendientesCajaAnterior->Divisa == 'Dolar') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisa = $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisa  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolar = $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolar  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }elseif ($TotalVuetosPendientesCajaAnterior->Divisa == 'Peso') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisa = $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisa  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDolar = $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDolar  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }elseif ($TotalVuetosPendientesCajaAnterior->Divisa == 'Bolivar') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisa = $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisa  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDolar = $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDolar  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }elseif ($TotalVuetosPendientesCajaAnterior->Divisa == 'Punto') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisa = $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisa  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDolar = $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDolar  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }elseif ($TotalVuetosPendientesCajaAnterior->Divisa == 'Transferencia') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisa = $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisa  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDolar = $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDolar  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }
            }
        }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // TODO optenemos los datos de la tabla excedente actual

    if ($cajas->estado == 'Cerrada') {
        $valor = Historial_Vueltos_Pendiente::where('caja_id', $cajas->id)->get();
    }
    if ($cajas->estado == 'Abierta'){

        $valor = $cajas->excedente_actual;
        // $vueltosPendientesCajaAnterior = Historial_Vueltos_Pendiente::where('caja_id', '<>', $cajas->id)->get();
    }

    foreach ($valor as $excedenteActual) {

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

            $cajas->TotalSumaVueltosPendienteDolarToDolar = $cajas->TotalSumaVueltosPendienteDolarToDolar + $excedenteActual->MontoDolar;

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

            $cajas->TotalSumaVueltosDevueltosDolarToDolar = $cajas->TotalSumaVueltosDevueltosDolarToDolar + $excedenteActual->MontoDolar;

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

            $cajas->TotalSumaVueltosPagarOficinaDolarToDolar = $cajas->TotalSumaVueltosPagarOficinaDolarToDolar + $excedenteActual->MontoDolar;

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
                $cajas->TotalSumaVueltosExcedenteNuevoServicioDolarToDolar = $cajas->TotalSumaVueltosExcedenteNuevoServicioDolarToDolar + $excedenteActual->MontoDolar;
            }
            if($excedenteActual->Tipo == 'Consumo'){
                $cajas->TotalSumaVueltosExcedenteNuevoConsumoDolarToDolar = $cajas->TotalSumaVueltosExcedenteNuevoConsumoDolarToDolar + $excedenteActual->MontoDolar;
            }

            if($excedenteActual->Tipo == 'Otros'){
                $cajas->TotalSumaVueltosExcedenteNuevoOtrosDolarToDolar = $cajas->TotalSumaVueltosExcedenteNuevoOtrosDolarToDolar + $excedenteActual->MontoDolar;
            }
            $cajas->TotalSumaVueltosExcedenteNuevoDolarToDolar = $cajas->TotalSumaVueltosExcedenteNuevoDolarToDolar + $excedenteActual->MontoDolar;
        }
    }
    // return $cajas->SumaVueltosDevueltosPesoDivisa;
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

foreach ($cajas->creditos_pagados as $credPagados ) {

    if ($credPagados->user_id == $caja->user->id){

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

//                    GESTIONAR DETALLE CREDITOS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$detalle_creditos = Detalle_credito::get();

// return $detalle_creditos;

foreach ($detalle_creditos as $detalleCredito ) {

    if ($detalleCredito->estado_credito == 'Vigente') {

        $cajas->SumaTotalCantidadCreditosVigentes = $cajas->SumaTotalCantidadCreditosVigentes + 1;
        $cajas->SumaTotalMontoCreditosVigentes = $cajas->SumaTotalMontoCreditosVigentes + $detalleCredito->monto;


    }

    if ($detalleCredito->estado_credito == 'Vencido') {

        $cajas->SumaTotalCantidadCreditosVencidos = $cajas->SumaTotalCantidadCreditosVencidos + 1;
        $cajas->SumaTotalMontoCreditosVencidos = $cajas->SumaTotalMontoCreditosVencidos + $detalleCredito->monto;


    }

    // if ($detalleCredito->estado_credito == 'Pagado') {

    //     $cajas->SumaTotalCantidadCreditosPagado = $cajas->SumaTotalCantidadCreditosPagado + 1;
    //     $cajas->SumaTotalMontoCreditosPagado = $cajas->SumaTotalMontoCreditosPagado + $detalleCredito->monto;


    // }

}


// return $cajas->SumaTotalCantidadCreditosVigentes;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // foreach ($cajas->pago_ventas as $pago ) {

        //     if ($pago->Divisa == 'Dolar') {
        //         $cajas->SumaTotalDolar = $cajas->SumaTotalDolar + ($pago->MontoDivisa - $pago->Vueltos * -1) ;
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
            // if ($vent->estado == 'Aceptada') {
            // $cajas->SumaTotalVentas = $cajas->SumaTotalVentas + $vent->total_venta;
            // $cajas->SumaTotalCantidadVentas = $cajas->SumaTotalCantidadVentas + 1;
            // }
            if ($vent->estado == 'Aceptada'  && $vent->status == 'Pagado') {

                    $cajas->SumaTotalVentas = $cajas->SumaTotalVentas + $vent->total_venta;
                    $cajas->SumaTotalCostoVentas = $cajas->SumaTotalCostoVentas + $vent->precio_costo;
                    $cajas->SumaTotalMargenVentas = $cajas->SumaTotalMargenVentas + $vent->margen_ganancia;
                    $cajas->SumaTotalUtilidadVentas = $cajas->SumaTotalUtilidadVentas + $vent->ganancia_neta;
                    // $cajas->SumaTotalCantidadVentas = $cajas->SumaTotalCantidadVentas + 1;

            }
            if ($vent->estado == 'Aceptada') {


                $cajas->SumaTotalCantidadVentas = $cajas->SumaTotalCantidadVentas + 1;

        }

            if ($vent->modo_pago == 'Contado') {
                $cajas->SumaTotalCantidadVentasContado = $cajas->SumaTotalCantidadVentasContado + 1;
            }

            if ($vent->modo_pago == 'Crédito') {
                $cajas->SumaTotalCantidadVentasCredito = $cajas->SumaTotalCantidadVentasCredito + 1;
                $cajas->SumaTotalVentasCredito = $cajas->SumaTotalVentasCredito + $vent->total_venta;
            }

            if ($vent->modo_pago == 'Cortesía') {
                $cajas->SumaTotalCantidadVentasCortesia = $cajas->SumaTotalCantidadVentasCortesia + 1;
            }
        }
        $nombre = [];
        foreach ($cajas->articulo_ventas as $art_vent ) {
            $nombre[] = Articulo::find($art_vent->articulo_id);
            $cajas->nombreArticulos = $nombre;
            if ($art_vent->venta->estado == 'Aceptada') {
            $cajas->SumaArticulosVendidos = $cajas->SumaArticulosVendidos + $art_vent->cantidad;
            }
            if($art_vent->estado_pago == 'Falta pagar'){
                // $cajas->SumaTotalVentas = $cajas->SumaTotalVentas - ($art_vent->cantidad * $art_vent->precio_venta_unidad);
                $cajas->SumaTotalVentasPorCobrar = $cajas->SumaTotalVentasPorCobrar + ($art_vent->cantidad * $art_vent->precio_venta_unidad);
            }

        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        foreach ($cajas->pagos_vueltos_extra as $pagoV ) {

            if ($pagoV->Divisa == 'Dolar') {

                $cajas->SumaTotalDolarVueltos = ($cajas->SumaTotalDolarVueltos + $pagoV->MontoDivisa);

            }elseif ($pagoV->Divisa == 'Peso') {
                $cajas->SumaTotalPesoVueltos = $cajas->SumaTotalPesoVueltos + $pagoV->MontoDivisa;
            }elseif ($pagoV->Divisa == 'Bolivar') {
                $cajas->SumaTotalBolivarVueltos = $cajas->SumaTotalBolivarVueltos + $pagoV->MontoDivisa;
            }elseif ($pagoV->Divisa == 'Punto') {
                $cajas->SumaTotalPuntoVueltos = $cajas->SumaTotalPuntoVueltos + $pagoV->MontoDivisa;
            }elseif ($pagoV->Divisa == 'Transferencia') {
                $cajas->SumaTotalTransferenciaVueltos = $cajas->SumaTotalTransferenciaVueltos + $pagoV->MontoDivisa;
            }

            $cajas->totalSumaTotalDolarToDolarVueltos = $cajas->totalSumaTotalDolarToDolarVueltos + $pagoV->MontoDivisa;

        }
        // return $cajas->SumaTotalDolarVueltos;
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // $tasaDolar
        // $tasaPeso
        // $tasaTransferenciaPunto
        // $tasaMixto
        // $tasaEfectivo
        // return $cajas->pago_servicios;

        // TODO pagos ventas (consumo)


        foreach ($cajas->pago_ventas as $pagoConsumo ) {

            // return $pagoConsumo->venta_id;
            $validarPagosConsumo = Venta::where('id',$pagoConsumo->venta_id)->first();
            // return $validarPagosConsumo;
            if ($validarPagosConsumo) {
                if ($pagoConsumo->Divisa == 'Dolar') {
                    if($pagoConsumo->Vueltos > 0){
                        $cajas->SumaTotalDolar = $cajas->SumaTotalDolar + ($pagoConsumo->MontoDivisa - $pagoConsumo->Vueltos * -1) - $validarPagosConsumo->excedente_nuevo;
                    }else{
                        $cajas->SumaTotalDolarConsuDflotante = $cajas->SumaTotalDolarConsuDflotante + ($pagoConsumo->Vueltos * -1);
                        $cajas->SumaTotalDolar = $cajas->SumaTotalDolar + ($pagoConsumo->MontoDivisa - $pagoConsumo->Vueltos * -1) - $validarPagosConsumo->excedente_nuevo;
                    }
                }elseif ($pagoConsumo->Divisa == 'Peso') {
                    if($pagoConsumo->Vueltos > 0){
                        $cajas->SumaTotalPeso = $cajas->SumaTotalPeso + ($pagoConsumo->MontoDivisa - $pagoConsumo->Vueltos * -1) - ($validarPagosConsumo->excedente_nuevo * $tasaPeso->tasa);
                    }else{
                        $cajas->SumaTotalPesoConsuDflotante = $cajas->SumaTotalPesoConsuDflotante + ( $pagoConsumo->Vueltos * -1);
                        $cajas->SumaTotalPeso = $cajas->SumaTotalPeso + ($pagoConsumo->MontoDivisa - $pagoConsumo->Vueltos * -1) - ($validarPagosConsumo->excedente_nuevo * $tasaPeso->tasa);
                    }
                }elseif ($pagoConsumo->Divisa == 'Bolivar') {
                    if($pagoConsumo->Vueltos > 0){
                        $cajas->SumaTotalBolivar = $cajas->SumaTotalBolivar + ($pagoConsumo->MontoDivisa - $pagoConsumo->Vueltos * -1) - ($validarPagosConsumo->excedente_nuevo * $tasaPeso->tasa);
                    }else{
                        $cajas->SumaTotalBolivarConsuDflotante = $cajas->SumaTotalBolivarConsuDflotante + ($pagoConsumo->Vueltos * -1) * $tasaEfectivo->tasa;
                        $cajas->SumaTotalBolivar = $cajas->SumaTotalBolivar + ($pagoConsumo->MontoDivisa - $pagoConsumo->Vueltos * -1) - ($validarPagosConsumo->excedente_nuevo * $tasaPeso->tasa);
                    }
                }elseif ($pagoConsumo->Divisa == 'Punto') {
                    if($pagoConsumo->Vueltos > 0){
                        $cajas->SumaTotalPunto = $cajas->SumaTotalPunto + ($pagoConsumo->MontoDivisa - $pagoConsumo->Vueltos * -1) - ($validarPagosConsumo->excedente_nuevo * $tasaPeso->tasa);
                    }else{
                        $cajas->SumaTotalPuntoConsuDflotante = $cajas->SumaTotalPuntoConsuDflotante + ($pagoConsumo->Vueltos * -1) * $tasaTransferenciaPunto->tasa;
                        $cajas->SumaTotalPunto = $cajas->SumaTotalPunto + ($pagoConsumo->MontoDivisa - $pagoConsumo->Vueltos * -1) - ($validarPagosConsumo->excedente_nuevo * $tasaPeso->tasa);
                    }
                }elseif ($pagoConsumo->Divisa == 'Transferencia') {
                    if($pagoConsumo->Vueltos > 0){
                        $cajas->SumaTotalTransferencia = $cajas->SumaTotalTransferencia + ($pagoConsumo->MontoDivisa - $pagoConsumo->Vueltos * -1) - ($validarPagosConsumo->excedente_nuevo * $tasaPeso->tasa);
                    }else{
                        $cajas->SumaTotalTransferenciaConsuDflotante = $cajas->SumaTotalTransferenciaConsuDflotante + ($pagoConsumo->Vueltos * -1) * $tasaTransferenciaPunto->tasa;
                        $cajas->SumaTotalTransferencia = $cajas->SumaTotalTransferencia + ($pagoConsumo->MontoDivisa - $pagoConsumo->Vueltos * -1) - ($validarPagosConsumo->excedente_nuevo * $tasaPeso->tasa);
                    }
                }

                $cajas->TotalSumaTotalConsuDflotante = $cajas->TotalSumaTotalConsuDflotante + ($pagoConsumo->Vueltos * -1);
            }



        }
        // return $cajas->SumaTotalDolarConsu;
        // TODO pagos servicios

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

                $cajas->TotalSumaTotalServDflotante = $cajas->TotalSumaTotalServDflotante + ($pagoS->Vueltos * -1);
            }



        }
// return $cajas->SumaTotalPesoServ;
        // TODO captuaramos en variables los montos pagados en el proseso de pagos extras de la tabla horas extras


        foreach ($cajas->pago_extras as $pagoVeX ) {
            // return $cajas->pago_extras;
            $validarPagosHorasExtras = Horas_extra::where('id',$pagoVeX->horas_extra_id)->first();
            // return $validarPagosHorasExtras;
            if ($validarPagosHorasExtras) {

                if ($pagoVeX->Divisa == 'Dolar') {
                    if($pagoVeX->Vueltos > 0){

                        $cajas->SumaTotalDolarExtra = $cajas->SumaTotalDolarExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1) - $validarPagosHorasExtras->excedente_nuevo;
                    // return $cajas->SumaTotalDolarExtra;
                    }else{
                        $cajas->SumaTotalDolarExtraDflotante = $cajas->SumaTotalDolarExtraDflotante + ($pagoVeX->Vueltos * -1);
                        $cajas->SumaTotalDolarExtra = $cajas->SumaTotalDolarExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1) - $validarPagosHorasExtras->excedente_nuevo;
                    }
                }elseif ($pagoVeX->Divisa == 'Peso') {
                    if($pagoVeX->Vueltos > 0){
                        $cajas->SumaTotalPesoExtra = $cajas->SumaTotalPesoExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1) - ($validarPagosHorasExtras->excedente_nuevo * $tasaPeso->tasa);
                    }else{
                        $cajas->SumaTotalPesoExtraDflotante = $cajas->SumaTotalPesoExtraDflotante + ( $pagoVeX->Vueltos * -1);
                        $cajas->SumaTotalPesoExtra = $cajas->SumaTotalPesoExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1) - ($validarPagosHorasExtras->excedente_nuevo * $tasaPeso->tasa);
                    }
                }elseif ($pagoVeX->Divisa == 'Bolivar') {
                    if($pagoVeX->Vueltos > 0){
                        $cajas->SumaTotalBolivarExtra = $cajas->SumaTotalBolivarExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1) - ($validarPagosHorasExtras->excedente_nuevo * $tasaEfectivo->tasa);
                    }else{
                        $cajas->SumaTotalBolivarExtraDflotante = $cajas->SumaTotalBolivarExtraDflotante + ($pagoVeX->Vueltos * -1) * $tasaEfectivo->tasa;
                        $cajas->SumaTotalBolivarExtra = $cajas->SumaTotalBolivarExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1) - ($validarPagosHorasExtras->excedente_nuevo * $tasaEfectivo->tasa);
                    }
                }elseif ($pagoVeX->Divisa == 'Punto') {
                    if($pagoVeX->Vueltos > 0){
                        $cajas->SumaTotalPuntoExtra = $cajas->SumaTotalPuntoExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1) - ($validarPagosHorasExtras->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                    }else{
                        $cajas->SumaTotalPuntoExtraDflotante = $cajas->SumaTotalPuntoExtraDflotante + ($pagoVeX->Vueltos * -1) * $tasaTransferenciaPunto->tasa;
                        $cajas->SumaTotalPuntoExtra = $cajas->SumaTotalPuntoExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1) - ($validarPagosHorasExtras->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                    }
                }elseif ($pagoVeX->Divisa == 'Transferencia') {
                    if($pagoVeX->Vueltos > 0){
                        $cajas->SumaTotalTransferenciaExtra = $cajas->SumaTotalTransferenciaExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1) - ($validarPagosHorasExtras->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                    }else{
                        $cajas->SumaTotalTransferenciaExtraDflotante = $cajas->SumaTotalTransferenciaExtraDflotante + ($pagoVeX->Vueltos * -1) * $tasaTransferenciaPunto->tasa;
                        $cajas->SumaTotalTransferenciaExtra = $cajas->SumaTotalTransferenciaExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1) - ($validarPagosHorasExtras->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                    }
                }
                $cajas->SumaTotalExtra = $cajas->SumaTotalExtra + ($validarPagosHorasExtras->total_horas_extras_otros_montos - $validarPagosHorasExtras->pago_con_excedente);
                $cajas->TotalSumaTotalExtraDflotante = $cajas->TotalSumaTotalExtraDflotante + ($pagoVeX->Vueltos * -1);
            }



        }

        foreach ($cajas->horas_extras as $creditosHorasExtras) {
            if($creditosHorasExtras->modo_pago == 'Credito' && $creditosHorasExtras->status == 'Falta pagar'){
                $cajas->SumaTotalHorasExtrasPorPagar = $cajas->SumaTotalHorasExtrasPorPagar + $creditosHorasExtras->total_horas_extras_otros_montos;
                $cajas->SumaTotalCantidadHorasExtrasPorPagar = $cajas->SumaTotalCantidadHorasExtrasPorPagar + 1;
            }

            if($creditosHorasExtras->modo_pago == 'Cortesia' && $creditosHorasExtras->status == 'Exonerado'){
                $cajas->SumaTotalHorasExtrasCortesia = $cajas->SumaTotalHorasExtrasCortesia + $creditosHorasExtras->total_horas_extras_otros_montos;
                $cajas->SumaTotalCantidadHorasExtrasCortesia = $cajas->SumaTotalCantidadHorasExtrasCortesia + 1;
            }
        }
        // return $cajas->SumaTotalHorasExtrasCortesia;
        // return $cajas->SumaTotalExtra;
// return $cajas;
        foreach ($cajas->servicios as $serv ) {
            if ($serv->estado == 'Aceptada') {
                if($serv->status == 'Pagado'){
                    ////////////////////////////////////////////////////////////////////////////////////////////////
                    //saber cuantos servicios se ha pagado con dolar,peso,punto,mixto,transferencia sin excedente

                    // $validarPagosServicios = Pago_Servicio::where('servicio_id',$serv->id)->get();
                    // if (count($validarPagosServicios)) {
                    //     foreach ($validarPagosServicios as $pagoServicio ) {
                    //         if ($pagoServicio->Divisa == 'Dolar') {
                    //             if($pagoServicio->Vueltos > 0){
                    //                 $cajas->SumaTotalDolarServ = $cajas->SumaTotalDolarServ + ($pagoServicio->MontoDivisa - $pagoServicio->Vueltos * -1) - $serv->excedente_nuevo;
                    //             }else{
                    //                 $cajas->SumaTotalDolarServ = $cajas->SumaTotalDolarServ + ($pagoServicio->MontoDivisa - $pagoServicio->Vueltos) - $serv->excedente_nuevo;
                    //             }
                    //         }elseif ($pagoServicio->Divisa == 'Peso') {
                    //             $cajas->SumaTotalPesoServ = $cajas->SumaTotalPesoServ + ($pagoServicio->MontoDivisa - $pagoServicio->Vueltos);
                    //         }elseif ($pagoServicio->Divisa == 'Bolivar') {
                    //             $cajas->SumaTotalBolivarServ = $cajas->SumaTotalBolivarServ + ($pagoServicio->MontoDivisa - $pagoServicio->Vueltos);
                    //         }elseif ($pagoServicio->Divisa == 'Punto') {
                    //             $cajas->SumaTotalPuntoServ = $cajas->SumaTotalPuntoServ + ($pagoServicio->MontoDivisa - $pagoServicio->Vueltos);
                    //         }elseif ($pagoServicio->Divisa == 'Transferencia') {
                    //             $cajas->SumaTotalTransferenciaServ = $cajas->SumaTotalTransferenciaServ + ($pagoServicio->MontoDivisa - $pagoServicio->Vueltos);
                    //         }
                    //     }
                    // }

                    ////////////////////////////////////////////////////////////////////////////////////////////////



                    // TODO Manejo de excedentes nuevos: divide excedentes nuevos cuando el servicio se cierra y excedentes nuevos cuando el servicio esta activo.


                    $cajas->SumaTotalServicios = $cajas->SumaTotalServicios + ($serv->total_venta - $serv->pago_con_excedente);
                    $cajas->SumaTotalCantidadServicios = $cajas->SumaTotalCantidadServicios + 1;
                    //////////////////////////////////////////////////////////////////////////////////////////
                    //contavilizamos cuanto hay en excedente nuevo total
                    $cajas->SumaTotalServiciosExcedenteNuevo = $cajas->SumaTotalServiciosExcedenteNuevo + $serv->excedente_nuevo;

                    //Aquí dividimos excedente nuevo cuando el servicio esta finalizado y cuando esta Iniciado
                    //para poder deducir si el dinero se reporta cuando se sierre la caja o si se deja para la otra caja

                    if ($serv->status_servicio == 'Finalizado') {
                        $cajas->SumaTotalServiciosExcedenteNuevoFinalizado = $cajas->SumaTotalServiciosExcedenteNuevoFinalizado + $serv->excedente_nuevo;
                    }

                    if ($serv->status_servicio == 'Iniciado') {
                        $cajas->SumaTotalServiciosExcedenteNuevoIniciado = $cajas->SumaTotalServiciosExcedenteNuevoIniciado + $serv->excedente_nuevo;
                    }
                    //////////////////////////////////////////////////////////////////////////////////////////
                    //contavilizamos cuanto hay pagado con excedente
                    $cajas->SumaTotalServiciosPagadosConExcedente = $cajas->SumaTotalServiciosPagadosConExcedente + $serv->pago_con_excedente;

                    if ($serv->tipo_pago == 'Dolar') {
                        if ($serv->status_servicio == 'Finalizado') {
                            $cajas->SumaTotalServiciosExcedenteNuevoDolarFinalizado = $cajas->SumaTotalServiciosExcedenteNuevoDolarFinalizado + $serv->excedente_nuevo;
                        }

                        if ($serv->status_servicio == 'Iniciado') {
                            $cajas->SumaTotalServiciosExcedenteNuevoDolarIniciado = $cajas->SumaTotalServiciosExcedenteNuevoDolarIniciado + $serv->excedente_nuevo;
                        }

                        $cajas->SumaTotalServiciosExcedenteNuevoDolar = $cajas->SumaTotalServiciosExcedenteNuevoDolar + $serv->excedente_nuevo;
                    }

                    if ($serv->tipo_pago == 'Peso') {
                        if ($serv->status_servicio == 'Finalizado') {
                            $cajas->SumaTotalServiciosExcedenteNuevoPesoFinalizado = $cajas->SumaTotalServiciosExcedenteNuevoPesoFinalizado + ($serv->excedente_nuevo * $tasaPeso->tasa);
                        }

                        if ($serv->status_servicio == 'Iniciado') {
                            $cajas->SumaTotalServiciosExcedenteNuevoPesoIniciado = $cajas->SumaTotalServiciosExcedenteNuevoPesoIniciado + ($serv->excedente_nuevo * $tasaPeso->tasa);
                        }

                        $cajas->SumaTotalServiciosExcedenteNuevoPeso = $cajas->SumaTotalServiciosExcedenteNuevoPeso + ($serv->excedente_nuevo * $tasaPeso->tasa);
                    }

                    if ($serv->tipo_pago == 'Bolivar') {
                        if ($serv->status_servicio == 'Finalizado') {
                            $cajas->SumaTotalServiciosExcedenteNuevoBolivarFinalizado = $cajas->SumaTotalServiciosExcedenteNuevoBolivarFinalizado + ($serv->excedente_nuevo * $tasaEfectivo->tasa);
                        }

                        if ($serv->status_servicio == 'Iniciado') {
                            $cajas->SumaTotalServiciosExcedenteNuevoBolivarIniciado = $cajas->SumaTotalServiciosExcedenteNuevoBolivarIniciado + ($serv->excedente_nuevo * $tasaEfectivo->tasa);
                        }

                        $cajas->SumaTotalServiciosExcedenteNuevoBolivar = $cajas->SumaTotalServiciosExcedenteNuevoBolivar + ($serv->excedente_nuevo * $tasaEfectivo->tasa);
                    }

                    if ($serv->tipo_pago == 'Punto') {
                        if ($serv->status_servicio == 'Finalizado') {
                            $cajas->SumaTotalServiciosExcedenteNuevoPuntoFinalizado = $cajas->SumaTotalServiciosExcedenteNuevoPuntoFinalizado + ($serv->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                        }

                        if ($serv->status_servicio == 'Iniciado') {
                            $cajas->SumaTotalServiciosExcedenteNuevoPuntoIniciado = $cajas->SumaTotalServiciosExcedenteNuevoPuntoIniciado + ($serv->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                        }

                        $cajas->SumaTotalServiciosExcedenteNuevoPunto = $cajas->SumaTotalServiciosExcedenteNuevoPunto + ($serv->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                    }

                    if ($serv->tipo_pago == 'Transferencia') {
                        if ($serv->status_servicio == 'Finalizado') {
                            $cajas->SumaTotalServiciosExcedenteNuevoTransferenciaFinalizado = $cajas->SumaTotalServiciosExcedenteNuevoTransferenciaFinalizado + ($serv->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                        }

                        if ($serv->status_servicio == 'Iniciado') {
                            $cajas->SumaTotalServiciosExcedenteNuevoTransferenciaIniciado = $cajas->SumaTotalServiciosExcedenteNuevoTransferenciaIniciado + ($serv->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                        }

                        $cajas->SumaTotalServiciosExcedenteNuevoTransferencia = $cajas->SumaTotalServiciosExcedenteNuevoTransferencia + ($serv->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                    }



                }

                if($serv->status == 'Falta pagar'){
                    $cajas->SumaTotalServiciosPorPagar = $cajas->SumaTotalServiciosPorPagar + $serv->total_venta;
                    $cajas->SumaTotalCantidadServiciosPorPagar = $cajas->SumaTotalCantidadServiciosPorPagar + 1;
                }

                if($serv->modo_pago == 'Cortesía' && $serv->status == 'Exonerado'){
                    $cajas->SumaTotalServiciosCortesia = $cajas->SumaTotalServiciosCortesia + $serv->total_venta;
                    $cajas->SumaTotalCantidadServiciosCortesia = $cajas->SumaTotalCantidadServiciosCortesia + 1;
                }



            }
        }


        $tasaDolarHabitacion = Tasa::where('nombre','=','DolarHabitacion')->first();
        $tasaDolarHabitacion = Tasa::where('nombre','=','PesoHabitacion')->first();
        $tasaDolarHabitacion = Tasa::where('nombre','=','DolarHabitacion')->first();
        $tasaDolarHabitacion = Tasa::where('nombre','=','DolarHabitacion')->first();
        $tasaPesoHabitacion = Tasa::where('nombre','=','PesoHabitacion')->first();
        $UserName = Auth::user()->name;
        $UserId = Auth::user()->id;

// return $cajas;
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // $cajas = Caja::find($caja->id);
        //         $cajas->user;
        //         $cajas->ventas;

        //         $cajas->pago_ventas;
        //         $cajas->articulo_ventas;


        //         foreach ($cajas->pago_ventas as $pago ) {

        //              if ($pago->Divisa == 'Dolar') {
        //                 $cajas->SumaTotalDolar = $cajas->SumaTotalDolar + $pago->MontoDivisa;
        //              }elseif ($pago->Divisa == 'Peso') {
        //                 $cajas->SumaTotalPeso = $cajas->SumaTotalPeso + $pago->MontoDivisa;
        //              }elseif ($pago->Divisa == 'Bolivar') {
        //                 $cajas->SumaTotalBolivar = $cajas->SumaTotalBolivar + $pago->MontoDivisa;
        //              }elseif ($pago->Divisa == 'Punto') {
        //                 $cajas->SumaTotalPunto = $cajas->SumaTotalPunto + $pago->MontoDivisa;
        //              }elseif ($pago->Divisa == 'Transferencia') {
        //                 $cajas->SumaTotalTransferencia = $cajas->SumaTotalTransferencia + $pago->MontoDivisa;
        //              }

        //         }

        //         foreach ($cajas->ventas as $vent ) {
        //             if ($vent->estado == 'Aceptada') {
        //                 if ($vent->tipo_pago != 'No pagado') {
        //                     $cajas->SumaTotalVentas = $cajas->SumaTotalVentas + $vent->total_venta;
        //                     $cajas->SumaTotalCostoVentas = $cajas->SumaTotalCostoVentas + $vent->precio_costo;
        //                     $cajas->SumaTotalMargenVentas = $cajas->SumaTotalMargenVentas + $vent->margen_ganancia;
        //                     $cajas->SumaTotalUtilidadVentas = $cajas->SumaTotalUtilidadVentas + $vent->ganancia_neta;
        //                     $cajas->SumaTotalCantidadVentas = $cajas->SumaTotalCantidadVentas + 1;
        //                 }
        //             }
        //          }
        //          $nombre = [];
        //          foreach ($cajas->articulo_ventas as $art_vent ) {
        //             $nombre[] = Articulo::find($art_vent->articulo_id);
        //             $cajas->nombreArticulos = $nombre;
        //             if ($art_vent->venta->estado == 'Aceptada') {

        //                     $cajas->SumaArticulosVendidos = $cajas->SumaArticulosVendidos + $art_vent->cantidad;
        //                 }

        //         }
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                //  return $cajas;
                $verificarHorasExtras = Horas_extra::where('caja_id',$cajas->id)->get();
                // return $verificarHorasExtras;
        return view('cajas.caja.show', compact('verificarHorasExtras','tasaDolarHabitacion','tasaPesoHabitacion','tasaDolar', 'tasaPeso','tasaTransferenciaPunto','tasaMixto','tasaEfectivo','title','cajas', 'caja','denominacion_dolar', 'denominacion_peso' ,'denominacion_bolivar'))->with($mensaje);
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
        // return $request;
        try{
            DB::beginTransaction();
            $date   = Carbon::now('America/Caracas');
            $fecha  = $date->format('d-m-Y');
            $year   = $date->format('Y');
            $mes    = $date->format('m');
            $hora   = $date->format('h:i:s A');
            $idUsuario = $request->get('idusuario');
            $estatus_caja = 'Cierre';
            $session_id = $request->get('session_id');
            $caja_id = $request->get('caja_id');



            //TODO INSERTAR REGISTROS EN LA TABLA CAJA

                $Caja = Caja::findOrFail($caja_id);
                $Caja->hora_cierre              = $hora;
                $Caja->monto_dolar_cierre       = $request->get('total_dolar');
                $Caja->monto_peso_cierre        = $request->get('total_peso');
                $Caja->monto_bolivar_cierre     = $request->get('total_bolivar');
                $Caja->monto_punto_cierre       = $request->get('total_punto');
                $Caja->monto_trans_cierre       = $request->get('total_trans');
                $Caja->monto_dolar_cierre_dif   = $request->get('total_dolar_dif');
                $Caja->monto_peso_cierre_dif    = $request->get('total_peso_dif');
                $Caja->monto_bolivar_cierre_dif = $request->get('total_bolivar_dif');
                $Caja->monto_punto_cierre_dif   = $request->get('total_punto_dif');
                $Caja->monto_trans_cierre_dif   = $request->get('total_trans_dif');
                $Caja->dolar_dolar_operador     = $request->get('dif_moneda_dolar_to_dolar_input');
                $Caja->peso_dolar_operador      = $request->get('dif_moneda_peso_to_dolar_input');
                $Caja->punto_dolar_operador     = $request->get('dif_moneda_punto_to_dolar_input');
                $Caja->trans_dolar_operador     = $request->get('dif_moneda_trans_to_dolar_input');
                $Caja->efectivo_dolar_operador  = $request->get('dif_moneda_efectivo_to_dolar_input');
                $Caja->dolar_sistema            = $request->get('dolar_sistema');
                $Caja->peso_sistema             = $request->get('peso_sistema');
                $Caja->punto_sistema            = $request->get('punto_sistema');
                $Caja->trans_sistema            = $request->get('trans_sistema');
                $Caja->efectivo_sistema         = $request->get('efectivo_sistema');
                $Caja->total_sistema_reg        = $request->get('total_sistema_reg_input');
                $Caja->total_operador_reg       = $request->get('total_operador_reg_input');
                $Caja->total_diferencia         = $request->get('total_dif_input');
                $Caja->Observaciones            = $request->get('Observaciones');
                $Caja->estado                   = 'Cerrada';
                $Caja->update();

                $session_id = Sessioncaja::findOrFail($session_id);
                $session_id->estado = 'Cerrada';
                $session_id->update();




                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                // TODO Guardamos en la tabla historial vueltos pendientes para que luego pueda ser consultada si alteracion en el registro

                $vueltos_pendientes_Actuales = Excedentes_Recibidos_Caja_Actual::where('caja_id', $caja_id)->get();

                if($vueltos_pendientes_Actuales){
                    foreach ($vueltos_pendientes_Actuales as $vtosPtes) {
                        // return $vueltos_pendientes_Actuales;
                        $historialVueltosPendientes = new Historial_Vueltos_Pendiente();
                        $historialVueltosPendientes->Tipo = $vtosPtes->Tipo;
                        $historialVueltosPendientes->Estado = $vtosPtes->Estado;
                        $historialVueltosPendientes->Divisa = $vtosPtes->Divisa;
                        $historialVueltosPendientes->MontoDivisa = $vtosPtes->MontoDivisa;
                        $historialVueltosPendientes->TasaTiket = $vtosPtes->TasaTiket;
                        $historialVueltosPendientes->MontoDolar = $vtosPtes->MontoDolar;
                        $historialVueltosPendientes->servicio_id = $vtosPtes->servicio_id;
                        $historialVueltosPendientes->caja_id = $vtosPtes->caja_id;
                        $historialVueltosPendientes->save();
                    }
                }

                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                // REVIEW ESTE METODO FUE MOVIDO AL METODO STOR CUANDO SE VA A CREAR LA CAJA PARA PODER CAMBIAR EL ID

                // TODO Ahora que lla guardamos los datos en la tabla historial
                //procedemos a borrar los registros donde el estado no sea pendiente
                // y a su vez vamos a cambiar el id colocandole el nuevo ide  de la caja
                //recien abierta.

                // TODO Creamos consulta para buscar registros y filtrarlos
                // $filtrarReg = Excedentes_Recibidos_Caja_Actual::get();
                // // return $filtrarReg;

                // if($filtrarReg){
                //     foreach ($filtrarReg as $fReg) {
                //         if($fReg->Estado == 'Pendiente'){
                //             $upReg = Excedentes_Recibidos_Caja_Actual::findOrFail($caja_id);
                //             $upReg->caja_id = $caja_id + 1;
                //             $upReg->update();
                //         }else{
                //             Excedentes_Recibidos_Caja_Actual::destroy($filtrarReg->id);
                //         }
                //     }
                // }


                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////////////////////////////////////// BsubTotald
            // $bcantidadR = $request->get('bcantidad');
            // $BsubTotaldR = $request->get('BsubTotald');
            // $bvalorR = $request->get('bvalor');
            // $btipoR = $request->get('btipo');
            // $bdenominacionR = $request->get('bdenominacion');

            // $BsubTotaldR = array_filter($BsubTotaldR);


            // if ($BsubTotaldR) {
            //     foreach($BsubTotaldR as $key => $val) {

            //         $bcantidad[]=$bcantidadR[$key];
            //         $BsubTotald[]=$BsubTotaldR[$key];
            //         $bvalor[]=$bvalorR[$key];
            //         $btipo[]=$btipoR[$key];
            //         $bdenominacion[]=$bdenominacionR[$key];
            //     }

            //     // dd($bcantidadR, $BsubTotaldR,$bvalorR,$btipoR,$bdenominacionR);
            //     //creamos un contador
            //     $cont = 0;

            //     //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
            //     while ($cont < count($BsubTotald)) {

            //         $detalleBolso = new Contabilidad();
            //         $detalleBolso->denominacion = $bdenominacion[$cont];//este idingreso se autogenera cuando se crea el objeto en la parte superior (*)
            //         $detalleBolso->valor = $bvalor[$cont];
            //         $detalleBolso->cantidad = $bcantidad[$cont];
            //         $detalleBolso->subtotal = $BsubTotald[$cont];
            //         $detalleBolso->tipo = $btipo[$cont];
            //         $detalleBolso->modo = $estatus_caja;
            //         $detalleBolso->caja_id = $Caja->id;
            //         $detalleBolso->save();

            //         $cont = $cont+1;
            //     }
            // }



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // $pcantidadR = $request->get('pcantidad');
            // $PsubTotaldR = $request->get('PsubTotald');
            // $pvalorR = $request->get('pvalor');
            // $ptipoR = $request->get('ptipo');
            // $pdenominacionR = $request->get('pdenominacion');

            // $PsubTotaldR = array_filter($PsubTotaldR);

            // if ($PsubTotaldR) {
            //     foreach($PsubTotaldR as $key => $val) {

            //         $pcantidad[]=$pcantidadR[$key];
            //         $PsubTotald[]=$PsubTotaldR[$key];
            //         $pvalor[]=$pvalorR[$key];
            //         $ptipo[]=$ptipoR[$key];
            //         $pdenominacion[]=$pdenominacionR[$key];
            //     }
            //     // dd($divisa, $MontoDivisa,$TasaTike,$MontoDolar,$Veltos);
            //     //creamos un contador
            //     $cont = 0;

            //     //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
            //     while ($cont < count($PsubTotald)) {

            //         $detalleBolsoP = new Contabilidad();
            //         $detalleBolsoP->denominacion = $pdenominacion[$cont];//este idingreso se autogenera cuando se crea el objeto en la parte superior (*)
            //         $detalleBolsoP->valor = $pvalor[$cont];
            //         $detalleBolsoP->cantidad = $pcantidad[$cont];
            //         $detalleBolsoP->subtotal = $PsubTotald[$cont];
            //         $detalleBolsoP->tipo = $ptipo[$cont];
            //         $detalleBolsoP->modo = $estatus_caja;
            //         $detalleBolsoP->caja_id = $Caja->id;
            //         $detalleBolsoP->save();

            //         $cont = $cont+1;
            //     }
            // }



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // $dcantidadR = $request->get('dcantidad');
            // $DsubTotaldR = $request->get('DsubTotald');
            // $dvalorR = $request->get('dvalor');
            // $dtipoR = $request->get('dtipo');
            // $ddenominacionR = $request->get('ddenominacion');

            // $DsubTotaldR = array_filter($DsubTotaldR);

            // if ($DsubTotaldR) {
            //     foreach($DsubTotaldR as $key => $val) {

            //         $dcantidad[]=$dcantidadR[$key];
            //         $DsubTotald[]=$DsubTotaldR[$key];
            //         $dvalor[]=$dvalorR[$key];
            //         $dtipo[]=$dtipoR[$key];
            //         $ddenominacion[]=$ddenominacionR[$key];
            //     }
            //     // dd($divisa, $MontoDivisa,$TasaTike,$MontoDolar,$Veltos);
            //     //creamos un contador
            //     $cont = 0;

            //     //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
            //     while ($cont < count($DsubTotald)) {

            //         $detalleBolsoD = new Contabilidad();
            //         $detalleBolsoD->denominacion = $ddenominacion[$cont];//este idingreso se autogenera cuando se crea el objeto en la parte superior (*)
            //         $detalleBolsoD->valor = $dvalor[$cont];
            //         $detalleBolsoD->cantidad = $dcantidad[$cont];
            //         $detalleBolsoD->subtotal = $DsubTotald[$cont];
            //         $detalleBolsoD->tipo = $dtipo[$cont];
            //         $detalleBolsoD->modo = $estatus_caja;
            //         $detalleBolsoD->caja_id = $Caja->id;
            //         $detalleBolsoD->save();

            //         $cont = $cont+1;
            //     }
            // }
            // Sessioncaja::crearsession();

            $UserName = $request->user();

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            DB::commit();

        }catch(\Exception $e)
        {

            DB::rollback();
            dd($e);
        }

        return redirect()
        ->route('caja.index')
        ->with('status_success', $UserName->name.'La caja fue cerrada Correctamente Gracias por usar nuestro Sistema. ¡ Te Esperamos Pronto...!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return 'Estoy en destroy';
    }
}
