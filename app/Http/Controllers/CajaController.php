<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Tasa;
use App\Venta;
use App\Credito;
use App\Empresa;
use App\Articulo;
use App\Servicio;
use Carbon\Carbon;
use App\Horas_extra;
use App\Sessioncaja;
use App\Contabilidad;
use App\ControlStock;
use App\Denominacion;
use App\Pago_Credito;
use App\Pago_Servicio;
use App\Detalle_credito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Historial_Vueltos_Pendiente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Excedentes_Recibidos_Caja_Actual;
use App\Excedentes_Pendientes_Caja_Anterior;
use App\HistorialCreditoCaja;

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
                //when we open the box we inital the stock

                $stocks = self::get_product_stock(['id','stock','nombre']);

                if($stocks){
                    foreach ($stocks as $value) {
                        $control_stock = new ControlStock();
                        $control_stock->stock_inicio = $value->stock;
                        $control_stock->user_id  = $idUsuario;
                        $control_stock->articulo_id  = $value->id;
                        $control_stock->caja_id  = $Caja->id;
                        $control_stock->save();
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
     *
     * @param array $arg // This $arg recive all attribute of query
     */

    public static function get_product_stock($arg = array()){
        if(isset($arg)){
            $stock = Articulo::select($arg)->get();
            if($stock){
                return $stock;
            }else{
                return false;
            }
        }
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
        $cajas->pago_vueltos;
        $cajas->articulo_ventas;
        $cajas->servicios;
        $cajas->detalle_creditos_pagados;
        // return $cajas;
        // $cajas->credito;
        $cajas->cortesias;
        $cajas->pago_servicios;
        $cajas->pago_creditos;
        $cajas->pago_ventas;
        $cajas->creditos_pagados;
        $cajas->excedente_actual;
        $cajas->horas_extras;
        $cajas->pagos_vueltos_extra;
        $cajas->pago_vueltos_credito;
        $cajas->pago_extras;
        // return $cajas;





//         // $creditos = Credito::where('caja_id',$caja->id)->get();

//         $cajas->creditos = $creditos;


// return $cajas->excedente_actual_valor;
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
            $vueltosPendientesCajaAnterior = Historial_Vueltos_Pendiente::where('caja_id', $cajas->id  - 1)->get();
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
                $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarFinal = $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarFinal  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
            }

            if ($TotalVuetosPendientesCajaAnterior->Estado == 'Pendiente' && $TotalVuetosPendientesCajaAnterior->Tipo == 'Servicio') {
                if ($TotalVuetosPendientesCajaAnterior->Divisa == 'Dolar') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisaServicio = $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisaServicio  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarServicio = $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarServicio  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }elseif ($TotalVuetosPendientesCajaAnterior->Divisa == 'Peso') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisaServicio = $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisaServicio  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDolarServicio = $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDolarServicio  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }elseif ($TotalVuetosPendientesCajaAnterior->Divisa == 'Bolivar') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisaServicio = $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisaServicio  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDolarServicio = $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDolarServicio  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }elseif ($TotalVuetosPendientesCajaAnterior->Divisa == 'Punto') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisaServicio = $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisaServicio  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDolarServicio = $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDolarServicio  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }elseif ($TotalVuetosPendientesCajaAnterior->Divisa == 'Transferencia') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisaServicio = $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisaServicio  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDolarServicio = $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDolarServicio  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }
                $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarServicioFinal = $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarServicioFinal  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
            }

            if ($TotalVuetosPendientesCajaAnterior->Estado == 'Pendiente' && $TotalVuetosPendientesCajaAnterior->Tipo == 'Consumo') {
                if ($TotalVuetosPendientesCajaAnterior->Divisa == 'Dolar') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisaConsumo = $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisaConsumo  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarConsumo = $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarConsumo  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }elseif ($TotalVuetosPendientesCajaAnterior->Divisa == 'Peso') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisaConsumo = $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisaConsumo  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDolarConsumo = $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDolarConsumo  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }elseif ($TotalVuetosPendientesCajaAnterior->Divisa == 'Bolivar') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisaConsumo = $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisaConsumo  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDolarConsumo = $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDolarConsumo  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }elseif ($TotalVuetosPendientesCajaAnterior->Divisa == 'Punto') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisaConsumo = $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisaConsumo  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDolarConsumo = $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDolarConsumo  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }elseif ($TotalVuetosPendientesCajaAnterior->Divisa == 'Transferencia') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisaConsumo = $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisaConsumo  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDolarConsumo = $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDolarConsumo  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }

                $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarConsumoFinal = $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarConsumoFinal  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
            }

            if ($TotalVuetosPendientesCajaAnterior->Estado == 'Pendiente' && $TotalVuetosPendientesCajaAnterior->Tipo == 'Horas_Extras') {
                if ($TotalVuetosPendientesCajaAnterior->Divisa == 'Dolar') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisaHorasExtras = $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDivisaHorasExtras  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarHorasExtras = $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarHorasExtras  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }elseif ($TotalVuetosPendientesCajaAnterior->Divisa == 'Peso') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisaHorasExtras = $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDivisaHorasExtras  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDolarHorasExtras = $cajas->TotalSumaVueltosCajaAnteriorPendientesPesoDolarHorasExtras  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }elseif ($TotalVuetosPendientesCajaAnterior->Divisa == 'Bolivar') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisaHorasExtras = $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDivisaHorasExtras  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDolarHorasExtras = $cajas->TotalSumaVueltosCajaAnteriorPendientesBolivarDolarHorasExtras  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }elseif ($TotalVuetosPendientesCajaAnterior->Divisa == 'Punto') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisaHorasExtras = $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDivisaHorasExtras  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDolarHorasExtras = $cajas->TotalSumaVueltosCajaAnteriorPendientesPuntoDolarHorasExtras  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }elseif ($TotalVuetosPendientesCajaAnterior->Divisa == 'Transferencia') {
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisaHorasExtras = $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDivisaHorasExtras  + $TotalVuetosPendientesCajaAnterior->MontoDivisa;
                    $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDolarHorasExtras = $cajas->TotalSumaVueltosCajaAnteriorPendientesTransferenciaDolarHorasExtras  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
                }

                $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarHorasExtrasFinal = $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarHorasExtrasFinal  + $TotalVuetosPendientesCajaAnterior->MontoDolar;
            }
        }

// return $cajas->TotalSumaVueltosCajaAnteriorPendientesDolarDolarServicioFinal;
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // TODO optenemos los datos de la tabla excedente actual

    if ($cajas->estado == 'Cerrada') {
        $valor = Historial_Vueltos_Pendiente::where('caja_id', $cajas->id)->get();

        $cajas->excedente_actual_valor  = $valor;
        // return $cajas->excedente_actual_valor;
    }
    if ($cajas->estado == 'Abierta'){

        $valor = $cajas->excedente_actual;
        $cajas->excedente_actual_valor  = $valor;
        // $vueltosPendientesCajaAnterior = Historial_Vueltos_Pendiente::where('caja_id', '<>', $cajas->id)->get();
    }
    // return $cajas->excedente_actual_valor;
// return $valor;
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
// return $cajas->creditos_pagados;
foreach ($cajas->creditos_pagados as $credPagados ) {
// return $cajas->creditos_pagados;
    if ($credPagados->user_id == $caja->user->id){

        $validarPagosCreditos = Pago_Credito::where('detalle__creditos__pagado_id',$credPagados->detalle__creditos__pagado_id)->get();
        if (count($validarPagosCreditos)) {
            foreach ($validarPagosCreditos as $credPagadosCaja ) {
                if ($credPagadosCaja->Divisa == 'Dolar') {
                    $cajas->SumaTotalDolarCred = $cajas->SumaTotalDolarCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);

                    $cajas->SumaTotalDolarCreditoDflotante = $cajas->SumaTotalDolarCreditoDflotante + ($credPagadosCaja->Vueltos);
                    $cajas->SumaTotalDolarCredito = $cajas->SumaTotalDolarCredito + ($credPagadosCaja->MontoConsumo * $tasaDolar->tasa) + ($credPagadosCaja->MontoServeicio * $tasaDolar->tasa);
                    $cajas->SumaTotalDolarCreditoFinal = $cajas->SumaTotalDolarCreditoFinal + ($credPagadosCaja->MontoDivisa);
                    $cajas->SumaTotalDolarCreditoFinalDolar = $cajas->SumaTotalDolarCreditoFinalDolar + ($credPagadosCaja->MontoDolar);

                }elseif ($credPagadosCaja->Divisa == 'Peso') {
                    $cajas->SumaTotalPesoCred = $cajas->SumaTotalPesoCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);

                    $cajas->SumaTotalPesoCreditoDflotante = $cajas->SumaTotalPesoCreditoDflotante + ( $credPagadosCaja->Vueltos * $tasaPeso->tasa);
                    $cajas->SumaTotalPesoCredito = $cajas->SumaTotalPesoCredito + ($credPagadosCaja->MontoConsumo * $tasaPeso->tasa) + ($credPagadosCaja->MontoServeicio * $tasaPeso->tasa);
                    $cajas->SumaTotalPesoCreditoFinal = $cajas->SumaTotalPesoCreditoFinal + ($credPagadosCaja->MontoDivisa);
                    $cajas->SumaTotalPesoCreditoFinalDolar = $cajas->SumaTotalPesoCreditoFinalDolar + ($credPagadosCaja->MontoDolar);

                }elseif ($credPagadosCaja->Divisa == 'Bolivar') {
                    $cajas->SumaTotalBolivarCred = $cajas->SumaTotalBolivarCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);

                    $cajas->SumaTotalBolivarCreditoDflotante = $cajas->SumaTotalBolivarCreditoDflotante + ($credPagadosCaja->Vueltos * $tasaEfectivo->tasa);
                    $cajas->SumaTotalBolivarCredito = $cajas->SumaTotalBolivarCredito + ($credPagadosCaja->MontoConsumo * $tasaEfectivo->tasa) + ($credPagadosCaja->MontoServeicio * $tasaEfectivo->tasa);
                    $cajas->SumaTotalBolivarCreditoFinal = $cajas->SumaTotalBolivarCreditoFinal + ($credPagadosCaja->MontoDivisa);
                    $cajas->SumaTotalBolivarCreditoFinalDolar = $cajas->SumaTotalBolivarCreditoFinalDolar + ($credPagadosCaja->MontoDolar);

                }elseif ($credPagadosCaja->Divisa == 'Punto') {
                    $cajas->SumaTotalPuntoCred = $cajas->SumaTotalPuntoCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);

                    $cajas->SumaTotalPuntoCreditoDflotante = $cajas->SumaTotalPuntoCreditoDflotante + ($credPagadosCaja->Vueltos * $tasaTransferenciaPunto->tasa);
                    $cajas->SumaTotalPuntoCredito = $cajas->SumaTotalPuntoCredito + ($credPagadosCaja->MontoConsumo * $tasaTransferenciaPunto->tasa) + ($credPagadosCaja->MontoServeicio * $tasaTransferenciaPunto->tasa);
                    $cajas->SumaTotalPuntoCreditoFinal = $cajas->SumaTotalPuntoCreditoFinal + ($credPagadosCaja->MontoDivisa);
                    $cajas->SumaTotalPuntoCreditoFinalDolar = $cajas->SumaTotalPuntoCreditoFinalDolar + ($credPagadosCaja->MontoDolar);

                }elseif ($credPagadosCaja->Divisa == 'Transferencia') {
                    $cajas->SumaTotalTransferenciaCred = $cajas->SumaTotalTransferenciaCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);

                    $cajas->SumaTotalTransferenciaCreditoDflotante = $cajas->SumaTotalTransferenciaCreditoDflotante + ($credPagadosCaja->Vueltos * $tasaTransferenciaPunto->tasa);
                    $cajas->SumaTotalTransferenciaCredito = $cajas->SumaTotalTransferenciaCredito + ($credPagadosCaja->MontoConsumo * $tasaTransferenciaPunto->tasa) + ($credPagadosCaja->MontoServeicio * $tasaTransferenciaPunto->tasa);
                    $cajas->SumaTotalTransferenciaCreditoFinal = $cajas->SumaTotalTransferenciaCreditoFinal + ($credPagadosCaja->MontoDivisa);
                    $cajas->SumaTotalTransferenciaCreditoFinalDolar = $cajas->SumaTotalTransferenciaCreditoFinalDolar + ($credPagadosCaja->MontoDolar);

                }

                $cajas->TotalSumaTotalCreditoDflotante = $cajas->TotalSumaTotalCreditoDflotante + ($credPagadosCaja->Vueltos);
                $cajas->TotalSumaTotalCreditoFinal = $cajas->TotalSumaTotalCreditoFinal + ($credPagadosCaja->MontoDolar);
            }
        }

        if ($credPagados->tipo_operacion == 'Consumo'){

            $validarPagosCreditosConsumo = Pago_Credito::where('detalle__creditos__pagado_id',$credPagados->detalle__creditos__pagado_id)->get();
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

            $validarPagosCreditosServicio = Pago_Credito::where('detalle__creditos__pagado_id',$credPagados->detalle__creditos__pagado_id)->get();
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

        if ($credPagados->tipo_operacion == 'Horas_Extras'){

            $validarPagosCreditosHorasExtras = Pago_Credito::where('detalle__creditos__pagado_id',$credPagados->detalle__creditos__pagado_id)->get();
            // return $validarPagosCreditosHorasExtras;
            if (count($validarPagosCreditosHorasExtras)) {
                foreach ($validarPagosCreditosHorasExtras as $credPagadosCajaHorasExtra ) {
                    if ($credPagadosCajaHorasExtra->Divisa == 'Dolar') {
                        $cajas->SumaTotalDolarCredHorasExtra = $cajas->SumaTotalDolarCredHorasExtra + ($credPagadosCajaHorasExtra->MontoDivisa - $credPagadosCajaHorasExtra->Vueltos * -1);
                    }elseif ($credPagadosCajaHorasExtra->Divisa == 'Peso') {
                        $cajas->SumaTotalPesoCredHorasExtra = $cajas->SumaTotalPesoCredHorasExtra + ($credPagadosCajaHorasExtra->MontoDivisa - $credPagadosCajaHorasExtra->Vueltos * -1);
                    }elseif ($credPagadosCajaHorasExtra->Divisa == 'Bolivar') {
                        $cajas->SumaTotalBolivarCredHorasExtra = $cajas->SumaTotalBolivarCredHorasExtra + ($credPagadosCajaHorasExtra->MontoDivisa - $credPagadosCajaHorasExtra->Vueltos * -1);
                    }elseif ($credPagadosCajaHorasExtra->Divisa == 'Punto') {
                        $cajas->SumaTotalPuntoCredHorasExtra = $cajas->SumaTotalPuntoCredHorasExtra + ($credPagadosCajaHorasExtra->MontoDivisa - $credPagadosCajaHorasExtra->Vueltos * -1);
                    }elseif ($credPagadosCajaHorasExtra->Divisa == 'Transferencia') {
                        $cajas->SumaTotalTransferenciaCredHorasExtra = $cajas->SumaTotalTransferenciaCredHorasExtra + ($credPagadosCajaHorasExtra->MontoDivisa - $credPagadosCajaHorasExtra->Vueltos * -1);
                    }
                }
            }

            $cajas->SumaTotalCreditosPagadosHorasExtrasPorCaja = $cajas->SumaTotalCreditosPagadosHorasExtrasPorCaja + $credPagados->monto;

        }
            $cajas->SumaTotalCreditosPagadosTotalesPorCaja = $cajas->SumaTotalCreditosPagadosTotalesPorCaja + $credPagados->monto;

    }else{

        $validarPagosCreditos = Pago_Credito::where('detalle__creditos__pagado_id',$credPagados->detalle__creditos__pagado_id)->get();
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

    if ($credPagados->tipo_operacion == 'Horas_Extras') {

        // $cajas->SumaTotalCreditosPagadosConsumo = $cajas->SumaTotalCreditosPagadosConsumo + $credPagados->monto;
        $cajas->SumaTotalCantidadCreditosPagadosHorasExtras =  $cajas->SumaTotalCantidadCreditosPagadosHorasExtras + 1;

    }
    // $cajas->SumaTotalCreditosPagadosTotales = $cajas->SumaTotalCreditosPagadosTotales + $credPagados->monto;

    if ($cajas->estado == 'Abierta') {

        $cajas->SumaTotalCantidadCreditosPagadosTotales = $cajas->SumaTotalCantidadCreditosPagadosTotales + 1;
    }


}
// return $cajas->SumaTotalCreditosPagadosHorasExtrasPorCaja;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//                    GESTIONAR DETALLE CREDITOS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if ($cajas->estado == 'Cerrada') {

                $historialCreditosCaja = HistorialCreditoCaja::where('caja_id', $id)->first();
                if($historialCreditosCaja){
                    // return $historialCreditosCaja;
                    $cajas->SumaTotalCantidadCreditosVigentes = $historialCreditosCaja->hist_creditos_vigentes;
                    $cajas->SumaTotalCantidadCreditosVencidos = $historialCreditosCaja->hist_creditos_vencidos;
                    $cajas->SumaTotalCantidadCreditosPagadosTotales = $historialCreditosCaja->hist_creditos_pagados;
                    $cajas->hist_creditos_nuevos = $historialCreditosCaja->hist_creditos_nuevos;
                    $cajas->hist_total_creditos = $historialCreditosCaja->hist_total_creditos;
                }else{
                    $cajas->SumaTotalCantidadCreditosVigentes = 0;
                    $cajas->SumaTotalCantidadCreditosVencidos = 0;
                    $cajas->SumaTotalCantidadCreditosPagadosTotales = 0;
                    $cajas->hist_creditos_nuevos = 0;
                    $cajas->hist_total_creditos = 0;
                }

            }


                $detalle_creditos = Detalle_credito::get();

                // return $detalle_creditos;

                foreach ($detalle_creditos as $detalleCredito ) {

                    if ($detalleCredito->estado_credito == 'Vigente') {
                        if ($cajas->estado == 'Abierta') {
                            $cajas->SumaTotalCantidadCreditosVigentes = $cajas->SumaTotalCantidadCreditosVigentes + 1;
                        }
                        $cajas->SumaTotalMontoCreditosVigentes = $cajas->SumaTotalMontoCreditosVigentes + $detalleCredito->monto;


                    }

                    if ($detalleCredito->estado_credito == 'Vencido') {

                        if ($cajas->estado == 'Abierta') {
                            $cajas->SumaTotalCantidadCreditosVencidos = $cajas->SumaTotalCantidadCreditosVencidos + 1;
                        }
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
$ver = [];
        foreach ($cajas->ventas as $vent ) {
            // if ($vent->estado == 'Aceptada') {
            // $cajas->SumaTotalVentas = $cajas->SumaTotalVentas + $vent->total_venta;
            // $cajas->SumaTotalCantidadVentas = $cajas->SumaTotalCantidadVentas + 1;
            // }
            if ($vent->estado == 'Aceptada'  && $vent->modo_pago == 'Contado') {

                    $cajas->SumaTotalVentas = $cajas->SumaTotalVentas + $vent->total_venta;
                    $ver[] = $vent->total_venta;
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
                $cajas->SumaTotalVentasCortesia = $cajas->SumaTotalVentasCortesia + $vent->total_venta;
            }
        }
        // return $ver;
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
        // return $cajas->pago_ventas;

        // TODO pagos ventas (consumo)


        foreach ($cajas->pago_ventas as $pagoConsumo ) {

            // return $pagoConsumo->venta_id;
            $validarPagosConsumo = Venta::where('id',$pagoConsumo->venta_id)->first();
            // return $validarPagosConsumo;
            if ($validarPagosConsumo) {
                if ($pagoConsumo->Divisa == 'Dolar') {
                    if($pagoConsumo->Vueltos > 0){
                        $cajas->SumaTotalDolar = $cajas->SumaTotalDolar + ($pagoConsumo->MontoDolarConsumo * $tasaDolar->tasa);
                    }else{
                        $cajas->SumaTotalDolarConsuDflotante = $cajas->SumaTotalDolarConsuDflotante + ($pagoConsumo->Vueltos * -1);
                        $cajas->SumaTotalDolar = $cajas->SumaTotalDolar + ($pagoConsumo->MontoDolarConsumo * $tasaDolar->tasa);
                        $test[] = ($pagoConsumo->MontoDivisa - $pagoConsumo->Vueltos * -1) - $validarPagosConsumo->excedente_nuevo;
                    }
                }elseif ($pagoConsumo->Divisa == 'Peso') {
                    if($pagoConsumo->Vueltos > 0){
                        $cajas->SumaTotalPeso = $cajas->SumaTotalPeso + ($pagoConsumo->MontoDolarConsumo * $tasaPeso->tasa);
                    }else{
                        $cajas->SumaTotalPesoConsuDflotante = $cajas->SumaTotalPesoConsuDflotante + ( $pagoConsumo->Vueltos * -1);
                        $cajas->SumaTotalPeso = $cajas->SumaTotalPeso + ($pagoConsumo->MontoDolarConsumo * $tasaPeso->tasa);
                    }
                }elseif ($pagoConsumo->Divisa == 'Bolivar') {
                    if($pagoConsumo->Vueltos > 0){
                        $cajas->SumaTotalBolivar = $cajas->SumaTotalBolivar + ($pagoConsumo->MontoDolarConsumo * $tasaEfectivo->tasa);
                    }else{
                        $cajas->SumaTotalBolivarConsuDflotante = $cajas->SumaTotalBolivarConsuDflotante + ($pagoConsumo->Vueltos * -1);
                        $cajas->SumaTotalBolivar = $cajas->SumaTotalBolivar + ($pagoConsumo->MontoDolarConsumo * $tasaEfectivo->tasa);
                    }
                }elseif ($pagoConsumo->Divisa == 'Punto') {
                    if($pagoConsumo->Vueltos > 0){
                        $cajas->SumaTotalPunto = $cajas->SumaTotalPunto + ($pagoConsumo->MontoDolarConsumo * $tasaTransferenciaPunto->tasa);
                    }else{
                        $cajas->SumaTotalPuntoConsuDflotante = $cajas->SumaTotalPuntoConsuDflotante + ($pagoConsumo->Vueltos * -1);
                        $cajas->SumaTotalPunto = $cajas->SumaTotalPunto + ($pagoConsumo->MontoDolarConsumo * $tasaTransferenciaPunto->tasa);
                    }
                }elseif ($pagoConsumo->Divisa == 'Transferencia') {
                    if($pagoConsumo->Vueltos > 0){
                        $cajas->SumaTotalTransferencia = $cajas->SumaTotalTransferencia + ($pagoConsumo->MontoDolarConsumo * $tasaTransferenciaPunto->tasa);
                    }else{
                        $cajas->SumaTotalTransferenciaConsuDflotante = $cajas->SumaTotalTransferenciaConsuDflotante + ($pagoConsumo->Vueltos * -1);
                        $cajas->SumaTotalTransferencia = $cajas->SumaTotalTransferencia + ($pagoConsumo->MontoDolarConsumo * $tasaTransferenciaPunto->tasa);
                    }
                }

                $cajas->TotalSumaTotalConsuDflotante = $cajas->TotalSumaTotalConsuDflotante + ($pagoConsumo->Vueltos * -1);
            }

            if ($validarPagosConsumo) {
                if ($pagoConsumo->Divisa == 'Dolar') {

                        // $ojo[] = ($pagoConsumo->MontoDivisa - $pagoConsumo->Vueltos * -1) - $validarpagoConsumoServicios->excedente_nuevo;
                        $cajas->SumaTotalDolarConsDflotante = $cajas->SumaTotalDolarConsDflotante + ($pagoConsumo->Vueltos);
                        $cajas->SumaTotalDolarCons = $cajas->SumaTotalDolarCons + ($pagoConsumo->MontoDolarConsumo * $tasaDolar->tasa);
                        $cajas->SumaTotalDolarConsFinal = $cajas->SumaTotalDolarConsFinal + ($pagoConsumo->MontoDivisa);
                        $cajas->SumaTotalDolarConsFinalDolar = $cajas->SumaTotalDolarConsFinalDolar + ($pagoConsumo->MontoDolar);

                }elseif ($pagoConsumo->Divisa == 'Peso') {



                        // $ojop[] = ($pagoConsumo->MontoDivisa - $pagoConsumo->Vueltos * -1) - ($validarpagoConsumoConsuicios->excedente_nuevo * $tasaPeso->tasa);
                        $cajas->SumaTotalPesoConsDflotante = $cajas->SumaTotalPesoConsDflotante + ( $pagoConsumo->Vueltos * $tasaPeso->tasa);
                        $cajas->SumaTotalPesoCons = $cajas->SumaTotalPesoCons + ($pagoConsumo->MontoDolarConsumo * $tasaPeso->tasa);
                        $cajas->SumaTotalPesoConsFinal = $cajas->SumaTotalPesoConsFinal + ($pagoConsumo->MontoDivisa);
                        $cajas->SumaTotalPesoConsFinalDolar = $cajas->SumaTotalPesoConsFinalDolar + ($pagoConsumo->MontoDolar);

                }elseif ($pagoConsumo->Divisa == 'Bolivar') {

                        $cajas->SumaTotalBolivarConsDflotante = $cajas->SumaTotalBolivarConsDflotante + ($pagoConsumo->Vueltos * $tasaEfectivo->tasa);
                        $cajas->SumaTotalBolivarCons = $cajas->SumaTotalBolivarCons + ($pagoConsumo->MontoDolarConsumo * $tasaEfectivo->tasa);
                        $cajas->SumaTotalBolivarConsFinal = $cajas->SumaTotalBolivarConsFinal + ($pagoConsumo->MontoDivisa);
                        $cajas->SumaTotalBolivarConsFinalDolar = $cajas->SumaTotalBolivarConsFinalDolar + ($pagoConsumo->MontoDolar);

                }elseif ($pagoConsumo->Divisa == 'Punto') {

                        $cajas->SumaTotalPuntoConsDflotante = $cajas->SumaTotalPuntoConsDflotante + ($pagoConsumo->Vueltos * $tasaTransferenciaPunto->tasa);
                        $cajas->SumaTotalPuntoCons = $cajas->SumaTotalPuntoCons + ($pagoConsumo->MontoDolarConsumo * $tasaTransferenciaPunto->tasa);
                        $cajas->SumaTotalPuntoConsFinal = $cajas->SumaTotalPuntoConsFinal + ($pagoConsumo->MontoDivisa);
                        $cajas->SumaTotalPuntoConsFinalDolar = $cajas->SumaTotalPuntoConsFinalDolar + ($pagoConsumo->MontoDolar);

                }elseif ($pagoConsumo->Divisa == 'Transferencia') {

                        // $ojot[] = ($pagoConsumo->MontoDivisa - $pagoConsumo->Vueltos * -1) - ($validarpagoConsumoConsuicios->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                        $cajas->SumaTotalTransferenciaConsDflotante = $cajas->SumaTotalTransferenciaConsDflotante + ($pagoConsumo->Vueltos * $tasaTransferenciaPunto->tasa);
                        $cajas->SumaTotalTransferenciaCons = $cajas->SumaTotalTransferenciaCons + ($pagoConsumo->MontoDolarConsumo * $tasaTransferenciaPunto->tasa);
                        $cajas->SumaTotalTransferenciaConsFinal = $cajas->SumaTotalTransferenciaConsFinal + ($pagoConsumo->MontoDivisa);
                        $cajas->SumaTotalTransferenciaConsFinalDolar = $cajas->SumaTotalTransferenciaConsFinalDolar + ($pagoConsumo->MontoDolar);

                }

                $cajas->TotalSumaTotalConsDflotante = $cajas->TotalSumaTotalConsDflotante + ($pagoConsumo->Vueltos);
                $cajas->TotalSumaTotalConsFinal = $cajas->TotalSumaTotalConsFinal + ($pagoConsumo->MontoDolar);

            }



        }


        foreach ($cajas->pago_vueltos as $pagoVtos ) {

            if($pagoVtos->Tipo == 'Servicio'){
                if ($pagoVtos->Divisa == 'Dolar') {

                        if($pagoVtos->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->MontoDivisa);
                        }

                        // if($pagoVtos->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->Vueltos);
                        // }

                        $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->MontoDivisa);
                        $cajas->SumaTotalDolarVueltosFinalDolar = $cajas->SumaTotalDolarVueltosFinalDolar + ($pagoVtos->MontoDolar);


                }elseif ($pagoVtos->Divisa == 'Peso') {
                    if($pagoVtos->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalPesoVueltosFinal = $cajas->SumaTotalPesoVueltosFinal + ( $pagoVtos->MontoDivisa);
                        }

                        // if($pagoVtos->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->Vueltos);
                        // }

                        $cajas->SumaTotalPesoVueltosFinal = $cajas->SumaTotalPesoVueltosFinal + ( $pagoVtos->MontoDivisa);
                        $cajas->SumaTotalPesoVueltosFinalDolar = $cajas->SumaTotalPesoVueltosFinalDolar + ( $pagoVtos->MontoDolar);

                }elseif ($pagoVtos->Divisa == 'Bolivar') {
                    if($pagoVtos->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalBolivarVueltosFinal = $cajas->SumaTotalBolivarVueltosFinal + ($pagoVtos->MontoDivisa);
                        }
                        // if($pagoVtos->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->Vueltos);
                        // }
                        $cajas->SumaTotalBolivarVueltosFinal = $cajas->SumaTotalBolivarVueltosFinal + ($pagoVtos->MontoDivisa);
                        $cajas->SumaTotalBolivarVueltosFinalDolar = $cajas->SumaTotalBolivarVueltosFinalDolar + ($pagoVtos->MontoDolar);

                }elseif ($pagoVtos->Divisa == 'Punto') {
                    if($pagoVtos->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalPuntoVueltosFinal = $cajas->SumaTotalPuntoVueltosFinal + ($pagoVtos->MontoDivisa);
                        }

                        // if($pagoVtos->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->Vueltos);
                        // }
                        $cajas->SumaTotalPuntoVueltosFinal = $cajas->SumaTotalPuntoVueltosFinal + ($pagoVtos->MontoDivisa);
                        $cajas->SumaTotalPuntoVueltosFinalDolar = $cajas->SumaTotalPuntoVueltosFinalDolar + ($pagoVtos->MontoDolar);

                }elseif ($pagoVtos->Divisa == 'Transferencia') {
                    if($pagoVtos->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalTransferenciaVueltosFinal = $cajas->SumaTotalTransferenciaVueltosFinal + ($pagoVtos->MontoDivisa);
                        }
                        // if($pagoVtos->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->Vueltos);
                        // }
                    $cajas->SumaTotalTransferenciaVueltosFinal = $cajas->SumaTotalTransferenciaVueltosFinal + ($pagoVtos->MontoDivisa);
                    $cajas->SumaTotalTransferenciaVueltosFinalDolar = $cajas->SumaTotalTransferenciaVueltosFinal + ($pagoVtos->MontoDolar);

                // }


            }

$cajas->TotalSumaTotalVueltosFinal = $cajas->TotalSumaTotalVueltosFinal + ($pagoVtos->MontoDolar);
            }
            // $validarPagosServicios = Servicio::where('id',$pagoVtos->servicio_id)->first();
            // if ($validarPagosServicios) {
                if($pagoVtos->Tipo == 'Consumo'){
                if ($pagoVtos->Divisa == 'Dolar') {

                        if($pagoVtos->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->MontoDivisa);
                        }

                        // if($pagoVtos->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->Vueltos);
                        // }

                        $cajas->SumaTotalDolarVueltosFinalConsumo = $cajas->SumaTotalDolarVueltosFinalConsumo + ($pagoVtos->MontoDivisa);
                        $cajas->SumaTotalDolarVueltosFinalDolarConsumo = $cajas->SumaTotalDolarVueltosFinalDolarConsumo + ($pagoVtos->MontoDolar);


                }elseif ($pagoVtos->Divisa == 'Peso') {
                    if($pagoVtos->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalPesoVueltosFinal = $cajas->SumaTotalPesoVueltosFinal + ( $pagoVtos->MontoDivisa);
                        }

                        // if($pagoVtos->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->Vueltos);
                        // }

                        $cajas->SumaTotalPesoVueltosFinalConsumo = $cajas->SumaTotalPesoVueltosFinalConsumo + ( $pagoVtos->MontoDivisa);
                        $cajas->SumaTotalPesoVueltosFinalDolarConsumo = $cajas->SumaTotalPesoVueltosFinalDolarConsumo + ( $pagoVtos->MontoDolar);

                }elseif ($pagoVtos->Divisa == 'Bolivar') {
                    if($pagoVtos->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalBolivarVueltosFinal = $cajas->SumaTotalBolivarVueltosFinal + ($pagoVtos->MontoDivisa);
                        }
                        // if($pagoVtos->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->Vueltos);
                        // }
                        $cajas->SumaTotalBolivarVueltosFinalConsumo = $cajas->SumaTotalBolivarVueltosFinalConsumo + ($pagoVtos->MontoDivisa);
                        $cajas->SumaTotalBolivarVueltosFinalDolarConsumo = $cajas->SumaTotalBolivarVueltosFinalDolarConsumo + ($pagoVtos->MontoDolar);

                }elseif ($pagoVtos->Divisa == 'Punto') {
                    if($pagoVtos->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalPuntoVueltosFinal = $cajas->SumaTotalPuntoVueltosFinal + ($pagoVtos->MontoDivisa);
                        }

                        // if($pagoVtos->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->Vueltos);
                        // }
                        $cajas->SumaTotalPuntoVueltosFinalConsumo = $cajas->SumaTotalPuntoVueltosFinalConsumo + ($pagoVtos->MontoDivisa);
                        $cajas->SumaTotalPuntoVueltosFinalDolarConsumo = $cajas->SumaTotalPuntoVueltosFinalDolarConsumo + ($pagoVtos->MontoDolar);

                }elseif ($pagoVtos->Divisa == 'Transferencia') {
                    if($pagoVtos->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalTransferenciaVueltosFinal = $cajas->SumaTotalTransferenciaVueltosFinal + ($pagoVtos->MontoDivisa);
                        }
                        // if($pagoVtos->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->Vueltos);
                        // }
                    $cajas->SumaTotalTransferenciaVueltosFinalConsumo = $cajas->SumaTotalTransferenciaVueltosFinalConsumo + ($pagoVtos->MontoDivisa);
                    $cajas->SumaTotalTransferenciaVueltosFinalDolarConsumo = $cajas->SumaTotalTransferenciaVueltosFinalConsumo + ($pagoVtos->MontoDolar);

                // }


            }

$cajas->TotalSumaTotalVueltosFinalConsumo = $cajas->TotalSumaTotalVueltosFinalConsumo + ($pagoVtos->MontoDolar);
            }


            if($pagoVtos->Tipo == 'Horas_Extras'){
                if ($pagoVtos->Divisa == 'Dolar') {

                        if($pagoVtos->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->MontoDivisa);
                        }

                        // if($pagoVtos->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->Vueltos);
                        // }

                        $cajas->SumaTotalDolarVueltosFinalHorasExtras = $cajas->SumaTotalDolarVueltosFinalHorasExtras + ($pagoVtos->MontoDivisa);
                        $cajas->SumaTotalDolarVueltosFinalDolarHorasExtras = $cajas->SumaTotalDolarVueltosFinalDolarHorasExtras + ($pagoVtos->MontoDolar);


                }elseif ($pagoVtos->Divisa == 'Peso') {
                    if($pagoVtos->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalPesoVueltosFinal = $cajas->SumaTotalPesoVueltosFinal + ( $pagoVtos->MontoDivisa);
                        }

                        // if($pagoVtos->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->Vueltos);
                        // }

                        $cajas->SumaTotalPesoVueltosFinalHorasExtras = $cajas->SumaTotalPesoVueltosFinalHorasExtras + ( $pagoVtos->MontoDivisa);
                        $cajas->SumaTotalPesoVueltosFinalDolarHorasExtras = $cajas->SumaTotalPesoVueltosFinalDolarHorasExtras + ( $pagoVtos->MontoDolar);

                }elseif ($pagoVtos->Divisa == 'Bolivar') {
                    if($pagoVtos->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalBolivarVueltosFinal = $cajas->SumaTotalBolivarVueltosFinal + ($pagoVtos->MontoDivisa);
                        }
                        // if($pagoVtos->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->Vueltos);
                        // }
                        $cajas->SumaTotalBolivarVueltosFinalHorasExtras = $cajas->SumaTotalBolivarVueltosFinalHorasExtras + ($pagoVtos->MontoDivisa);
                        $cajas->SumaTotalBolivarVueltosFinalDolarHorasExtras = $cajas->SumaTotalBolivarVueltosFinalDolarHorasExtras + ($pagoVtos->MontoDolar);

                }elseif ($pagoVtos->Divisa == 'Punto') {
                    if($pagoVtos->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalPuntoVueltosFinal = $cajas->SumaTotalPuntoVueltosFinal + ($pagoVtos->MontoDivisa);
                        }

                        // if($pagoVtos->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->Vueltos);
                        // }
                        $cajas->SumaTotalPuntoVueltosFinalHorasExtras = $cajas->SumaTotalPuntoVueltosFinalHorasExtras + ($pagoVtos->MontoDivisa);
                        $cajas->SumaTotalPuntoVueltosFinalDolarHorasExtras = $cajas->SumaTotalPuntoVueltosFinalDolarHorasExtras + ($pagoVtos->MontoDolar);

                }elseif ($pagoVtos->Divisa == 'Transferencia') {
                    if($pagoVtos->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalTransferenciaVueltosFinal = $cajas->SumaTotalTransferenciaVueltosFinal + ($pagoVtos->MontoDivisa);
                        }
                        // if($pagoVtos->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoVtos->Vueltos);
                        // }
                    $cajas->SumaTotalTransferenciaVueltosFinalHorasExtras = $cajas->SumaTotalTransferenciaVueltosFinalHorasExtras + ($pagoVtos->MontoDivisa);
                    $cajas->SumaTotalTransferenciaVueltosFinalDolarHorasExtras = $cajas->SumaTotalTransferenciaVueltosFinalHorasExtras + ($pagoVtos->MontoDolar);

                // }


            }

                $cajas->TotalSumaTotalVueltosFinalHorasExtras = $cajas->TotalSumaTotalVueltosFinalHorasExtras + ($pagoVtos->MontoDolar);
            }




        }

// return $cajas->SumaTotalDolarVueltosFinalHorasExtras;
        foreach ($cajas->pagos_vueltos_extra as $pagoCred ) {
            if($pagoCred->Tipo == 'Creditos'){
                if ($pagoCred->Divisa == 'Dolar') {

                        if($pagoCred->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoCred->MontoDivisa);
                        }

                        // if($pagoCred->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoCred->Vueltos);
                        // }

                        $cajas->SumaTotalDolarVueltosFinalCredito = $cajas->SumaTotalDolarVueltosFinalCredito + ($pagoCred->MontoDivisa);
                        $cajas->SumaTotalDolarVueltosFinalDolarCredito = $cajas->SumaTotalDolarVueltosFinalDolarCredito + ($pagoCred->MontoDolar);


                }elseif ($pagoCred->Divisa == 'Peso') {
                    if($pagoCred->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalPesoVueltosFinal = $cajas->SumaTotalPesoVueltosFinal + ( $pagoCred->MontoDivisa);
                        }

                        // if($pagoCred->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoCred->Vueltos);
                        // }

                        $cajas->SumaTotalPesoVueltosFinalCredito = $cajas->SumaTotalPesoVueltosFinalCredito + ( $pagoCred->MontoDivisa);
                        $cajas->SumaTotalPesoVueltosFinalDolarCredito = $cajas->SumaTotalPesoVueltosFinalDolarCredito + ( $pagoCred->MontoDolar);

                }elseif ($pagoCred->Divisa == 'Bolivar') {
                    if($pagoCred->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalBolivarVueltosFinal = $cajas->SumaTotalBolivarVueltosFinal + ($pagoCred->MontoDivisa);
                        }
                        // if($pagoCred->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoCred->Vueltos);
                        // }
                        $cajas->SumaTotalBolivarVueltosFinalCredito = $cajas->SumaTotalBolivarVueltosFinalCredito + ($pagoCred->MontoDivisa);
                        $cajas->SumaTotalBolivarVueltosFinalDolarCredito = $cajas->SumaTotalBolivarVueltosFinalDolarCredito + ($pagoCred->MontoDolar);

                }elseif ($pagoCred->Divisa == 'Punto') {
                    if($pagoCred->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalPuntoVueltosFinal = $cajas->SumaTotalPuntoVueltosFinal + ($pagoCred->MontoDivisa);
                        }

                        // if($pagoCred->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoCred->Vueltos);
                        // }
                        $cajas->SumaTotalPuntoVueltosFinalCredito = $cajas->SumaTotalPuntoVueltosFinalCredito + ($pagoCred->MontoDivisa);
                        $cajas->SumaTotalPuntoVueltosFinalDolarCredito = $cajas->SumaTotalPuntoVueltosFinalDolarCredito + ($pagoCred->MontoDolar);

                }elseif ($pagoCred->Divisa == 'Transferencia') {
                    if($pagoCred->tipo_vuelto == 'Vueltos_Pago'){
                            // $cajas->SumaTotalTransferenciaVueltosFinal = $cajas->SumaTotalTransferenciaVueltosFinal + ($pagoCred->MontoDivisa);
                        }
                        // if($pagoCred->tipo_vuelto == 'Vueltos_Excedentes'){
                        //     $cajas->SumaTotalDolarVueltosFinal = $cajas->SumaTotalDolarVueltosFinal + ($pagoCred->Vueltos);
                        // }
                    $cajas->SumaTotalTransferenciaVueltosFinalCredito = $cajas->SumaTotalTransferenciaVueltosFinalCredito + ($pagoCred->MontoDivisa);
                    $cajas->SumaTotalTransferenciaVueltosFinalDolarCredito = $cajas->SumaTotalTransferenciaVueltosFinalCredito + ($pagoCred->MontoDolar);

                // }


            }

            $cajas->TotalSumaTotalVueltosFinalCredito = $cajas->TotalSumaTotalVueltosFinalCredito + ($pagoCred->MontoDolar);
            }
        }
// return $cajas->pagos_vueltos_extra;
        foreach ($cajas->excedente_actual_valor as $excSerTotal ) {

            if($excSerTotal->Tipo == 'Servicio' && $excSerTotal->Estado == 'Pendiente'){
                if ($excSerTotal->Divisa == 'Dolar') {

                    $cajas->SumaTotalDolarExcedenteFinal = $cajas->SumaTotalDolarExcedenteFinal + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalDolarExcedenteFinalDolar = $cajas->SumaTotalDolarExcedenteFinalDolar + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Peso') {

                    $cajas->SumaTotalPesoExcedenteFinal = $cajas->SumaTotalPesoExcedenteFinal + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalPesoExcedenteFinalDolar = $cajas->SumaTotalPesoExcedenteFinalDolar + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Bolivar') {

                    $cajas->SumaTotalBolivarExcedenteFinal = $cajas->SumaTotalBolivarExcedenteFinal + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalBolivarExcedenteFinalDolar = $cajas->SumaTotalBolivarExcedenteFinalDolar + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Punto') {

                    $cajas->SumaTotalPuntoExcedenteFinal = $cajas->SumaTotalPuntoExcedenteFinal + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalPuntoExcedenteFinalDolar = $cajas->SumaTotalPuntoExcedenteFinalDolar + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Transferencia') {

                    $cajas->SumaTotalTransferenciaExcedenteFinal = $cajas->SumaTotalTransferenciaExcedenteFinal + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalTransferenciaExcedenteFinalDolar = $cajas->SumaTotalTransferenciaExcedenteFinalDolar + ($excSerTotal->MontoDolar);

                }

                $cajas->TotalSumaTotalExcedenteFinal = $cajas->TotalSumaTotalExcedenteFinal + ($excSerTotal->MontoDolar);
            }

            if($excSerTotal->Tipo == 'Servicio' && $excSerTotal->Estado == 'PagarOficina'){
                if ($excSerTotal->Divisa == 'Dolar') {

                    $cajas->SumaTotalDolarPagarOficinaFinal = $cajas->SumaTotalDolarPagarOficinaFinal + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalDolarPagarOficinaFinalDolar = $cajas->SumaTotalDolarPagarOficinaFinalDolar + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Peso') {

                    $cajas->SumaTotalPesoPagarOficinaFinal = $cajas->SumaTotalPesoPagarOficinaFinal + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalPesoPagarOficinaFinalDolar = $cajas->SumaTotalPesoPagarOficinaFinalDolar + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Bolivar') {

                    $cajas->SumaTotalBolivarPagarOficinaFinal = $cajas->SumaTotalBolivarPagarOficinaFinal + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalBolivarPagarOficinaFinalDolar = $cajas->SumaTotalBolivarPagarOficinaFinalDolar + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Punto') {

                    $cajas->SumaTotalPuntoPagarOficinaFinal = $cajas->SumaTotalPuntoPagarOficinaFinal + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalPuntoPagarOficinaFinalDolar = $cajas->SumaTotalPuntoPagarOficinaFinalDolar + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Transferencia') {

                    $cajas->SumaTotalTransferenciaPagarOficinaFinal = $cajas->SumaTotalTransferenciaPagarOficinaFinal + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalTransferenciaPagarOficinaFinalDolar = $cajas->SumaTotalTransferenciaPagarOficinaFinalDolar + ($excSerTotal->MontoDolar);

                }

                $cajas->TotalSumaTotalPagarOficinaFinal = $cajas->TotalSumaTotalPagarOficinaFinal + ($excSerTotal->MontoDolar);
            }

            if($excSerTotal->Tipo == 'Consumo' && $excSerTotal->Estado == 'Pendiente'){
                if ($excSerTotal->Divisa == 'Dolar') {

                    $cajas->SumaTotalDolarExcedenteFinalConsumo = $cajas->SumaTotalDolarExcedenteFinalConsumo + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalDolarExcedenteFinalDolarConsumo = $cajas->SumaTotalDolarExcedenteFinalDolarConsumo + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Peso') {

                    $cajas->SumaTotalPesoExcedenteFinalConsumo = $cajas->SumaTotalPesoExcedenteFinalConsumo + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalPesoExcedenteFinalDolarConsumo = $cajas->SumaTotalPesoExcedenteFinalDolarConsumo + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Bolivar') {

                    $cajas->SumaTotalBolivarExcedenteFinalConsumo = $cajas->SumaTotalBolivarExcedenteFinalConsumo + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalBolivarExcedenteFinalDolarConsumo = $cajas->SumaTotalBolivarExcedenteFinalDolarConsumo + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Punto') {

                    $cajas->SumaTotalPuntoExcedenteFinalConsumo = $cajas->SumaTotalPuntoExcedenteFinalConsumo + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalPuntoExcedenteFinalDolarConsumo = $cajas->SumaTotalPuntoExcedenteFinalDolarConsumo + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Transferencia') {

                    $cajas->SumaTotalTransferenciaExcedenteFinalConsumo = $cajas->SumaTotalTransferenciaExcedenteFinalConsumo + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalTransferenciaExcedenteFinalDolarConsumo = $cajas->SumaTotalTransferenciaExcedenteFinalDolarConsumo + ($excSerTotal->MontoDolar);

                }

                $cajas->TotalSumaTotalExcedenteFinalConsumo = $cajas->TotalSumaTotalExcedenteFinalConsumo + ($excSerTotal->MontoDolar);
            }

            if($excSerTotal->Tipo == 'Consumo' && $excSerTotal->Estado == 'PagarOficina'){
                if ($excSerTotal->Divisa == 'Dolar') {

                    $cajas->SumaTotalDolarPagarOficinaFinalConsumo = $cajas->SumaTotalDolarPagarOficinaFinalConsumo + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalDolarPagarOficinaFinalDolarConsumo = $cajas->SumaTotalDolarPagarOficinaFinalDolarConsumo + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Peso') {

                    $cajas->SumaTotalPesoPagarOficinaFinalConsumo = $cajas->SumaTotalPesoPagarOficinaFinalConsumo + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalPesoPagarOficinaFinalDolarConsumo = $cajas->SumaTotalPesoPagarOficinaFinalDolarConsumo + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Bolivar') {

                    $cajas->SumaTotalBolivarPagarOficinaFinalConsumo = $cajas->SumaTotalBolivarPagarOficinaFinalConsumo + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalBolivarPagarOficinaFinalDolarConsumo = $cajas->SumaTotalBolivarPagarOficinaFinalDolarConsumo + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Punto') {

                    $cajas->SumaTotalPuntoPagarOficinaFinalConsumo = $cajas->SumaTotalPuntoPagarOficinaFinalConsumo + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalPuntoPagarOficinaFinalDolarConsumo = $cajas->SumaTotalPuntoPagarOficinaFinalDolarConsumo + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Transferencia') {

                    $cajas->SumaTotalTransferenciaPagarOficinaFinalConsumo = $cajas->SumaTotalTransferenciaPagarOficinaFinalConsumo + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalTransferenciaPagarOficinaFinalDolarConsumo = $cajas->SumaTotalTransferenciaPagarOficinaFinalDolarConsumo + ($excSerTotal->MontoDolar);

                }

                $cajas->TotalSumaTotalPagarOficinaFinalConsumo = $cajas->TotalSumaTotalPagarOficinaFinalConsumo + ($excSerTotal->MontoDolar);
            }


            if($excSerTotal->Tipo == 'Horas_Extras' && $excSerTotal->Estado == 'Pendiente'){
                if ($excSerTotal->Divisa == 'Dolar') {

                    $cajas->SumaTotalDolarExcedenteFinalHorasExtras = $cajas->SumaTotalDolarExcedenteFinalHorasExtras + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalDolarExcedenteFinalDolarHorasExtras = $cajas->SumaTotalDolarExcedenteFinalDolarHorasExtras + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Peso') {

                    $cajas->SumaTotalPesoExcedenteFinalHorasExtras = $cajas->SumaTotalPesoExcedenteFinalHorasExtras + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalPesoExcedenteFinalDolarHorasExtras = $cajas->SumaTotalPesoExcedenteFinalDolarHorasExtras + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Bolivar') {

                    $cajas->SumaTotalBolivarExcedenteFinalHorasExtras = $cajas->SumaTotalBolivarExcedenteFinalHorasExtras + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalBolivarExcedenteFinalDolarHorasExtras = $cajas->SumaTotalBolivarExcedenteFinalDolarHorasExtras + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Punto') {

                    $cajas->SumaTotalPuntoExcedenteFinalHorasExtras = $cajas->SumaTotalPuntoExcedenteFinalHorasExtras + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalPuntoExcedenteFinalDolarHorasExtras = $cajas->SumaTotalPuntoExcedenteFinalDolarHorasExtras + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Transferencia') {

                    $cajas->SumaTotalTransferenciaExcedenteFinalHorasExtras = $cajas->SumaTotalTransferenciaExcedenteFinalHorasExtras + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalTransferenciaExcedenteFinalDolarHorasExtras = $cajas->SumaTotalTransferenciaExcedenteFinalDolarHorasExtras + ($excSerTotal->MontoDolar);

                }

                $cajas->TotalSumaTotalExcedenteFinalHorasExtras = $cajas->TotalSumaTotalExcedenteFinalHorasExtras + ($excSerTotal->MontoDolar);
            }

            if($excSerTotal->Tipo == 'Horas_Extras' && $excSerTotal->Estado == 'PagarOficina'){
                if ($excSerTotal->Divisa == 'Dolar') {

                    $cajas->SumaTotalDolarPagarOficinaFinalHorasExtras = $cajas->SumaTotalDolarPagarOficinaFinalHorasExtras + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalDolarPagarOficinaFinalDolarHorasExtras = $cajas->SumaTotalDolarPagarOficinaFinalDolarHorasExtras + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Peso') {

                    $cajas->SumaTotalPesoPagarOficinaFinalHorasExtras = $cajas->SumaTotalPesoPagarOficinaFinalHorasExtras + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalPesoPagarOficinaFinalDolarHorasExtras = $cajas->SumaTotalPesoPagarOficinaFinalDolarHorasExtras + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Bolivar') {

                    $cajas->SumaTotalBolivarPagarOficinaFinalHorasExtras = $cajas->SumaTotalBolivarPagarOficinaFinalHorasExtras + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalBolivarPagarOficinaFinalDolarHorasExtras = $cajas->SumaTotalBolivarPagarOficinaFinalDolarHorasExtras + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Punto') {

                    $cajas->SumaTotalPuntoPagarOficinaFinalHorasExtras = $cajas->SumaTotalPuntoPagarOficinaFinalHorasExtras + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalPuntoPagarOficinaFinalDolarHorasExtras = $cajas->SumaTotalPuntoPagarOficinaFinalDolarHorasExtras + ($excSerTotal->MontoDolar);

                }elseif ($excSerTotal->Divisa == 'Transferencia') {

                    $cajas->SumaTotalTransferenciaPagarOficinaFinalHorasExtras = $cajas->SumaTotalTransferenciaPagarOficinaFinalHorasExtras + ($excSerTotal->MontoDivisa);
                    $cajas->SumaTotalTransferenciaPagarOficinaFinalDolarHorasExtras = $cajas->SumaTotalTransferenciaPagarOficinaFinalDolarHorasExtras + ($excSerTotal->MontoDolar);

                }

                $cajas->TotalSumaTotalPagarOficinaFinalHorasExtras = $cajas->TotalSumaTotalPagarOficinaFinalHorasExtras + ($excSerTotal->MontoDolar);
            }


        }
        // return $cajas->TotalSumaTotalPagarOficinaFinal;
        // TODO pagos servicios
$ojo = [];
$ojop = [];
$ojot = [];

        $sumaPagoServicios = Pago_Servicio::where('caja_id', $id)->get();
        // return $sumaPagoServicios;

        foreach ($sumaPagoServicios as $pagoS ) {


            // $validarPagosServicios = Servicio::where('id',$pagoS->servicio_id)->first();
            if ($pagoS) {
                if ($pagoS->Divisa == 'Dolar') {

                        // $ojo[] = ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - $validarPagosServicios->excedente_nuevo;
                        $cajas->SumaTotalDolarServDflotante = $cajas->SumaTotalDolarServDflotante + ($pagoS->Vueltos);
                        $cajas->SumaTotalDolarServ = $cajas->SumaTotalDolarServ + ($pagoS->MontoDolarServicio * $tasaDolar->tasa);
                        $cajas->SumaTotalDolarServFinal = $cajas->SumaTotalDolarServFinal + ($pagoS->MontoDivisa);
                        $cajas->SumaTotalDolarServFinalDolar = $cajas->SumaTotalDolarServFinalDolar + ($pagoS->MontoDolar);


                }elseif ($pagoS->Divisa == 'Peso') {



                        // $ojop[] = ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaPeso->tasa);
                        $cajas->SumaTotalPesoServDflotante = $cajas->SumaTotalPesoServDflotante + ( $pagoS->Vueltos * $tasaPeso->tasa);
                        $cajas->SumaTotalPesoServ = $cajas->SumaTotalPesoServ + ($pagoS->MontoDolarServicio * $tasaPeso->tasa);
                        $cajas->SumaTotalPesoServFinal = $cajas->SumaTotalPesoServFinal + ($pagoS->MontoDivisa);
                        $cajas->SumaTotalPesoServFinalDolar = $cajas->SumaTotalPesoServFinalDolar + ($pagoS->MontoDolar);

                }elseif ($pagoS->Divisa == 'Bolivar') {

                        $cajas->SumaTotalBolivarServDflotante = $cajas->SumaTotalBolivarServDflotante + ($pagoS->Vueltos * $tasaEfectivo->tasa);
                        $cajas->SumaTotalBolivarServ = $cajas->SumaTotalBolivarServ + ($pagoS->MontoDolarServicio * $tasaEfectivo->tasa);
                        $cajas->SumaTotalBolivarServFinal = $cajas->SumaTotalBolivarServFinal + ($pagoS->MontoDivisa);
                        $cajas->SumaTotalBolivarServFinalDolar = $cajas->SumaTotalBolivarServFinalDolar + ($pagoS->MontoDolar);

                }elseif ($pagoS->Divisa == 'Punto') {

                        $cajas->SumaTotalPuntoServDflotante = $cajas->SumaTotalPuntoServDflotante + ($pagoS->Vueltos * $tasaTransferenciaPunto->tasa);
                        $cajas->SumaTotalPuntoServ = $cajas->SumaTotalPuntoServ + ($pagoS->MontoDolarServicio * $tasaTransferenciaPunto->tasa);
                        $cajas->SumaTotalPuntoServFinal = $cajas->SumaTotalPuntoServFinal + ($pagoS->MontoDivisa);
                        $cajas->SumaTotalPuntoServFinalDolar = $cajas->SumaTotalPuntoServFinalDolar + ($pagoS->MontoDolar);

                }elseif ($pagoS->Divisa == 'Transferencia') {

                        // $ojot[] = ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                        $cajas->SumaTotalTransferenciaServDflotante = $cajas->SumaTotalTransferenciaServDflotante + ($pagoS->Vueltos * $tasaTransferenciaPunto->tasa);
                        $cajas->SumaTotalTransferenciaServ = $cajas->SumaTotalTransferenciaServ + ($pagoS->MontoDolarServicio * $tasaTransferenciaPunto->tasa);
                        $cajas->SumaTotalTransferenciaServFinal = $cajas->SumaTotalTransferenciaServFinal + ($pagoS->MontoDivisa);
                        $cajas->SumaTotalTransferenciaServFinalDolar = $cajas->SumaTotalTransferenciaServFinalDolar + ($pagoS->MontoDolar);

                }

                $cajas->TotalSumaTotalServDflotante = $cajas->TotalSumaTotalServDflotante + ($pagoS->Vueltos);
                $cajas->TotalSumaTotalServFinal = $cajas->TotalSumaTotalServFinal + ($pagoS->MontoDolar);

            }



        }
// return $cajas->pago_credito;
        // return $cajas->SumaTotalPesoPagarOficinaFinal;
        foreach ($cajas->pago_credito as $pagoCreditos ) {
            // return $cajas->pago_credito;
            // $validarPagosHorasExtras = Horas_extra::where('id',$pagoVeX->horas_extra_id)->first();
            // return $validarPagosHorasExtras;
            // if ($pagoCreditos) {

            //     if ($pagoCreditos->Divisa == 'Dolar') {
            //         if($pagoCreditos->Vueltos > 0){

            //             $cajas->SumaTotalDolarCredito = $cajas->SumaTotalDolarCredito + ($pagoCreditos->MontoDivisa - $pagoCreditos->Vueltos * -1);
            //         // return $cajas->SumaTotalDolarCredito;
            //         }else{
            //             $cajas->SumaTotalDolarCreditoDflotante = $cajas->SumaTotalDolarCreditoDflotante + ($pagoCreditos->Vueltos * -1);
            //             $cajas->SumaTotalDolarCredito = $cajas->SumaTotalDolarCredito + ($pagoCreditos->MontoDivisa - $pagoCreditos->Vueltos * -1);
            //         }
            //     }elseif ($pagoCreditos->Divisa == 'Peso') {
            //         if($pagoCreditos->Vueltos > 0){
            //             $cajas->SumaTotalPesoCredito = $cajas->SumaTotalPesoCredito + ($pagoCreditos->MontoDivisa - $pagoCreditos->Vueltos * -1);
            //         }else{
            //             $cajas->SumaTotalPesoCreditoDflotante = $cajas->SumaTotalPesoCreditoDflotante + ( $pagoCreditos->Vueltos * -1);
            //             $cajas->SumaTotalPesoCredito = $cajas->SumaTotalPesoCredito + ($pagoCreditos->MontoDivisa - $pagoCreditos->Vueltos * -1);
            //         }
            //     }elseif ($pagoCreditos->Divisa == 'Bolivar') {
            //         if($pagoCreditos->Vueltos > 0){
            //             $cajas->SumaTotalBolivarCredito = $cajas->SumaTotalBolivarCredito + ($pagoCreditos->MontoDivisa - $pagoCreditos->Vueltos * -1);
            //         }else{
            //             $cajas->SumaTotalBolivarCreditoDflotante = $cajas->SumaTotalBolivarCreditoDflotante + ($pagoCreditos->Vueltos * -1) * $tasaEfectivo->tasa;
            //             $cajas->SumaTotalBolivarCredito = $cajas->SumaTotalBolivarCredito + ($pagoCreditos->MontoDivisa - $pagoCreditos->Vueltos * -1);
            //         }
            //     }elseif ($pagoCreditos->Divisa == 'Punto') {
            //         if($pagoCreditos->Vueltos > 0){
            //             $cajas->SumaTotalPuntoCredito = $cajas->SumaTotalPuntoCredito + ($pagoCreditos->MontoDivisa - $pagoCreditos->Vueltos * -1);
            //         }else{
            //             $cajas->SumaTotalPuntoCreditoDflotante = $cajas->SumaTotalPuntoCreditoDflotante + ($pagoCreditos->Vueltos * -1) * $tasaTransferenciaPunto->tasa;
            //             $cajas->SumaTotalPuntoCredito = $cajas->SumaTotalPuntoCredito + ($pagoCreditos->MontoDivisa - $pagoCreditos->Vueltos * -1);
            //         }
            //     }elseif ($pagoCreditos->Divisa == 'Transferencia') {
            //         if($pagoCreditos->Vueltos > 0){
            //             $cajas->SumaTotalTransferenciaCredito = $cajas->SumaTotalTransferenciaCredito + ($pagoCreditos->MontoDivisa - $pagoCreditos->Vueltos * -1);
            //         }else{
            //             $cajas->SumaTotalTransferenciaCreditoDflotante = $cajas->SumaTotalTransferenciaCreditoDflotante + ($pagoCreditos->Vueltos * -1) * $tasaTransferenciaPunto->tasa;
            //             $cajas->SumaTotalTransferenciaCredito = $cajas->SumaTotalTransferenciaCredito + ($pagoCreditos->MontoDivisa - $pagoCreditos->Vueltos * -1);
            //         }
            //     }
            //     $cajas->SumaTotalCredito = $cajas->SumaTotalCredito + ($pagoCreditos->MontoDolar - $pagoCreditos->Vueltos * -1);
            //     $cajas->TotalSumaTotalCreditoDflotante = $cajas->TotalSumaTotalCreditoDflotante + ($pagoCreditos->Vueltos * -1);
            // }

            if ($pagoCreditos) {
                if ($pagoCreditos->Divisa == 'Dolar') {

                        // $ojo[] = ($pagoCreditos->MontoDivisa - $pagoCreditos->Vueltos * -1) - $validarpagoCreditosServicios->excedente_nuevo;
                        // $cajas->SumaTotalDolarCreditoDflotante = $cajas->SumaTotalDolarCreditoDflotante + ($pagoCreditos->Vueltos);
                        // $cajas->SumaTotalDolarCredito = $cajas->SumaTotalDolarCredito + ($pagoCreditos->MontoConsumo * $tasaDolar->tasa) + ($pagoCreditos->MontoServeicio * $tasaDolar->tasa);
                        // $cajas->SumaTotalDolarCreditoFinal = $cajas->SumaTotalDolarCreditoFinal + ($pagoCreditos->MontoDivisa);
                        // $cajas->SumaTotalDolarCreditoFinalDolar = $cajas->SumaTotalDolarCreditoFinalDolar + ($pagoCreditos->MontoDolar);

                }elseif ($pagoCreditos->Divisa == 'Peso') {



                        // $ojop[] = ($pagoCreditos->MontoDivisa - $pagoCreditos->Vueltos * -1) - ($validarpagoCreditosCreditouicios->excedente_nuevo * $tasaPeso->tasa);
                        // $cajas->SumaTotalPesoCreditoDflotante = $cajas->SumaTotalPesoCreditoDflotante + ( $pagoCreditos->Vueltos * $tasaPeso->tasa);
                        // $cajas->SumaTotalPesoCredito = $cajas->SumaTotalPesoCredito + ($pagoCreditos->MontoConsumo * $tasaPeso->tasa) + ($pagoCreditos->MontoServeicio * $tasaPeso->tasa);
                        // $cajas->SumaTotalPesoCreditoFinal = $cajas->SumaTotalPesoCreditoFinal + ($pagoCreditos->MontoDivisa);
                        // $cajas->SumaTotalPesoCreditoFinalDolar = $cajas->SumaTotalPesoCreditoFinalDolar + ($pagoCreditos->MontoDolar);

                }elseif ($pagoCreditos->Divisa == 'Bolivar') {

                        // $cajas->SumaTotalBolivarCreditoDflotante = $cajas->SumaTotalBolivarCreditoDflotante + ($pagoCreditos->Vueltos * $tasaEfectivo->tasa);
                        // $cajas->SumaTotalBolivarCredito = $cajas->SumaTotalBolivarCredito + ($pagoCreditos->MontoConsumo * $tasaEfectivo->tasa) + ($pagoCreditos->MontoServeicio * $tasaEfectivo->tasa);
                        // $cajas->SumaTotalBolivarCreditoFinal = $cajas->SumaTotalBolivarCreditoFinal + ($pagoCreditos->MontoDivisa);
                        // $cajas->SumaTotalBolivarCreditoFinalDolar = $cajas->SumaTotalBolivarCreditoFinalDolar + ($pagoCreditos->MontoDolar);

                }elseif ($pagoCreditos->Divisa == 'Punto') {

                        // $cajas->SumaTotalPuntoCreditoDflotante = $cajas->SumaTotalPuntoCreditoDflotante + ($pagoCreditos->Vueltos * $tasaTransferenciaPunto->tasa);
                        // $cajas->SumaTotalPuntoCredito = $cajas->SumaTotalPuntoCredito + ($pagoCreditos->MontoConsumo * $tasaTransferenciaPunto->tasa) + ($pagoCreditos->MontoServeicio * $tasaTransferenciaPunto->tasa);
                        // $cajas->SumaTotalPuntoCreditoFinal = $cajas->SumaTotalPuntoCreditoFinal + ($pagoCreditos->MontoDivisa);
                        // $cajas->SumaTotalPuntoCreditoFinalDolar = $cajas->SumaTotalPuntoCreditoFinalDolar + ($pagoCreditos->MontoDolar);

                }elseif ($pagoCreditos->Divisa == 'Transferencia') {

                        // $ojot[] = ($pagoCreditos->MontoDivisa - $pagoCreditos->Vueltos * -1) - ($validarpagoCreditosCreditouicios->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                        // $cajas->SumaTotalTransferenciaCreditoDflotante = $cajas->SumaTotalTransferenciaCreditoDflotante + ($pagoCreditos->Vueltos * $tasaTransferenciaPunto->tasa);
                        // $cajas->SumaTotalTransferenciaCredito = $cajas->SumaTotalTransferenciaCredito + ($pagoCreditos->MontoConsumo * $tasaTransferenciaPunto->tasa) + ($pagoCreditos->MontoServeicio * $tasaTransferenciaPunto->tasa);
                        // $cajas->SumaTotalTransferenciaCreditoFinal = $cajas->SumaTotalTransferenciaCreditoFinal + ($pagoCreditos->MontoDivisa);
                        // $cajas->SumaTotalTransferenciaCreditoFinalDolar = $cajas->SumaTotalTransferenciaCreditoFinalDolar + ($pagoCreditos->MontoDolar);

                }

                // $cajas->TotalSumaTotalCreditoDflotante = $cajas->TotalSumaTotalCreditoDflotante + ($pagoCreditos->Vueltos);
                // $cajas->TotalSumaTotalCreditoFinal = $cajas->TotalSumaTotalCreditoFinal + ($pagoCreditos->MontoDolar);

            }



        }
// return $cajas->SumaTotalDolarExtra;
        // foreach ($cajas->horas_extras as $creditosHorasExtras) {
        //     if($creditosHorasExtras->modo_pago == 'Credito' && $creditosHorasExtras->status == 'Falta pagar'){
        //         $cajas->SumaTotalHorasExtrasPorPagar = $cajas->SumaTotalHorasExtrasPorPagar + $creditosHorasExtras->total_horas_extras_otros_montos;
        //         $cajas->SumaTotalCantidadHorasExtrasPorPagar = $cajas->SumaTotalCantidadHorasExtrasPorPagar + 1;
        //     }

        //     if($creditosHorasExtras->modo_pago == 'Cortesia' && $creditosHorasExtras->status == 'Exonerado'){
        //         $cajas->SumaTotalHorasExtrasCortesia = $cajas->SumaTotalHorasExtrasCortesia + $creditosHorasExtras->total_horas_extras_otros_montos;
        //         $cajas->SumaTotalCantidadHorasExtrasCortesia = $cajas->SumaTotalCantidadHorasExtrasCortesia + 1;
        //     }
        // }



// return $cajas->SumaTotalPesoServDflotante;
        // TODO captuaramos en variables los montos pagados en el proseso de pagos extras de la tabla horas extras


        foreach ($cajas->pago_extras as $pagoVeX ) {
            // return $cajas->pago_extras;
            $validarPagosHorasExtras = Horas_extra::where('id',$pagoVeX->horas_extra_id)->first();
            // return $validarPagosHorasExtras;
            // if ($validarPagosHorasExtras) {

            //     if ($pagoVeX->Divisa == 'Dolar') {
            //         if($pagoVeX->Vueltos > 0){

            //             $cajas->SumaTotalDolarExtra = $cajas->SumaTotalDolarExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1);
            //         // return $cajas->SumaTotalDolarExtra;
            //         }else{
            //             $cajas->SumaTotalDolarExtraDflotante = $cajas->SumaTotalDolarExtraDflotante + ($pagoVeX->Vueltos * -1);
            //             $cajas->SumaTotalDolarExtra = $cajas->SumaTotalDolarExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1);
            //         }
            //     }elseif ($pagoVeX->Divisa == 'Peso') {
            //         if($pagoVeX->Vueltos > 0){
            //             $cajas->SumaTotalPesoExtra = $cajas->SumaTotalPesoExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1);
            //         }else{
            //             $cajas->SumaTotalPesoExtraDflotante = $cajas->SumaTotalPesoExtraDflotante + ( $pagoVeX->Vueltos * -1);
            //             $cajas->SumaTotalPesoExtra = $cajas->SumaTotalPesoExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1);
            //         }
            //     }elseif ($pagoVeX->Divisa == 'Bolivar') {
            //         if($pagoVeX->Vueltos > 0){
            //             $cajas->SumaTotalBolivarExtra = $cajas->SumaTotalBolivarExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1);
            //         }else{
            //             $cajas->SumaTotalBolivarExtraDflotante = $cajas->SumaTotalBolivarExtraDflotante + ($pagoVeX->Vueltos * -1) * $tasaEfectivo->tasa;
            //             $cajas->SumaTotalBolivarExtra = $cajas->SumaTotalBolivarExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1);
            //         }
            //     }elseif ($pagoVeX->Divisa == 'Punto') {
            //         if($pagoVeX->Vueltos > 0){
            //             $cajas->SumaTotalPuntoExtra = $cajas->SumaTotalPuntoExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1);
            //         }else{
            //             $cajas->SumaTotalPuntoExtraDflotante = $cajas->SumaTotalPuntoExtraDflotante + ($pagoVeX->Vueltos * -1) * $tasaTransferenciaPunto->tasa;
            //             $cajas->SumaTotalPuntoExtra = $cajas->SumaTotalPuntoExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1);
            //         }
            //     }elseif ($pagoVeX->Divisa == 'Transferencia') {
            //         if($pagoVeX->Vueltos > 0){
            //             $cajas->SumaTotalTransferenciaExtra = $cajas->SumaTotalTransferenciaExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1);
            //         }else{
            //             $cajas->SumaTotalTransferenciaExtraDflotante = $cajas->SumaTotalTransferenciaExtraDflotante + ($pagoVeX->Vueltos * -1) * $tasaTransferenciaPunto->tasa;
            //             $cajas->SumaTotalTransferenciaExtra = $cajas->SumaTotalTransferenciaExtra + ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1);
            //         }
            //     }
            //     $cajas->SumaTotalExtra = $cajas->SumaTotalExtra + ($pagoVeX->MontoDolar - $pagoVeX->Vueltos * -1);
            //     $cajas->TotalSumaTotalExtraDflotante = $cajas->TotalSumaTotalExtraDflotante + ($pagoVeX->Vueltos * -1);
            // }

            if ($validarPagosHorasExtras) {
                if ($pagoVeX->Divisa == 'Dolar') {

                        // $ojo[] = ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1) - $validarpagoVeXServicios->excedente_nuevo;
                        $cajas->SumaTotalDolarHorasExtrasDflotante = $cajas->SumaTotalDolarHorasExtrasDflotante + ($pagoVeX->Vueltos);
                        $cajas->SumaTotalDolarHorasExtras = $cajas->SumaTotalDolarHorasExtras + ($pagoVeX->MontoDolarHorasExtras * $tasaDolar->tasa);
                        $cajas->SumaTotalDolarHorasExtrasFinal = $cajas->SumaTotalDolarHorasExtrasFinal + ($pagoVeX->MontoDivisa);
                        $cajas->SumaTotalDolarHorasExtrasFinalDolar = $cajas->SumaTotalDolarHorasExtrasFinalDolar + ($pagoVeX->MontoDolar);

                }elseif ($pagoVeX->Divisa == 'Peso') {



                        // $ojop[] = ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1) - ($validarpagoVeXHorasExtrasuicios->excedente_nuevo * $tasaPeso->tasa);
                        $cajas->SumaTotalPesoHorasExtrasDflotante = $cajas->SumaTotalPesoHorasExtrasDflotante + ( $pagoVeX->Vueltos * $tasaPeso->tasa);
                        $cajas->SumaTotalPesoHorasExtras = $cajas->SumaTotalPesoHorasExtras + ($pagoVeX->MontoDolarHorasExtras * $tasaPeso->tasa);
                        $cajas->SumaTotalPesoHorasExtrasFinal = $cajas->SumaTotalPesoHorasExtrasFinal + ($pagoVeX->MontoDivisa);
                        $cajas->SumaTotalPesoHorasExtrasFinalDolar = $cajas->SumaTotalPesoHorasExtrasFinalDolar + ($pagoVeX->MontoDolar);

                }elseif ($pagoVeX->Divisa == 'Bolivar') {

                        $cajas->SumaTotalBolivarHorasExtrasDflotante = $cajas->SumaTotalBolivarHorasExtrasDflotante + ($pagoVeX->Vueltos * $tasaEfectivo->tasa);
                        $cajas->SumaTotalBolivarHorasExtras = $cajas->SumaTotalBolivarHorasExtras + ($pagoVeX->MontoDolarHorasExtras * $tasaEfectivo->tasa);
                        $cajas->SumaTotalBolivarHorasExtrasFinal = $cajas->SumaTotalBolivarHorasExtrasFinal + ($pagoVeX->MontoDivisa);
                        $cajas->SumaTotalBolivarHorasExtrasFinalDolar = $cajas->SumaTotalBolivarHorasExtrasFinalDolar + ($pagoVeX->MontoDolar);

                }elseif ($pagoVeX->Divisa == 'Punto') {

                        $cajas->SumaTotalPuntoHorasExtrasDflotante = $cajas->SumaTotalPuntoHorasExtrasDflotante + ($pagoVeX->Vueltos * $tasaTransferenciaPunto->tasa);
                        $cajas->SumaTotalPuntoHorasExtras = $cajas->SumaTotalPuntoHorasExtras + ($pagoVeX->MontoDolarHorasExtras * $tasaTransferenciaPunto->tasa);
                        $cajas->SumaTotalPuntoHorasExtrasFinal = $cajas->SumaTotalPuntoHorasExtrasFinal + ($pagoVeX->MontoDivisa);
                        $cajas->SumaTotalPuntoHorasExtrasFinalDolar = $cajas->SumaTotalPuntoHorasExtrasFinalDolar + ($pagoVeX->MontoDolar);

                }elseif ($pagoVeX->Divisa == 'Transferencia') {

                        // $ojot[] = ($pagoVeX->MontoDivisa - $pagoVeX->Vueltos * -1) - ($validarpagoVeXHorasExtrasuicios->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                        $cajas->SumaTotalTransferenciaHorasExtrasDflotante = $cajas->SumaTotalTransferenciaHorasExtrasDflotante + ($pagoVeX->Vueltos * $tasaTransferenciaPunto->tasa);
                        $cajas->SumaTotalTransferenciaHorasExtras = $cajas->SumaTotalTransferenciaHorasExtras + ($pagoVeX->MontoDolarHorasExtras * $tasaTransferenciaPunto->tasa);
                        $cajas->SumaTotalTransferenciaHorasExtrasFinal = $cajas->SumaTotalTransferenciaHorasExtrasFinal + ($pagoVeX->MontoDivisa);
                        $cajas->SumaTotalTransferenciaHorasExtrasFinalDolar = $cajas->SumaTotalTransferenciaHorasExtrasFinalDolar + ($pagoVeX->MontoDolar);

                }

                $cajas->TotalSumaTotalHorasExtrasDflotante = $cajas->TotalSumaTotalHorasExtrasDflotante + ($pagoVeX->Vueltos);
                $cajas->TotalSumaTotalHorasExtrasFinal = $cajas->TotalSumaTotalHorasExtrasFinal + ($pagoVeX->MontoDolar);

            }



        }
// return $cajas->pago_extras;
        foreach ($cajas->horas_extras as $creditosHorasExtras) {
            if($creditosHorasExtras->modo_pago == 'Contado' && $creditosHorasExtras->status == 'Pagado'){
                $cajas->SumaTotalExtra = $cajas->SumaTotalExtra + $creditosHorasExtras->total_horas_extras_otros_montos;
                $cajas->SumaTotalCantidadHorasExtrasCortesia = $cajas->SumaTotalCantidadHorasExtrasContado + 1;
            }

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
        // return $cajas->horas_extras;
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
                    if($serv->is_cambio == 'No'){
                        $cajas->SumaTotalCantidadServicios = $cajas->SumaTotalCantidadServicios + 1;
                    }
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
            $appDate = Empresa::get();
                //  return $cajas;
                $verificarHorasExtras = Horas_extra::where('caja_id',$cajas->id)->get();
                // return $verificarHorasExtras;
        return view('cajas.caja.show', compact('appDate','verificarHorasExtras','tasaDolarHabitacion','tasaPesoHabitacion','tasaDolar', 'tasaPeso','tasaTransferenciaPunto','tasaMixto','tasaEfectivo','title','cajas', 'caja','denominacion_dolar', 'denominacion_peso' ,'denominacion_bolivar'))->with($mensaje);
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
                //Guardamos registros en la tabla historialcreditoscaja

                $historialCreditosCaja = new HistorialCreditoCaja();
                $historialCreditosCaja->hist_creditos_vigentes = $request->get('hist_creditos_vigentes');
                $historialCreditosCaja->hist_creditos_vencidos = $request->get('hist_creditos_vencidos');
                $historialCreditosCaja->hist_creditos_pagados = $request->get('hist_creditos_pagados');
                $historialCreditosCaja->hist_creditos_nuevos = $request->get('hist_creditos_nuevos');
                $historialCreditosCaja->hist_total_creditos = $request->get('hist_total_creditos');
                $historialCreditosCaja->user_id = $request->get('idusuario');
                $historialCreditosCaja->caja_id = $caja_id;
                $historialCreditosCaja->save();

                 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //when we open the box we inital the stock



                $controlStock = ControlStock::where('caja_id', $caja_id)->get();

                if($controlStock){
                    $stocks = self::get_product_stock(['id','stock','nombre']);
                    if($stocks){
                    foreach ($stocks as $value) {
                        $control_stock = ControlStock::where('articulo_id',$value->id)->where('caja_id',$caja_id)->first();
                        if($control_stock){
                            $control_stock->stock_cierre = $value->stock;
                            $control_stock->stock_dif  = $control_stock->stock_cierre - $value->stock;
                            $control_stock->stock_cierre_operador  = $request->get('stock_cierre_operador');
                            $control_stock->observaciones  = $request->get('observacionesStock');
                            $control_stock->update();
                        }else{
                            $control_stock = new ControlStock();
                            $control_stock->stock_inicio = 0;
                            $control_stock->stock_cierre = $value->stock;
                            $control_stock->user_id  = $idUsuario;
                            $control_stock->articulo_id  = $value->id;
                            $control_stock->caja_id  = $Caja->id;
                            $control_stock->save();
                        }

                    }

                }
                }




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
