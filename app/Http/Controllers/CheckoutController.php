<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Tasa;
use App\User;
use App\Level;
use App\Precio;
use App\Horario;
use App\Persona;
use App\Servicio;
use App\Excedente;
use Carbon\Carbon;
use App\Habitacione;
use App\Horas_extra;
use App\Sessioncaja;
use App\Denominacion;
use App\Pago_Credito;
use App\Config_Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Excedentes_Recibidos_Caja_Actual;

class CheckoutController extends Controller
{

    // TODO nuevo comentario

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hola = 0;

        $title = 'Check Out';

        $levels = Level::orderBy('id','desc')->get();
        $horarios = Horario::get();
        $habitaciones = Habitacione::where('status','=','Ocupada')->get();
        $tasaDolarHabitacion = Tasa::where('nombre','=','DolarHabitacion')->first();
        // return $tasaDolarHabitacion->tasa;
        $tasaPesoHabitacion = Tasa::where('nombre','=','PesoHabitacion')->first();
        $users = User::with('roles')->orderBy('id','Desc')->get();
        $servicios = Servicio::where('status_servicio', 'Iniciado')->get();


// return $servicios;



        return view('checkout.index', compact('servicios','title','levels','habitaciones','horarios', 'tasaDolarHabitacion', 'tasaPesoHabitacion', 'users'));
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
        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return $id;
        $title = 'Salida';
        $servicio = Servicio::where('id', $id)->where('status_servicio', 'Iniciado')->first();
        // return $servicio;

        $horarios = Horario::where('tipo',$servicio->tipo_habitacion)->orwhere('tipo','24 HORAS')->get();
        $cliente = Persona::where('id',$servicio->persona_id)->first();
        $servicio;
        $servicio->servicios_ventas;
        // $servicio->horario;
        // $servicio->servicios_ventas[0]->articulo;
        // return $servicio;
        Sessioncaja::crearsession();
        $tasa = Tasa::find(1);
        $tasa->updated_at;
        $fechaActual = Carbon::now();
        // dd($tasa->updated_at->diffInHours($fechaActual));
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
        // return $request;
        $title = 'PROCESAR SALIDA HABITACIÓN';

        $horarios_id = Horario::where('nombre',$servicio->horario)->first();
        // return $horarios_id;
        $habitacionese = Habitacione::where('status','Disponible')->get();
        // $habitacionese =[];

        //creamos un contador
        $cont = 0;

        //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
        // while ($cont < count($habitacionese)) {
        //     // $precio_habitacion_cambio[$cont] = Precio::where('cat_id',$habitacionese[$cont]->cat->id)->first();
        //     // return $precio_habitacion_cambio[$cont];
        //     $habitacionese[$cont]->habitacion_id = $habitacionese[$cont]->id;
        //     $habitacionese[$cont]->nombre = $habitacionese[$cont]->nombre;
        //     $habitacionese[$cont]->categoria_id = $habitacionese[$cont]->cat->id;
        //     $habitacionese[$cont]->categoria = $habitacionese[$cont]->cat->nombre;
        //     $habitacionese[$cont]->categoria_desc = $habitacionese[$cont]->cat->descripcion;
        //     // $habitacionese[$cont]->precio_id = $precio_habitacion_cambio[$cont]->id;
        //     // $habitacionese[$cont]->precio = $precio_habitacion_cambio[$cont]->precio;

        //     $cont = $cont+1;
        // }
        // foreach ($habitacionese as $habitaciones) {

        //     $precio_habitacion_cambio = Precio::where('cat_id',$habitaciones->cat->id)->where('horario_id',$horarios_id->id)->first();
        //     // return $precio_habitacion_cambio->precio;

        //     $habitacionese[]->habitacion_id = $habitaciones->id;
        //     $habitacionese[]->nombre = $habitaciones->nombre;
        //     $habitacionese[]->categoria = $habitaciones->cat->nombre;
        //     $habitacionese[]->precio = $precio_habitacion_cambio->precio;


        // }
        // return $habitacionese;

        $users = User::with('roles')->orderBy('id','Desc')->get();
        // $denominacion_dolar = Denominacion::where('moneda', 'Dolar')->orderBy('id', 'desc')->get();
        // $levels = Level::orderBy('id','desc')->get();
        // $horario = Horario::where('id',$request->get('horario_id'))->first();
        // return $horario;
        // $clientes = Persona::get();
        // $precio = Precio::where('id',$request->get('precio_id'))->first();
        // return $horario;
        // $habitacion = Habitacione::where('id',$request->get('habitacion_id'))->first();
        // return $habitacion;
        $tasaDolarHabitacion = Tasa::where('nombre','=','DolarHabitacion')->first();
        // return $tasaDolarHabitacion->tasa;
        $tasaPesoHabitacion = Tasa::where('nombre','=','PesoHabitacion')->first();
        $tasaDolar = DB::table('tasas')->where('nombre', '=', 'Dolar')->first();
        $tasaPeso = DB::table('tasas')->where('nombre', '=', 'Peso')->first();
        $tasaTransferenciaPunto = DB::table('tasas')->where('nombre', '=', 'Transferencia_Punto')->first();
        $tasaMixto = DB::table('tasas')->where('nombre', '=', 'Mixto')->first();
        $tasaEfectivo = DB::table('tasas')->where('nombre', '=', 'Efectivo')->first();
        $users = User::with('roles')->orderBy('id','Desc')->get();

        // $articulos = DB::table('articulos as art')
        //                 ->select(DB::raw('CONCAT(art.codigo, " - ", art.nombre) AS articulo'), 'art.imagen', 'art.vender_al', 'art.nombre','art.id', 'precio_costo', 'porEspecial', 'isDolar', 'isPeso', 'isTransPunto', 'isMixto', 'isEfectivo', 'isKilo', 'stock', 'art.nombre')
        //                 ->where('art.estado', '=', 'Activo')
        //                 ->where('art.stock', '>', '0')
        //                 ->where('art.precio_costo', '>', '0')
        //                 ->get();



        $UserId = Auth::user()->id;
        $UserName = Auth::user()->name;
        $cajaSessionid =  Sessioncaja::where('estado', 'Abierta')->orderBy('id', 'desc')->first();
        $caja = Caja::where("estado","=",'Abierta')->where("sessioncaja_id","=", $cajaSessionid->id)->first();
        $servicioNum = Servicio::latest('id')->first();
        if (is_null($servicioNum)) {

            $num_servicio = Sessioncaja::numCodigo('S', $UserId, 1);

            // dd($serie_comprobante);
        }else{
            $num_servicio = Sessioncaja::numCodigo('CS', $UserId, $servicioNum->id+1);

        }

        $cajas = Caja::find($caja->id);
                    $cajas->user;
                    $cajas->ventas;
                    $cajas->pago_ventas;
                    $cajas->articulo_ventas;
                    $cajas->excedente_actual;


                    $cont = 0;
                    while ($cont < count($cajas->pago_ventas)) {
                        $v1[] = $cajas->pago_ventas[$cont]->Divisa;
                        $cont = $cont+1;
                    }

                    foreach ($cajas->pago_ventas as $pago ) {

                        if ($pago->Divisa == 'Dolar') {
                            $cajas->SumaTotalDolar = $cajas->SumaTotalDolar + $pago->MontoDivisa;
                        }elseif ($pago->Divisa == 'Peso') {
                            $cajas->SumaTotalPeso = $cajas->SumaTotalPeso + $pago->MontoDivisa;
                        }elseif ($pago->Divisa == 'Bolivar') {
                            $cajas->SumaTotalBolivar = $cajas->SumaTotalBolivar + $pago->MontoDivisa;
                        }elseif ($pago->Divisa == 'Punto') {
                            $cajas->SumaTotalPunto = $cajas->SumaTotalPunto + $pago->MontoDivisa;
                        }elseif ($pago->Divisa == 'Transferencia') {
                            $cajas->SumaTotalTransferencia = $cajas->SumaTotalTransferencia + $pago->MontoDivisa;
                        }

                    }

                    foreach ($cajas->ventas as $vent ) {
                        if ($vent->estado == 'Aceptada') {
                        $cajas->SumaTotalVentas = $cajas->SumaTotalVentas + $vent->total_venta;
                        $cajas->SumaTotalCantidadVentas = $cajas->SumaTotalCantidadVentas + 1;
                        }
                    }

                    foreach ($cajas->articulo_ventas as $art_vent ) {
                        $cajas->SumaArticulosVendidos = $cajas->SumaArticulosVendidos + $art_vent->cantidad;
                    }
                    // return $users->roles[0]->name;
                    // return $servicio->habitacion_id;

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
                    $excedenteCliente = Excedente::where('persona_id',$cliente->id)->first();

                    // return $excedenteCliente;

                    if($excedenteCliente){
                        $cajas->excedenteCLiente = $excedenteCliente->excedente;
                    }

                    // TODO Crear metodo para calcular pago de horas extras en la vista checkout show
                    //capturamos la hora del sistema
                    $first   = Carbon::now('America/Caracas');

                    //asignamos a la variable $second los datos de fecha y hora de salida
                    $second = $servicio->fecha_salida. ' '.$servicio->hora_salida;
                    //damos formato y convertimos en objeto la variable $second
                    $second = Carbon::createFromFormat('Y-m-d H:i:s', $second);
                    // return $cajas;
                    // TODO comprobamos si la fecha del sistema es mayor a la fecha de salida
                    // si fecha de salida es mayor multiplicamos por valor de horas extras de la tabla config_sucursals

                    if($first->gt($second)){



                        // TODO consultamos la tabla config_sucursals para traer el precio de la hora extra.

                        $precioHoraExtraData = Config_Sucursal::where('sucursal_id', $cajas->sucursal_id)->first();

                        // TODO consultamos la tabla pagos extras para ver si ya hay un pago extra registrado por ese servicio
                        //si lo hay comprobamos que sea un pago por horas extras y no por otros montos para luego comparar las
                        //horas pagadas con las horas que tiene el sistema en este momento


                        $verificarHorasExtras = Horas_extra::where('servicio_id',$id)->get();

                        // return $verificarHorasExtras;

                        if($verificarHorasExtras){

                            foreach ($verificarHorasExtras as $verificarHoras) {
                                $cajas->difHorasExtraRegPagadas = $cajas->difHorasExtraRegPagadas + $verificarHoras->cantidad_hora_extra;
                            }

                        }else{
                            $verificarHorasExtras = 0;
                            $cajas->difHorasExtraRegPagadas = 0;
                        }
                        $cajas->difHorasExtraReg = $second->diffInHours($first);
                        $cajas->precioHorasExtraSis = $precioHoraExtraData->precioHorasExtra;
                        $cajas->tiempoMinutosExtraSis = $precioHoraExtraData->minutosMaximosCobrar;
                        $cajas->tiempoCalculado = 'Excedido por: '.$first->diffInDays($second).' Días '. $first->diffInHours($second) . ' Horas'. $first->diffInMinutes($second). ' Miuntos';
                        $cajas->criterio = 'Excedido por: ';
                        // return $precioHoraExtraData->precioHorasExtra * $second->diffInHours($first);

                        // return $first . 'es mayor ' . $second. ' diferencia ' . $second->diffInHours($first);
                        // return $first . 'es mayor' . $second;
                    }else{

                        $verificarHorasExtras = Horas_extra::where('servicio_id',$id)->get();

                        // return $verificarHorasExtras;

                        if($verificarHorasExtras){

                            foreach ($verificarHorasExtras as $verificarHoras) {
                                $cajas->difHorasExtraRegPagadas = $cajas->difHorasExtraRegPagadas + $verificarHoras->cantidad_hora_extra;
                            }

                        }else{
                            $verificarHorasExtras = 0;
                            $cajas->difHorasExtraRegPagadas = 0;
                        }
                        $cajas->difHorasExtraReg = 0;
                        $cajas->precioHorasExtraSis = 0;
                        $cajas->tiempoCalculado = 'Faltan: '.$first->diffInDays($second).' Días '. $first->diffInHours($second) . ' Horas'. $first->diffInMinutes($second). ' Miuntos';
                        $cajas->criterio = 'Faltan: ';
                        //  return $first . 'es menor ' . $second. ' diferencia ' . $second->diffInHours($first);
                        //de lo contrario
                        // return 'es menor';
                        // return $second->diffInHours($first);
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

                    $mismaHabitacion = Habitacione::findOrfail($servicio->habitacion_id);
                    // return $mismaHabitacion;

                    // return $servicio;
                // return redirect()->route('proceso', array('title' => $title,'levels' => $levels,'habitacion' => $habitacion,'horarios' => $horarios, 'tasaDolarHabitacion' => $tasaDolarHabitacion, 'tasaPesoHabitacion' => $tasaPesoHabitacion, 'users' => $users));

             return view('checkout.show', compact('dolarDisponible','pesoDisponible','bolivarDisponible','verificarHorasExtras','mismaHabitacion','cajas','articulos','servicio','serie_comprobante','UserId','UserName','caja','ventaNum','tasaDolar','tasaPeso','tasaTransferenciaPunto','tasaMixto','tasaEfectivo','denominacion_dolar','title','levels','habitacionese','horarios', 'tasaDolarHabitacion', 'tasaPesoHabitacion', 'users', 'cliente','precio','num_servicio'));
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
// return 'listo';

        // $id = 2;
        $servicio_id = Servicio::where('habitacion_id',$id)->where('status_servicio', 'Iniciado')->first();
    //    return $servicio_id;
       if($servicio_id){
        // return 'todo bien';
        $servicio = Servicio::findOrFail($servicio_id->id);
        $servicio->status_servicio = 'Finalizado';
        $servicio->update();

        $habitacion = Habitacione::findOrFail($id);
        $habitacion->status = 'Limpieza';
        $habitacion->update();

        return redirect()
        ->route('checkout.index')
        ->with('status_success', 'La habitacion fue cerrada  exitosamente...');
       }else{
        // return 'no hay registros';
        return redirect()
        ->route('checkout.index')
        ->with('status_success', 'La habitacion fue cerrada previamente de manera exitosa...');
       }


    }

    // public function getHabitacion(Request $request){
    //     // return $request;
    //     if ($request->ajax()) {
    //         $origenArticulos = Habitacione::g('vender_al', $request->accion)
    //             ->where('stock', '>', '0')
    //             ->get();

    //             // return $origenesArtArray;
    //             return response()->json($origenArticulos);
    //     }
    // }


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
