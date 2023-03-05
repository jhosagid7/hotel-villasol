<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Excedente;
use App\Reintegro;
use Carbon\Carbon;
use App\PreExcedente;
use App\DetallePagoOficina;
use App\HistorialExcedente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReintegroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
        $pcliente_id = $request->get('pcliente_id');
        $phistorial_id = $request->get('phistorial_id');
        $pnombre_cliente = $request->get('pnombre_cliente');
        $cliente = $request->get('cliente');
        $monto_deuda = $request->get('ptotal_sistema_reg_input');
        $monto_pagado = $request->get('ptotal_operador_reg_input');
        $monto_dolar = $request->get('pcantidad_dolar_rep');
        $monto_peso = $request->get('pcantidad_peso_rep');
        $monto_bolivar = $request->get('pcantidad_efectivo_rep');
        $monto_trans = $request->get('pcantidad_trans_rep');
        $monto_dolar_to_dolar = $request->get('pdif_moneda_dolar_to_dolar_input');
        $monto_peso_to_dolar = $request->get('pdif_moneda_peso_to_dolar_input');
        $monto_bolivar_to_dolar = $request->get('pdif_moneda_efectivo_to_dolar_input');
        $monto_trans_to_dolar = $request->get('pdif_moneda_trans_to_dolar_input');
        $tasa_dolar = $request->get('pTasaDolar');
        $tasa_peso = $request->get('pTasaPeso');
        $tasa_bolivar = $request->get('pTasaBolivar');
        $tasa_trans = $request->get('pTasaTrans');
        $observacion = $request->get('pObservaciones');
        $operador = Auth::user()->name;
        $user_id = Auth::user()->id;
        $caja = Caja::where("estado", "=", 'Abierta')->first();
        $caja_id = $caja->id;
        $fecha_pago = Carbon::now();





        try {

            $reintegro = new  Reintegro();
            $reintegro->nombre_cliente = $pnombre_cliente;
            $reintegro->monto_deuda = $monto_deuda;
            $reintegro->monto_pagado = $monto_pagado;
            $reintegro->monto_dolar = $monto_dolar;
            $reintegro->monto_peso = $monto_peso;
            $reintegro->monto_bolivar = $monto_bolivar;
            $reintegro->monto_trans = $monto_trans;
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


            // TODO Guardamos los registros en la tabla Detalle pagos oficina

            if ($monto_pagado == $monto_deuda) {
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

                if ($is_cliente) {
                    if ($is_cliente->deuda_total_acumulada == $is_cliente->monto_excedente_actual) {
                        PreExcedente::destroy($is_cliente->id);
                        // TODO Ahora actualizamos la tabla historial_excedentes colocando el id del detalle pago oficina para poder agrupar los por ide de pago
                        // y asi poder consultarlos luego y cambiando el estatus a pagado


                        $historialExcedentes = HistorialExcedente::where('persona_id', $pcliente_id)->where('tipo_registro', 'Pago_por_oficina')->where('status', 'Pendiente')->get();

                        if ($historialExcedentes) {

                            foreach ($historialExcedentes as $historialExcedente) {

                                $HistorialExcedente = HistorialExcedente::findOrFail($historialExcedente->id);
                                $HistorialExcedente->status = 'Pagado';
                                $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                                $HistorialExcedente->update();
                            }

                            // TODO Ahora eliminamos de la tabla excedente el registro del usuario

                            $eliminarRegistroExcedente = Excedente::where('persona_id', $pcliente_id)->where('tipo', 'Pagar_por_oficina')->first();

                            if ($eliminarRegistroExcedente) {
                                Excedente::destroy($eliminarRegistroExcedente->id);
                            }
                        }
                    } else {
                        // TODO Guardamos en la tabla historial excedente

                        $historialExcedentes = HistorialExcedente::where('persona_id', $pcliente_id)->where('tipo_registro', 'Pago_por_oficina')->where('status', 'Pendiente')->get();

                        if ($historialExcedentes) {
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

                        if ($saldo_disponible > 0) {
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

                        if ($is_cliente) {
                            PreExcedente::destroy($is_cliente->id);
                        }
                    }
                }
            } else {
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


                // TODO Guardamos en la tabla historial excedente

                $historialExcedentes = HistorialExcedente::where('persona_id', $pcliente_id)->where('tipo_registro', 'Pago_por_oficina')->where('status', 'Pendiente')->get();

                if ($historialExcedentes) {
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

                if ($saldo_disponible > 0) {
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

                if ($is_cliente) {
                    $updatePreExcedente = PreExcedente::findOrFail($is_cliente->id);
                    $updatePreExcedente->monto_excedente_actual -= $monto_pagado;
                    $updatePreExcedente->deuda_total_acumulada -= $monto_pagado;
                    $updatePreExcedente->update();
                }
            }


            DB::commit();
        } catch (\Exception $e) {

            DB::rollback();
            if (isset($MontoDolarR)) {

                return redirect()
                    ->route('registro')
                    ->with('status_danger', '¡Error Pago incompleto! ... ');
            }
        }

        return app(CheckoutController::class)->index();
    }
}
