<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Venta;
use App\Cambio;
use App\Credito;
use App\Horario;
use App\Persona;
use App\Cortesia;
use App\Servicio;
use App\Excedente;
use App\Reintegro;
use Carbon\Carbon;
use App\Pago_Extra;
use App\Pago_Venta;
use App\Habitacione;
use App\Horas_extra;
use App\Pago_Vuelto;
use App\Prexcedente;
use App\Pago_Credito;
use App\PreExcedente;
use App\Pago_Servicio;
use App\Detalle_credito;
use App\Servicios_Ventas;
use App\Temp_Pago_Vuelto;
use App\DetallePagoOficina;
use App\HistorialExcedente;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Excedentes_Recibidos_Caja_Actual;
use App\Http\Controllers\PrinterController;
use App\Excedentes_Pendientes_Caja_Anterior;

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
            $modo_pago = $request->get('modo_pago');
            $nuevo_excedente = 0;
            $cliente_id = $request->get('cliente_id');
            
            $monto_reintegro = 0;
            $pagoConVueltosCaja = 0;

            
            
            $is_vueltos_caja = PreExcedente::where('cliente_id', $cliente_id)->first();
            // return $is_vueltos_caja;
            

            if($is_vueltos_caja && $pagoConExcedente > 0){
                // return 'entro 1';
                $monto_excedente_actual = $is_vueltos_caja->monto_excedente_actual;
                $deuda_total_acumulada = $is_vueltos_caja->deuda_total_acumulada;

                $dispExcedente = $request->get('dispExcedente');

                $monto_reintegro = $dispExcedente - $deuda_total_acumulada;
                
                $pagoConVueltosCaja = $pagoConExcedente - $monto_reintegro;

                $monto_dejado = $monto_dejado + $pagoConVueltosCaja;

                // $pagoConExcedente = $monto_reintegro;

                //Hasta aqui todo bien.   
                // return $monto_reintegro;
                if($pagoConExcedente  >= $monto_reintegro){
                    //Cargamos las variables que usaremos para manejar el reintegro.
                    // return 'entro en reintegro';
                    // return $monto_excedente_actual;
                    $pagoConExcedente = $monto_reintegro;

                    $pcliente_id = $cliente_id;
                    $pnombre_cliente = $request->get('nombre');
                    $monto_deuda = $deuda_total_acumulada;
                    $monto_pagado = $pagoConVueltosCaja;
                    $monto_dolar = $pagoConVueltosCaja;
                    $monto_dolar_to_dolar = $pagoConVueltosCaja;
                    $monto_peso_to_dolar = 0;
                    $monto_bolivar_to_dolar = 0;
                    $monto_trans_to_dolar = 0;
                    $tasa_dolar = $request->get('tasaDolar');
                    $tasa_peso = $request->get('tasaPeso');
                    $tasa_bolivar = $request->get('tasaEfectivo');
                    $tasa_trans = $request->get('tasaTransPunto');
                    $observacion = 'Pago realizado automaticamente por el sistema (Pagando nueva habitacion con vueltos en caja)';
                    $operador = Auth::user()->name;
                    $user_id = Auth::user()->id;
                    $caja = Caja::where("estado","=",'Abierta')->first();
                    $caja_id = $caja->id;
                    $fecha_pago = Carbon::now();
                    
                    //recuperamos el id de la tabla preHistorial
                    $clientes_vueltos = PreExcedente::where('cliente_id', $pcliente_id)->first();
                    $phistorial_id = $clientes_vueltos->id;
                    // return $phistorial_id;
                    

                    $reintegro = new  Reintegro();
                    $reintegro->nombre_cliente = $pnombre_cliente;
                    $reintegro->monto_deuda = $monto_deuda;
                    $reintegro->monto_pagado = $monto_pagado;
                    $reintegro->monto_dolar = $monto_dolar;
                    $reintegro->monto_dolar_to_dolar = $monto_dolar_to_dolar;
                    $reintegro->monto_peso_to_dolar = $monto_peso_to_dolar;
                    $reintegro->monto_bolivar_to_dolar = $monto_bolivar_to_dolar;
                    $reintegro->monto_trans_to_dolar = $monto_trans_to_dolar;
                    $reintegro->tasa_dolar = $tasa_dolar;
                    $reintegro->tasa_peso = $tasa_peso;
                    $reintegro->tasa_bolivar = $tasa_bolivar;
                    $reintegro->tasa_trans = $tasa_trans;
                    $reintegro->observacion = $observacion;
                    $reintegro->operador = $operador;
                    $reintegro->historial_excedente_id = $phistorial_id;
                    $reintegro->user_id = $user_id;
                    $reintegro->cliente_id = $pcliente_id;
                    $reintegro->caja_id = $caja_id;
                    $reintegro->save();

                    // return $reintegro->id;
                    // TODO Guardamos los registros en la tabla Detalle pagos oficina

                    if($monto_pagado == $monto_deuda){
                        // return 'Es igual';
                        $DetallePagoOficina = new  DetallePagoOficina();
                        $DetallePagoOficina->tipo_pago = 'Efectivo';
                        $DetallePagoOficina->num_transaccion = '0001';
                        $DetallePagoOficina->deuda = $monto_deuda;
                        $DetallePagoOficina->saldo_pagado = $monto_pagado;
                        $DetallePagoOficina->fecha_pago = $fecha_pago;
                        $DetallePagoOficina->persona_id = $pcliente_id;
                        $DetallePagoOficina->caja_id = $caja_id;
                        $DetallePagoOficina->user_id = $user_id;
                        $DetallePagoOficina->save();



                        $is_cliente = PreExcedente::where('cliente_id', $pcliente_id)->first();

                        if($is_cliente){
                            if($is_cliente->deuda_total_acumulada == $is_cliente->monto_excedente_actual){
                                PreExcedente::destroy($is_cliente->id);
                                // TODO Ahora actualizamos la tabla historial_excedentes colocando el id del detalle pago oficina para poder agrupar los por ide de pago
                                // y asi poder consultarlos luego y cambiando el estatus a pagado


                                $historialExcedentes = HistorialExcedente::where('persona_id',$pcliente_id)->where('tipo_registro','Pago_por_oficina')->where('status','Pendiente')->get();

                                if($historialExcedentes){

                                    foreach ($historialExcedentes as $historialExcedente) {

                                        $HistorialExcedente = HistorialExcedente::findOrFail($historialExcedente->id);
                                        $HistorialExcedente->status = 'Pagado';
                                        $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                                        $HistorialExcedente->update();
                                    }

                                    // TODO Ahora eliminamos de la tabla excedente el registro del usuario

                                    $eliminarRegistroExcedente = Excedente::where('persona_id',$pcliente_id)->where('tipo','Pagar_por_oficina')->first();

                                    if ($eliminarRegistroExcedente) {
                                        Excedente::destroy($eliminarRegistroExcedente->id);
                                    }
                                    }

                        }else{
                            // TODO Guardamos en la tabla historial excedente
                            // return 'otro';
                        $historialExcedentes = HistorialExcedente::where('persona_id',$pcliente_id)->where('tipo_registro','Pago_por_oficina')->where('status','Pendiente')->get();

                        if($historialExcedentes){
                            $saldo_disponible = 0;
                            $motivo = '';
                            $banco_id = '';
                            $servicio_id = '';

                            foreach ($historialExcedentes as $historialExcedente) {
                                $saldo_disponible = $historialExcedente->saldo_disponible;
                                $motivo = $historialExcedente->motivo;
                                $banco_id = $historialExcedente->banco_id;
                                $servicio_id = $historialExcedente->servicio_id;

                                $HistorialExcedente = HistorialExcedente::findOrFail($historialExcedente->id);
                                $HistorialExcedente->status = 'Pagado';
                                $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                                $HistorialExcedente->update();

                            }
                        }

                        if($saldo_disponible > 0){
                            $saldo_anterior = $saldo_disponible;
                            $saldo_disponible = $saldo_disponible - $monto_pagado;

                        }

                        $HistorialExcedente = new  HistorialExcedente;
                        $HistorialExcedente->tipo_registro = 'Pago_por_oficina';
                        $HistorialExcedente->status = 'Pendiente';
                        $HistorialExcedente->tipo_operacion = 'Egreso';
                        $HistorialExcedente->num_servicio = $DetallePagoOficina->id;
                        $HistorialExcedente->motivo = $motivo;
                        $HistorialExcedente->saldo_anterior = $saldo_anterior;
                        $HistorialExcedente->saldo_operacion = $monto_pagado;
                        $HistorialExcedente->saldo_disponible = $saldo_disponible;
                        $HistorialExcedente->operador = $operador;
                        $HistorialExcedente->banco_id = $banco_id;
                        $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                        $HistorialExcedente->persona_id = $pcliente_id;
                        $HistorialExcedente->servicio_id = $servicio_id;
                        $HistorialExcedente->caja_id = $caja_id;
                        $HistorialExcedente->user_id  = $user_id;
                        $HistorialExcedente->save();


                        $UpdateExcedente = Excedente::where('persona_id', $pcliente_id)->first();
                        $UpdateExcedente->excedente -= $monto_pagado;
                        $UpdateExcedente->update();

                        if($is_cliente){
                            PreExcedente::destroy($is_cliente->id);
                        }
                        }





                    }
                    }else{
                        // return 'No es igual';
                        $DetallePagoOficina = new  DetallePagoOficina();
                        $DetallePagoOficina->tipo_pago = 'Efectivo';
                        $DetallePagoOficina->num_transaccion = '0001';
                        $DetallePagoOficina->deuda = $monto_deuda;
                        $DetallePagoOficina->saldo_pagado = $monto_pagado;
                        $DetallePagoOficina->fecha_pago = $fecha_pago;
                        $DetallePagoOficina->persona_id = $pcliente_id;
                        $DetallePagoOficina->caja_id = $caja_id;
                        $DetallePagoOficina->user_id = $user_id;
                        $DetallePagoOficina->save();

                        // return $DetallePagoOficina->id;
                        // TODO Guardamos en la tabla historial excedente

                        $historialExcedentes = HistorialExcedente::where('persona_id',$pcliente_id)->where('tipo_registro','Pago_por_oficina')->where('status','Pendiente')->get();

                        if($historialExcedentes){
                            $saldo_disponible = 0;
                            $motivo = '';
                            $banco_id = '';
                            $servicio_id = '';

                            foreach ($historialExcedentes as $historialExcedente) {
                                $saldo_disponible = $historialExcedente->saldo_disponible;
                                $motivo = $historialExcedente->motivo;
                                $banco_id = $historialExcedente->banco_id;
                                $servicio_id = $historialExcedente->servicio_id;

                                $HistorialExcedente = HistorialExcedente::findOrFail($historialExcedente->id);
                                $HistorialExcedente->status = 'Pagado';
                                $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                                $HistorialExcedente->update();

                            }
                        }
                        
                        if($saldo_disponible > 0){
                            $saldo_anterior = $saldo_disponible;
                            $saldo_disponible = $saldo_disponible - $monto_pagado;

                        }

                        $HistorialExcedente = new  HistorialExcedente;
                        $HistorialExcedente->tipo_registro = 'Pago_por_oficina';
                        $HistorialExcedente->status = 'Pendiente';
                        $HistorialExcedente->tipo_operacion = 'Egreso';
                        $HistorialExcedente->num_servicio = $DetallePagoOficina->id;
                        $HistorialExcedente->motivo = $motivo;
                        $HistorialExcedente->saldo_anterior = $saldo_anterior;
                        $HistorialExcedente->saldo_operacion = $monto_pagado;
                        $HistorialExcedente->saldo_disponible = $saldo_disponible;
                        $HistorialExcedente->operador = $operador;
                        $HistorialExcedente->banco_id = $banco_id;
                        $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                        $HistorialExcedente->persona_id = $pcliente_id;
                        $HistorialExcedente->servicio_id = $servicio_id;
                        $HistorialExcedente->caja_id = $caja_id;
                        $HistorialExcedente->user_id  = $user_id;
                        $HistorialExcedente->save();

                        
                        $UpdateExcedente = Excedente::where('persona_id', $pcliente_id)->first();
                        $UpdateExcedente->excedente -= $monto_pagado;
                        $UpdateExcedente->update();

                        $is_cliente = PreExcedente::where('cliente_id', $pcliente_id)->first();

                        if($is_cliente){
                            if($monto_reintegro > 0){
                                
                                $updatePreExcedente = PreExcedente::findOrFail($is_cliente->id);
                                $updatePreExcedente->monto_excedente_actual -= ($monto_pagado + $monto_reintegro);
                                $updatePreExcedente->deuda_total_acumulada -= $monto_pagado;
                                $updatePreExcedente->update();
                            }else{

                                $updatePreExcedente = PreExcedente::findOrFail($is_cliente->id);
                                $updatePreExcedente->monto_excedente_actual -= $monto_pagado;
                                $updatePreExcedente->deuda_total_acumulada -= $monto_pagado;
                                $updatePreExcedente->update();
                            }
                        }
                    }

                    if($pagoConVueltosCaja > 0){
                        $pago_con_vueltos_caja = 'Vueltos';
                    }
                }else{
                    // return 'entro 3';
                    //Si entra aqui es cuando solo se paga con vueltos oficina pero que tambien tiene vueltos caja.
                
                    $monto_reintegro = $request->get('pagoConExcedente');
                    
                    $monto_dejado = $request->get('monto_dejado');
                    $pagoConExcedente = $request->get('pagoConExcedente');

                    $pagoConVueltosCaja = 0;
                    
                    $is_cliente = PreExcedente::where('cliente_id', $cliente_id)->first();
                    // return $is_cliente->id;
                    if($is_cliente){
                        if($monto_reintegro > 0){
                            
                            $updatePreExcedente = PreExcedente::findOrFail($is_cliente->id);
                            $updatePreExcedente->monto_excedente_actual -= $monto_reintegro;
                            $updatePreExcedente->update();
                        }
                    }

                }
            }else{
                // return $monto_reintegro;
                $monto_reintegro = $pagoConExcedente;
            }
            // return '$montoBase';
            // return 'normal';

            // return $is_vueltos_caja;

            if($pagoConExcedente > 0){
                $modo_pago = "Contado-Excedente";

                // if($monto_dejado == 0){
                //     $modo_pago = 'Contado-Excedente';
                // }

                // if($monto_dejado > 0){
                //     $modo_pago = 'Contado-Excedente';
                // }

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
            $servicio->is_cambio = 'No';
            $servicio->status = $status;
            $servicio->precio_costo = $request->get('precio_costo');
            $servicio->cantidad = $request->get('cantidad');
            $servicio->dinero_dejado = $monto_dejado;
            $servicio->excedente_nuevo = $nuevo_excedente;
            $servicio->pago_con_excedente = $monto_reintegro ? $monto_reintegro : null;
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


            // return '$montoBase';
           

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

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
             /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    // return $pagoConVueltosCaja;

                        // TODO creamos metodo para realizar el pago cuando se paga con dinero contable viene en la variable base_vuelto_monto_dejado
                        // primero validamos si exciste un pago hecho.
                        // return '$montoBase';
                        $dispExcedente = $request->get('dispExcedente');
                        $montoBase = $pagoConVueltosCaja ? $pagoConVueltosCaja + $request->get('base_vuelto_monto_dejado') : $request->get('base_vuelto_monto_dejado');

                        // return $montoBase;
                        $montoResta = $request->get('monto_dejadoResta');
                        $total_venta = $request->get('total_costo');
                        $pcliente_id = $request->get('cliente_id');
                        $user_id = Auth::user()->id;


                        $montoBase = floatval($montoBase);
                        $montoResta = floatval($montoResta);
                        $total_venta = floatval($total_venta);
                        $monto_deuda = floatval($dispExcedente);
                        $monto_pagado = floatval($pagoConExcedente);

                        $servicio_id = $servicio->id;
                        $caja_id = $request->get('caja_id');

                        $opS = $montoBase;
                        $fecha_pago = Carbon::now();

                        if($pagoConExcedente > 0){
                            $opS += $monto_pagado;
                        }

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            //validamos si el monto pagado es mayor a 0 sea que lo paguen con montoBase o con montoPendiente y que el tipo de pago sea contado
            
                        if($opS > 0 && $modo_pago == 'contado'  || $modo_pago == 'Contado-Excedente'){
                            if($monto_pagado > 0){
                                $modo_pago = 'Contado-Excedente';
                                
                                // TODO Guardamos los registros en la tabla Detalle pagos oficina

                            if($monto_pagado == $monto_deuda){
                                $DetallePagoOficina = new  DetallePagoOficina();
                                $DetallePagoOficina->tipo_pago = 'Efectivo';
                                $DetallePagoOficina->num_transaccion = $servicio->num_servicio;
                                $DetallePagoOficina->deuda = $monto_deuda;
                                $DetallePagoOficina->saldo_pagado = $monto_pagado;
                                $DetallePagoOficina->fecha_pago = $fecha_pago;
                                $DetallePagoOficina->persona_id = $pcliente_id;
                                $DetallePagoOficina->caja_id = $caja_id;
                                $DetallePagoOficina->user_id = $user_id;
                                $DetallePagoOficina->save();

                                // TODO Ahora actualizamos la tabla historial_excedentes colocando el id del detalle pago oficina para poder agrupar los por ide de pago
                                // y asi poder consultarlos luego y cambiando el estatus a pagado


                                $historialExcedentes = HistorialExcedente::where('persona_id',$pcliente_id)->where('tipo_registro','Pago_por_oficina')->where('status','Pendiente')->get();

                                if($historialExcedentes){

                                    foreach ($historialExcedentes as $historialExcedente) {

                                        $HistorialExcedente = HistorialExcedente::findOrFail($historialExcedente->id);
                                        $HistorialExcedente->status = 'Pagado';
                                        $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                                        $HistorialExcedente->update();
                                    }

                                    // TODO Ahora eliminamos de la tabla excedente el registro del usuario

                                    $eliminarRegistroExcedente = Excedente::where('persona_id',$pcliente_id)->where('tipo','Pagar_por_oficina')->first();

                                    if ($eliminarRegistroExcedente) {
                                        Excedente::destroy($eliminarRegistroExcedente->id);
                                    }
                                }







                            }else{
                                
                                $DetallePagoOficina = new  DetallePagoOficina();
                                $DetallePagoOficina->tipo_pago = 'Efectivo';
                                $DetallePagoOficina->num_transaccion = $servicio->num_servicio;
                                $DetallePagoOficina->deuda = $monto_deuda;
                                $DetallePagoOficina->saldo_pagado = $monto_pagado;
                                $DetallePagoOficina->fecha_pago = $fecha_pago;
                                $DetallePagoOficina->persona_id = $pcliente_id;
                                $DetallePagoOficina->caja_id = $caja_id;
                                $DetallePagoOficina->user_id = $user_id;
                                $DetallePagoOficina->save();


                                // TODO Guardamos en la tabla historial excedente

                                $historialExcedentes = HistorialExcedente::where('persona_id',$pcliente_id)->where('tipo_registro','Pago_por_oficina')->where('status','Pendiente')->get();

                                // return $historialExcedentes;
                                
                                if($historialExcedentes){
                                    $saldo_disponible = 0;
                                    $motivo = '';
                                    $banco_id = '';
                                    $servicio_id = '';

                                    foreach ($historialExcedentes as $historialExcedente) {
                                        $saldo_disponible = $historialExcedente->saldo_disponible;
                                        $motivo = $historialExcedente->motivo;
                                        $banco_id = $historialExcedente->banco_id;
                                        $servicio_id = $historialExcedente->servicio_id;

                                        $HistorialExcedente = HistorialExcedente::findOrFail($historialExcedente->id);
                                        $HistorialExcedente->status = 'Pagado';
                                        $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                                        $HistorialExcedente->update();

                                    }
                                }
                                
                                if($saldo_disponible > 0){
                                    $saldo_anterior = $saldo_disponible;
                                    $saldo_disponible = $saldo_disponible - $monto_pagado;

                                }

                                $HistorialExcedente = new  HistorialExcedente;
                                $HistorialExcedente->tipo_registro = 'Pago_por_oficina';
                                $HistorialExcedente->status = 'Pendiente';
                                $HistorialExcedente->tipo_operacion = 'Egreso';
                                $HistorialExcedente->modo_pago = 'Por caja';
                                $HistorialExcedente->num_servicio = $servicio->num_servicio;
                                $HistorialExcedente->motivo = $motivo;
                                $HistorialExcedente->saldo_anterior = $saldo_anterior;
                                $HistorialExcedente->saldo_operacion = $monto_pagado;
                                $HistorialExcedente->saldo_disponible = $saldo_disponible;
                                $HistorialExcedente->operador = $operador;
                                $HistorialExcedente->banco_id = $banco_id;
                                $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                                $HistorialExcedente->persona_id = $pcliente_id;
                                $HistorialExcedente->servicio_id = $servicio->id;
                                $HistorialExcedente->caja_id = $caja_id;
                                $HistorialExcedente->user_id  = $user_id;
                                $HistorialExcedente->save();


                                $UpdateExcedente = Excedente::where('persona_id', $pcliente_id)->first();
                                $UpdateExcedente->excedente -= $monto_pagado;
                                $UpdateExcedente->update();
                                

                            }


                            }
                            // return $total_venta;
                            //validamos que el monto pagado sea mayor o igual al total de la venta
                            if($opS >= $total_venta){
                                // return 'si';
                                // calculamos excedente si el valor pagado es mayor a la venta
                                // $MontoDolarR = $request->get('MontoDolar');
                                // return $montoD;
                                //validamos si montobase es mayor y montopendiente es menor... lo que significa esto es que estamos reciviendo una moneda nueva
                                if($montoBase > 0){
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
                                            echo 'igual <br> ';
                                            echo 'value '.$value.' <br> ';
                                            echo 'restk '.$restk.' <br> ';
                                            $restk = $restk - $value;
                                            $x = floatval($x - $value);
                                            $restk = floatval($restk);
                                            echo  ' divisa: '.$p.' montoDivisa: '.$montoDiv[$p].' tasaTiket: '.$TasaT[$p].' montoDolar: '.floatval($value).' montoDolarConsumo: '.floatval($value).'  excedente:  '. 0 .' vueltos: '. 0 .'<br> ';

                                            $Pago_Servicio = new Pago_Servicio();
                                            $Pago_Servicio->Divisa = $p;
                                            $Pago_Servicio->MontoDivisa = $montoDiv[$p];
                                            $Pago_Servicio->TasaTiket = $TasaT[$p];
                                            $Pago_Servicio->MontoDolar = floatval($value);
                                            $Pago_Servicio->MontoDolarServicio = floatval($value);
                                            $Pago_Servicio->Excedente = 0;
                                            $Pago_Servicio->Vueltos = 0;
                                            $Pago_Servicio->servicio_id = $servicio_id;
                                            $Pago_Servicio->caja_id = $caja_id;
                                            $Pago_Servicio->save();



                                            echo 'excd '. 0 .' <br> ';
                                            echo 'vueltos '. 0 .' <br> ';
                                            echo $restk.' <br> ';
                                            $residuo = $restk;


                                        }else if (round($value,6) < round($restk,6)){

                                            echo 'value '.$value.' <br> ';
                                            echo 'restk '.$restk.' <br> ';
                                            echo 'menor <br> ';
                                            $restk = $restk - $value;
                                            echo  ' divisa: '.$p.' montoDivisa: '.$montoDiv[$p].' tasaTiket: '.$TasaT[$p].' montoDolar: '.floatval($value).' montoDolarConsumo: '.floatval($value).'  excedente:  '. 0 .' vueltos: '. 0 .'<br> ';


                                            $Pago_Servicio = new Pago_Servicio();
                                            $Pago_Servicio->Divisa = $p;
                                            $Pago_Servicio->MontoDivisa = $montoDiv[$p];
                                            $Pago_Servicio->TasaTiket = $TasaT[$p];
                                            $Pago_Servicio->MontoDolar = floatval($value);
                                            $Pago_Servicio->MontoDolarServicio = floatval($value);
                                            $Pago_Servicio->Excedente = 0;
                                            $Pago_Servicio->Vueltos = 0;
                                            $Pago_Servicio->servicio_id = $servicio_id;
                                            $Pago_Servicio->caja_id = $caja_id;
                                            $Pago_Servicio->save();

                                            echo 'excd '. 0 .' <br> ';
                                            echo 'vueltos '. 0 .' <br> ';
                                            echo $restk.' <br> ';
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

                                            $Pago_Servicio = new Pago_Servicio();
                                            $Pago_Servicio->Divisa = $p;
                                            $Pago_Servicio->MontoDivisa = $montoDiv[$p];
                                            $Pago_Servicio->TasaTiket = $TasaT[$p];
                                            $Pago_Servicio->MontoDolar = floatval($value);
                                            $Pago_Servicio->MontoDolarServicio = floatval($residuo);
                                            $Pago_Servicio->Excedente = $exc;
                                            $Pago_Servicio->Vueltos = $vuel;
                                            $Pago_Servicio->servicio_id = $servicio_id;
                                            $Pago_Servicio->caja_id = $caja_id;
                                            $Pago_Servicio->save();


                                            if($exc > 0){
                                                $excdtsRecibidosCaja = new Excedentes_Recibidos_Caja_Actual();
                                                $excdtsRecibidosCaja->Tipo = 'Servicio';
                                                $excdtsRecibidosCaja->Estado = 'Pendiente';
                                                $excdtsRecibidosCaja->Divisa = $p;
                                                $excdtsRecibidosCaja->MontoDivisa = floatval($exc * $TasaT[$p]);
                                                $excdtsRecibidosCaja->TasaTiket = $TasaT[$p];
                                                $excdtsRecibidosCaja->MontoDolar = floatval($exc);
                                                $excdtsRecibidosCaja->servicio_id = $servicio_id;
                                                $excdtsRecibidosCaja->venta_id = 0;
                                                $excdtsRecibidosCaja->horas_extra_id = 0;
                                                $excdtsRecibidosCaja->caja_id = $caja_id;
                                                $excdtsRecibidosCaja->save();
                                                echo  'Excedentes_Recibidos_Caja_Actual Tipo: Servicio Estado: Pendiente Divisa: '.$p.' MontoDivisa: '.floatval($exc * $TasaT[$p]).' TasaTiket: '.$TasaT[$p].'  MontoDolar:  '.floatval($exc).'<br> ';
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
                                                        $Pago_Extras_Vueltos->Tipo = 'Servicio';
                                                        $Pago_Extras_Vueltos->tipo_vuelto = 'Vueltos_Pago';
                                                        $Pago_Extras_Vueltos->Divisa = $Vdivisa[$cont];
                                                        $Pago_Extras_Vueltos->MontoDivisa = $VMontoDivisa[$cont];
                                                        $Pago_Extras_Vueltos->TasaTiket = $VTasaTiket[$cont];
                                                        $Pago_Extras_Vueltos->MontoDolar = floatval($VMontoDolar[$cont]);
                                                        $Pago_Extras_Vueltos->servicio_id = $servicio_id;
                                                        $Pago_Extras_Vueltos->venta_id = 0;
                                                        $Pago_Extras_Vueltos->horas_extra_id = 0;
                                                        $Pago_Extras_Vueltos->detalle__creditos__pagado_id = 0;
                                                        $Pago_Extras_Vueltos->caja_id = $caja_id;
                                                        $Pago_Extras_Vueltos->save();

                                                        echo  'Pago_Vuelto Tipo: Consumo  Divisa: '.$Vdivisa[$cont].' MontoDivisa: '.$VMontoDivisa[$cont].' TasaTiket: '.$VTasaTiket[$cont].' MontoDolar: '.floatval($VMontoDolar[$cont]).'<br> ';
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
                                }

                                if($pagoConVueltosCaja > 0){
                                    $Pago_Servicio = new Pago_Servicio();
                                    $Pago_Servicio->Divisa = 'Dolar';
                                    $Pago_Servicio->MontoDivisa = $pagoConVueltosCaja;
                                    $Pago_Servicio->TasaTiket = $request->get('tasaDolar');
                                    $Pago_Servicio->MontoDolar = floatval($pagoConVueltosCaja);
                                    $Pago_Servicio->MontoDolarServicio = floatval($pagoConVueltosCaja);
                                    $Pago_Servicio->Excedente = 0;
                                    $Pago_Servicio->Vueltos = 0;
                                    $Pago_Servicio->servicio_id = $servicio_id;
                                    $Pago_Servicio->caja_id = $caja_id;
                                    $Pago_Servicio->save();
                                }

                                
// return 'Finalizo';

                            }else{
                                
                                return Redirect::back()
                                ->with('status_danger', '¡Error Pago incompleto! Debe ingresar un monto para pagar y procesar el servicio... ');
                            }

                            
                        }
                        




                        
            if($modo_pago == 'contado' || $modo_pago == 'Contado-Excedente'){

                
            }
            //ahora actualizamos la tabla Habitaico con un estatus de ocupada
            $habitacion = Habitacione::findOrFail($id_habitaicon);
            $habitacion->status = 'Ocupada';
            $habitacion->update();
            
            DB::commit();

            $printer = new PrinterController;
            
            $printer->ticketServicio('Servicio', $numeroServisio, $nombreHabitacion, $detalleHabitacion, $modo_pago, $tipo_pago, $total_costo, $operador,$tipo);
            
            if( $printer->print_error === 1 ) {
                return Redirect::to('checkout')->with('status_success', 'El servicio fué registrado exitosamente');
            }else{
                return Redirect::to('checkout')->with('status_warning', 'El servicio fué registrado exitosamente. Sin embargo, no se pudo emitir el ticket con la impresora: ' . $printer->print_name );
            }
            // return $total_venta;
        }catch(\Exception $e)
        {

            // dd($e);
            DB::rollback();
            if (isset($MontoDolarR)) {

                return redirect()
                ->route('recepcion')
                ->with('status_danger', '¡Error Pago incompleto! Debe ingresar un monto para pagar y procesar el servicio... ');
            }
        }


        
        // return Redirect::to('checkout')->with('success', 'El servicio fué registrado exitosamente');
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

        // return $request;
        // TODO Este metodo maneja el cambio de habitacion


//ver

        $validarServicio = Servicio::findOrfail($id);
        // return count($validarServicio);

        if ($validarServicio) {

            $bandera = $request->get('banderaHorasExtras');


            $observacion_text = $request->get('observacion_text');
            if ($bandera == 'PagoHorasExtras') {
                // return 'si PagoHorasExtras';
                try{

                    $myTime = Carbon::now('America/Caracas');
                    // number_format($número, 2, '.', '');



                    $nombreHabitacionReg = Habitacione::findOrfail($request->get('habitacion_id_vieja'));
                    $nombreCLienteReg = Persona::findOrfail($request->get('cliente_id'));
                    $fecha_hora_entradaReg = $request->get('fecha_entrada') . ' ' . $request->get('hora_entrada');
                    $fecha_hora_salida_sugeridaReg = $request->get('fecha_salida') . ' ' . $request->get('hora_salida');
                    $fecha_hora_salida_realReg = Carbon::now('America/Caracas');
                    // $fecha_hora_salida_realData->toDateTimeString();

                    // return $fecha_hora_entradaReg . ' - ' .$fecha_hora_salida_sugeridaReg . ' - ' .$fecha_hora_salida_realReg;

                    $user_idData = $request->get('user_id');
                    $id_operacionData = $request->get('id_operacion');
                    $num_servicioData = 'CS'.$user_idData.str_pad($id_operacionData,8,'0',STR_PAD_LEFT);
                    $nombre_habitacionData = $nombreHabitacionReg->nombre;
                    $nombre_clienteData = $nombreCLienteReg->nombre;
                    $cedula_clienteData = $nombreCLienteReg->num_documento;
                    $fecha_hora_entradaData = $fecha_hora_entradaReg;
                    $fecha_hora_salida_sugeridaData = $fecha_hora_salida_sugeridaReg;
                    $fecha_hora_salida_realData = $fecha_hora_salida_realReg->toDateTimeString();
                    $precio_hora_extraData = $request->get('precioHorasExtras');
                    $cantidad_hora_extraData = $request->get('cantHorasExtras');
                    $monto_total_hora_extraData = $request->get('montoTotalHorasExtras');

                    $otros_montosData = $request->get('OtrosMontos');
                    $detalle_otros_montosData = $request->get('observacionOtrosMontos');

                    $total_horas_extras_otros_montosData = $request->get('total_costo');
                    $monto_dejado = $request->get('monto_dejado');
                    $excedente_nuevoData = $request->get('');
                    $pago_con_excedenteData = $request->get('');
                    $modo_pagoData = $request->get('');
                    $tipo_pago = $request->get('tipo_pago');
                    $servicio_idData = $request->get('');
                    $caja_idData = $request->get('');
                    $servicio_id = $id;
                    // return $servicio_id;

                    $total_costo = $request->get('total_costo');
                    $status = '';

                    // TODO we collect the variables to handle payments with surpluces


                    $pagoConExcedente = $request->get('pagoConExcedente');
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

                                $validarPagosCreditos = Pago_Credito::where('detalle__creditos__pagado_id',$credPagados->detalle__creditos__pagado_id)->get();
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


                        

                        $Restardivisa = '';
                        $RestarMontoDivisa = 0;
                        $RestarTasaTiket = 0;
                        $RestarMontoDolar = 0;

                        

                        // ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        // ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


                        // // return $dolarDisponible;
                        // // return $pesoDisponible;
                        // // return $bolivarDisponible;

                        // TODO Se procede a unir el vuelto pagado que ahora es dinero contado con el dinero_dejado si lo hubiera para procesar el pago
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
                    // TODO Actualizamos el servicio principal o el servicio donde se genero el primer pago (D/vueltos)


                    // return $monto_dejado;

                    // TODO Fin de metodo para pagar con vueltos pendientes
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////



                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    


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

                        }

                        if($monto_dejado > $total_costo){
                            $status = 'Pagado';
                            $nuevo_excedente = $monto_dejado - $total_costo;

                        }

                        
                    }

                    if($modo_pago == 'Contado-Excedente'){
                        $status = 'Pagado';
                    }

                    if($modo_pago == 'Excedente'){
                        $status = 'Pagado';
                    }




                    $horasExtras = new Horas_extra();
                    $horasExtras->num_servicio = $num_servicioData;
                    $horasExtras->nombre_habitacion = $nombre_habitacionData;
                    $horasExtras->nombre_cliente = $nombre_clienteData;
                    $horasExtras->cedula_cliente = $cedula_clienteData;
                    $horasExtras->fecha_hora_entrada = $fecha_hora_entradaData;
                    $horasExtras->fecha_hora_salida_sugerida = $fecha_hora_salida_sugeridaData;
                    $horasExtras->fecha_hora_salida_real = $fecha_hora_salida_realData;
                    $horasExtras->precio_hora_extra = $precio_hora_extraData;
                    $horasExtras->cantidad_hora_extra = $cantidad_hora_extraData;
                    $horasExtras->monto_total_hora_extra = $monto_total_hora_extraData;
                    $horasExtras->otros_montos = $otros_montosData;
                    $horasExtras->detalle_otros_montos = $detalle_otros_montosData;
                    $horasExtras->total_horas_extras_otros_montos = $total_horas_extras_otros_montosData;
                    $horasExtras->dinero_dejado = $monto_dejado;
                    $horasExtras->excedente_nuevo = $nuevo_excedente;
                    $horasExtras->pago_con_excedente = $pagoConExcedente;
                    $horasExtras->modo_pago = $modo_pago;
                    $horasExtras->tipo_pago = $tipo_pago;
                    $horasExtras->status = $status;
                    $horasExtras->servicio_id = $id;
                    $horasExtras->caja_id = $request->get('caja_id');
                    $horasExtras->save();


                    

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
                                $detalleCredito->numero_factura = $num_servicioData;
                                $detalleCredito->tipo_operacion = 'Horas_Extras';
                                $detalleCredito->operacion_id = $id;
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
                                $detalleCredito->numero_factura = $num_servicioData;
                                $detalleCredito->tipo_operacion = 'Horas_Extras';
                                $detalleCredito->operacion_id = $id;
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
                            $detalleCredito->numero_factura = $num_servicioData;
                            $detalleCredito->tipo_operacion = 'Horas_Extras';
                            $detalleCredito->operacion_id = $id;
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
                            $cortesia->servicio_id = $id;
                            $cortesia->save();
                    }


                     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        // TODO creamos metodo para realizar el pago cuando se paga con dinero contable viene en la variable base_vuelto_monto_dejado
                        // primero validamos si exciste un pago hecho.

                        $montoBase = $request->get('base_vuelto_monto_dejado');
                        $montoResta = $request->get('monto_dejadoResta');
                        $montoPendiente = $request->get('VueltospagoConExcedente');
                        $total_venta = $request->get('total_costo');


                        $montoBase = floatval($montoBase);
                        $montoPendiente = floatval($montoPendiente);
                        $montoResta = floatval($montoResta);
                        $total_venta = floatval($total_venta);

                        $opS = $montoBase + $montoPendiente;

                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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

                                            $Pago_Extra = new Pago_Extra();
                                            $Pago_Extra->Divisa = $p;
                                            $Pago_Extra->MontoDivisa = $montoDiv[$p];
                                            $Pago_Extra->TasaTiket = $TasaT[$p];
                                            $Pago_Extra->MontoDolar = floatval($value);
                                            $Pago_Extra->MontoDolarHoraExctra = floatval($value);
                                            $Pago_Extra->Excedente = 0;
                                            $Pago_Extra->Vueltos = 0;
                                            $Pago_Extra->horas_extra_id = $horasExtras->id;
                                            $Pago_Extra->servicio_id = $servicio_id;
                                            $Pago_Extra->caja_id = $request->get('caja_id');
                                            $Pago_Extra->save();

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

                                            $Pago_Extra = new Pago_Extra();
                                            $Pago_Extra->Divisa = $p;
                                            $Pago_Extra->MontoDivisa = $montoDiv[$p];
                                            $Pago_Extra->TasaTiket = $TasaT[$p];
                                            $Pago_Extra->MontoDolar = floatval($value);
                                            $Pago_Extra->MontoDolarHoraExctra = floatval($value);
                                            $Pago_Extra->Excedente = 0;
                                            $Pago_Extra->Vueltos = 0;
                                            $Pago_Extra->horas_extra_id = $horasExtras->id;
                                            $Pago_Extra->servicio_id = $servicio_id;
                                            $Pago_Extra->caja_id = $request->get('caja_id');
                                            $Pago_Extra->save();

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
                                            $Pago_Extra = new Pago_Extra();
                                            $Pago_Extra->Divisa = $p;
                                            $Pago_Extra->MontoDivisa = $montoDiv[$p];
                                            $Pago_Extra->TasaTiket = $TasaT[$p];
                                            $Pago_Extra->MontoDolar = floatval($value);
                                            $Pago_Extra->MontoDolarHoraExctra = floatval($residuo);
                                            $Pago_Extra->Excedente = $exc;
                                            $Pago_Extra->Vueltos = $vuel;
                                            $Pago_Extra->horas_extra_id = $horasExtras->id;
                                            $Pago_Extra->servicio_id = $servicio_id;
                                            $Pago_Extra->caja_id = $request->get('caja_id');
                                            $Pago_Extra->save();

                                            if($exc > 0){
                                                $excdtsRecibidosCaja = new Excedentes_Recibidos_Caja_Actual();
                                                $excdtsRecibidosCaja->Tipo = 'Horas_Extras';
                                                $excdtsRecibidosCaja->Estado = 'Pendiente';
                                                $excdtsRecibidosCaja->Divisa = $p;
                                                $excdtsRecibidosCaja->MontoDivisa = floatval($exc * $TasaT[$p]);
                                                $excdtsRecibidosCaja->TasaTiket = $TasaT[$p];
                                                $excdtsRecibidosCaja->MontoDolar = floatval($exc);
                                                $excdtsRecibidosCaja->servicio_id = $servicio_id;
                                                $excdtsRecibidosCaja->venta_id = 0;
                                                $excdtsRecibidosCaja->horas_extra_id = $horasExtras->id;
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
                                                        $Pago_Extras_Vueltos->Tipo = 'Horas_Extras';
                                                        $Pago_Extras_Vueltos->tipo_vuelto = 'Vueltos_Pago';
                                                        $Pago_Extras_Vueltos->Divisa = $Vdivisa[$cont];
                                                        $Pago_Extras_Vueltos->MontoDivisa = $VMontoDivisa[$cont];
                                                        $Pago_Extras_Vueltos->TasaTiket = $VTasaTiket[$cont];
                                                        $Pago_Extras_Vueltos->MontoDolar = $VMontoDolar[$cont];
                                                        $Pago_Extras_Vueltos->servicio_id = $servicio_id;
                                                        $Pago_Extras_Vueltos->venta_id = 0;
                                                        $Pago_Extras_Vueltos->horas_extra_id = $horasExtras->id;
                                                        $Pago_Extras_Vueltos->detalle__creditos__pagado_id = 0;
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
                                                    $AgregarVtossPtesToVtosPtes->Tipo = $RestarVtossPtes->Tipo;
                                                    $AgregarVtossPtesToVtosPtes->Estado = 'Devueltos';
                                                    $AgregarVtossPtesToVtosPtes->Divisa = $RestarVtossPtes->Divisa;
                                                    $AgregarVtossPtesToVtosPtes->MontoDivisa = floatval($value * $RestarVtossPtes->TasaTiket);
                                                    $AgregarVtossPtesToVtosPtes->TasaTiket = $RestarVtossPtes->TasaTiket;
                                                    $AgregarVtossPtesToVtosPtes->MontoDolar = floatval($value);
                                                    $AgregarVtossPtesToVtosPtes->servicio_id = $servicio_id;
                                                    $AgregarVtossPtesToVtosPtes->venta_id = 0;
                                                    $AgregarVtossPtesToVtosPtes->horas_extra_id = $horasExtras->id;
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
                                                        $Pago_Consumo_Vueltos->Tipo = $RestarVtossPtes->Tipo;
                                                        $Pago_Consumo_Vueltos->tipo_vuelto = 'Vueltos_Excedentes';
                                                        $Pago_Consumo_Vueltos->Divisa = $Restardivisa;
                                                        $Pago_Consumo_Vueltos->MontoDivisa = $RestarMontoDivisa;
                                                        $Pago_Consumo_Vueltos->TasaTiket = $RestarTasaTiket;
                                                        $Pago_Consumo_Vueltos->MontoDolar = $RestarMontoDolar;
                                                        $Pago_Consumo_Vueltos->servicio_id = $servicio_id;
                                                        $Pago_Consumo_Vueltos->venta_id = 0;
                                                        $Pago_Consumo_Vueltos->horas_extra_id = $horasExtras->id;
                                                        $Pago_Consumo_Vueltos->detalle__creditos__pagado_id = 0;
                                                        $Pago_Consumo_Vueltos->caja_id = $request->get('caja_id');
                                                        $Pago_Consumo_Vueltos->save();

                                                        $tipo_pago = $Restardivisa;
                                                    // REVIEW REVISAR

                                                    $Pago_Extra = new Pago_Extra();
                                                    $Pago_Extra->Divisa = $Restardivisa;
                                                    $Pago_Extra->MontoDivisa = $RestarMontoDivisa;
                                                    $Pago_Extra->TasaTiket = $RestarTasaTiket;
                                                    $Pago_Extra->MontoDolar = floatval($RestarMontoDolar);
                                                    $Pago_Extra->MontoDolarHoraExctra = floatval($RestarMontoDolar);
                                                    $Pago_Extra->Excedente = 0;
                                                    $Pago_Extra->Vueltos = 0;
                                                    $Pago_Extra->horas_extra_id = $horasExtras->id;
                                                    $Pago_Extra->servicio_id = $servicio_id;
                                                    $Pago_Extra->caja_id = $request->get('caja_id');
                                                    $Pago_Extra->save();
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
                                                    $AgregarVtossPtesToVtosPtes->Tipo = $RestarVtossPtes->Tipo;
                                                    $AgregarVtossPtesToVtosPtes->Estado = 'Devueltos';
                                                    $AgregarVtossPtesToVtosPtes->Divisa = $RestarVtossPtes->Divisa;
                                                    $AgregarVtossPtesToVtosPtes->MontoDivisa = floatval($restk * $RestarVtossPtes->TasaTiket);
                                                    $AgregarVtossPtesToVtosPtes->TasaTiket = $RestarVtossPtes->TasaTiket;
                                                    $AgregarVtossPtesToVtosPtes->MontoDolar = floatval($restk);
                                                    $AgregarVtossPtesToVtosPtes->servicio_id = $servicio_id;
                                                    $AgregarVtossPtesToVtosPtes->venta_id = 0;
                                                    $AgregarVtossPtesToVtosPtes->horas_extra_id = $horasExtras->id;
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
                                                        $Pago_Consumo_Vueltos->Tipo = $RestarVtossPtes->Tipo;
                                                        $Pago_Consumo_Vueltos->tipo_vuelto = 'Vueltos_Excedentes';
                                                        $Pago_Consumo_Vueltos->Divisa = $Restardivisa;
                                                        $Pago_Consumo_Vueltos->MontoDivisa = $RestarMontoDivisa;
                                                        $Pago_Consumo_Vueltos->TasaTiket = $RestarTasaTiket;
                                                        $Pago_Consumo_Vueltos->MontoDolar = $RestarMontoDolar;
                                                        $Pago_Consumo_Vueltos->servicio_id = $servicio_id;
                                                        $Pago_Consumo_Vueltos->venta_id = 0;
                                                        $Pago_Consumo_Vueltos->horas_extra_id = $horasExtras->id;
                                                        $Pago_Consumo_Vueltos->detalle__creditos__pagado_id = 0;
                                                        $Pago_Consumo_Vueltos->caja_id = $request->get('caja_id');
                                                        $Pago_Consumo_Vueltos->save();

                                                        $tipo_pago = $Restardivisa;
                                                // REVIEW REVISAR

                                                    $Pago_Extra = new Pago_Extra();
                                                    $Pago_Extra->Divisa = $Restardivisa;
                                                    $Pago_Extra->MontoDivisa = $RestarMontoDivisa;
                                                    $Pago_Extra->TasaTiket = $RestarTasaTiket;
                                                    $Pago_Extra->MontoDolar = floatval($RestarMontoDolar);
                                                    $Pago_Extra->MontoDolarHoraExctra = floatval($RestarMontoDolar);
                                                    $Pago_Extra->Excedente = 0;
                                                    $Pago_Extra->Vueltos = 0;
                                                    $Pago_Extra->horas_extra_id = $horasExtras->id;
                                                    $Pago_Extra->servicio_id = $servicio_id;
                                                    $Pago_Extra->caja_id = $request->get('caja_id');
                                                    $Pago_Extra->save();
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
                                                    $AgregarVtossPtesToVtosPtes->Tipo = $RestarVtossPtes->Tipo;
                                                    $AgregarVtossPtesToVtosPtes->Estado = 'Devueltos';
                                                    $AgregarVtossPtesToVtosPtes->Divisa = $RestarVtossPtes->Divisa;
                                                    $AgregarVtossPtesToVtosPtes->MontoDivisa = floatval($value * $RestarVtossPtes->TasaTiket);
                                                    $AgregarVtossPtesToVtosPtes->TasaTiket = $RestarVtossPtes->TasaTiket;
                                                    $AgregarVtossPtesToVtosPtes->MontoDolar = floatval($value);
                                                    $AgregarVtossPtesToVtosPtes->servicio_id = $servicio_id;
                                                    $AgregarVtossPtesToVtosPtes->venta_id = 0;
                                                    $AgregarVtossPtesToVtosPtes->horas_extra_id = $horasExtras->id;
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
                                                        $Pago_Consumo_Vueltos->Tipo = $RestarVtossPtes->Tipo;
                                                        $Pago_Consumo_Vueltos->tipo_vuelto = 'Vueltos_Excedentes';
                                                        $Pago_Consumo_Vueltos->Divisa = $Restardivisa;
                                                        $Pago_Consumo_Vueltos->MontoDivisa = $RestarMontoDivisa;
                                                        $Pago_Consumo_Vueltos->TasaTiket = $RestarTasaTiket;
                                                        $Pago_Consumo_Vueltos->MontoDolar = $RestarMontoDolar;
                                                        $Pago_Consumo_Vueltos->servicio_id = $servicio_id;
                                                        $Pago_Consumo_Vueltos->venta_id = 0;
                                                        $Pago_Consumo_Vueltos->horas_extra_id = $horasExtras->id;
                                                        $Pago_Consumo_Vueltos->detalle__creditos__pagado_id = 0;
                                                        $Pago_Consumo_Vueltos->caja_id = $request->get('caja_id');
                                                        $Pago_Consumo_Vueltos->save();

                                                        $tipo_pago = $Restardivisa;
                                                // REVIEW REVISAR

                                                    $Pago_Extra = new Pago_Extra();
                                                    $Pago_Extra->Divisa = $Restardivisa;
                                                    $Pago_Extra->MontoDivisa = $RestarMontoDivisa;
                                                    $Pago_Extra->TasaTiket = $RestarTasaTiket;
                                                    $Pago_Extra->MontoDolar = floatval($RestarMontoDolar);
                                                    $Pago_Extra->MontoDolarHoraExctra = floatval($RestarMontoDolar);
                                                    $Pago_Extra->Excedente = 0;
                                                    $Pago_Extra->Vueltos = 0;
                                                    $Pago_Extra->horas_extra_id = $horasExtras->id;
                                                    $Pago_Extra->servicio_id = $servicio_id;
                                                    $Pago_Extra->caja_id = $request->get('caja_id');
                                                    $Pago_Extra->save();
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
                                                    $AgregarVtossPtesToVtosPtes->Tipo = $RestarVtossPtes->Tipo;
                                                    $AgregarVtossPtesToVtosPtes->Estado = 'Devueltos';
                                                    $AgregarVtossPtesToVtosPtes->Divisa = $RestarVtossPtes->Divisa;
                                                    $AgregarVtossPtesToVtosPtes->MontoDivisa = floatval($restk * $RestarVtossPtes->TasaTiket);
                                                    $AgregarVtossPtesToVtosPtes->TasaTiket = $RestarVtossPtes->TasaTiket;
                                                    $AgregarVtossPtesToVtosPtes->MontoDolar = floatval($restk);
                                                    $AgregarVtossPtesToVtosPtes->servicio_id = $servicio_id;
                                                    $AgregarVtossPtesToVtosPtes->venta_id = 0;
                                                    $AgregarVtossPtesToVtosPtes->horas_extra_id = $horasExtras->id;
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
                                                        $Pago_Consumo_Vueltos->Tipo = $RestarVtossPtes->Tipo;
                                                        $Pago_Consumo_Vueltos->tipo_vuelto = 'Vueltos_Excedentes';
                                                        $Pago_Consumo_Vueltos->Divisa = $Restardivisa;
                                                        $Pago_Consumo_Vueltos->MontoDivisa = $RestarMontoDivisa;
                                                        $Pago_Consumo_Vueltos->TasaTiket = $RestarTasaTiket;
                                                        $Pago_Consumo_Vueltos->MontoDolar = $RestarMontoDolar;
                                                        $Pago_Consumo_Vueltos->servicio_id = $servicio_id;
                                                        $Pago_Consumo_Vueltos->venta_id = 0;
                                                        $Pago_Consumo_Vueltos->horas_extra_id = $horasExtras->id;
                                                        $Pago_Consumo_Vueltos->detalle__creditos__pagado_id = 0;
                                                        $Pago_Consumo_Vueltos->caja_id = $request->get('caja_id');
                                                        $Pago_Consumo_Vueltos->save();

                                                        $tipo_pago = $Restardivisa;
                                                // REVIEW REVISAR

                                                    $Pago_Extra = new Pago_Extra();
                                                    $Pago_Extra->Divisa = $Restardivisa;
                                                    $Pago_Extra->MontoDivisa = $RestarMontoDivisa;
                                                    $Pago_Extra->TasaTiket = $RestarTasaTiket;
                                                    $Pago_Extra->MontoDolar = floatval($RestarMontoDolar);
                                                    $Pago_Extra->MontoDolarHoraExctra = floatval($RestarMontoDolar);
                                                    $Pago_Extra->Excedente = 0;
                                                    $Pago_Extra->Vueltos = 0;
                                                    $Pago_Extra->horas_extra_id = $horasExtras->id;
                                                    $Pago_Extra->servicio_id = $servicio_id;
                                                    $Pago_Extra->caja_id = $request->get('caja_id');
                                                    $Pago_Extra->save();
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

                                            $Pago_Extra = new Pago_Extra();
                                            $Pago_Extra->Divisa = $p;
                                            $Pago_Extra->MontoDivisa = $montoDiv[$p];
                                            $Pago_Extra->TasaTiket = $TasaT[$p];
                                            $Pago_Extra->MontoDolar = floatval($value);
                                            $Pago_Extra->MontoDolarHoraExctra = floatval($value);
                                            $Pago_Extra->Excedente = 0;
                                            $Pago_Extra->Vueltos = 0;
                                            $Pago_Extra->horas_extra_id = $horasExtras->id;
                                            $Pago_Extra->servicio_id = $servicio_id;
                                            $Pago_Extra->caja_id = $request->get('caja_id');
                                            $Pago_Extra->save();

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

                                            $Pago_Extra = new Pago_Extra();
                                            $Pago_Extra->Divisa = $p;
                                            $Pago_Extra->MontoDivisa = $montoDiv[$p];
                                            $Pago_Extra->TasaTiket = $TasaT[$p];
                                            $Pago_Extra->MontoDolar = floatval($value);
                                            $Pago_Extra->MontoDolarHoraExctra = floatval($value);
                                            $Pago_Extra->Excedente = 0;
                                            $Pago_Extra->Vueltos = 0;
                                            $Pago_Extra->horas_extra_id = $horasExtras->id;
                                            $Pago_Extra->servicio_id = $servicio_id;
                                            $Pago_Extra->caja_id = $request->get('caja_id');
                                            $Pago_Extra->save();
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

                                            $Pago_Extra = new Pago_Extra();
                                            $Pago_Extra->Divisa = $p;
                                            $Pago_Extra->MontoDivisa = $montoDiv[$p];
                                            $Pago_Extra->TasaTiket = $TasaT[$p];
                                            $Pago_Extra->MontoDolar = floatval($value);
                                            $Pago_Extra->MontoDolarHoraExctra = floatval($residuo);
                                            $Pago_Extra->Excedente = $exc;
                                            $Pago_Extra->Vueltos = $vuel;
                                            $Pago_Extra->horas_extra_id = $horasExtras->id;
                                            $Pago_Extra->servicio_id = $servicio_id;
                                            $Pago_Extra->caja_id = $request->get('caja_id');
                                            $Pago_Extra->save();

                                            if($exc > 0){
                                                $excdtsRecibidosCaja = new Excedentes_Recibidos_Caja_Actual();
                                                $excdtsRecibidosCaja->Tipo = 'Horas_Extras';
                                                $excdtsRecibidosCaja->Estado = 'Pendiente';
                                                $excdtsRecibidosCaja->Divisa = $p;
                                                $excdtsRecibidosCaja->MontoDivisa = floatval($exc * $TasaT[$p]);
                                                $excdtsRecibidosCaja->TasaTiket = $TasaT[$p];
                                                $excdtsRecibidosCaja->MontoDolar = floatval($exc);
                                                $excdtsRecibidosCaja->servicio_id = $servicio_id;
                                                $excdtsRecibidosCaja->venta_id = 0;
                                                $excdtsRecibidosCaja->horas_extra_id = $horasExtras->id;
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
                                                        $Pago_Extras_Vueltos->Tipo = 'Horas_Extras';
                                                        $Pago_Extras_Vueltos->tipo_vuelto = 'Vueltos_Excedentes';
                                                        $Pago_Extras_Vueltos->Divisa = $Vdivisa[$cont];
                                                        $Pago_Extras_Vueltos->MontoDivisa = $VMontoDivisa[$cont];
                                                        $Pago_Extras_Vueltos->TasaTiket = $VTasaTiket[$cont];
                                                        $Pago_Extras_Vueltos->MontoDolar = $VMontoDolar[$cont];
                                                        $Pago_Extras_Vueltos->servicio_id = $servicio_id;
                                                        $Pago_Extras_Vueltos->venta_id = 0;
                                                        $Pago_Extras_Vueltos->horas_extra_id = $horasExtras->id;
                                                        $Pago_Extras_Vueltos->detalle__creditos__pagado_id = 0;
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

                                $cantidadPagos = Pago_Extra::where('horas_extra_id', $horasExtras->id)->get();

                                $cantVentas = count($cantidadPagos);

                                if($cantVentas >= 2){
                                    $actualizarVentasToMixto = Horas_extra::findOrfail($horasExtras->id);
                                    $actualizarVentasToMixto->tipo_pago = 'Mixto';
                                    $actualizarVentasToMixto->update();


                                }
// return 'Finalizo';

                            }else{
                                return Redirect::back()
                                ->with('status_danger', '¡Error Pago incompleto! Debe ingresar un monto para pagar y procesar el servicio... ');
                            }

                            // return 'no';
                        }
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////





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
                        //         $Pago_Extra = new Pago_Extra();
                        //         $Pago_Extra->Divisa = $divisa[$cont];
                        //         $Pago_Extra->MontoDivisa = $MontoDivisa[$cont];
                        //         $Pago_Extra->TasaTiket = $TasaTiket[$cont];
                        //         $Pago_Extra->MontoDolar = $MontoDolar[$cont];
                        //         $Pago_Extra->Vueltos = $Vueltos[$cont];
                        //         $Pago_Extra->horas_extra_id = $horasExtras->id;
                        //         $Pago_Extra->servicio_id = $id;
                        //         $Pago_Extra->caja_id = $request->get('caja_id');
                        //         $Pago_Extra->save();

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


                        //             $Pago_Extras_Vueltos = new Pago_Vuelto();
                        //             $Pago_Extras_Vueltos->Tipo = 'Horas_Extras';
                        //             $Pago_Extras_Vueltos->Divisa = $Vdivisa[$cont];
                        //             $Pago_Extras_Vueltos->MontoDivisa = $VMontoDivisa[$cont];
                        //             $Pago_Extras_Vueltos->TasaTiket = $VTasaTiket[$cont];
                        //             $Pago_Extras_Vueltos->MontoDolar = $VMontoDolar[$cont];
                        //             $Pago_Extras_Vueltos->servicio_id = $id;
                        //             $Pago_Extras_Vueltos->venta_id = 0;
                        //             $Pago_Extras_Vueltos->caja_id = $request->get('caja_id');
                        //             $Pago_Extras_Vueltos->save();

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

                        //         $pagoVueltos = Pago_Vuelto::findOrFail($Pago_Extras_Vueltos->id);
                        //         // return $pagoVueltos;

                        //         if ($pagoVueltos) {

                        //             // return $Pago_Extra->id;
                        //             $UdatePagoServiciosConVtosPendientes = Pago_Extra::findOrFail($Pago_Extra->id);
                        //             // return $UdatePagoServiciosConVtosPendientes;
                        //             if ($UdatePagoServiciosConVtosPendientes->Divisa == $pagoVueltos->Divisa) {
                        //                 $UdatePagoServiciosConVtosPendientes->MontoDivisa = $UdatePagoServiciosConVtosPendientes->MontoDivisa + $pagoVueltos->MontoDivisa;
                        //                 $UdatePagoServiciosConVtosPendientes->MontoDolar = $UdatePagoServiciosConVtosPendientes->MontoDolar + $pagoVueltos->MontoDolar;
                        //                 // return $UdatePagoServiciosConVtosPendientes->MontoDivisa;
                        //                 $UdatePagoServiciosConVtosPendientes->update();
                        //             }else{
                        //                 $Pago_Extra = new Pago_Extra();
                        //                 $Pago_Extra->Divisa = $pagoVueltos->Divisa;
                        //                 $Pago_Extra->MontoDivisa = $pagoVueltos->MontoDivisa;
                        //                 $Pago_Extra->TasaTiket = $pagoVueltos->TasaTiket;
                        //                 $Pago_Extra->MontoDolar = $pagoVueltos->MontoDolar;
                        //                 $Pago_Extra->Vueltos = 0;
                        //                 $Pago_Extra->horas_extra_id = $horasExtras->id;
                        //                 $Pago_Extra->servicio_id = $id;
                        //                 $Pago_Extra->caja_id = $request->get('caja_id');
                        //                 $Pago_Extra->save();
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

                        //         // $Restardivisa = '';
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

                        //             $Pago_Extra = new Pago_Extra();
                        //             $Pago_Extra->Divisa = $pagoVueltos->Divisa;
                        //             $Pago_Extra->MontoDivisa = $pagoVueltos->MontoDivisa;
                        //             $Pago_Extra->TasaTiket = $pagoVueltos->TasaTiket;
                        //             $Pago_Extra->MontoDolar = $pagoVueltos->MontoDolar;
                        //             $Pago_Extra->Vueltos = -$isVuelos;
                        //             $Pago_Extra->horas_extra_id = $horasExtras->id;
                        //             $Pago_Extra->servicio_id = $id;
                        //             $Pago_Extra->caja_id = $request->get('caja_id');
                        //             $Pago_Extra->save();



                        //         }

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
                        //                 $Pago_Extras_Vueltos->Tipo = 'Horas_Extras';
                        //                 $Pago_Extras_Vueltos->Divisa = $Vdivisa[$cont];
                        //                 $Pago_Extras_Vueltos->MontoDivisa = $VMontoDivisa[$cont];
                        //                 $Pago_Extras_Vueltos->TasaTiket = $VTasaTiket[$cont];
                        //                 $Pago_Extras_Vueltos->MontoDolar = $VMontoDolar[$cont];
                        //                 $Pago_Extras_Vueltos->servicio_id = $id;
                        //                 $Pago_Extras_Vueltos->venta_id = 0;
                        //                 $Pago_Extras_Vueltos->caja_id = $request->get('caja_id');
                        //                 $Pago_Extras_Vueltos->save();

                        //                 $cont = $cont+1;
                        //             }

                        //         }
                        //         ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        //         ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////






                        //     }

                        //     ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        //     ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        // }


                    }

                    // TODO Este codigo se ejecuta solo si escogieron la misma habitacion que esta en uso en otras palabras
                    //si solo estan cambiando de servicio y no de habitacion



                        //ahora actualizamos la tabla Habitacion con un estatus de ocupada
                        // $habitacion = Habitacione::findOrFail($id_habitaicon);
                        // $habitacion->status = 'Ocupada';
                        // $habitacion->update();

                        // $servicio_id = Servicio::where('habitacion_id',$habitacion_id_vieja)->where('status_servicio', 'Iniciado')->where('id', $id)->first();
                        // //    return $servicio_id;
                        // if($servicio_id){
                        //     // return 'todo bien';
                        //     $servicio = Servicio::findOrFail($servicio_id->id);
                        //     $servicio->status_servicio = 'Finalizado';
                        //     $servicio->update();

                        //     if($request->get('habitacion_id_nueva2') <> $request->get('habitacion_id_vieja')){

                        //         $habitacionCambio = Habitacione::findOrFail($habitacion_id_vieja);
                        //         $habitacionCambio->status = 'Limpieza';
                        //         $habitacionCambio->update();
                        //     }
                        // }


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
                $verificarHorasExtras = Horas_extra::findOrFail($horasExtras->id);

                // $titulo
                // $nombre_habitacion = $verificarHorasExtras->nombre_habitacion;
                // $num_servicio = $verificarHorasExtras->num_servicio;
                // $modo_pago = $verificarHorasExtras->modo_pago;
                // $tipo_pago = $verificarHorasExtras->tipo_pago;
                // $monto_total_hora_extra = $verificarHorasExtras->monto_total_hora_extra;
                // $cantidad_hora_extra = $verificarHorasExtras->cantidad_hora_extra;
                // $precio_hora_extra = $verificarHorasExtras->precio_hora_extra;
                // $otros_montos = ;
                // $total_horas_extras_otros_montos = ;
                // return $verificarHorasExtras->monto_total_hora_extra;
                $printer->ticketPagoExtra('Pagos Extras','Servicio', $verificarHorasExtras);
                if( $printer->print_error === 1 ) {
                    return Redirect::to('checkout/'.$id)->with('status_success', 'El servicio fué registrado exitosamente');
                }else{
                    return Redirect::to('checkout/'.$id)->with('status_warning', 'El servicio fué registrado exitosamente. Sin embargo, no se pudo emitir el ticket con la impresora: ' . $printer->print_name );
                }
                // return view('checkin.checkin.index', compact('title','tasas'));
                // return Redirect::to('checkout/'.$id)->with('success', 'El servicio fué registrado exitosamente');



            } else if ($bandera == 'pagarVueltosPendientes') {
                // return $request;
                // TODO validamos que lleguen las variables base_vuelto_monto_dejado y monto_dejadoResta para ver si existen diferencia
                //lo que siginifica que si de la resta de monto_dejadoResta - base_vuelto_monto_dejado > 0 entonses significa que aparte de
                //pagar con vueltos pendientes tambien dieron dinero en efectivo ejemplo. si yo debo regresar 4 dolares pero no los tengo
                // entonses el cliente me da 1 dolar y así yo regresaria un billete de 5 dolares para eso es que verificamos si dieron dinero
                //en efectivo.

                //recuperamos las variables.

                try{

                    $VueltospagoConExcedente = $request->get('VueltospagoConExcedente');

                    if($VueltospagoConExcedente > 0 || !$VueltospagoConExcedente = null){

                        $VueltosdispExcedente = $request->get('VueltosdispExcedente');
                        $monto_dejadoResta = $request->get('monto_dejadoResta');


                        $base_vuelto_monto_dejado = $request->get('base_vuelto_monto_dejado');
                        $monto_dejado = $request->get('monto_dejado');
                        $modo_pago = $request->get('modo_pago');

                        $resta = $monto_dejadoResta - $base_vuelto_monto_dejado;

                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        // TODO creamos metodo para realizar el pago cuando se paga con dinero contable viene en la variable base_vuelto_monto_dejado
                        // primero validamos si exciste un pago hecho.

                        $montoBase = $request->get('base_vuelto_monto_dejado');
                        $montoResta = $request->get('monto_dejadoResta');
                        $montoPendiente = $request->get('VueltospagoConExcedente');
                        // $total_venta = $request->get('total_venta');


                        $montoBase = floatval($montoBase);
                        $montoPendiente = floatval($montoPendiente);
                        $montoResta = floatval($montoResta);
                        // $total_venta = floatval($total_venta);

                        $opS = $montoBase + $montoPendiente;

                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        //                         if($base_vuelto_monto_dejado > 0){
                        // return 'es mayor';
                        //                         }else{
                        //                             return 'es menor';
                        //                         }

                        // return $opS;

                        if($opS > 0 && $modo_pago == 'contado'){

                            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            // Aquí llenamos la tabla Excedente_Recibidos_Caja_Acatual con la divisa que deja el cliente para acompletar el vuelto

                            // $isVuelos = $request->get('isVueltos');

                            if($base_vuelto_monto_dejado > 0){
                                // return 'mayor a 0 ' . $resta;
                                $MontoDivisa = $request->get('MontoDivisa');
                                $divisa = $request->get('divisa');
                                $TasaTiket = $request->get('TasaTike');
                                $MontoDolar = $request->get('MontoDolar');

                                $MontoDivisa = array_filter($MontoDivisa);

                                // return $MontoDivisa;
                                if(count($MontoDivisa)){
                                    foreach($MontoDivisa as $key => $val) {


                                        $Vdivisa[]=$divisa[$key];
                                        $VMontoDivisa[]=$MontoDivisa[$key];
                                        $VTasaTiket[]=$TasaTiket[$key];
                                        $VMontoDolar[]=$MontoDolar[$key];

                                    }

                                    $cont = 0;
                                    // return 'mayor a 0 ' . $resta;

                                    while ($cont < count($VMontoDolar)) {


                                        $excdtsRecibidosCaja = new Excedentes_Recibidos_Caja_Actual();
                                        $excdtsRecibidosCaja->Tipo = 'Servicio';
                                        $excdtsRecibidosCaja->Estado = 'Pendiente';
                                        $excdtsRecibidosCaja->Divisa = $Vdivisa[$cont];
                                        $excdtsRecibidosCaja->TasaTiket = $VTasaTiket[$cont];
                                        $excdtsRecibidosCaja->MontoDivisa = $VMontoDivisa[$cont];
                                        $excdtsRecibidosCaja->MontoDolar = $VMontoDolar[$cont];
                                        $excdtsRecibidosCaja->servicio_id = $id;
                                        $excdtsRecibidosCaja->venta_id = 0;
                                        $excdtsRecibidosCaja->caja_id = $request->get('caja_id');
                                        $excdtsRecibidosCaja->save();


                                        echo  'Agregamos tabla excedente divisa: '.$Vdivisa[$cont].' montoDivisa: '.$VMontoDivisa[$cont].' tasaTiket: '.$VTasaTiket[$cont].' montoDolar: '.floatval($VMontoDolar[$cont]).' montoDolarConsumo: '.floatval($VMontoDolar[$cont]).'  excedente:  '. 0 .' vueltos: '. 0 .'<br> ';

                                            $Pago_Servicio = new Pago_Servicio();
                                            $Pago_Servicio->Divisa = $Vdivisa[$cont];
                                            $Pago_Servicio->MontoDivisa = $VMontoDivisa[$cont];
                                            $Pago_Servicio->TasaTiket = $VTasaTiket[$cont];
                                            $Pago_Servicio->MontoDolar = floatval($VMontoDolar[$cont]);
                                            $Pago_Servicio->MontoDolarServicio = floatval($VMontoDolar[$cont]);
                                            $Pago_Servicio->Excedente = 0;
                                            $Pago_Servicio->Vueltos = 0;
                                            $Pago_Servicio->servicio_id = $id;
                                            $Pago_Servicio->caja_id = $request->get('caja_id');
                                            $Pago_Servicio->save();
                                            echo  'Agregamos pagos servicios divisa: '.$Vdivisa[$cont].' montoDivisa: '.$VMontoDivisa[$cont].' tasaTiket: '.$VTasaTiket[$cont].' montoDolar: '.floatval($VMontoDolar[$cont]).' montoDolarConsumo: '.floatval($VMontoDolar[$cont]).'  excedente:  '. 0 .' vueltos: '. 0 .'<br> ';



                                            if($Pago_Servicio->id){
                                                $actualizarServicioToMixto = Servicio::findOrfail($id);
                                                $actualizarServicioToMixto->dinero_dejado = $actualizarServicioToMixto->dinero_dejado + $VMontoDolar[$cont];
                                                $actualizarServicioToMixto->excedente_nuevo = $actualizarServicioToMixto->excedente_nuevo + $VMontoDolar[$cont];
                                                $actualizarServicioToMixto->tipo_pago = 'Mixto';
                                                $actualizarServicioToMixto->update();


                                            }

                                        $cont = $cont+1;
                                    }
                                }
                            }

                             ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                            $isVuelos = $request->get('isVueltos');

                            if($isVuelos > 0 || $isVuelos != null){




                                $MontoDivisaVueltos = $request->get('MontoDivisaV');
                                $divisaVueltos = $request->get('divisaV');
                                $TasaTikeVueltos = $request->get('TasaTikeV');
                                $MontoDolarVueltos = $request->get('MontoDolarV');


                                $MontoDivisaVueltos = array_filter($MontoDivisaVueltos);


                                foreach($MontoDivisaVueltos as $key => $val) {


                                    $Vdivisav[]=$divisaVueltos[$key];
                                    $VMontoDivisav[]=$MontoDivisaVueltos[$key];
                                    $VTasaTiketv[]=$TasaTikeVueltos[$key];
                                    $VMontoDolarv[]=$MontoDolarVueltos[$key];





                                }




                                // dd($divisa, $MontoDivisa,$TasaTike,$MontoDolar,$Veltos);
                                //creamos un contador
                                $cont = 0;


                                //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
                                while ($cont < count($VMontoDolarv)) {


                                    $Pago_Consumo_Vueltos = new Temp_Pago_Vuelto();
                                    $Pago_Consumo_Vueltos->Tipo = 'Pendiente';
                                    $Pago_Consumo_Vueltos->Divisa = $Vdivisav[$cont];
                                    $Pago_Consumo_Vueltos->MontoDivisa = $VMontoDivisav[$cont];
                                    $Pago_Consumo_Vueltos->TasaTiket = $VTasaTiketv[$cont];
                                    $Pago_Consumo_Vueltos->MontoDolar = $VMontoDolarv[$cont];
                                    $Pago_Consumo_Vueltos->servicio_id = $id;
                                    $Pago_Consumo_Vueltos->caja_id = $request->get('caja_id');
                                    $Pago_Consumo_Vueltos->save();

                                    $cont = $cont+1;
                                }


                                    // return $RestarVtossPtesToVtosPtes;

                            }
                            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


                        $totalResta = $montoResta;

                            // return 'totalResta '.$totalResta;
                            $pg = 0;
                            while ($totalResta > 0) {

                                $TotalExcedenteData = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$id)
                                    ->where('caja_id',$request->get('caja_id'))
                                    ->where('Estado','Pendiente')
                                    ->select(DB::raw('SUM(MontoDolar) as totalExcedente'))
                                    ->get();
                                    $TotalExcedenteSumado = floatval($TotalExcedenteData[0]->totalExcedente);

                                        // return $TotalExcedente;

                                //realizamos la consulta en la base de datos y ordenamos los datos de menor a mayor sobre la columna MontoDolar
                                //para que luego reste el pago con vueltos pendientes
                                $RestarVtossPtesToVtosPtes = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$id)
                                    ->where('caja_id',$request->get('caja_id'))
                                    ->where('Estado','Pendiente')
                                    ->orderBy('MontoDolar', 'ASC')
                                    ->get();


                                $TotalVueltoTempSumados = Temp_Pago_Vuelto::where('servicio_id',$id)
                                    ->where('caja_id',$request->get('caja_id'))
                                    ->where('Tipo','Pendiente')
                                    ->select(DB::raw('SUM(MontoDolar) as totalVueltosTemp'))
                                    ->get();
                                    $TotalVueltoTempSumado = floatval($TotalVueltoTempSumados[0]->totalVueltosTemp);

                                        // return $TotalExcedente;

                                //realizamos la consulta en la base de datos y ordenamos los datos de menor a mayor sobre la columna MontoDolar
                                //para que luego reste el pago con vueltos pendientes


                                    if($totalResta <= $TotalExcedenteSumado){

                                        foreach($RestarVtossPtesToVtosPtes as $RestarExcedentesPtes){

                                            $TotalVueltoTemps = Temp_Pago_Vuelto::where('servicio_id',$id)
                                            ->where('caja_id',$request->get('caja_id'))
                                            ->where('Tipo','Pendiente')
                                            ->orderBy('MontoDolar', 'ASC')
                                            ->get();

                                            $D = $RestarExcedentesPtes->MontoDolar;
                                            foreach ($TotalVueltoTemps as $TotalVueltoTemp) {
                                                if($totalResta > 0){
                                                //     if($pg > 0){
                                                //         $pv = $pg;
                                                //     }else{
                                                //         $pv = $TotalVueltoTemp->MontoDolar;
                                                //     }

                                                    // return ' pv'.$pv.' '.$D;

                                                    $pv = $TotalVueltoTemp->MontoDolar;

                                                    if($pv == $D && $totalResta > 0 && $D > 0){
                                                        echo "<br><br><br>pago $pv deuda $D resta $totalResta<br><br><br>";
                                                        $deuda = $D;
                                                        $pg = $pv;
                                                        $op = $pv - $deuda;
                                                        $D = $op;
                                                        $totalResta = $totalResta - $pv;

                                                        echo "resta $totalResta<br><br><br>";

                                                        //ingresamos en la tabla vueltos
                                                        echo  'Guardamos vueltos Tipo: '.$RestarExcedentesPtes->Tipo.' tipo_vuelto: Vueltos_Excedentes divisa: '.$TotalVueltoTemp->Divisa.' montoDivisa: '.$TotalVueltoTemp->MontoDivisa.' tasaTiket: '.$TotalVueltoTemp->TasaTiket.' montoDolar: '.floatval($pg).' servicio_id : '.$id.'  venta_id:  '. $RestarExcedentesPtes->venta_id .' horas_extra_id: '. $RestarExcedentesPtes->horas_extra_id .' caja_id: '.$RestarExcedentesPtes->caja_id.' <br> ';



                                                        //eliminamos el retistro de la tabla temp_pagos_vueltos
                                                        echo "$totalResta eliminamos el id: $TotalVueltoTemp->id dela tabla temp_pagos_vueltos <br>";

                                                        //Actualizamos la tabla excedentes_actuals con estado devuelto

                                                        echo "actualizamos el estado a devuelto <br>";

                                                        $RestarVtossPtes = Excedentes_Recibidos_Caja_Actual::findOrFail($RestarExcedentesPtes->id);

                                                    if ($RestarVtossPtes) {
                                                        $RestarVtossPtes->Estado = 'Devueltos';
                                                        $RestarVtossPtes->update();

                                                    }
                                                    echo 'igual <br> ';
                                                    echo  'Actualizar registro  en la tabla Excedente_Recibidos => Tipo: '.$RestarVtossPtes->Tipo.' Estado: Devueltos divisa: '.$RestarVtossPtes->Divisa.' montoDivisa: '.floatval($TotalVueltoTemp->MontoDivisa).' tasaTiket: '.$TotalVueltoTemp->TasaTiket.' montoDolar: '.floatval($TotalVueltoTemp->MontoDdolar).'<br> ';
                                                    $RestarVtossPtes = Excedentes_Recibidos_Caja_Actual::findOrFail($RestarExcedentesPtes->id);
                                                    $D = 0;


                                                    $Pago_Vueltos = new Pago_Vuelto();
                                                    $Pago_Vueltos->Tipo = $RestarVtossPtes->Tipo;
                                                    $Pago_Vueltos->tipo_vuelto = 'Vueltos_Excedentes';
                                                    $Pago_Vueltos->Divisa = $TotalVueltoTemp->Divisa;
                                                    $Pago_Vueltos->MontoDivisa = $pv * $TotalVueltoTemp->TasaTiket;
                                                    $Pago_Vueltos->TasaTiket = $TotalVueltoTemp->TasaTiket;
                                                    $Pago_Vueltos->MontoDolar = $pv;
                                                    $Pago_Vueltos->servicio_id = $id;
                                                    $Pago_Vueltos->venta_id = $RestarVtossPtes->venta_id;
                                                    $Pago_Vueltos->horas_extra_id = $RestarVtossPtes->horas_extra_id;
                                                    $Pago_Vueltos->detalle__creditos__pagado_id = 0;
                                                    $Pago_Vueltos->caja_id = $request->get('caja_id');
                                                    $Pago_Vueltos->save();

                                                    Temp_Pago_Vuelto::destroy($TotalVueltoTemp->id);

                                                    }else if(round($pv,6) < round($D,6) && $totalResta > 0 && $D > 0){
                                                        echo "<br><br><br>pago $pv deuda $D resta $totalResta<br><br><br>";
                                                        $totalResta = $totalResta - $pv;
                                                        $op = $D - $pv;

                                                        echo "menor pago $pv  debia $D cantPago 0";
                                                        $D = $op;
                                                        echo "cantDeuda $D resta $totalResta <br>";
                                                        echo "resta $totalResta<br><br><br>";

                                                        $RestarVtossTemp = Temp_Pago_Vuelto::findOrFail($TotalVueltoTemp->id);

                                                        if ($RestarVtossTemp) {
                                                            $RestarVtossTemp->MontoDivisa = $RestarVtossTemp->MontoDivisa - ($pv * $RestarVtossTemp->TasaTiket);
                                                            $RestarVtossTemp->MontoDolar = $RestarVtossTemp->MontoDolar - $pv;
                                                            $RestarVtossTemp->update();
                                                        }

                                                        if($RestarVtossTemp->MontoDivisa == 0){
                                                        Temp_Pago_Vuelto::destroy($TotalVueltoTemp->id);
                                                        }





                                                     echo "actualizamos el estado a devuelto <br>";

                                                        $RestarVtossPtes = Excedentes_Recibidos_Caja_Actual::findOrFail($RestarExcedentesPtes->id);

                                                    if ($RestarVtossPtes) {
                                                        $RestarVtossPtes->MontoDivisa = $RestarVtossPtes->MontoDivisa - ($pv * $RestarVtossPtes->TasaTiket);
                                                        $RestarVtossPtes->MontoDolar = $RestarVtossPtes->MontoDolar - $pv;
                                                        $RestarVtossPtes->update();

                                                        $AgregarVtossPtesToVtosPtes = new Excedentes_Recibidos_Caja_Actual();
                                                        $AgregarVtossPtesToVtosPtes->Tipo = $RestarVtossPtes->Tipo;
                                                        $AgregarVtossPtesToVtosPtes->Estado = 'Devueltos';
                                                        $AgregarVtossPtesToVtosPtes->Divisa = $RestarVtossPtes->Divisa;
                                                        $AgregarVtossPtesToVtosPtes->MontoDivisa = floatval($pv * $RestarVtossPtes->TasaTiket);
                                                        $AgregarVtossPtesToVtosPtes->TasaTiket = $RestarVtossPtes->TasaTiket;
                                                        $AgregarVtossPtesToVtosPtes->MontoDolar = floatval($pv);
                                                        $AgregarVtossPtesToVtosPtes->servicio_id = $id;
                                                        $AgregarVtossPtesToVtosPtes->venta_id = $RestarVtossPtes->venta_id;
                                                        $AgregarVtossPtesToVtosPtes->horas_extra_id = $RestarVtossPtes->horas_extra_id;
                                                        $AgregarVtossPtesToVtosPtes->caja_id = $request->get('caja_id');
                                                        $AgregarVtossPtesToVtosPtes->save();

                                                    // return $AgregarVtossPtesToVtosPtes->id;

                                                    // TODO verificar si despues de la actualizacion el registro que en 0 si es así procedemos a borrarlo de lo contrario se deja quieto
                                                    if($RestarVtossPtes->MontoDolar == 0){
                                                        Excedentes_Recibidos_Caja_Actual::destroy($RestarVtossPtes->id);
                                                    }

                                                    }
                                                    echo 'menor <br> ';
                                                    echo  'Actualizar registro  en la tabla Excedente_Recibidos => Tipo: '.$RestarVtossPtes->Tipo.' Estado: Devueltos divisa: '.$RestarVtossPtes->Divisa.' montoDivisa: '.floatval($TotalVueltoTemp->MontoDivisa).' tasaTiket: '.$TotalVueltoTemp->TasaTiket.' montoDolar: '.floatval($TotalVueltoTemp->MontoDdolar).'<br> ';

                                                    $Pago_Vueltos = new Pago_Vuelto();
                                                    $Pago_Vueltos->Tipo = $RestarVtossPtes->Tipo;
                                                    $Pago_Vueltos->tipo_vuelto = 'Vueltos_Excedentes';
                                                    $Pago_Vueltos->Divisa = $TotalVueltoTemp->Divisa;
                                                    $Pago_Vueltos->MontoDivisa = $pv * $RestarVtossTemp->TasaTiket;
                                                    $Pago_Vueltos->TasaTiket = $RestarVtossTemp->TasaTiket;
                                                    $Pago_Vueltos->MontoDolar = $pv;
                                                    $Pago_Vueltos->servicio_id = $id;
                                                    $Pago_Vueltos->venta_id = $RestarVtossPtes->venta_id;
                                                    $Pago_Vueltos->horas_extra_id = $RestarVtossPtes->horas_extra_id;
                                                    $Pago_Vueltos->detalle__creditos__pagado_id = 0;
                                                    $Pago_Vueltos->caja_id = $request->get('caja_id');
                                                    $Pago_Vueltos->save();


                                                    $RestarVtossPtess = Excedentes_Recibidos_Caja_Actual::findOrFail($RestarVtossPtes->id);
                                                    $D = $RestarVtossPtess->MontoDolar;

                                                    }else if(round($pv,6) > round($D,6) && $totalResta > 0 && $D > 0){
                                                        echo "<br><br><br>pago $pv deuda $D resta $totalResta<br><br><br>";
                                                        $op = $pv - $D;
                                                        $pg = $op;
                                                        $totalResta = $totalResta - $D;
                                                        echo "pago $D mayor a la deuda $D quedo $pg resta $totalResta <br>";
                                                        echo "resta $totalResta<br><br><br>";

                                                        $RestarVtossTemp = Temp_Pago_Vuelto::findOrFail($TotalVueltoTemp->id);

                                                        if ($RestarVtossTemp) {
                                                            $RestarVtossTemp->MontoDivisa = $RestarVtossTemp->MontoDivisa - ($D * $RestarVtossTemp->TasaTiket);
                                                            $RestarVtossTemp->MontoDolar = $RestarVtossTemp->MontoDolar - $D;
                                                            $RestarVtossTemp->update();
                                                        }

                                                        $RestarVtossPtes = Excedentes_Recibidos_Caja_Actual::findOrFail($RestarExcedentesPtes->id);

                                                    if ($RestarVtossPtes) {
                                                        $RestarVtossPtes->Estado = 'Devueltos';
                                                        $RestarVtossPtes->update();

                                                    }

                                                    $Pago_Vueltos = new Pago_Vuelto();
                                                    $Pago_Vueltos->Tipo = $RestarVtossPtes->Tipo;
                                                    $Pago_Vueltos->tipo_vuelto = 'Vueltos_Excedentes';
                                                    $Pago_Vueltos->Divisa = $TotalVueltoTemp->Divisa;
                                                    $Pago_Vueltos->MontoDivisa = $D * $RestarVtossTemp->TasaTiket;
                                                    $Pago_Vueltos->TasaTiket = $RestarVtossTemp->TasaTiket;
                                                    $Pago_Vueltos->MontoDolar = $D;
                                                    $Pago_Vueltos->servicio_id = $id;
                                                    $Pago_Vueltos->venta_id = $RestarVtossPtes->venta_id;
                                                    $Pago_Vueltos->horas_extra_id = $RestarVtossPtes->horas_extra_id;
                                                    $Pago_Vueltos->detalle__creditos__pagado_id = 0;
                                                    $Pago_Vueltos->caja_id = $request->get('caja_id');
                                                    $Pago_Vueltos->save();
                                                    echo 'igual <br> ';
                                                    echo  'Actualizar registro  en la tabla Excedente_Recibidos => Tipo: '.$RestarVtossPtes->Tipo.' Estado: Devueltos divisa: '.$RestarVtossPtes->Divisa.' montoDivisa: '.floatval($TotalVueltoTemp->MontoDivisa).' tasaTiket: '.$TotalVueltoTemp->TasaTiket.' montoDolar: '.floatval($TotalVueltoTemp->MontoDdolar).'<br> ';
                                                    $RestarVtossPtes = Excedentes_Recibidos_Caja_Actual::findOrFail($RestarExcedentesPtes->id);
                                                    $D = 0;
                                                    }
                                                }
                                                echo "<br><br> Iteracion...<br><br>";

                                            }
                                            echo "<br><br> Iteracion excedente...<br><br>";
                                        }

                                    }

                                // return 'Finalizado...while';
                            }
                            // return 'Finalizado...salio del while';
                        }

                        // return 'Finalizado...salio de ops';
                        // if($VueltospagoConExcedente > 0 && 1 == 2 ){

                        //     // $datos = Excedentes_Recibidos_Caja_Actual::get();
                        //     // // return $datos;

                        //     // $excdentes = [
                        //     //     [
                        //     //         'Tipo'=> 'Cunsumo',
                        //     //         'Divisa'=> 'Dolar',
                        //     //         'MontoDolar'=>1.33
                        //     //     ],
                        //     //     [
                        //     //         'Tipo'=> 'Servicio',
                        //     //         'Divisa'=> 'Dolar',
                        //     //         'MontoDolar'=>6.67
                        //     //     ]

                        //     //     ];

                        //     //     foreach ($excdentes as $excdente => $valor) {
                        //     //         $excdenter[$excdente] = $valor;

                        //     //         // echo $excdenter[$excdente]['MontoDolar']. '<br>';

                        //     //         $deuda = $excdenter[$excdente]['MontoDolar'];
                        //     //         // echo $deuda. '<br>';
                        //     //         if($deuda > 0){

                        //     //         }
                        //     //     }

                        //     // return $excdente;





                        //     // return 'Finalizo...';

                        //     ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        //     ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        //     // Aquí llenamos la tabla Excedente_Recibidos_Caja_Acatual con la divisa que deja el cliente para acompletar el vuelto

                        //     // $isVuelos = $request->get('isVueltos');

                        //     if($base_vuelto_monto_dejado > 0){
                        //         // return 'mayor a 0 ' . $resta;
                        //         $MontoDivisa = $request->get('MontoDivisa');
                        //         $divisa = $request->get('divisa');
                        //         $TasaTiket = $request->get('TasaTike');
                        //         $MontoDolar = $request->get('MontoDolar');

                        //         $MontoDivisa = array_filter($MontoDivisa);

                        //         // return $MontoDivisa;
                        //         if(count($MontoDivisa)){
                        //             foreach($MontoDivisa as $key => $val) {


                        //                 $Vdivisa[]=$divisa[$key];
                        //                 $VMontoDivisa[]=$MontoDivisa[$key];
                        //                 $VTasaTiket[]=$TasaTiket[$key];
                        //                 $VMontoDolar[]=$MontoDolar[$key];

                        //             }

                        //             $cont = 0;
                        //             // return 'mayor a 0 ' . $resta;

                        //             while ($cont < count($VMontoDolar)) {


                        //                 $excdtsRecibidosCaja = new Excedentes_Recibidos_Caja_Actual();
                        //                 $excdtsRecibidosCaja->Tipo = 'Servicio';
                        //                 $excdtsRecibidosCaja->Estado = 'Pendiente';
                        //                 $excdtsRecibidosCaja->Divisa = $Vdivisa[$cont];
                        //                 $excdtsRecibidosCaja->TasaTiket = $VTasaTiket[$cont];
                        //                 $excdtsRecibidosCaja->MontoDivisa = $VMontoDivisa[$cont];
                        //                 $excdtsRecibidosCaja->MontoDolar = $VMontoDolar[$cont];
                        //                 $excdtsRecibidosCaja->servicio_id = $id;
                        //                 $excdtsRecibidosCaja->venta_id = 0;
                        //                 $excdtsRecibidosCaja->caja_id = $request->get('caja_id');
                        //                 $excdtsRecibidosCaja->save();


                        //                 echo  'Agregamos tabla excedente divisa: '.$Vdivisa[$cont].' montoDivisa: '.$VMontoDivisa[$cont].' tasaTiket: '.$VTasaTiket[$cont].' montoDolar: '.floatval($VMontoDolar[$cont]).' montoDolarConsumo: '.floatval($VMontoDolar[$cont]).'  excedente:  '. 0 .' vueltos: '. 0 .'<br> ';

                        //                     $Pago_Servicio = new Pago_Servicio();
                        //                     $Pago_Servicio->Divisa = $Vdivisa[$cont];
                        //                     $Pago_Servicio->MontoDivisa = $VMontoDivisa[$cont];
                        //                     $Pago_Servicio->TasaTiket = $VTasaTiket[$cont];
                        //                     $Pago_Servicio->MontoDolar = floatval($VMontoDolar[$cont]);
                        //                     $Pago_Servicio->MontoDolarServicio = floatval($VMontoDolar[$cont]);
                        //                     $Pago_Servicio->Excedente = 0;
                        //                     $Pago_Servicio->Vueltos = 0;
                        //                     $Pago_Servicio->servicio_id = $id;
                        //                     $Pago_Servicio->caja_id = $request->get('caja_id');
                        //                     $Pago_Servicio->save();
                        //                     echo  'Agregamos pagos servicios divisa: '.$Vdivisa[$cont].' montoDivisa: '.$VMontoDivisa[$cont].' tasaTiket: '.$VTasaTiket[$cont].' montoDolar: '.floatval($VMontoDolar[$cont]).' montoDolarConsumo: '.floatval($VMontoDolar[$cont]).'  excedente:  '. 0 .' vueltos: '. 0 .'<br> ';



                        //                     if($Pago_Servicio->id){
                        //                         $actualizarServicioToMixto = Servicio::findOrfail($id);
                        //                         $actualizarServicioToMixto->dinero_dejado = $actualizarServicioToMixto->dinero_dejado + $VMontoDolar[$cont];
                        //                         $actualizarServicioToMixto->excedente_nuevo = $actualizarServicioToMixto->excedente_nuevo + $VMontoDolar[$cont];
                        //                         $actualizarServicioToMixto->tipo_pago = 'Mixto';
                        //                         $actualizarServicioToMixto->update();


                        //                     }

                        //                 $cont = $cont+1;
                        //             }
                        //         }
                        //     }

                        //      ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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


                        //             $Vdivisav[]=$divisaVueltos[$key];
                        //             $VMontoDivisav[]=$MontoDivisaVueltos[$key];
                        //             $VTasaTiketv[]=$TasaTikeVueltos[$key];
                        //             $VMontoDolarv[]=$MontoDolarVueltos[$key];





                        //         }




                        //         // dd($divisa, $MontoDivisa,$TasaTike,$MontoDolar,$Veltos);
                        //         //creamos un contador
                        //         $cont = 0;


                        //         //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
                        //         while ($cont < count($VMontoDolarv)) {


                        //             $Pago_Consumo_Vueltos = new Pago_Vuelto();
                        //             $Pago_Consumo_Vueltos->Tipo = 'Servicio';
                        //             $Pago_Consumo_Vueltos->Divisa = $Vdivisav[$cont];
                        //             $Pago_Consumo_Vueltos->MontoDivisa = $VMontoDivisav[$cont];
                        //             $Pago_Consumo_Vueltos->TasaTiket = $VTasaTiketv[$cont];
                        //             $Pago_Consumo_Vueltos->MontoDolar = $VMontoDolarv[$cont];
                        //             $Pago_Consumo_Vueltos->servicio_id = $id;
                        //             $Pago_Consumo_Vueltos->caja_id = $request->get('caja_id');
                        //             $Pago_Consumo_Vueltos->save();

                        //             $cont = $cont+1;
                        //         }


                        //             // return $RestarVtossPtesToVtosPtes;

                        //     }
                        //     ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        //     ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        //             // TODO Ir a la tabla Excedentes_Recibidos_Caja_Actual para actualizar el registro y restar los vueltos pendientes
                        //             $Pago_Vuelto_Cuenta = Pago_Vuelto::where('servicio_id',$id)->where('caja_id',$request->get('caja_id'))->get();
                        //             $total_Pago_Vuelto_Cuenta = 0;
                        //             // return $Pago_Vuelto_Cuenta;




                        //             $mayorVuelto = $Pago_Vuelto_Cuenta[0];

                        //             foreach ($Pago_Vuelto_Cuenta as $keyVueltos) {

                        //                 $total_Pago_Vuelto_Cuenta += $keyVueltos->MontoDolar;

                        //                 if($mayorVuelto > $keyVueltos){ $mayorVuelto = $keyVueltos;	}
                        //             }
                        //             // return $total_Pago_Vuelto_Cuenta;

                        //             // while ($total_Pago_Vuelto_Cuenta > 0) {
                        //                 # code...


                        //             // return $mayorVuelto;

                        //             // TODO Crear proceso que maneje el pago con vueltos pendiente


                        //             // TODO Ir a la tabla Excedentes_Recibidos_Caja_Actual para actualizar el registro y restar los vueltos pendientes
                        //             $RestarVtossPtesToVtosPtes = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$id)->where('caja_id',$request->get('caja_id'))->where('Estado','Pendiente')->get();
                        //             // return $RestarVtossPtesToVtosPtes;

                        //             $totalExcedenteSumado = 0;




                        //             $mayor=$RestarVtossPtesToVtosPtes[0];
                        //             // return $mayor;

                        //             foreach ($RestarVtossPtesToVtosPtes as $key) {

                        //                 $totalExcedenteSumado += $key->MontoDolar;

                        //                 if($mayor>$key){ $mayor=$key;	}
                        //             }
                        //             $monto_dejadoResta = floatval($monto_dejadoResta);
                        //             $totalExcedenteSumado = floatval($totalExcedenteSumado);
                        //             // return $totalExcedenteSumado .' '.$monto_dejadoResta;

                        //             // return 'Excedente: '.$totalExcedenteSumado.' Vueltos: '.$total_Pago_Vuelto_Cuenta;


                        //             // TODO pregutamos si el vulto pagado es menor o = al total de excedente a apagar para en primer lugar
                        //             // si es menor lo que hacemos es restarle al excedente de mayor cantidad el sobrante
                        //             // ejemplo: supangamos que tenemos dos registros con excedente uno vale 4 y en pesos otro 6 y en dolar
                        //             // si la suma total de excedente vale 10 y vueltos es 2 entonces 10 - 2 = 8. se le resta al registro de excedente
                        //             // de mayor cantidad suponiendo que es 6 en dolar entronces serian 6 - 2 = 4 todos los demas registros quedarían igual
                        //             // y el registro que era de valor 6 quedaría en 4 y se crearía un nuevo registro con el valor de 2 pero devuelto.
                        //             // Ahora si la suma total de los vueltos pagados es igual a la suma total de los excedentes entonces signica que
                        //             //pago la totalidad y se procede solo a cambiar el estatus de pendiente a devueltos.

                        //             // TODO Preguntamos si $totalExcedenteSumado es == a $total_Pago_Vuelto_Cuenta para solo cambiar el estatus a devueltos
                        //             //en la  tabal Excedentes_Recibidos_Caja_Actual

                        //             // return 'Bien';

                        //             if ($totalExcedenteSumado == $monto_dejadoResta) {
                        //                 // return 'igual';
                        //                 $RestarVtossPtesTo = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$id)->where('caja_id',$request->get('caja_id'))->where('Estado','Pendiente')->get();


                        //                 foreach ($RestarVtossPtesTo as $restaVueltosTo ) {
                        //                     if ($restaVueltosTo) {
                        //                         // return $restaVueltosTo->id;
                        //                         $UpdateDevueltos = Excedentes_Recibidos_Caja_Actual::findOrFail($restaVueltosTo->id);
                        //                         $UpdateDevueltos->Estado = 'Devueltos';
                        //                         $UpdateDevueltos->update();

                        //                     }
                        //                 }
                        //                 // return 'Bienss';
                        //                 $total_Pago_Vuelto_Cuenta = 0;
                        //             }
                        //             // return 'mal';

                        //             if ($totalExcedenteSumado > $monto_dejadoResta && $monto_dejadoResta > 0 ) {
                        //                 // return 'aquí';
                        //                 // $montoResta = $totalExcedenteSumado - $total_Pago_Vuelto_Cuenta;
                        //                 // return $montoResta;

                        //                 // TODO Ir a la tabla Excedentes_Recibidos_Caja_Actual para actualizar el registro y restar los vueltos pendientes
                        //                 $RestarVtos = Excedentes_Recibidos_Caja_Actual::findOrFail($mayor->id);

                        //                 if ($RestarVtos->MontoDolar >= $monto_dejadoResta) {
                        //                     // return 'Bien';

                        //                     if ($RestarVtos) {
                        //                         $RestarVtos->MontoDivisa = $RestarVtos->MontoDivisa - ($monto_dejadoResta * $RestarVtos->TasaTiket);
                        //                         $RestarVtos->MontoDolar = $RestarVtos->MontoDolar - $monto_dejadoResta;
                        //                         $RestarVtos->update();


                        //                         // TODO Ir a la tabla Excedentes_Recibidos_Caja_Actual y crear un registro nuevo con el monto pagado pero con estatus Devueltos flotantes en la misma divisa

                        //                         $AgregarVtossPtesToVtosPtes = new Excedentes_Recibidos_Caja_Actual();
                        //                         $AgregarVtossPtesToVtosPtes->Tipo = $RestarVtos->MontoDivisa;
                        //                         $AgregarVtossPtesToVtosPtes->Estado = 'Devueltos';
                        //                         $AgregarVtossPtesToVtosPtes->Divisa = $RestarVtos->Divisa;
                        //                         $AgregarVtossPtesToVtosPtes->MontoDivisa = ($monto_dejadoResta * $RestarVtos->TasaTiket);
                        //                         $AgregarVtossPtesToVtosPtes->TasaTiket = $RestarVtos->TasaTiket;
                        //                         $AgregarVtossPtesToVtosPtes->MontoDolar = $monto_dejadoResta;
                        //                         $AgregarVtossPtesToVtosPtes->servicio_id = $id;
                        //                         $AgregarVtossPtesToVtosPtes->venta_id = 0;
                        //                         $AgregarVtossPtesToVtosPtes->caja_id = $request->get('caja_id');
                        //                         $AgregarVtossPtesToVtosPtes->save();

                        //                         // return $AgregarVtossPtesToVtosPtes->id;

                        //                         // TODO verificar si despues de la actualizacion el registro que en 0 si es así procedemos a borrarlo de lo contrario se deja quieto
                        //                         if($RestarVtos->MontoDolar == 0){
                        //                             Excedentes_Recibidos_Caja_Actual::destroy($RestarVtos->id);
                        //                         }
                        //                     }

                        //                     $total_Pago_Vuelto_Cuenta = 0;
                        //                 }else if ($RestarVtos->MontoDolar < $monto_dejadoResta) {
                        //                     // return 'Bien mal ff';
                        //                     //consultamos la tabla Excedentes recibidos en caja actual para restarle el vuelto


                        //                     $resta_actual = $isVuelos;
                        //                     $valor = $resta_actual;
                        //                     $resto = 0;


                        //                     while ($valor > 0) {

                        //                         // return 'cero';

                        //                         $RestarVtossPtesToVtosPtes = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$id)->where('caja_id',$request->get('caja_id'))->where('Estado','Pendiente')->get();

                        //                         foreach ($RestarVtossPtesToVtosPtes as $key) {
                        //                             $resto = $key->MontoDolar - $resta_actual;
                        //                             $valor = $resto;
                        //                             // $resto = $resta_actual - $key->MontoDolar;
                        //                             // return $resto;

                        //                             if($resto < 0 ){
                        //                                 // return 'menos';

                        //                                 $UpdateDevueltos = Excedentes_Recibidos_Caja_Actual::findOrFail($key->id);
                        //                                 $UpdateDevueltos->Estado = 'Devueltos';
                        //                                 $UpdateDevueltos->update();

                        //                                 $resto = $resta_actual - $key->MontoDolar;
                        //                                 $valor = $resto;
                        //                                 $resta_actual = $resto;

                        //                                 // return $resto;

                        //                             }else {
                        //                                 // return 'mas';

                        //                                 $RestarVtos = Excedentes_Recibidos_Caja_Actual::findOrFail($key->id);
                        //                                 if ($RestarVtos) {

                        //                                     if($RestarVtos->MontoDolar > $resto){
                        //                                         $valor = 0;
                        //                                         // return $RestarVtos->TasaTiket;
                        //                                         // if($RestarVtos->Divisa == 'Dolar'){

                        //                                         // }

                        //                                         $RestarVtos->Estado = 'Devueltos';
                        //                                         $RestarVtos->MontoDivisa = $RestarVtos->MontoDivisa - ($resto * $RestarVtos->TasaTiket);
                        //                                         $RestarVtos->MontoDolar = $RestarVtos->MontoDolar - $resto;
                        //                                         $RestarVtos->update();


                        //                                         // TODO Ir a la tabla Excedentes_Recibidos_Caja_Actual y crear un registro nuevo con el monto pagado pero con estatus Devueltos flotantes en la misma divisa

                        //                                         $AgregarVtossPtesToVtosPtes = new Excedentes_Recibidos_Caja_Actual();
                        //                                         $AgregarVtossPtesToVtosPtes->Tipo = $RestarVtos->MontoDivisa;
                        //                                         $AgregarVtossPtesToVtosPtes->Estado = 'Pendiente';
                        //                                         $AgregarVtossPtesToVtosPtes->Divisa = $RestarVtos->Divisa;
                        //                                         $AgregarVtossPtesToVtosPtes->MontoDivisa = ($resto * $RestarVtos->TasaTiket);
                        //                                         $AgregarVtossPtesToVtosPtes->TasaTiket = $RestarVtos->TasaTiket;
                        //                                         $AgregarVtossPtesToVtosPtes->MontoDolar = $resto;
                        //                                         $AgregarVtossPtesToVtosPtes->servicio_id = $id;
                        //                                         $AgregarVtossPtesToVtosPtes->venta_id = 0;
                        //                                         $AgregarVtossPtesToVtosPtes->caja_id = $request->get('caja_id');
                        //                                         $AgregarVtossPtesToVtosPtes->save();

                        //                                         // return $AgregarVtossPtesToVtosPtes->id;
                        //                                     }
                        //                                     if($RestarVtos->MontoDolar == $resto){
                        //                                         $valor = 0;
                        //                                         // TODO verificar si despues de la actualizacion el registro que en 0 si es así procedemos a borrarlo de lo contrario se deja quieto
                        //                                         if($RestarVtossPtesToVtosPtes->MontoDolar == 0){
                        //                                             Excedentes_Recibidos_Caja_Actual::destroy($RestarVtos->id);
                        //                                         }
                        //                                     }
                        //                                 }
                        //                             }
                        //                         }


                        //                     }


                        //                 }

                        //             }


                        //         // }



                        //             ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        //             ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        // }
                        // return 'no entro ' . $monto_dejadoResta;
                    }

                    DB::commit();

                }catch(\Exception $e)
                {

                    dd($e);

                    DB::rollback();
                    // if (isset($MontoDolarR)) {

                        return redirect()
                        ->route('recepcion')
                        ->with('status_danger', '¡Error Pago incompleto! Debe ingresar un monto para pagar y procesar el servicio... ');
                    // }

                }


                    return Redirect::to('checkout/'.$id)->with('success', 'El servicio fué registrado exitosamente');
            } else if ($bandera == 'cambiarHabitacion') {
                // return 'no';

                // return $request;

                try{

                    DB::beginTransaction();
                    // number_format($número, 2, '.', '');
                    
                    $myTime = Carbon::now('America/Caracas');
                    $id_habitaicon = $request->get('habitacion_id_nueva2');
                    $habitacion_id_vieja = $request->get('habitacion_id_vieja');
                    $tipo_pago = $request->get('tipo_pago');
                    $monto_dejado = $request->get('monto_dejado');
                    $pagoConExcedente = $request->get('pagoConExcedente');
                    $total_costo = $request->get('total_costo');
                    $status = '';
                    $nombreHabitacion = $request->get('nombre_nueva2');
                    $nombreHabitacionCambio = $request->get('nombre_nueva2').'/'.$request->get('nombre_vieja');
                    $numeroServisio = $request->get('num_servicio');
                    $operador = $request->get('operador');
                    $detalleHabitacion = $request->get('categoria_dest_nueva2');
                    $modo_pago = $request->get('modo_pago');
                    $nuevo_excedente = 0;

                    $cliente_id = $request->get('cliente_id');

                    $monto_reintegro = 0;
                    $pagoConVueltosCaja = 0;




                    $is_vueltos_caja = PreExcedente::where('cliente_id', $cliente_id)->first();
                    // return $is_vueltos_caja;
            

                    if($is_vueltos_caja && $pagoConExcedente > 0){
                        // return 'entro 1';
                        $monto_excedente_actual = $is_vueltos_caja->monto_excedente_actual;
                        $deuda_total_acumulada = $is_vueltos_caja->deuda_total_acumulada;

                        $dispExcedente = $request->get('dispExcedente');

                        $monto_reintegro = $dispExcedente - $deuda_total_acumulada;
                        
                        $pagoConVueltosCaja = $pagoConExcedente - $monto_reintegro;

                        $monto_dejado = $monto_dejado + $pagoConVueltosCaja;

                        // $pagoConExcedente = $monto_reintegro;

                        //Hasta aqui todo bien.   
                        // return $monto_reintegro;
                        if($pagoConExcedente  >= $monto_reintegro){
                            //Cargamos las variables que usaremos para manejar el reintegro.
                            // return 'entro en reintegro';
                            // return $monto_excedente_actual;
                            $pagoConExcedente = $monto_reintegro;

                            $pcliente_id = $cliente_id;
                            $pnombre_cliente = $request->get('nombre');
                            $monto_deuda = $deuda_total_acumulada;
                            $monto_pagado = $pagoConVueltosCaja;
                            $monto_dolar = $pagoConVueltosCaja;
                            $monto_dolar_to_dolar = $pagoConVueltosCaja;
                            $monto_peso_to_dolar = 0;
                            $monto_bolivar_to_dolar = 0;
                            $monto_trans_to_dolar = 0;
                            $tasa_dolar = $request->get('tasaDolar');
                            $tasa_peso = $request->get('tasaPeso');
                            $tasa_bolivar = $request->get('tasaEfectivo');
                            $tasa_trans = $request->get('tasaTransPunto');
                            $observacion = 'Pago realizado automaticamente por el sistema (Cambiando habitacion con vueltos en caja)';
                            $operador = Auth::user()->name;
                            $user_id = Auth::user()->id;
                            $caja = Caja::where("estado","=",'Abierta')->first();
                            $caja_id = $caja->id;
                            $fecha_pago = Carbon::now();
                            
                            //recuperamos el id de la tabla preHistorial
                            $clientes_vueltos = PreExcedente::where('cliente_id', $pcliente_id)->first();
                            $phistorial_id = $clientes_vueltos->id;
                            // return $phistorial_id;
                            

                            $reintegro = new  Reintegro();
                            $reintegro->nombre_cliente = $pnombre_cliente;
                            $reintegro->monto_deuda = $monto_deuda;
                            $reintegro->monto_pagado = $monto_pagado;
                            $reintegro->monto_dolar = $monto_dolar;
                            $reintegro->monto_dolar_to_dolar = $monto_dolar_to_dolar;
                            $reintegro->monto_peso_to_dolar = $monto_peso_to_dolar;
                            $reintegro->monto_bolivar_to_dolar = $monto_bolivar_to_dolar;
                            $reintegro->monto_trans_to_dolar = $monto_trans_to_dolar;
                            $reintegro->tasa_dolar = $tasa_dolar;
                            $reintegro->tasa_peso = $tasa_peso;
                            $reintegro->tasa_bolivar = $tasa_bolivar;
                            $reintegro->tasa_trans = $tasa_trans;
                            $reintegro->observacion = $observacion;
                            $reintegro->operador = $operador;
                            $reintegro->historial_excedente_id = $phistorial_id;
                            $reintegro->user_id = $user_id;
                            $reintegro->cliente_id = $pcliente_id;
                            $reintegro->caja_id = $caja_id;
                            $reintegro->save();

                            // return $reintegro->id;
                            // TODO Guardamos los registros en la tabla Detalle pagos oficina

                            if($monto_pagado == $monto_deuda){
                                // return 'Es igual';
                                $DetallePagoOficina = new  DetallePagoOficina();
                                $DetallePagoOficina->tipo_pago = 'Efectivo';
                                $DetallePagoOficina->num_transaccion = '0001';
                                $DetallePagoOficina->deuda = $monto_deuda;
                                $DetallePagoOficina->saldo_pagado = $monto_pagado;
                                $DetallePagoOficina->fecha_pago = $fecha_pago;
                                $DetallePagoOficina->persona_id = $pcliente_id;
                                $DetallePagoOficina->caja_id = $caja_id;
                                $DetallePagoOficina->user_id = $user_id;
                                $DetallePagoOficina->save();



                                $is_cliente = PreExcedente::where('cliente_id', $pcliente_id)->first();

                                if($is_cliente){
                                    if($is_cliente->deuda_total_acumulada == $is_cliente->monto_excedente_actual){
                                        PreExcedente::destroy($is_cliente->id);
                                        // TODO Ahora actualizamos la tabla historial_excedentes colocando el id del detalle pago oficina para poder agrupar los por ide de pago
                                        // y asi poder consultarlos luego y cambiando el estatus a pagado


                                        $historialExcedentes = HistorialExcedente::where('persona_id',$pcliente_id)->where('tipo_registro','Pago_por_oficina')->where('status','Pendiente')->get();

                                        if($historialExcedentes){

                                            foreach ($historialExcedentes as $historialExcedente) {

                                                $HistorialExcedente = HistorialExcedente::findOrFail($historialExcedente->id);
                                                $HistorialExcedente->status = 'Pagado';
                                                $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                                                $HistorialExcedente->update();
                                            }

                                            // TODO Ahora eliminamos de la tabla excedente el registro del usuario

                                            $eliminarRegistroExcedente = Excedente::where('persona_id',$pcliente_id)->where('tipo','Pagar_por_oficina')->first();

                                            if ($eliminarRegistroExcedente) {
                                                Excedente::destroy($eliminarRegistroExcedente->id);
                                            }
                                            }

                                }else{
                                    // TODO Guardamos en la tabla historial excedente
                                    // return 'otro';
                                $historialExcedentes = HistorialExcedente::where('persona_id',$pcliente_id)->where('tipo_registro','Pago_por_oficina')->where('status','Pendiente')->get();

                                if($historialExcedentes){
                                    $saldo_disponible = 0;
                                    $motivo = '';
                                    $banco_id = '';
                                    $servicio_id = '';

                                    foreach ($historialExcedentes as $historialExcedente) {
                                        $saldo_disponible = $historialExcedente->saldo_disponible;
                                        $motivo = $historialExcedente->motivo;
                                        $banco_id = $historialExcedente->banco_id;
                                        $servicio_id = $historialExcedente->servicio_id;

                                        $HistorialExcedente = HistorialExcedente::findOrFail($historialExcedente->id);
                                        $HistorialExcedente->status = 'Pagado';
                                        $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                                        $HistorialExcedente->update();

                                    }
                                }

                                if($saldo_disponible > 0){
                                    $saldo_anterior = $saldo_disponible;
                                    $saldo_disponible = $saldo_disponible - $monto_pagado;

                                }

                                $HistorialExcedente = new  HistorialExcedente;
                                $HistorialExcedente->tipo_registro = 'Pago_por_oficina';
                                $HistorialExcedente->status = 'Pendiente';
                                $HistorialExcedente->tipo_operacion = 'Egreso';
                                $HistorialExcedente->num_servicio = $DetallePagoOficina->id;
                                $HistorialExcedente->motivo = $motivo;
                                $HistorialExcedente->saldo_anterior = $saldo_anterior;
                                $HistorialExcedente->saldo_operacion = $monto_pagado;
                                $HistorialExcedente->saldo_disponible = $saldo_disponible;
                                $HistorialExcedente->operador = $operador;
                                $HistorialExcedente->banco_id = $banco_id;
                                $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                                $HistorialExcedente->persona_id = $pcliente_id;
                                $HistorialExcedente->servicio_id = $servicio_id;
                                $HistorialExcedente->caja_id = $caja_id;
                                $HistorialExcedente->user_id  = $user_id;
                                $HistorialExcedente->save();


                                $UpdateExcedente = Excedente::where('persona_id', $pcliente_id)->first();
                                $UpdateExcedente->excedente -= $monto_pagado;
                                $UpdateExcedente->update();

                                if($is_cliente){
                                    PreExcedente::destroy($is_cliente->id);
                                }
                                }





                            }
                            }else{
                                // return 'No es igual';
                                $DetallePagoOficina = new  DetallePagoOficina();
                                $DetallePagoOficina->tipo_pago = 'Efectivo';
                                $DetallePagoOficina->num_transaccion = '0001';
                                $DetallePagoOficina->deuda = $monto_deuda;
                                $DetallePagoOficina->saldo_pagado = $monto_pagado;
                                $DetallePagoOficina->fecha_pago = $fecha_pago;
                                $DetallePagoOficina->persona_id = $pcliente_id;
                                $DetallePagoOficina->caja_id = $caja_id;
                                $DetallePagoOficina->user_id = $user_id;
                                $DetallePagoOficina->save();

                                // return $DetallePagoOficina->id;
                                // TODO Guardamos en la tabla historial excedente

                                $historialExcedentes = HistorialExcedente::where('persona_id',$pcliente_id)->where('tipo_registro','Pago_por_oficina')->where('status','Pendiente')->get();

                                if($historialExcedentes){
                                    $saldo_disponible = 0;
                                    $motivo = '';
                                    $banco_id = '';
                                    $servicio_id = '';

                                    foreach ($historialExcedentes as $historialExcedente) {
                                        $saldo_disponible = $historialExcedente->saldo_disponible;
                                        $motivo = $historialExcedente->motivo;
                                        $banco_id = $historialExcedente->banco_id;
                                        $servicio_id = $historialExcedente->servicio_id;

                                        $HistorialExcedente = HistorialExcedente::findOrFail($historialExcedente->id);
                                        $HistorialExcedente->status = 'Pagado';
                                        $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                                        $HistorialExcedente->update();

                                    }
                                }
                                
                                if($saldo_disponible > 0){
                                    $saldo_anterior = $saldo_disponible;
                                    $saldo_disponible = $saldo_disponible - $monto_pagado;

                                }

                                $HistorialExcedente = new  HistorialExcedente;
                                $HistorialExcedente->tipo_registro = 'Pago_por_oficina';
                                $HistorialExcedente->status = 'Pendiente';
                                $HistorialExcedente->tipo_operacion = 'Egreso';
                                $HistorialExcedente->num_servicio = $DetallePagoOficina->id;
                                $HistorialExcedente->motivo = $motivo;
                                $HistorialExcedente->saldo_anterior = $saldo_anterior;
                                $HistorialExcedente->saldo_operacion = $monto_pagado;
                                $HistorialExcedente->saldo_disponible = $saldo_disponible;
                                $HistorialExcedente->operador = $operador;
                                $HistorialExcedente->banco_id = $banco_id;
                                $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                                $HistorialExcedente->persona_id = $pcliente_id;
                                $HistorialExcedente->servicio_id = $servicio_id;
                                $HistorialExcedente->caja_id = $caja_id;
                                $HistorialExcedente->user_id  = $user_id;
                                $HistorialExcedente->save();

                                
                                $UpdateExcedente = Excedente::where('persona_id', $pcliente_id)->first();
                                $UpdateExcedente->excedente -= $monto_pagado;
                                $UpdateExcedente->update();

                                $is_cliente = PreExcedente::where('cliente_id', $pcliente_id)->first();

                                if($is_cliente){
                                    if($monto_reintegro > 0){
                                        
                                        $updatePreExcedente = PreExcedente::findOrFail($is_cliente->id);
                                        $updatePreExcedente->monto_excedente_actual -= ($monto_pagado + $monto_reintegro);
                                        $updatePreExcedente->deuda_total_acumulada -= $monto_pagado;
                                        $updatePreExcedente->update();
                                    }else{

                                        $updatePreExcedente = PreExcedente::findOrFail($is_cliente->id);
                                        $updatePreExcedente->monto_excedente_actual -= $monto_pagado;
                                        $updatePreExcedente->deuda_total_acumulada -= $monto_pagado;
                                        $updatePreExcedente->update();
                                    }
                                }
                            }

                            if($pagoConVueltosCaja > 0){
                                $pago_con_vueltos_caja = 'Vueltos';
                            }
                        }else{
                            // return 'entro 3';
                            //Si entra aqui es cuando solo se paga con vueltos oficina pero que tambien tiene vueltos caja.
                        
                            $monto_reintegro = $request->get('pagoConExcedente');
                            
                            $monto_dejado = $request->get('monto_dejado');
                            $pagoConExcedente = $request->get('pagoConExcedente');

                            $pagoConVueltosCaja = 0;
                            
                            $is_cliente = PreExcedente::where('cliente_id', $cliente_id)->first();
                            // return $is_cliente->id;
                            if($is_cliente){
                                if($monto_reintegro > 0){
                                    
                                    $updatePreExcedente = PreExcedente::findOrFail($is_cliente->id);
                                    $updatePreExcedente->monto_excedente_actual -= $monto_reintegro;
                                    $updatePreExcedente->update();
                                }
                            }

                        }
                    }else{
                        // return $monto_reintegro;
                        $monto_reintegro = $pagoConExcedente;
                    }
                    // return '$montoBase';
                    // return 'normal';

                    // return $is_vueltos_caja;

                    // return $tipo_pago;


                    // return $monto_dejado;




                    //Esta seccion recupera los datos para prosesar los reintegros o cuando los clientes usan 
                    //el dinero que tienen en la oficina

                    

                    if($pagoConExcedente > 0){
                        $modo_pago = "Contado-Excedente";
        
                    }else{
                        $modo_pago = $request->get('modo_pago');
                    }



                    $comprobarHoraServicio_id = Servicio::where('habitacion_id',$habitacion_id_vieja)->where('status_servicio', 'Iniciado')->where('id', $id)->first();
                    // return $comprobarHoraServicio_id;

                    if($comprobarHoraServicio_id){

                        $horarios = Horario::findOrFail($request->get('horario_id'));

                        if($comprobarHoraServicio_id->tipo_habitacion == $horarios->tipo){
                            $tipo =  $comprobarHoraServicio_id->tipo_habitacion;
                            $horario = $comprobarHoraServicio_id->horario;
                            // return 'es igual';
                            $fecha_salida = $request->get('fecha_salida');
                            $hora_salida = $request->get('hora_salida');
                        }else{
                            // return 'no es igual';
                            if($horarios){
                                $tipo =  $horarios->tipo;
                                $horario = $horarios->nombre;
                                // return $tipo;
                                if($tipo == '24 HORAS'){

                                    $fechaS = $request->get('fecha_salida');
                                    $fecha_salida = Carbon::createFromFormat('Y-m-d', $fechaS);
                                    $fecha_salida->addDays(1);
                                    $fecha_salida = $fecha_salida->toDateString();
                                    $hora_salida = $request->get('hora_entrada');

                                }else{

                                    $fecha_salida = $request->get('fecha_salida');
                                    $hora_salida = $request->get('hora_salida');
                                }
                            }
                        }

                    }




                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    // TODO metodo para pagar con vueltos pendientes
                    // TODO Method to pay with pending returns

                    

                    if($modo_pago == 'cambio'){
                        $status = 'Exonerado';
                        $estado = 'Aceptada';
                        $tipo_pago = 'Exonerado';

                    }

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
                    $servicio->nombre_habitacion = $request->get('nombre_nueva2');
                    $servicio->detalle_habitacion = $request->get('categoria_dest_nueva2');
                    $servicio->tipo_habitacion = $tipo;
                    $servicio->horario = $horario;
                    $servicio->fecha_entrada = $request->get('fecha_entrada');
                    $servicio->hora_entrada = $request->get('hora_entrada');
                    $servicio->fecha_salida = $fecha_salida;
                    $servicio->hora_salida = $hora_salida;
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
                    $servicio->is_cambio = 'Si';
                    $servicio->status = $status;
                    $servicio->precio_costo = $request->get('precio_nueva');
                    $servicio->cantidad = $request->get('cantidad');
                    $servicio->dinero_dejado = $monto_dejado;
                    $servicio->excedente_nuevo = $nuevo_excedente;
                    $servicio->pago_con_excedente = $monto_reintegro ? $monto_reintegro : null;
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

                    //TODO  SAVE IN TABLE CHANGE (Cambios)
                    $saveCambio = New Cambio;
                    $saveCambio->servicio_id = $id;
                    $saveCambio->habitacion = $request->get('nombre_vieja');;
                    $saveCambio->servicio_id_cambio = $servicio->id;
                    $saveCambio->habitacion_cambio = $request->get('nombre_nueva2');
                    $saveCambio->caja_id = $request->get('caja_id');
                    $saveCambio->observacion = $request->get('observacion_text');
                    $saveCambio->save();





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

                    

                    $PasarVtossPtesToNextServ = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$id)->where('Estado','Pendiente')->first();
                    if ($PasarVtossPtesToNextServ) {
                        $PasarVtossPtesToNextServ->servicio_id = $servicio->id;
                        $PasarVtossPtesToNextServ->update();
                    }


                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    // TODO creamos metodo para realizar el pago cuando se paga con dinero contable viene en la variable base_vuelto_monto_dejado
                    // primero validamos si exciste un pago hecho.
                    // return '$montoBase';
                    $dispExcedente = $request->get('dispExcedente');
                    $montoBase = $pagoConVueltosCaja ? $pagoConVueltosCaja + $request->get('base_vuelto_monto_dejado') : $request->get('base_vuelto_monto_dejado');

                    // return $montoBase;
                    $montoResta = $request->get('monto_dejadoResta');
                    $total_venta = $request->get('total_costo');
                    $pcliente_id = $request->get('cliente_id');
                    $user_id = Auth::user()->id;


                    $montoBase = floatval($montoBase);
                    $montoResta = floatval($montoResta);
                    $total_venta = floatval($total_venta);
                    $monto_deuda = floatval($dispExcedente);
                    $monto_pagado = floatval($pagoConExcedente);

                    $servicio_id = $servicio->id;
                    $caja_id = $request->get('caja_id');

                    $opS = $montoBase;
                    $fecha_pago = Carbon::now();

                    if($pagoConExcedente > 0){
                        $opS += $monto_pagado;
                    }

                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    //validamos si el monto pagado es mayor a 0 sea que lo paguen con montoBase o con montoPendiente y que el tipo de pago sea contado
            
                    if($opS > 0 && $modo_pago == 'contado'  || $modo_pago == 'Contado-Excedente'){
                        if($monto_pagado > 0){
                            $modo_pago = 'Contado-Excedente';
                            
                            // TODO Guardamos los registros en la tabla Detalle pagos oficina

                        if($monto_pagado == $monto_deuda){
                            $DetallePagoOficina = new  DetallePagoOficina();
                            $DetallePagoOficina->tipo_pago = 'Efectivo';
                            $DetallePagoOficina->num_transaccion = $servicio->num_servicio;
                            $DetallePagoOficina->deuda = $monto_deuda;
                            $DetallePagoOficina->saldo_pagado = $monto_pagado;
                            $DetallePagoOficina->fecha_pago = $fecha_pago;
                            $DetallePagoOficina->persona_id = $pcliente_id;
                            $DetallePagoOficina->caja_id = $caja_id;
                            $DetallePagoOficina->user_id = $user_id;
                            $DetallePagoOficina->save();

                            // TODO Ahora actualizamos la tabla historial_excedentes colocando el id del detalle pago oficina para poder agrupar los por ide de pago
                            // y asi poder consultarlos luego y cambiando el estatus a pagado


                            $historialExcedentes = HistorialExcedente::where('persona_id',$pcliente_id)->where('tipo_registro','Pago_por_oficina')->where('status','Pendiente')->get();

                            if($historialExcedentes){

                                foreach ($historialExcedentes as $historialExcedente) {

                                    $HistorialExcedente = HistorialExcedente::findOrFail($historialExcedente->id);
                                    $HistorialExcedente->status = 'Pagado';
                                    $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                                    $HistorialExcedente->update();
                                }

                                // TODO Ahora eliminamos de la tabla excedente el registro del usuario

                                $eliminarRegistroExcedente = Excedente::where('persona_id',$pcliente_id)->where('tipo','Pagar_por_oficina')->first();

                                if ($eliminarRegistroExcedente) {
                                    Excedente::destroy($eliminarRegistroExcedente->id);
                                }
                            }







                        }else{
                            
                            $DetallePagoOficina = new  DetallePagoOficina();
                            $DetallePagoOficina->tipo_pago = 'Efectivo';
                            $DetallePagoOficina->num_transaccion = $servicio->num_servicio;
                            $DetallePagoOficina->deuda = $monto_deuda;
                            $DetallePagoOficina->saldo_pagado = $monto_pagado;
                            $DetallePagoOficina->fecha_pago = $fecha_pago;
                            $DetallePagoOficina->persona_id = $pcliente_id;
                            $DetallePagoOficina->caja_id = $caja_id;
                            $DetallePagoOficina->user_id = $user_id;
                            $DetallePagoOficina->save();


                            // TODO Guardamos en la tabla historial excedente

                            $historialExcedentes = HistorialExcedente::where('persona_id',$pcliente_id)->where('tipo_registro','Pago_por_oficina')->where('status','Pendiente')->get();

                            // return $historialExcedentes;
                            
                            if($historialExcedentes){
                                $saldo_disponible = 0;
                                $motivo = '';
                                $banco_id = '';
                                $servicio_id = '';

                                foreach ($historialExcedentes as $historialExcedente) {
                                    $saldo_disponible = $historialExcedente->saldo_disponible;
                                    $motivo = $historialExcedente->motivo;
                                    $banco_id = $historialExcedente->banco_id;
                                    $servicio_id = $historialExcedente->servicio_id;

                                    $HistorialExcedente = HistorialExcedente::findOrFail($historialExcedente->id);
                                    $HistorialExcedente->status = 'Pagado';
                                    $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                                    $HistorialExcedente->update();

                                }
                            }
                            
                            if($saldo_disponible > 0){
                                $saldo_anterior = $saldo_disponible;
                                $saldo_disponible = $saldo_disponible - $monto_pagado;

                            }

                            $HistorialExcedente = new  HistorialExcedente;
                            $HistorialExcedente->tipo_registro = 'Pago_por_oficina';
                            $HistorialExcedente->status = 'Pendiente';
                            $HistorialExcedente->tipo_operacion = 'Egreso';
                            $HistorialExcedente->modo_pago = 'Por caja';
                            $HistorialExcedente->num_servicio = $servicio->num_servicio;
                            $HistorialExcedente->motivo = $motivo;
                            $HistorialExcedente->saldo_anterior = $saldo_anterior;
                            $HistorialExcedente->saldo_operacion = $monto_pagado;
                            $HistorialExcedente->saldo_disponible = $saldo_disponible;
                            $HistorialExcedente->operador = $operador;
                            $HistorialExcedente->banco_id = $banco_id;
                            $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                            $HistorialExcedente->persona_id = $pcliente_id;
                            $HistorialExcedente->servicio_id = $servicio->id;
                            $HistorialExcedente->caja_id = $caja_id;
                            $HistorialExcedente->user_id  = $user_id;
                            $HistorialExcedente->save();


                            $UpdateExcedente = Excedente::where('persona_id', $pcliente_id)->first();
                            $UpdateExcedente->excedente -= $monto_pagado;
                            $UpdateExcedente->update();
                            

                        }


                        }
                        // return $total_venta;
                        //validamos que el monto pagado sea mayor o igual al total de la venta
                        if($opS >= $total_venta){
                            // return 'si';
                            // calculamos excedente si el valor pagado es mayor a la venta
                            // $MontoDolarR = $request->get('MontoDolar');
                            // return $montoD;
                            //validamos si montobase es mayor y montopendiente es menor... lo que significa esto es que estamos reciviendo una moneda nueva
                            if($montoBase > 0){
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
                                        echo 'igual <br> ';
                                        echo 'value '.$value.' <br> ';
                                        echo 'restk '.$restk.' <br> ';
                                        $restk = $restk - $value;
                                        $x = floatval($x - $value);
                                        $restk = floatval($restk);
                                        echo  ' divisa: '.$p.' montoDivisa: '.$montoDiv[$p].' tasaTiket: '.$TasaT[$p].' montoDolar: '.floatval($value).' montoDolarConsumo: '.floatval($value).'  excedente:  '. 0 .' vueltos: '. 0 .'<br> ';

                                        $Pago_Servicio = new Pago_Servicio();
                                        $Pago_Servicio->Divisa = $p;
                                        $Pago_Servicio->MontoDivisa = $montoDiv[$p];
                                        $Pago_Servicio->TasaTiket = $TasaT[$p];
                                        $Pago_Servicio->MontoDolar = floatval($value);
                                        $Pago_Servicio->MontoDolarServicio = floatval($value);
                                        $Pago_Servicio->Excedente = 0;
                                        $Pago_Servicio->Vueltos = 0;
                                        $Pago_Servicio->servicio_id = $servicio_id;
                                        $Pago_Servicio->caja_id = $caja_id;
                                        $Pago_Servicio->save();



                                        echo 'excd '. 0 .' <br> ';
                                        echo 'vueltos '. 0 .' <br> ';
                                        echo $restk.' <br> ';
                                        $residuo = $restk;


                                    }else if (round($value,6) < round($restk,6)){

                                        echo 'value '.$value.' <br> ';
                                        echo 'restk '.$restk.' <br> ';
                                        echo 'menor <br> ';
                                        $restk = $restk - $value;
                                        echo  ' divisa: '.$p.' montoDivisa: '.$montoDiv[$p].' tasaTiket: '.$TasaT[$p].' montoDolar: '.floatval($value).' montoDolarConsumo: '.floatval($value).'  excedente:  '. 0 .' vueltos: '. 0 .'<br> ';


                                        $Pago_Servicio = new Pago_Servicio();
                                        $Pago_Servicio->Divisa = $p;
                                        $Pago_Servicio->MontoDivisa = $montoDiv[$p];
                                        $Pago_Servicio->TasaTiket = $TasaT[$p];
                                        $Pago_Servicio->MontoDolar = floatval($value);
                                        $Pago_Servicio->MontoDolarServicio = floatval($value);
                                        $Pago_Servicio->Excedente = 0;
                                        $Pago_Servicio->Vueltos = 0;
                                        $Pago_Servicio->servicio_id = $servicio_id;
                                        $Pago_Servicio->caja_id = $caja_id;
                                        $Pago_Servicio->save();

                                        echo 'excd '. 0 .' <br> ';
                                        echo 'vueltos '. 0 .' <br> ';
                                        echo $restk.' <br> ';
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

                                        $Pago_Servicio = new Pago_Servicio();
                                        $Pago_Servicio->Divisa = $p;
                                        $Pago_Servicio->MontoDivisa = $montoDiv[$p];
                                        $Pago_Servicio->TasaTiket = $TasaT[$p];
                                        $Pago_Servicio->MontoDolar = floatval($value);
                                        $Pago_Servicio->MontoDolarServicio = floatval($residuo);
                                        $Pago_Servicio->Excedente = $exc;
                                        $Pago_Servicio->Vueltos = $vuel;
                                        $Pago_Servicio->servicio_id = $servicio_id;
                                        $Pago_Servicio->caja_id = $caja_id;
                                        $Pago_Servicio->save();


                                        if($exc > 0){
                                            $excdtsRecibidosCaja = new Excedentes_Recibidos_Caja_Actual();
                                            $excdtsRecibidosCaja->Tipo = 'Servicio';
                                            $excdtsRecibidosCaja->Estado = 'Pendiente';
                                            $excdtsRecibidosCaja->Divisa = $p;
                                            $excdtsRecibidosCaja->MontoDivisa = floatval($exc * $TasaT[$p]);
                                            $excdtsRecibidosCaja->TasaTiket = $TasaT[$p];
                                            $excdtsRecibidosCaja->MontoDolar = floatval($exc);
                                            $excdtsRecibidosCaja->servicio_id = $servicio_id;
                                            $excdtsRecibidosCaja->venta_id = 0;
                                            $excdtsRecibidosCaja->horas_extra_id = 0;
                                            $excdtsRecibidosCaja->caja_id = $caja_id;
                                            $excdtsRecibidosCaja->save();
                                            echo  'Excedentes_Recibidos_Caja_Actual Tipo: Servicio Estado: Pendiente Divisa: '.$p.' MontoDivisa: '.floatval($exc * $TasaT[$p]).' TasaTiket: '.$TasaT[$p].'  MontoDolar:  '.floatval($exc).'<br> ';
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
                                                    $Pago_Extras_Vueltos->Tipo = 'Servicio';
                                                    $Pago_Extras_Vueltos->tipo_vuelto = 'Vueltos_Pago';
                                                    $Pago_Extras_Vueltos->Divisa = $Vdivisa[$cont];
                                                    $Pago_Extras_Vueltos->MontoDivisa = $VMontoDivisa[$cont];
                                                    $Pago_Extras_Vueltos->TasaTiket = $VTasaTiket[$cont];
                                                    $Pago_Extras_Vueltos->MontoDolar = floatval($VMontoDolar[$cont]);
                                                    $Pago_Extras_Vueltos->servicio_id = $servicio_id;
                                                    $Pago_Extras_Vueltos->venta_id = 0;
                                                    $Pago_Extras_Vueltos->horas_extra_id = 0;
                                                    $Pago_Extras_Vueltos->detalle__creditos__pagado_id = 0;
                                                    $Pago_Extras_Vueltos->caja_id = $caja_id;
                                                    $Pago_Extras_Vueltos->save();

                                                    echo  'Pago_Vuelto Tipo: Consumo  Divisa: '.$Vdivisa[$cont].' MontoDivisa: '.$VMontoDivisa[$cont].' TasaTiket: '.$VTasaTiket[$cont].' MontoDolar: '.floatval($VMontoDolar[$cont]).'<br> ';
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
                            }

                            if($pagoConVueltosCaja > 0){
                                $Pago_Servicio = new Pago_Servicio();
                                $Pago_Servicio->Divisa = 'Dolar';
                                $Pago_Servicio->MontoDivisa = $pagoConVueltosCaja;
                                $Pago_Servicio->TasaTiket = $request->get('tasaDolar');
                                $Pago_Servicio->MontoDolar = floatval($pagoConVueltosCaja);
                                $Pago_Servicio->MontoDolarServicio = floatval($pagoConVueltosCaja);
                                $Pago_Servicio->Excedente = 0;
                                $Pago_Servicio->Vueltos = 0;
                                $Pago_Servicio->servicio_id = $servicio_id;
                                $Pago_Servicio->caja_id = $caja_id;
                                $Pago_Servicio->save();
                            }

                            
                        // return 'Finalizo';

                        }else{
                            
                            return Redirect::back()
                            ->with('status_danger', '¡Error Pago incompleto! Debe ingresar un monto para pagar y procesar el servicio... ');
                        }

                        
                    }



                    //ahora actualizamos la tabla Habitaico con un estatus de ocupada
                    $habitacion = Habitacione::findOrFail($id_habitaicon);
                    $habitacion->status = 'Ocupada';
                    $habitacion->update();

                    $servicio_id = Servicio::where('habitacion_id',$habitacion_id_vieja)->where('status_servicio', 'Iniciado')->where('id', $id)->first();
                    //    return $servicio_id;
                    if($servicio_id){
                        // return 'todo bien';
                        $servicio = Servicio::findOrFail($servicio_id->id);
                        $servicio->status_servicio = 'Finalizado';
                        $servicio->update();

                        if($request->get('habitacion_id_nueva2') <> $request->get('habitacion_id_vieja')){

                            $habitacionCambio = Habitacione::findOrFail($habitacion_id_vieja);
                            $habitacionCambio->status = 'Limpieza';
                            $habitacionCambio->update();
                        }
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

                if( $printer->print_error === 1 ) {
                    return Redirect::to('checkout')->with('status_success', 'El servicio fué registrado exitosamente');
                }else{
                    return Redirect::to('checkout')->with('status_warning', 'El servicio fué registrado exitosamente. Sin embargo, no se pudo emitir el ticket con la impresora: ' . $printer->print_name );
                }
                // return view('checkin.checkin.index', compact('title','tasas'));
                return Redirect::to('checkout')->with('success', 'El servicio fué registrado exitosamente');
            }
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