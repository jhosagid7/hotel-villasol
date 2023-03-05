<?php

namespace App\Http\Controllers;

use App\Banco;
use App\Excedente;
use Carbon\Carbon;
use App\PreExcedente;
use App\BancosCliente;
use App\BancosEmpresa;
use App\DetallePagoOficina;
use App\HistorialExcedente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DetallePagoOficinaController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;

        $telefono_pago_movil_cliente = $request->get('pago_movil');

        $codigo_cliente = $request->get('codigo_banco_cliente');
        $num_cuenta_cliente = $request->get('num_cuenta');
        $tipo_cuenta_cliente = $request->get('tipo_cuenta');
        $nombre_banco_cliente = $request->get('nombre_banco');



        $num_transaccion = $request->get('num_operacion');
        $deuda = $request->get('deudaPendiente');
        $saldo_pagado = $request->get('deudaPendiente');
        $fecha_pago = Carbon::now();
        $persona_id = $request->get('dcliente_id');
        $tipo_documento = $request->get('tipo_documento');
        $sucursal_id = $request->get('sucursal_id');
        $caja_id  = $request->get('caja_id');
        $user_id = Auth::user()->id;

        try{
            // TODO Guardamos los datos de la cuenta bancaria de la empresa pero revisamos si ya existe esa cuenta registrada

            // TODO Guardamos los registros en la tabla Detalle pagos oficina

            $DetallePagoOficina = new  DetallePagoOficina();
            $DetallePagoOficina->tipo_pago = $tipo_documento;
            $DetallePagoOficina->telefono_pago_movil_cliente = $telefono_pago_movil_cliente;
            $DetallePagoOficina->num_cuenta_cliente = $num_cuenta_cliente;
            $DetallePagoOficina->tipo_cuenta_cliente = $tipo_cuenta_cliente;
            $DetallePagoOficina->nombre_banco_cliente = $nombre_banco_cliente;
            $DetallePagoOficina->num_transaccion = $num_transaccion;
            $DetallePagoOficina->deuda = $deuda;
            $DetallePagoOficina->saldo_pagado = $saldo_pagado;
            $DetallePagoOficina->fecha_pago = $fecha_pago;
            $DetallePagoOficina->persona_id = $persona_id;
            $DetallePagoOficina->caja_id = $caja_id;
            $DetallePagoOficina->user_id = $user_id;
            $DetallePagoOficina->save();

            // TODO Ahora actualizamos la tabla historial_excedentes colocando el id del detalle pago oficina para poder agrupar los por ide de pago
            // y asi poder consultarlos luego y cambiando el estatus a pagado

            $historialExcedentes = HistorialExcedente::where('persona_id',$persona_id)->where('tipo_registro','Pago_por_oficina')->where('status','Pendiente')->get();

            if($historialExcedentes){

                foreach ($historialExcedentes as $historialExcedente) {

                    $HistorialExcedente = HistorialExcedente::findOrFail($historialExcedente->id);
                    $HistorialExcedente->status = 'Pagado';
                    $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                    $HistorialExcedente->update();

                }

                // TODO Ahora eliminamos de la tabla excedente el registro del usuario

                $eliminarRegistroExcedente = Excedente::where('persona_id',$persona_id)->where('tipo','Pagar_por_oficina')->first();

                if ($eliminarRegistroExcedente) {
                    Excedente::destroy($eliminarRegistroExcedente->id);
                }

                // TODO Ahora eliminamos de la tabla preExcedente el registro del usuario ya que esta tabla maneja los mostos de pagar por
                // oficina en el area de la caja

                $eliminarRegistroPreExcedente = PreExcedente::where('cliente_id',$persona_id)->first();

                if ($eliminarRegistroPreExcedente) {
                    PreExcedente::destroy($eliminarRegistroPreExcedente->id);
                }



            }

            DB::commit();

        }catch(\Exception $e)
        {

            dd($e);
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
