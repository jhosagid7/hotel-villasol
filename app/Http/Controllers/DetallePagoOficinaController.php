<?php

namespace App\Http\Controllers;

use App\Banco;
use App\Excedente;
use Carbon\Carbon;
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




        $telefono_pago_movil_cliente = $request->get('pago_mobil_banco_cliente');
        $telefono_pago_movil_empresa = $request->get('pago_mobil_banco_empresa');
        $banco_id_banco_empresa = $request->get('banco_id_banco_empresa');
        $banco_id_banco_cliente = $request->get('banco_id_banco_cliente');

        $banco_id_banco_cliente =
        $codigo_cliente = $request->get('codigo_banco_cliente');
        $num_cuenta_cliente = $request->get('num_cuenta_banco_cliente');
        $tipo_cuenta_cliente = $request->get('tipo_cuenta_banco_cliente');
        $nombre_banco_cliente = $request->get('nombre_banco_cliente');
        $num_cuenta_empresa = $request->get('num_cuenta_banco_empresa');
        $nombre_banco_empresa = $request->get('nombre_banco_empresa');
        $codigo_banco_empresa = $request->get('codigo_banco_empresa');
        $tipo_cuenta_empresa = $request->get('tipo_cuenta_banco_empresa');
        $num_transaccion = $request->get('num_operacion');
        $deuda = $request->get('deudaPendiente');
        $saldo_pagado = $request->get('deudaPendiente');
        $fecha_pago = Carbon::now();
        $persona_id = $request->get('dcliente_id');
        $sucursal_id = $request->get('sucursal_id');
        // return $sucursal_id;
        $caja_id  = $request->get('caja_id');
        $user_id = Auth::user()->id;


        try{


            // TODO Guardamos los datos de la cuenta bancaria de la empresa pero revisamos si ya existe esa cuenta registrada

            $ifBancoEmpresa = BancosEmpresa::where('pertenece','Empresa')->where('codigo',$codigo_banco_empresa)->where('num_cuenta',$num_cuenta_empresa)->first();
            // return $ifCliente;

            if(!$ifBancoEmpresa){
                //buscamos el nombre y el ide del banco para guardarlos en la tabla banco empresa pra que siempre se guarde con el nombre que aparece enla tabla bancos
                $CodigoBancoEmpresa = Banco::where('codigo',$codigo_banco_empresa)->first();
                if($CodigoBancoEmpresa){
                    $nombre_banco_empresa = $CodigoBancoEmpresa->nombre_banco;
                    $codigo_banco_empresa = $CodigoBancoEmpresa->codigo;
                    $banco_id_banco_empresa = $CodigoBancoEmpresa->id;
                }

                // return 'no';
                $BancosCliente = new BancosEmpresa;
                $BancosCliente->pertenece = 'Empresa';
                $BancosCliente->nombre_banco = $nombre_banco_empresa;
                $BancosCliente->codigo = $codigo_banco_empresa;
                $BancosCliente->num_cuenta = $num_cuenta_empresa;
                $BancosCliente->tipo_cuenta = $tipo_cuenta_empresa;
                $BancosCliente->pago_mobil = $telefono_pago_movil_empresa;
                $BancosCliente->sucursal_id = $sucursal_id;
                $BancosCliente->banco_id = $banco_id_banco_empresa;
                $BancosCliente->save();


            }
            //


            // TODO Guardamos los registros en la tabla Detalle pagos oficina

            $DetallePagoOficina = new  DetallePagoOficina();
            $DetallePagoOficina->tipo_pago = 'Transferencia';
            $DetallePagoOficina->telefono_pago_movil_cliente = $telefono_pago_movil_cliente;
            $DetallePagoOficina->num_cuenta_cliente = $codigo_cliente.'-'.$num_cuenta_cliente;
            $DetallePagoOficina->tipo_cuenta_cliente = $tipo_cuenta_cliente;
            $DetallePagoOficina->nombre_banco_cliente = $nombre_banco_cliente;
            $DetallePagoOficina->num_cuenta_empresa = $codigo_banco_empresa.'-'.$num_cuenta_empresa;
            $DetallePagoOficina->nombre_banco_empresa = $nombre_banco_empresa;
            $DetallePagoOficina->tipo_cuenta_empresa = $tipo_cuenta_empresa;
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

    /**
     * Display the specified resource.
     *
     * @param  \App\DetallePagoOficina  $detallePagoOficina
     * @return \Illuminate\Http\Response
     */
    public function show(DetallePagoOficina $detallePagoOficina)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DetallePagoOficina  $detallePagoOficina
     * @return \Illuminate\Http\Response
     */
    public function edit(DetallePagoOficina $detallePagoOficina)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DetallePagoOficina  $detallePagoOficina
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetallePagoOficina $detallePagoOficina)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DetallePagoOficina  $detallePagoOficina
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetallePagoOficina $detallePagoOficina)
    {
        //
    }
}
