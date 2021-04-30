<?php

namespace App\Http\Controllers;

use App\Caja;
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
use App\Pago_Credito;
use App\Pago_Servicio;
use App\Detalle_credito;
use App\Servicios_Ventas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Excedentes_Recibidos_Caja_Actual;
use App\Horario;
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

        // TODO Validamos si el registro ya ha sido registrado en caso contrario lo guarda


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


            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // TODO metodo para pagar con vueltos pendientes
            $VueltospagoConExcedente = $request->get('VueltospagoConExcedente');

            if($VueltospagoConExcedente > 0){

                if($monto_dejado == 0){
                    $modo_pago = 'contado';
                    $status = 'Pagado';
                    $monto_dejado = $VueltospagoConExcedente;
                }

                if($monto_dejado > 0){
                    $modo_pago = 'contado';
                    $status = 'Pagado';
                    $monto_dejado = $VueltospagoConExcedente + $request->get('modo_pago');
                }

            }else{
                $status = 'Pagado';
                $modo_pago = $request->get('modo_pago');
            }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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


// return $modo_pago;

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

        // TODO Este metodo maneja el cambio de habitacion


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

            $horarios = Horario::findOrFail($request->get('horario_id'));

            if($horarios){
                $tipo =  $horarios->tipo;
                $horario = $horarios->nombre;
            }



            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // TODO metodo para pagar con vueltos pendientes
            $VueltospagoConExcedente = $request->get('VueltospagoConExcedente');

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

                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

                // return 'algo';
                $dolarDisponible = (($cajas->SumaTotalDolarCredConsumo + $cajas->SumaTotalDolarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoDolarDivisa) + ($cajas->SumaTotalDolarServ + $cajas->SumaTotalDolar) + ($cajas->SumaVueltosDevueltosDolarDivisa + $cajas->SumaTotalDolarServDflotante) - $cajas->SumaTotalDolarVueltos);
                // return $dolarDisponible;
                $pesoDisponible = (($cajas->SumaTotalPesoCredConsumo + $cajas->SumaTotalPesoCredServicio) + ($cajas->SumaVueltosExcedenteNuevoPesoDivisa) + ($cajas->SumaTotalPesoServ + $cajas->SumaTotalPeso) + ($cajas->SumaVueltosDevueltosPesoDivisa + $cajas->SumaTotalPesoServDflotante) - $cajas->SumaTotalPesoVueltos);
                // return $pesoDisponible;
                $bolivarDisponible = (($cajas->SumaTotalBolivarCredConsumo + $cajas->SumaTotalBolivarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoBolivarDivisa) + ($cajas->SumaTotalBolivarServ + $cajas->SumaTotalBolivar) + ($cajas->SumaVueltosDevueltosBolivarDivisa + $cajas->SumaTotalBolivarServDflotante) - $cajas->SumaTotalBolivarVueltos);
                // return $bolivarDisponible;


                // TODO Crear proceso que maneje el pago con vueltos pendiente


                // TODO Ir a la tabla Excedentes_Recibidos_Caja_Actual para actualizar el registro y restar los vueltos pendientes
                $RestarVtossPtesToVtosPtes = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$id)->first();


                if ($RestarVtossPtesToVtosPtes) {
                    $RestarVtossPtesToVtosPtes->MontoDivisa = $RestarVtossPtesToVtosPtes->MontoDivisa - ($VueltospagoConExcedente * $RestarVtossPtesToVtosPtes->TasaTiket);
                    $RestarVtossPtesToVtosPtes->MontoDolar = $RestarVtossPtesToVtosPtes->MontoDolar - $VueltospagoConExcedente;
                    $RestarVtossPtesToVtosPtes->update();

                    // TODO Ir a la tabla Excedentes_Recibidos_Caja_Actual y crear un registro nuevo con el monto pagado pero con estatus Devueltos flotantes en la misma divisa

                    $AgregarVtossPtesToVtosPtes = new Excedentes_Recibidos_Caja_Actual();
                    $AgregarVtossPtesToVtosPtes->Tipo = 'Servicio';
                    $AgregarVtossPtesToVtosPtes->Estado = 'Devueltos';
                    $AgregarVtossPtesToVtosPtes->Divisa = $RestarVtossPtesToVtosPtes->Divisa;
                    $AgregarVtossPtesToVtosPtes->MontoDivisa = ($VueltospagoConExcedente * $RestarVtossPtesToVtosPtes->TasaTiket);
                    $AgregarVtossPtesToVtosPtes->TasaTiket = $RestarVtossPtesToVtosPtes->TasaTiket;
                    $AgregarVtossPtesToVtosPtes->MontoDolar = $VueltospagoConExcedente;
                    $AgregarVtossPtesToVtosPtes->servicio_id = $id;
                    $AgregarVtossPtesToVtosPtes->caja_id = $request->get('caja_id');
                    $AgregarVtossPtesToVtosPtes->save();
                }


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

                    if($RestarVtossPtesToVtosPtesDevueltos->Divisa == 'Dolar'){
                        if ($dolarDisponible >= ($VueltospagoConExcedente * 1)) {
                            $Restardivisa = 'Dolar';
                            $RestarMontoDivisa = $VueltospagoConExcedente * 1;
                            $RestarTasaTiket = $RestarVtossPtesToVtosPtes->TasaTiket;
                            $RestarMontoDolar = $VueltospagoConExcedente;
                        }else if ($pesoDisponible >= ($VueltospagoConExcedente * $tasaPeso->tasa)) {
                            $Restardivisa = 'Peso';
                            $RestarMontoDivisa = $VueltospagoConExcedente * $tasaPeso->tasa;
                            $RestarTasaTiket = $tasaPeso->tasa;
                            $RestarMontoDolar = $VueltospagoConExcedente;
                        }else if ($bolivarDisponible >= ($VueltospagoConExcedente * $tasaEfectivo->tasa)) {
                            $Restardivisa = 'Bolivar';
                            $RestarMontoDivisa = $VueltospagoConExcedente * $tasaEfectivo->tasa;
                            $RestarTasaTiket = $tasaEfectivo->tasa;
                            $RestarMontoDolar = $VueltospagoConExcedente;
                        }



                    }else if($RestarVtossPtesToVtosPtesDevueltos->Divisa == 'Peso'){
                        if ($pesoDisponible >= ($VueltospagoConExcedente * $tasaPeso->tasa)) {
                            $Restardivisa = 'Peso';
                            $RestarMontoDivisa = $VueltospagoConExcedente * $tasaPeso->tasa;
                            $RestarTasaTiket = $tasaPeso->tasa;
                            $RestarMontoDolar = $VueltospagoConExcedente;
                        }else if ($dolarDisponible >= ($VueltospagoConExcedente * 1)) {
                            $Restardivisa = 'Dolar';
                            $RestarMontoDivisa = $VueltospagoConExcedente * 1;
                            $RestarTasaTiket = $RestarVtossPtesToVtosPtes->TasaTiket;
                            $RestarMontoDolar = $VueltospagoConExcedente;
                        }else if ($bolivarDisponible >= ($VueltospagoConExcedente * $tasaEfectivo->tasa)) {
                            $Restardivisa = 'Bolivar';
                            $RestarMontoDivisa = $VueltospagoConExcedente * $tasaEfectivo->tasa;
                            $RestarTasaTiket = $tasaEfectivo->tasa;
                            $RestarMontoDolar = $VueltospagoConExcedente;
                        }



                    }else if($RestarVtossPtesToVtosPtesDevueltos->Divisa == 'Bolivar'){
                        if ($bolivarDisponible >= ($VueltospagoConExcedente * $tasaEfectivo->tasa)) {
                            $Restardivisa = 'Bolivar';
                            $RestarMontoDivisa = $VueltospagoConExcedente * $tasaEfectivo->tasa;
                            $RestarTasaTiket = $tasaEfectivo->tasa;
                            $RestarMontoDolar = $VueltospagoConExcedente;
                        }else if ($pesoDisponible >= ($VueltospagoConExcedente * $tasaPeso->tasa)) {
                            $Restardivisa = 'Peso';
                            $RestarMontoDivisa = $VueltospagoConExcedente * $tasaPeso->tasa;
                            $RestarTasaTiket = $tasaPeso->tasa;
                            $RestarMontoDolar = $VueltospagoConExcedente;
                        }else if ($dolarDisponible >= ($VueltospagoConExcedente * 1)) {
                            $Restardivisa = 'Dolar';
                            $RestarMontoDivisa = $VueltospagoConExcedente * 1;
                            $RestarTasaTiket = $RestarVtossPtesToVtosPtes->TasaTiket;
                            $RestarMontoDolar = $VueltospagoConExcedente;
                        }


                    }
                        $Pago_Servicio_Vueltos = new Pago_Vuelto();
                        $Pago_Servicio_Vueltos->Divisa = $Restardivisa;
                        $Pago_Servicio_Vueltos->MontoDivisa = $RestarMontoDivisa;
                        $Pago_Servicio_Vueltos->TasaTiket = $RestarTasaTiket;
                        $Pago_Servicio_Vueltos->MontoDolar = $RestarMontoDolar;
                        $Pago_Servicio_Vueltos->servicio_id = $id;
                        $Pago_Servicio_Vueltos->save();

                }




                // return $dolarDisponible;
                // return $pesoDisponible;
                // return $bolivarDisponible;

                // TODO Se procede a unir el vuento pagado que ahora es dinero contado con el dinero_dejado si lo hubiera para procesar el pago
                if($monto_dejado > 0){
                    $modo_pago = 'contado';
                    $status = 'Pagado';
                    $monto_dejado = $VueltospagoConExcedente + $monto_dejado;
                }

                if($monto_dejado == 0){
                    $modo_pago = 'contado';
                    $status = 'Pagado';
                    $monto_dejado = $VueltospagoConExcedente;
                }

            }else{
                $status = 'Pagado';
                $modo_pago = $request->get('modo_pago');
            }
                // TODO Actualizamos el servicio principal o el servicio donde se genero el primer pago (D/vueltos)


// return $monto_dejado;





            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            // $vueltosPendientesProceso = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$id)->get();

            // foreach ($vueltosPendientesProceso as $key) {
            //     # code...
            // }

// return $vueltosPendientesProceso;
// return array_filter($request->get('MontoDivisa'));


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
            $servicio->tipo_habitacion = $tipo;
            $servicio->horario = $horario;
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
            $servicio->dinero_dejado = $monto_dejado;
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

                if(count($MontoDivisaR) > 0){


                    // return count($MontoDivisaR);

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



                    // BUG actualizar la tabla pago servicios cuando se paga con vueltos pendientes
                    // de lo contrario solo registra el dinero dejado de contado.

                    if($VueltospagoConExcedente > 0){

                        $Restardivisa = '';
                        $RestarMontoDivisa = 0;
                        $RestarTasaTiket = 0;
                        $RestarMontoDolar = 0;

                        // TODO consultamos la tabla Pagos vueltos para descubrir con que moneda se dieron los vueltos
                        //para sumarcelos a pago servicio si la divisa usada es igual a la de pagos servicios solo se
                        // suma y es distinta se hace un nuevo registro en la tabla y quedaría como si se hubiece pagado
                        //con dos divisas

                        $pagoVueltos = Pago_Vuelto::findOrFail($Pago_Servicio_Vueltos->id);
                        // return $RestarVtossPtesToVtosPtesDevueltos;

                        if ($pagoVueltos) {

                            $UdatePagoServiciosConVtosPendientes = Pago_Servicio::findOrFail($Pago_Servicio->id);

                            if ($UdatePagoServiciosConVtosPendientes->Divisa == $pagoVueltos->Divisa) {
                                $UdatePagoServiciosConVtosPendientes->MontoDivisa = $UdatePagoServiciosConVtosPendientes->MontoDivisa + $pagoVueltos->MontoDivisa;
                                $UdatePagoServiciosConVtosPendientes->MontoDolar = $UdatePagoServiciosConVtosPendientes->MontoDolar + $pagoVueltos->MontoDolar;
                                $UdatePagoServiciosConVtosPendientes->update();
                            }else{
                                $Pago_Servicio = new Pago_Servicio();
                                $Pago_Servicio->Divisa = $pagoVueltos->Divisa;
                                $Pago_Servicio->MontoDivisa = $pagoVueltos->MontoDivisa;
                                $Pago_Servicio->TasaTiket = $pagoVueltos->TasaTiket;
                                $Pago_Servicio->MontoDolar = $pagoVueltos->MontoDolar;
                                $Pago_Servicio->Vueltos = 0;
                                $Pago_Servicio->servicio_id = $servicio->id;
                                $Pago_Servicio->save();
                            }


                        }

                        // TODO Ahora actualizamos la tabla Excedentes_Recibidos_Caja_Actual para pasar el dinero pendiente si lo hay al nuevo servicio
                        //que se creo porque de lo contrario se perderia el vuelto pendiente

                        // TODO verificamos si exciste un vuelto pendiente con el id del servicio que cerramos $id


                            $PasarVtossPtesToNextServ = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$id)->where('Estado','Pendiente')->first();
                            if ($PasarVtossPtesToNextServ) {
                                $PasarVtossPtesToNextServ->servicio_id = $servicio->id;
                                $PasarVtossPtesToNextServ->update();
                            }




                    }
                }else{
                    // BUG actualizar la tabla pago servicios cuando se paga con vueltos pendientes
                    // de lo contrario solo registra el dinero dejado de contado.

                    if($VueltospagoConExcedente > 0){

                        $Restardivisa = '';
                        $RestarMontoDivisa = 0;
                        $RestarTasaTiket = 0;
                        $RestarMontoDolar = 0;

                        // TODO consultamos la tabla Pagos vueltos para descubrir con que moneda se dieron los vueltos
                        //para sumarcelos a pago servicio si la divisa usada es igual a la de pagos servicios solo se
                        // suma y es distinta se hace un nuevo registro en la tabla y quedaría como si se hubiece pagado
                        //con dos divisas

                        $pagoVueltos = Pago_Vuelto::findOrFail($Pago_Servicio_Vueltos->id);
                        // return $RestarVtossPtesToVtosPtesDevueltos;

                        if ($pagoVueltos) {


                                $Pago_Servicio = new Pago_Servicio();
                                $Pago_Servicio->Divisa = $pagoVueltos->Divisa;
                                $Pago_Servicio->MontoDivisa = $pagoVueltos->MontoDivisa;
                                $Pago_Servicio->TasaTiket = $pagoVueltos->TasaTiket;
                                $Pago_Servicio->MontoDolar = $pagoVueltos->MontoDolar;
                                $Pago_Servicio->Vueltos = 0;
                                $Pago_Servicio->servicio_id = $servicio->id;
                                $Pago_Servicio->save();



                        }

                        // TODO Ahora actualizamos la tabla Excedentes_Recibidos_Caja_Actual para pasar el dinero pendiente si lo hay al nuevo servicio
                        //que se creo porque de lo contrario se perderia el vuelto pendiente

                        // TODO verificamos si exciste un vuelto pendiente con el id del servicio que cerramos $id


                            $PasarVtossPtesToNextServ = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$id)->where('Estado','Pendiente')->first();
                            if ($PasarVtossPtesToNextServ) {
                                $PasarVtossPtesToNextServ->servicio_id = $servicio->id;
                                $PasarVtossPtesToNextServ->update();
                            }




                    }
                }




            // if($VueltospagoConExcedente > 0){
            //     $vueltosPendientesProceso = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$id)->get();

            //     foreach ($vueltosPendientesProceso as $vtosPP) {

            //         $TablaPagoServicios = Pago_Servicio::where('servicio_id',$id)->get();

            //         foreach ($TablaPagoServicios as $TPagoServicios) {
            //             if ($vtosPP->Divisa == $TPagoServicios->Divisa) {

            //                 $vtosPPU = Pago_Servicio::findOrFail($TablaPagoServicios->id);
            //                 $vtosPPU->MontoDivisa = $vtosPPU->MontoDivisa + ($VueltospagoConExcedente * $vtosPP->TasaTiket);
            //                 $vtosPPU->MontoDolar = $vtosPPU->MontoDolar + $VueltospagoConExcedente;
            //                 $vtosPPU->update();


            //             }else {
            //                 $vtosPP = new Pago_Servicio();
            //                 $vtosPP->Divisa = $vtosPP->Divisa;
            //                 $vtosPP->MontoDivisa = $VueltospagoConExcedente * $vtosPP->TasaTiket;
            //                 $vtosPP->TasaTiket = $vtosPP->TasaTiket;
            //                 $vtosPP->MontoDolar = $VueltospagoConExcedente;
            //                 $vtosPP->Vueltos = 0;
            //                 $vtosPP->servicio_id = $servicio->id;
            //                 $vtosPP->save();
            //             }
            //         }

            //     }

            //     $RestarVueltosPendientes = Excedentes_Recibidos_Caja_Actual::findOrFail($vueltosPendientesProceso->id);
            //     if ($RestarVueltosPendientes) {
            //         $RestarVueltosPendientes->MontoDivisa = $RestarVueltosPendientes->MontoDivisa - ($VueltospagoConExcedente * $vtosPP->TasaTiket);
            //         $RestarVueltosPendientes->MontoDolar = $RestarVueltosPendientes->MontoDolar - $VueltospagoConExcedente;
            //         $RestarVueltosPendientes->update();

            //         $AgregarVtosPP = new Excedentes_Recibidos_Caja_Actual();
            //                 $AgregarVtosPP->Divisa = $AgregarVtosPP->Divisa;
            //                 $AgregarVtosPP->MontoDivisa = ($VueltospagoConExcedente * $vtosPP->TasaTiket);
            //                 $AgregarVtosPP->TasaTiket = $AgregarVtosPP->TasaTiket;
            //                 $AgregarVtosPP->MontoDolar = $VueltospagoConExcedente;
            //                 $AgregarVtosPP->Vueltos = $AgregarVtosPP->Vueltos;
            //                 $AgregarVtosPP->servicio_id = $servicio->id;
            //                 $AgregarVtosPP->save();
            //     }

            // }

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
