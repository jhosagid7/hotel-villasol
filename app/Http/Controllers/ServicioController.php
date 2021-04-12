<?php

namespace App\Http\Controllers;

use App\Credito;
use App\Persona;
use App\Cortesia;
use App\Servicio;
use App\Excedente;
use Carbon\Carbon;
use App\Pago_Venta;
use App\Habitacione;
use App\Pago_Vuelto;
use App\Prexcedente;
use App\Pago_Servicio;
use App\Detalle_credito;
use App\Excedentes_Recibidos_Caja_Actual;
use App\Servicios_Ventas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\PrinterController;

class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $validarServicio = Servicio::where('nombre_habitacion',$request->get('nombreHabitacion'))->where('status_servicio','Iniciado')->get();
        // return count($validarServicio);

        if (count($validarServicio)) {
            // return 'si';
            return Redirect::to('checkout')->with('success', 'El servicio fué registrado previamente exitosamente');
        } else {
            // return 'no';

        // return $validarServicio;

        try{
            DB::beginTransaction();
            $myTime = Carbon::now('America/Caracas');
            // number_format($número, 2, '.', '');

            $id_habitaicon = $request->get('id_habitacion');

            $tipo_pago = $request->get('tipo_pago');
            // return $tipo_pago;

            $monto_dejado = $request->get('monto_dejado');
            $pagoConExcedente = $request->get('pagoConExcedente');
            $total_costo = $request->get('total_costo');
            $status = '';

            $nombreHabitacion = $request->get('nombreHabitacion');
            $numeroServisio = $request->get('num_servicio');
            $operador = $request->get('operador');
            $detalleHabitacion = $request->get('detalle_habitacion');
            $tipo =  $request->get('horario');

            $nuevo_excedente = 0;

            if($pagoConExcedente > 0){

                if($monto_dejado == 0){
                    $modo_pago = 'Excedente';
                }

                if($monto_dejado > 0){
                    $modo_pago = 'Contado-Excedente';
                }

            }else{
                $modo_pago = $request->get('modo_pago');
            }

// return $modo_pago;



            if($modo_pago == 'cortesia'){
                $status = 'Exonerado';

                $tipo_pago = 'Exonerado';

            }

            if($modo_pago == 'credito'){

                $status = 'Falta pagar';

                $tipo_pago = 'No pagado';
            }



            if($modo_pago == 'contado'){

                if($monto_dejado == $total_costo){
                    $status = 'Pagado';

                }

                if($monto_dejado > $total_costo){
                    $status = 'Pagado';
                    $nuevo_excedente = $monto_dejado - $total_costo;

                }

                // if($monto_dejado > $total_costo){


                //     // dd($status);

                //     $exced = $monto_dejado - $total_costo;

                //     $ifCliente = Excedente::where('persona_id',$request->get('cliente_id'))->first();
                //     // return $ifCliente;

                //     if($ifCliente){
                //         // return 'si';

                //         $upExcedente = Excedente::findOrFail($ifCliente->id);
                //         $upExcedente->excedente = $upExcedente->excedente + $exced;
                //         $upExcedente->update();
                //     }else{
                //         // return 'no';
                //         // $excedente = new Excedente;
                //         // $excedente->nombre_cliente = $request->get('nombre');
                //         // $excedente->cedula_cliente = $request->get('num_documento');
                //         // $excedente->direccion_cliente = $request->get('direccion');
                //         // $excedente->telefono_cliente = $request->get('telefono');
                //         // $excedente->excedente = $exced;
                //         // $excedente->persona_id = $request->get('cliente_id');
                //         // $excedente->save();


                //         $prexcedente = new Prexcedente;
                //         $prexcedente->excedente = $exced;
                //         $prexcedente->persona_id = $request->get('cliente_id');
                //         $prexcedente->save();
                //     }

                // }

                if($monto_dejado < $total_costo){
                    $status = 'Falta pagar';
                    $estado = 'Pendiente';
                }
            }

            if($modo_pago == 'Contado-Excedente'){
                $status = 'Pagado';
            }

            if($modo_pago == 'Excedente'){
                $status = 'Pagado';
            }




            $servicio = new Servicio;
            $servicio->num_servicio = $request->get('num_servicio');
            $servicio->operador = $request->get('operador');
            $servicio->status_servicio = 'Iniciado';
            $servicio->habitacion_id = $id_habitaicon;
            $servicio->nombre_habitacion = $request->get('nombreHabitacion');
            $servicio->detalle_habitacion = $request->get('detalle_habitacion');
            $servicio->tipo_habitacion = $request->get('horario_tipo');
            $servicio->horario = $request->get('horario');
            $servicio->fecha_entrada = $request->get('fecha_entrada');
            $servicio->hora_entrada = $request->get('hora_entrada');
            $servicio->fecha_salida = $request->get('fecha_salida');
            $servicio->hora_salida = $request->get('hora_salida');
            $servicio->tasaDolar = $request->get('tasaDolar');
            $servicio->porDolar = $request->get('porDolar');
            $servicio->tasaPeso = $request->get('tasaPeso');
            $servicio->porPeso = $request->get('porPeso');
            $servicio->tasaTransPunto = $request->get('tasaTransPunto');
            $servicio->porTransPunto = $request->get('porTransPunto');
            $servicio->tasaMixto = $request->get('tasaMixto');
            $servicio->porMixto = $request->get('porMixto');
            $servicio->tasaEfectivo = $request->get('tasaEfectivo');
            $servicio->porEfectivo = $request->get('porEfectivo');
            $servicio->tasaDolarHabitacion = $request->get('tasaDolarHabitacion');
            $servicio->porDolarHabitacion = $request->get('porDolarHabitacion');
            $servicio->tasaPesoHabitacion = $request->get('tasaPesoHabitacion');
            $servicio->porPesoHabitacion = $request->get('porPesoHabitacion');
            $servicio->num_Punto = $request->get('num_Punto');
            $servicio->num_Trans = $request->get('num_Trans');
            $servicio->modo_pago = $modo_pago;
            $servicio->tipo_pago = $tipo_pago;
            $servicio->status = $status;
            $servicio->precio_costo = $request->get('precio_costo');
            $servicio->cantidad = $request->get('cantidad');
            $servicio->dinero_dejado = $request->get('monto_dejado');
            $servicio->excedente_nuevo = $nuevo_excedente;
            $servicio->pago_con_excedente = $request->get('pagoConExcedente');
            $servicio->total_venta = $request->get('total_costo');
            $servicio->estado = 'Aceptada';
            $servicio->nombre_cliente = $request->get('nombre');
            $servicio->cedula_cliente = $request->get('num_documento');
            $servicio->direccion_cliente = $request->get('direccion');
            $servicio->telefono_cliente = $request->get('telefono');
            $servicio->limite_fecha = $request->get('limite_fecha');
            $servicio->limite_monto = $request->get('limite_monto');
            $servicio->persona_id = $request->get('cliente_id');
            $servicio->user_id = $request->get('user_id');
            $servicio->caja_id = $request->get('caja_id');
            $servicio->save();



            if($modo_pago == 'contado'){

                if($monto_dejado > $total_costo){


                    // dd($status);

                    $exced = $monto_dejado - $total_costo;
                    $excedMontoDolar = $monto_dejado - $total_costo;

                    if ($tipo_pago == 'Dolar') {
                        $tasaTiket = $request->get('tasaDolar');
                        $exced = $exced * $tasaTiket;
                        $divisaExced = 'Dolar';
                    }

                    if ($tipo_pago == 'Peso') {
                        $tasaTiket = $request->get('tasaPeso');
                        $exced = $exced * $tasaTiket;
                        $divisaExced = 'Peso';
                    }

                    if ($tipo_pago == 'Trans/Punto') {
                        // return 'Trans/Punto';
                        $numPunto = $request->get('num_Punto');
                        // return empty($numPunto);
                        $numTrans = $request->get('num_Trans');
                        // return $numTrans;
                        $numPuntoTrans = '';
                        if (empty($numPunto) && !empty($numTrans)) {
                            // return 'p=null y t=si';
                            $numPuntoTrans = $numTrans;
                        }

                        if (!empty($numPunto) && empty($numTrans)) {
                            // return 'p=si y t=null';
                            $numPuntoTrans = $numPunto;
                        }

                        if (!empty($numPunto) && !empty($numTrans)) {
                            // return 'p=si y t=si';
                            $numPuntoTrans = $numPunto. ' - ' .$numTrans;
                        }

                        if (empty($numPunto) && empty($numTrans)) {
                            // return 'p=null y t=null';
                            $numPuntoTrans = 'S/N - S/N';
                        }
                        // return $numPuntoTrans;

                        $tasaTiket = $numPuntoTrans;
                        $exced = $exced * $request->get('tasaTransPunto');
                        $divisaExced = 'Trans/Punto';
                    }

                    // if ($tipo_pago == 'Mixto') {

                    //     // return 'MIxto';
                    //     // $exced = $exced * $request->get('tasaMixto');
                    //     // $divisaExced = 'Dolar';

                    //     $MontoDivisaM = $request->get('MontoDivisa');
                    //     $divisaM = $request->get('divisa');
                    //     $TasaTikeM = $request->get('TasaTike');
                    //     $MontoDolarM = $request->get('MontoDolar');
                    //     $MontoDivisaM = array_filter($MontoDivisaM);

                    //     // TODO Comvertimos la variable $MontoDolarM en un array y buscamos el valor mas alto

                    //     $MontoDolarMax = array_filter($MontoDolarM);

                    //     // return $MontoDolarM;
                    //     $valorMax = max($MontoDolarMax);
                    //     // return $valorMax . ' excedente es '. $exced;

                    //     foreach($MontoDivisaM as $key => $val) {
                    //         // return $valorMax . ' excedente es '. $exced . ' la divisa es ' .$divisaM[$key];
                    //         if($MontoDolarM[$key] == $valorMax){
                    //             // return 'MIxto';
                    //             // return $valorMax . ' excedente es '. $exced . ' la divisa es ' .$divisaM[$key];

                    //             $resta = $MontoDolarM[$key] - $exced;

                    //             if ($resta >= 0) {
                    //                 return $resta;
                    //             }else{

                    //                 $textos = $MontoDolarMax;
                    //                 // return $textos;

                    //                 if (($clave = array_search($valorMax, $textos)) !== false) {
                    //                     unset($textos[$clave]);
                    //                     return $textos;
                    //                 }
                    //             }

                    //             $divisaExced=$divisaM[$key];
                    //             $MontoDolar = $MontoDolarM[$key];
                    //             $exced = $exced * $TasaTikeM;


                    //         }
                    //         $ValorMaximo = $MontoDolarM[$key];
                    //     $divisa[]=$divisaM[$key];
                    //     $MontoDolar[]=$MontoDolarM[$key];
                    //     }
                    // }

                    if ($tipo_pago == 'Efectivo') {
                        $tasaTiket = $request->get('tasaEfectivo');
                        $exced = $exced * $tasaTiket;
                        $divisaExced = 'Bolivar';
                    }


                    $excdtsRecibidosCaja = new Excedentes_Recibidos_Caja_Actual();
                    $excdtsRecibidosCaja->Tipo = 'Servicio';
                    $excdtsRecibidosCaja->Estado = 'Pendiente';
                    $excdtsRecibidosCaja->Divisa = $divisaExced;
                    $excdtsRecibidosCaja->TasaTiket = $tasaTiket;
                    $excdtsRecibidosCaja->MontoDivisa = $exced;
                    $excdtsRecibidosCaja->MontoDolar = $excedMontoDolar;
                    $excdtsRecibidosCaja->servicio_id = $servicio->id;
                    $excdtsRecibidosCaja->caja_id = $request->get('caja_id');;
                    $excdtsRecibidosCaja->save();

                    $ifCliente = Excedente::where('persona_id',$request->get('cliente_id'))->first();
                    // return $ifCliente;

                    // TODO definir que hacer con los excedentes si pasan a nuevo


                    // if($ifCliente){
                    //     // return 'si';

                    //     $upExcedente = Excedente::findOrFail($ifCliente->id);
                    //     $upExcedente->excedente = $upExcedente->excedente + $exced;
                    //     $upExcedente->update();
                    // }else{
                    //     // return 'no';
                    //     // $excedente = new Excedente;
                    //     // $excedente->nombre_cliente = $request->get('nombre');
                    //     // $excedente->cedula_cliente = $request->get('num_documento');
                    //     // $excedente->direccion_cliente = $request->get('direccion');
                    //     // $excedente->telefono_cliente = $request->get('telefono');
                    //     // $excedente->excedente = $exced;
                    //     // $excedente->persona_id = $request->get('cliente_id');
                    //     // $excedente->save();


                    //     $prexcedente = new Prexcedente;
                    //     $prexcedente->excedente = $exced;
                    //     $prexcedente->servicio_id = $servicio->id;
                    //     $prexcedente->save();
                    // }



                }

            }

            if($modo_pago == 'Contado-Excedente'){




                    // dd($status);

                    $restarExced = $request->get('pagoConExcedente');

                    $ifCliente = Excedente::where('persona_id',$request->get('cliente_id'))->first();
                    // return $ifCliente;

                    if($ifCliente){
                        // return 'si';

                        $upExcedente = Excedente::findOrFail($ifCliente->id);
                        $upExcedente->excedente = $upExcedente->excedente - $restarExced;
                        $upExcedente->update();
                    }
                    // else{
                        // return 'no';
                        // $excedente = new Excedente;
                        // $excedente->nombre_cliente = $request->get('nombre');
                        // $excedente->cedula_cliente = $request->get('num_documento');
                        // $excedente->direccion_cliente = $request->get('direccion');
                        // $excedente->telefono_cliente = $request->get('telefono');
                        // $excedente->excedente = $exced;
                        // $excedente->persona_id = $request->get('cliente_id');
                        // $excedente->save();


                    //     $prexcedente = new Prexcedente;
                    //     $prexcedente->excedente = $exced;
                    //     $prexcedente->servicio_id = $servicio->id;
                    //     $prexcedente->save();
                    // }


            }

            if($modo_pago == 'Excedente'){




                // dd($status);

                $restarExced = $request->get('pagoConExcedente');

                $ifCliente = Excedente::where('persona_id',$request->get('cliente_id'))->first();
                // return $ifCliente;

                if($ifCliente){
                    // return 'si';

                    $upExcedente = Excedente::findOrFail($ifCliente->id);
                    $upExcedente->excedente = $upExcedente->excedente - $restarExced;
                    $upExcedente->update();
                }
                // else{
                    // return 'no';
                    // $excedente = new Excedente;
                    // $excedente->nombre_cliente = $request->get('nombre');
                    // $excedente->cedula_cliente = $request->get('num_documento');
                    // $excedente->direccion_cliente = $request->get('direccion');
                    // $excedente->telefono_cliente = $request->get('telefono');
                    // $excedente->excedente = $exced;
                    // $excedente->persona_id = $request->get('cliente_id');
                    // $excedente->save();


                //     $prexcedente = new Prexcedente;
                //     $prexcedente->excedente = $exced;
                //     $prexcedente->servicio_id = $servicio->id;
                //     $prexcedente->save();
                // }


        }

            if($modo_pago == 'credito'){



                $fecha_vencimiento = Carbon::now();
                $fecha_vencimiento->addDays($request->get('limite_fecha'));
                $fecha_vencimiento->toDateString();


                $deuda_actual = Credito::where('persona_id',$request->get('cliente_id'))->first();
                // return $deuda_actual;
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
                        $detalleCredito->numero_factura = $request->get('num_servicio');
                        $detalleCredito->tipo_operacion = 'Servicio';
                        $detalleCredito->operacion_id = $servicio->id;
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
                        $detalleCredito->numero_factura = $request->get('num_servicio');
                        $detalleCredito->tipo_operacion = 'Servicio';
                        $detalleCredito->operacion_id = $servicio->id;
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
                    $credito->nombre_cliente = $request->get('nombre');
                    $credito->cedula_cliente = $request->get('num_documento');
                    $credito->direccion_cliente = $request->get('direccion');
                    $credito->telefono_cliente = $request->get('telefono');
                    $credito->total_factura = 1;
                    $credito->total_deuda = $total_costo;
                    $credito->fecha_limite_pago = $fecha_vencimiento_pago;
                    $credito->estado_credito = 'Activo';
                    $credito->persona_id = $request->get('cliente_id');
                    $credito->user_id = Auth::user()->id;
                    $credito->save();


                    $detalleCredito = new Detalle_credito;
                    $detalleCredito->numero_factura = $request->get('num_servicio');
                    $detalleCredito->tipo_operacion = 'Servicio';
                    $detalleCredito->operacion_id = $servicio->id;
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
                    $cortesia->servicio_id = $servicio->id;
                    $cortesia->save();
            }





            if($modo_pago == 'contado' || $modo_pago == 'Contado-Excedente'){
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
                if($monto_dejado == $total_costo){
                    $Vueltos[]=$VeltosR[$key];

                }else{
                    $Vueltos[]=$VeltosR[$key] - $VeltosR[$key];

                }


            }




            // dd($divisa, $MontoDivisa,$TasaTike,$MontoDolar,$Veltos);
            //creamos un contador
            $cont = 0;


            //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
            while ($cont < count($MontoDolar)) {


                $Pago_Servicio = new Pago_Servicio();
                $Pago_Servicio->Divisa = $divisa[$cont];
                $Pago_Servicio->MontoDivisa = $MontoDivisa[$cont];
                $Pago_Servicio->TasaTiket = $TasaTiket[$cont];
                $Pago_Servicio->MontoDolar = $MontoDolar[$cont];
                $Pago_Servicio->Vueltos = $Vueltos[$cont];
                $Pago_Servicio->servicio_id = $servicio->id;
                $Pago_Servicio->save();

                $cont = $cont+1;
            }

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // En esta seccion trabajaremos la parte de vueltos llenamos la tabla pagos_vueltos

            $isVuelos = $request->get('isVueltos');

            if($isVuelos > 0 || $isVuelos != null){




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


                $Pago_Servicio = new Pago_Vuelto();
                $Pago_Servicio->Divisa = $Vdivisa[$cont];
                $Pago_Servicio->MontoDivisa = $VMontoDivisa[$cont];
                $Pago_Servicio->TasaTiket = $VTasaTiket[$cont];
                $Pago_Servicio->MontoDolar = $VMontoDolar[$cont];
                $Pago_Servicio->servicio_id = $servicio->id;
                $Pago_Servicio->save();

                $cont = $cont+1;
            }

        }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
            //ahora actualizamos la tabla Habitaico con un estatus de ocupada
            $habitacion = Habitacione::findOrFail($id_habitaicon);
            $habitacion->status = 'Ocupada';
            $habitacion->update();

            DB::commit();

        }catch(\Exception $e)
        {

            dd($e);
            DB::rollback();
            if (isset($MontoDolarR)) {

                return redirect()
                ->route('recepcion')
                ->with('status_danger', '¡Error Pago incompleto! Debe ingresar un monto para pagar y procesar el servicio... ');
            }
        }

        $printer = new PrinterController;

        $printer->ticketServicio('Servicio', $numeroServisio, $nombreHabitacion, $detalleHabitacion, $modo_pago, $tipo_pago, $total_costo, $operador,$tipo);
        // return view('checkin.checkin.index', compact('title','tasas'));
        return Redirect::to('checkout')->with('success', 'El servicio fué registrado exitosamente');
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {

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
        return $request;

        $validarServicio = Servicio::findOrfail($id);
        // return count($validarServicio);

        if ($validarServicio) {


        // return $validarServicio;

        try{
            DB::beginTransaction();
            $myTime = Carbon::now('America/Caracas');
            // number_format($número, 2, '.', '');

            $id_habitaicon = $request->get('habitacion_id_nueva2');
            $habitacion_id_vieja = $request->get('habitacion_id_vieja');

            $tipo_pago = $request->get('tipo_pago');
            // return $tipo_pago;
            $modo_pago = $request->get('modo_pago');
            $monto_dejado = $request->get('monto_dejado');
            $total_costo = $request->get('total_costo');
            $status = '';

            $nombreHabitacion = $request->get('nombre_nueva2');
            $nombreHabitacionCambio = $request->get('nombre_nueva2').'/'.$request->get('nombre_vieja');
            $numeroServisio = $request->get('num_servicio');
            $operador = $request->get('operador');
            $detalleHabitacion = $request->get('categoria_dest_nueva2');
            $tipo =  $request->get('horario');


            if($modo_pago == 'cambio'){
                $status = 'Exonerado';
                $estado = 'Aceptada';
                $tipo_pago = 'Exonerado';

            }


            if($modo_pago == 'cortesia'){
                $status = 'Exonerado';
                $estado = 'Aceptada';
                $tipo_pago = 'Exonerado';

            }

            if($modo_pago == 'credito'){

                $status = 'Falta pagar';
                $estado = 'Aceptada';
                $tipo_pago = 'No pagado';
            }



            if($modo_pago == 'contado'){

                if($monto_dejado == $total_costo){
                    $status = 'Pagado';
                    $estado = 'Aceptada';
                }

                if($monto_dejado > $total_costo){
                    $status = 'Pagado';
                    $estado = 'Aceptada';
                    // dd($status);

                    $exced = $monto_dejado - $total_costo;

                    $excedente = new Excedente;
                    $excedente->nombre_cliente = $request->get('nombre');
                    $excedente->cedula_cliente = $request->get('num_documento');
                    $excedente->direccion_cliente = $request->get('direccion');
                    $excedente->telefono_cliente = $request->get('telefono');
                    $excedente->excedente = $exced;
                    $excedente->persona_id = $request->get('cliente_id');
                    $excedente->save();
                }

                if($monto_dejado < $total_costo){
                    $status = 'Falta pagar';
                    $estado = 'Pendiente';
                }
            }




            $servicio = new Servicio;
            $servicio->num_servicio = $request->get('num_servicio').'/'.$request->get('num_servicio_vieja');
            $servicio->operador = $request->get('operador');
            $servicio->status_servicio = 'Iniciado';
            $servicio->habitacion_id = $id_habitaicon;
            $servicio->nombre_habitacion = $request->get('nombre_nueva2').'/'.$request->get('nombre_vieja');
            $servicio->detalle_habitacion = $request->get('categoria_dest_nueva2');
            $servicio->tipo_habitacion = $request->get('horario_tipo');
            $servicio->horario = $request->get('horario');
            $servicio->fecha_entrada = $request->get('fecha_entrada');
            $servicio->hora_entrada = $request->get('hora_entrada');
            $servicio->fecha_salida = $request->get('fecha_salida');
            $servicio->hora_salida = $request->get('hora_salida');
            $servicio->tasaDolar = $request->get('tasaDolar');
            $servicio->porDolar = $request->get('porDolar');
            $servicio->tasaPeso = $request->get('tasaPeso');
            $servicio->porPeso = $request->get('porPeso');
            $servicio->tasaTransPunto = $request->get('tasaTransPunto');
            $servicio->porTransPunto = $request->get('porTransPunto');
            $servicio->tasaMixto = $request->get('tasaMixto');
            $servicio->porMixto = $request->get('porMixto');
            $servicio->tasaEfectivo = $request->get('tasaEfectivo');
            $servicio->porEfectivo = $request->get('porEfectivo');
            $servicio->tasaDolarHabitacion = $request->get('tasaDolarHabitacion');
            $servicio->porDolarHabitacion = $request->get('porDolarHabitacion');
            $servicio->tasaPesoHabitacion = $request->get('tasaPesoHabitacion');
            $servicio->porPesoHabitacion = $request->get('porPesoHabitacion');
            $servicio->num_Punto = $request->get('num_Punto');
            $servicio->num_Trans = $request->get('num_Trans');
            $servicio->modo_pago = $modo_pago;
            $servicio->tipo_pago = $tipo_pago;
            $servicio->status = $status;
            $servicio->precio_costo = $request->get('precio_costo');
            $servicio->cantidad = $request->get('cantidad');
            $servicio->dinero_dejado = $request->get('monto_dejado');
            $servicio->total_venta = $request->get('total_costo');
            $servicio->estado = 'Aceptada';
            $servicio->nombre_cliente = $request->get('nombre');
            $servicio->cedula_cliente = $request->get('num_documento');
            $servicio->direccion_cliente = $request->get('direccion');
            $servicio->telefono_cliente = $request->get('telefono');
            $servicio->limite_fecha = $request->get('limite_fecha');
            $servicio->limite_monto = $request->get('limite_monto');
            $servicio->persona_id = $request->get('cliente_id');
            $servicio->user_id = $request->get('user_id');
            $servicio->caja_id = $request->get('caja_id');
            $servicio->save();






            if($modo_pago == 'credito'){



                $fecha_vencimiento = Carbon::now();
                $fecha_vencimiento->addDays($request->get('limite_fecha'));
                $fecha_vencimiento->toDateString();


                $deuda_actual = Credito::where('persona_id',$request->get('cliente_id'))->first();
                // return $deuda_actual;
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
                        $detalleCredito->numero_factura = $request->get('num_servicio');
                        $detalleCredito->tipo_operacion = 'Servicio';
                        $detalleCredito->operacion_id = $servicio->id;
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
                        $detalleCredito->numero_factura = $request->get('num_servicio');
                        $detalleCredito->tipo_operacion = 'Servicio';
                        $detalleCredito->operacion_id = $servicio->id;
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
                    $credito->nombre_cliente = $request->get('nombre');
                    $credito->cedula_cliente = $request->get('num_documento');
                    $credito->direccion_cliente = $request->get('direccion');
                    $credito->telefono_cliente = $request->get('telefono');
                    $credito->total_factura = 1;
                    $credito->total_deuda = $total_costo;
                    $credito->fecha_limite_pago = $fecha_vencimiento_pago;
                    $credito->estado_credito = 'Activo';
                    $credito->persona_id = $request->get('cliente_id');
                    $credito->user_id = Auth::user()->id;
                    $credito->save();


                    $detalleCredito = new Detalle_credito;
                    $detalleCredito->numero_factura = $request->get('num_servicio');
                    $detalleCredito->tipo_operacion = 'Servicio';
                    $detalleCredito->operacion_id = $servicio->id;
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
                    $cortesia->servicio_id = $servicio->id;
                    $cortesia->save();
            }





            if($modo_pago == 'contado'){
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




            // dd($divisa, $MontoDivisa,$TasaTike,$MontoDolar,$Veltos);
            //creamos un contador
            $cont = 0;


            //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
            while ($cont < count($MontoDolar)) {


                $Pago_Servicio = new Pago_Servicio();
                $Pago_Servicio->Divisa = $divisa[$cont];
                $Pago_Servicio->MontoDivisa = $MontoDivisa[$cont];
                $Pago_Servicio->TasaTiket = $TasaTiket[$cont];
                $Pago_Servicio->MontoDolar = $MontoDolar[$cont];
                $Pago_Servicio->Vueltos = $Vueltos[$cont];
                $Pago_Servicio->servicio_id = $servicio->id;
                $Pago_Servicio->save();

                $cont = $cont+1;
            }
        }
            //ahora actualizamos la tabla Habitaico con un estatus de ocupada
            $habitacion = Habitacione::findOrFail($id_habitaicon);
            $habitacion->status = 'Ocupada';
            $habitacion->update();




            $servicio_id = Servicio::where('habitacion_id',$habitacion_id_vieja)->where('status_servicio', 'Iniciado')->first();
            //    return $servicio_id;
            if($servicio_id){
                // return 'todo bien';
                $servicio = Servicio::findOrFail($servicio_id->id);
                $servicio->status_servicio = 'Finalizado';
                $servicio->update();

                $habitacionCambio = Habitacione::findOrFail($habitacion_id_vieja);
                $habitacionCambio->status = 'Limpieza';
                $habitacionCambio->update();

            }

            DB::commit();

        }catch(\Exception $e)
        {

            dd($e);
            DB::rollback();
            if (isset($MontoDolarR)) {

                return redirect()
                ->route('recepcion')
                ->with('status_danger', '¡Error Pago incompleto! Debe ingresar un monto para pagar y procesar el servicio... ');
            }
        }

        $printer = new PrinterController;

        $printer->ticketServicioCambio('Cambio de Habitación','Servicio', $numeroServisio,$nombreHabitacionCambio, $nombreHabitacion, $detalleHabitacion, $modo_pago, $tipo_pago, $total_costo, $operador,$tipo);
        // return view('checkin.checkin.index', compact('title','tasas'));
        return Redirect::to('checkout')->with('success', 'El servicio fué registrado exitosamente');
    }
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
