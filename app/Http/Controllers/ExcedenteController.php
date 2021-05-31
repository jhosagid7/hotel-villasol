<?php

namespace App\Http\Controllers;

use App\Excedente;
use App\BancosCliente;
use App\HistorialExcedente;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Excedentes_Recibidos_Caja_Actual;

class ExcedenteController extends Controller
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


            $bandera = $request->get('bandera');
            $nombre_cliente = $request->get('nombre');
            $tipo_documento = $request->get('tipo_documento');
            $num_documento = $request->get('num_documento');
            $direccion = $request->get('direccion');
            $telefono = $request->get('telefono');
            $email = $request->get('email');
            $selec_banco = $request->get('selec_banco');
            $nombre_banco = $request->get('nombre_banco');
            $codigo = $request->get('codigo');
            $num_cuenta = $request->get('num_cuenta');
            $tipo_cuenta = $request->get('tipo_cuenta');
            $dcliente_id = $request->get('dcliente_id');
            $banco_id = $request->get('banco_id');


            $excedente = $request->get('excedente');;
            $servicio_id = $request->get('servicio_id');;
            $num_servicio = $request->get('num_servicio');;
            $motivo = $request->get('motivo');;
            $caja_id = $request->get('caja_id');;

            $operador = Auth::user()->name;
            $user_id = Auth::user()->id;




            // return $bandera;

            // TODO Verificamos que variable viene en bandera para hacer el proceso de pagar por oficina o crear nuevo excedente
            //comensamos proceso de pagar por oficina
            if($bandera == 'pagarPorOficina'){
                // return 'pagar por oficina';



                try{


                // TODO Guardamos en la tabla excedente

                $exced = $excedente;

                            $ifCliente = Excedente::where('persona_id',$dcliente_id)->where('tipo','Pagar_por_oficina')->first();
                            // return $ifCliente;

                            if($ifCliente){
                                // return 'si';

                                $upExcedente = Excedente::findOrFail($ifCliente->id);
                                $upExcedente->excedente = $upExcedente->excedente + $exced;
                                $upExcedente->update();
                            }else{
                                // return 'no';
                                $dexcedente = new Excedente;
                                $dexcedente->tipo = 'Pagar_por_oficina';
                                $dexcedente->nombre_cliente = $nombre_cliente;
                                $dexcedente->cedula_cliente = $num_documento;
                                $dexcedente->direccion_cliente = $direccion;
                                $dexcedente->telefono_cliente = $telefono;
                                $dexcedente->excedente = $exced;
                                $dexcedente->persona_id = $dcliente_id;
                                $dexcedente->save();



                            }

                // TODO Guardamos los datos del banco del cliente

                $ifBancoCliente = BancosCliente::where('persona_id',$dcliente_id)->where('num_cuenta',$num_cuenta)->first();
                            // return $ifCliente;

                            if(!$ifBancoCliente){
                                // return 'si';

                                // return 'no';
                                $BancosCliente = new BancosCliente;
                                $BancosCliente->nombre_banco = $nombre_banco;
                                $BancosCliente->codigo = $codigo;
                                $BancosCliente->num_cuenta = $num_cuenta;
                                $BancosCliente->tipo_cuenta = $tipo_cuenta;
                                $BancosCliente->persona_id = $dcliente_id;
                                $BancosCliente->banco_id = $banco_id;
                                $BancosCliente->save();



                            }

                            // TODO Guardamos en la tabla historial excedente

                            $ifSaldoAnterior = HistorialExcedente::where('persona_id',$dcliente_id)->where('tipo_registro','Pagar_por_oficina')->latest()->first();
                            // return $ifSaldoAnterior;

                            if($ifSaldoAnterior){
                                $saldo_anterior = $ifSaldoAnterior->saldo_disponible;
                                $saldo_disponible = $ifSaldoAnterior->saldo_disponible + $excedente;

                            }else{
                                $saldo_anterior = 0;
                                $saldo_disponible = $excedente;
                            }

                            $HistorialExcedente = new  HistorialExcedente;
                            $HistorialExcedente->tipo_registro = 'Pago_por_oficina';
                            $HistorialExcedente->num_servicio = $num_servicio;
                            $HistorialExcedente->motivo = $motivo;
                            $HistorialExcedente->saldo_anterior = $saldo_anterior;
                            $HistorialExcedente->saldo_operacion = $excedente;
                            $HistorialExcedente->saldo_disponible = $saldo_disponible;
                            $HistorialExcedente->operador = $operador;
                            $HistorialExcedente->banco_id = $banco_id;
                            $HistorialExcedente->persona_id = $dcliente_id;
                            $HistorialExcedente->servicio_id = $servicio_id;
                            $HistorialExcedente->caja_id = $caja_id;
                            $HistorialExcedente->user_id  = $user_id;
                            $HistorialExcedente->save();


                // TODO Actualizamos la tabla excedentes__recibidos__caja__actuals para poner el estatus en Pagar por oficina

                $upExcedentesRecibidosCajaActual = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$servicio_id)->first();
                $upExcedentesRecibidosCajaActual->Estado = 'PagarOficina';
                $upExcedentesRecibidosCajaActual->update();

                // DB::commit();

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

            return app(CheckoutController::class)->show($servicio_id);

            }


            //comensamos proceso para crear nuevo excedente
            if ($bandera == 'crearCuenta') {
                // return 'crear nueva Cuenta';
                // return $request;

                try{


                    // TODO Guardamos en la tabla excedente

                    $exced = $excedente;

                                $ifCliente = Excedente::where('persona_id',$dcliente_id)->where('tipo','Excedente')->first();
                                // return $ifCliente;

                                if($ifCliente){
                                    // return 'si';

                                    $upExcedente = Excedente::findOrFail($ifCliente->id);
                                    $upExcedente->excedente = $upExcedente->excedente + $exced;
                                    $upExcedente->update();
                                }else{
                                    // return 'no';
                                    $dexcedente = new Excedente;
                                    $dexcedente->tipo = 'Excedente';
                                    $dexcedente->nombre_cliente = $nombre_cliente;
                                    $dexcedente->cedula_cliente = $num_documento;
                                    $dexcedente->direccion_cliente = $direccion;
                                    $dexcedente->telefono_cliente = $telefono;
                                    $dexcedente->excedente = $exced;
                                    $dexcedente->persona_id = $dcliente_id;
                                    $dexcedente->save();



                                }



                                // TODO Guardamos en la tabla historial excedente

                                $ifSaldoAnterior = HistorialExcedente::where('persona_id',$dcliente_id)->where('tipo_registro','Excedente')->latest()->first();
                                // return $ifSaldoAnterior;

                                if($ifSaldoAnterior){
                                    $saldo_anterior = $ifSaldoAnterior->saldo_disponible;
                                    $saldo_disponible = $ifSaldoAnterior->saldo_disponible + $excedente;

                                }else{
                                    $saldo_anterior = 0;
                                    $saldo_disponible = $excedente;
                                }

                                $HistorialExcedente = new  HistorialExcedente;
                                $HistorialExcedente->tipo_registro = 'Excedente';
                                $HistorialExcedente->num_servicio = $num_servicio;
                                $HistorialExcedente->motivo = $motivo;
                                $HistorialExcedente->saldo_anterior = $saldo_anterior;
                                $HistorialExcedente->saldo_operacion = $excedente;
                                $HistorialExcedente->saldo_disponible = $saldo_disponible;
                                $HistorialExcedente->operador = $operador;
                                $HistorialExcedente->banco_id = $banco_id;
                                $HistorialExcedente->persona_id = $dcliente_id;
                                $HistorialExcedente->servicio_id = $servicio_id;
                                $HistorialExcedente->caja_id = $caja_id;
                                $HistorialExcedente->user_id  = $user_id;
                                $HistorialExcedente->save();


                    // TODO Actualizamos la tabla excedentes__recibidos__caja__actuals para poner el estatus en Pagar por oficina

                    $upExcedentesRecibidosCajaActual = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$servicio_id)->first();
                    $upExcedentesRecibidosCajaActual->Estado = 'ExcedenteNuevo';
                    $upExcedentesRecibidosCajaActual->update();

                    // DB::commit();

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

                return app(CheckoutController::class)->show($servicio_id);
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
