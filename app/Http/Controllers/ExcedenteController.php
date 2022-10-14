<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Tasa;
use App\User;
use App\Banco;
use App\Credito;
use App\Persona;
use App\Articulo;
use App\Excedente;
use Carbon\Carbon;
use App\Sessioncaja;
use App\PreExcedente;
use App\BancosCliente;
use App\BancosEmpresa;
use App\Detalle_credito;
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
    public function index(Request $request)
    {
        if ($request) {
            $mesActual = Carbon::now()->format('Y-m-d');
            $restaMes = Carbon::now()->subWeek(1);
            $restaMes = $restaMes->format('Y-m-d');

            $fecha = $request->get('fecha');
            $tipo = $request->get('tipo');
            $cliente = $request->get('cliente');
            $operador = $request->get('operador');

            $users = User::Where('id', '<>', '2')->get();
            $clientes = Persona::get();


            $title='Pagar por oficina';
            $pagarporoficinas = Excedente::where('tipo','Pagar_por_oficina')->get();

            $pagarporoficinas = Excedente::where('tipo','Pagar_por_oficina')->fecha($fecha)
            // ->tipo($tipo)
            ->cliente($cliente)
            ->operador($operador)
            ->get();






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




            return view('pagos.oficina.index', compact('clientes','users','title','pagarporoficinas'));
        }
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
            $isTransferencia = $request->get('isTransferencia');
            $isPagoMobil = $request->get('isPagoMobil');
            $isEfectivo = $request->get('isEfectivo');
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
            $pago_movil = $request->get('pago_mobil');
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


            if ($tipo_documento == null) {
                $tipo_documento = 'CI.V-';
            } else {
                $tipo_documento = $tipo_documento;
            }




            // return $bandera;

            // TODO Verificamos que variable viene en bandera para hacer el proceso de pagar por oficina o crear nuevo excedente
            //comensamos proceso de pagar por oficina
            if($bandera == 'pagarPorOficina'){
                // return $request->isEfectivo;



                try{

                    // TODO verificamos que exista el cliente si no lo registramos

                    $existeCliente = Persona::where('id',$dcliente_id)->where('nombre', '<>', 'Proveedor Comun')->where('nombre', '<>', 'Cliente Comun')->first();

                    if($existeCliente){
                        $dcliente_id = $existeCliente->id;
                    }else{
                        $insertCliente = new Persona;
                        $insertCliente->tipo_persona = 'Cliente';
                        $insertCliente->nombre = $nombre_cliente;
                        $insertCliente->tipo_documento = $tipo_documento;
                        $insertCliente->num_documento = $num_documento;
                        $insertCliente->direccion = $direccion;
                        $insertCliente->telefono = $telefono;
                        $insertCliente->email = $email;
                        $insertCliente->isCortesia = null;
                        $insertCliente->isCredito = null;
                        $insertCliente->imagen = 'thumb_upl_57e81d357d468.jpg';
                        $insertCliente->limite_fecha = null;
                        $insertCliente->limite_monto = null;
                        $insertCliente->save();

                        $dcliente_id = $insertCliente->id;
                    }


                // TODO Guardamos en la tabla excedente

                $exced = $excedente;

                            $ifCliente = Excedente::where('persona_id',$dcliente_id)->where('tipo','Pagar_por_oficina')->first();
                            // return $ifCliente;
                            if ($num_cuenta == null) {
                                    $num_cuenta = '0';
                                } else {
                                    $num_cuenta = $num_cuenta;
                                }

                            if($ifCliente){
                                // return 'si';

                                $upExcedente = Excedente::findOrFail($ifCliente->id);
                                $upExcedente->direccion_cliente = $direccion;
                                $upExcedente->telefono_pago_movil_cliente = $pago_movil;
                                $upExcedente->nombre_banco_cliente = $nombre_banco;
                                $upExcedente->num_cuenta_cliente = $codigo .' - '.$num_cuenta;
                                $upExcedente->tipo_cuenta_cliente = $tipo_cuenta;
                                $upExcedente->excedente = $upExcedente->excedente + $exced;
                                $upExcedente->isTransferencia = $request->isTransferencia;
                                $upExcedente->isPagoMobil = $request->isPagoMobil;
                                $upExcedente->isEfectivo = $request->isEfectivo;
                                $upExcedente->update();
                            }else{
                                // return 'no';


                                $dexcedente = new Excedente;
                                $dexcedente->tipo = 'Pagar_por_oficina';
                                $dexcedente->nombre_cliente = $nombre_cliente;
                                $dexcedente->cedula_cliente = $num_documento;
                                $dexcedente->direccion_cliente = $direccion;
                                $dexcedente->telefono_pago_movil_cliente = $pago_movil;
                                $dexcedente->nombre_banco_cliente = $nombre_banco;
                                $dexcedente->num_cuenta_cliente = $codigo .' - '.$num_cuenta;
                                $dexcedente->tipo_cuenta_cliente = $tipo_cuenta;
                                $dexcedente->excedente = $exced;
                                $dexcedente->isTransferencia = $request->isTransferencia;
                                $dexcedente->isPagoMobil = $request->isPagoMobil;
                                $dexcedente->isEfectivo = $request->isEfectivo;
                                $dexcedente->persona_id = $dcliente_id;
                                $dexcedente->save();



                            }

                            // TODO Guardamos los datos del banco del cliente pero revisamos si ya existe esa cuenta registrada

                            // if (!$isTransferencia == null || !$isPagoMobil == null) {
                            //     $ifBancoCliente = BancosCliente::where('persona_id',$dcliente_id)->where('codigo',$codigo)->where('num_cuenta',$num_cuenta)->first();
                            //     // return $ifCliente;

                            //     if(!$ifBancoCliente){
                            //         // return 'si';

                            //         // return 'no';

                            //         if (!$nombre_banco == null) {
                            //                 if ($num_cuenta == null) {
                            //                     $num_cuenta = '0';
                            //                 } else {
                            //                     $num_cuenta = $num_cuenta;
                            //                 }

                            //             $BancosCliente = new BancosCliente;
                            //             $BancosCliente->pertenece = 'Cliente';
                            //             $BancosCliente->nombre_banco = $nombre_banco;
                            //             $BancosCliente->codigo = $codigo;
                            //             $BancosCliente->num_cuenta = $num_cuenta;
                            //             $BancosCliente->tipo_cuenta = $tipo_cuenta;
                            //             $BancosCliente->pago_mobil = $pago_mobil;
                            //             $BancosCliente->persona_id = $dcliente_id;
                            //             $BancosCliente->banco_id = $banco_id;
                            //             $BancosCliente->save();
                            //         }




                            //     }
                            // }



                            // TODO Guardamos en la tabla historial excedente

                            $ifSaldoAnterior = HistorialExcedente::where('persona_id',$dcliente_id)->where('tipo_registro','Pago_por_oficina')->where('status','Pendiente')->latest()->first();
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
                            $HistorialExcedente->status = 'Pendiente';
                            $HistorialExcedente->tipo_operacion = 'Ingreso';
                            $HistorialExcedente->modo_pago = 'no_definido';
                            $HistorialExcedente->num_servicio = $num_servicio;
                            $HistorialExcedente->motivo = $motivo;
                            $HistorialExcedente->saldo_anterior = $saldo_anterior;
                            $HistorialExcedente->saldo_operacion = $excedente;
                            $HistorialExcedente->saldo_disponible = $saldo_disponible;
                            $HistorialExcedente->operador = $operador;
                            $HistorialExcedente->detalle_pago_oficina_id = null;
                            $HistorialExcedente->persona_id = $dcliente_id;
                            $HistorialExcedente->servicio_id = $servicio_id;
                            $HistorialExcedente->caja_id = $caja_id;
                            $HistorialExcedente->user_id  = $user_id;
                            $HistorialExcedente->save();

                            $is_cliente = PreExcedente::where('cliente_id', $dcliente_id)->first();

                            if(!$is_cliente){
                                $pre_excedente = new PreExcedente;
                                $pre_excedente->nombre_cliente = $nombre_cliente;
                                $pre_excedente->monto_excedente_actual = $saldo_disponible;
                                $pre_excedente->deuda_total_acumulada = $excedente;
                                $pre_excedente->user_id = $user_id;
                                $pre_excedente->cliente_id = $dcliente_id;
                                $pre_excedente->caja_id = $caja_id;
                                $pre_excedente->save();
                            }else{
                                $updatePreExcedente = PreExcedente::findOrFail($is_cliente->id);
                                $updatePreExcedente->monto_excedente_actual = $saldo_disponible;
                                $updatePreExcedente->deuda_total_acumulada += $excedente;
                                $updatePreExcedente->update();
                            }





// return 'llego... '.$servicio_id;
                // TODO Actualizamos la tabla excedentes__recibidos__caja__actuals para poner el estatus en Pagar por oficina

                $upExcedentesRecibidosCajaActual = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$servicio_id)->get();
                foreach ($upExcedentesRecibidosCajaActual as $upExcedentesRecibidosCaja) {
                    if($upExcedentesRecibidosCaja->Estado == 'Pendiente'){
                        $upExcedentesRecibidosCajaData = Excedentes_Recibidos_Caja_Actual::findOrFail($upExcedentesRecibidosCaja->id);
                    $upExcedentesRecibidosCajaData->Estado = 'PagarOficina';
                    $upExcedentesRecibidosCajaData->update();
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

            return app(CheckoutController::class)->show($servicio_id);

            }


            //comensamos proceso para crear nuevo excedente
            if ($bandera == 'crearCuenta') {
                // return 'crear nueva Cuenta';
                // return $request;

                try{

                    // TODO verificamos que exista el cliente si no lo registramos

                    $existeCliente = Persona::where('id',$dcliente_id)->where('nombre', '<>', 'Proveedor Comun')->where('nombre', '<>', 'Cliente Comun')->first();

                    if($existeCliente){
                        $dcliente_id = $existeCliente->id;
                    }else{
                        $insertCliente = new Persona;
                        $insertCliente->tipo_persona = 'Cliente';
                        $insertCliente->nombre = $nombre_cliente;
                        $insertCliente->tipo_documento = $tipo_documento;
                        $insertCliente->num_documento = $num_documento;
                        $insertCliente->direccion = $direccion;
                        $insertCliente->telefono = $telefono;
                        $insertCliente->email = $email;
                        $insertCliente->isCortesia = null;
                        $insertCliente->isCredito = null;
                        $insertCliente->imagen = 'thumb_upl_57e81d357d468.jpg';
                        $insertCliente->limite_fecha = null;
                        $insertCliente->limite_monto = null;
                        $insertCliente->save();

                        $dcliente_id = $insertCliente->id;
                    }



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
                                    $dexcedente->telefono_pago_movil_cliente = $telefono;
                                    $dexcedente->nombre_banco_cliente = $telefono;
                                    $dexcedente->num_cuenta_cliente = $telefono;
                                    $dexcedente->tipo_cuenta_cliente = $telefono;
                                    $dexcedente->excedente = $exced;
                                    $dexcedente->persona_id = $dcliente_id;
                                    $dexcedente->save();



                                }



                                // TODO Guardamos en la tabla historial excedente

                                $ifSaldoAnterior = HistorialExcedente::where('persona_id',$dcliente_id)->where('tipo_registro','Excedente')->where('status','Pendiente')->latest()->first();
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
                                $HistorialExcedente->status = 'Pendiente';
                                $HistorialExcedente->tipo_operacion = 'Ingreso';
                                $HistorialExcedente->num_servicio = $num_servicio;
                                $HistorialExcedente->motivo = $motivo;
                                $HistorialExcedente->saldo_anterior = $saldo_anterior;
                                $HistorialExcedente->saldo_operacion = $excedente;
                                $HistorialExcedente->saldo_disponible = $saldo_disponible;
                                $HistorialExcedente->operador = $operador;
                                $HistorialExcedente->banco_id = $banco_id;
                                $HistorialExcedente->detalle_pago_oficina_id = null;
                                $HistorialExcedente->persona_id = $dcliente_id;
                                $HistorialExcedente->servicio_id = $servicio_id;
                                $HistorialExcedente->caja_id = $caja_id;
                                $HistorialExcedente->user_id  = $user_id;
                                $HistorialExcedente->save();


                    // TODO Actualizamos la tabla excedentes__recibidos__caja__actuals para poner el estatus en Pagar por oficina

                    $upExcedentesRecibidosCajaActual = Excedentes_Recibidos_Caja_Actual::where('servicio_id',$servicio_id)->first();
                    $upExcedentesRecibidosCajaActual->Estado = 'ExcedenteNuevo';
                    $upExcedentesRecibidosCajaActual->update();

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
        $title = 'Facturas por Pagar';
        $BancosClientes = Excedente::where('persona_id',$id)->where('tipo','Pagar_por_oficina')->get();
        // return $cliente_id;
        $pagarporoficina = Excedente::where('persona_id',$id)->where('tipo','Pagar_por_oficina')->first();

        $historialExcedentes = HistorialExcedente::where('persona_id',$id)->where('tipo_registro','Pago_por_oficina')->where('status','Pendiente')->orderBy('caja_id', 'ASC')->get();

        // return $pagarporoficina;

        $pagarporoficina->caja_id = $historialExcedentes;
        // return $pagarporoficina;

        $tasaDolarHabitacion = Tasa::where('nombre','=','DolarHabitacion')->first();
        // return $tasaDolarHabitacion->tasa;
        $tasaPesoHabitacion = Tasa::where('nombre','=','PesoHabitacion')->first();
        $tasaDolar = DB::table('tasas')->where('nombre', '=', 'Dolar')->first();
        $tasaPeso = DB::table('tasas')->where('nombre', '=', 'Peso')->first();
        $tasaTransferenciaPunto = DB::table('tasas')->where('nombre', '=', 'Transferencia_Punto')->first();
        $tasaMixto = DB::table('tasas')->where('nombre', '=', 'Mixto')->first();
        $tasaEfectivo = DB::table('tasas')->where('nombre', '=', 'Efectivo')->first();
        $users = User::with('roles')->orderBy('id','Desc')->get();
        $UserName = Auth::user()->name;
        $cajaSessionid =  Sessioncaja::where('estado', 'Abierta')->orderBy('id', 'desc')->first();
        $Cajas = Caja::where("estado","=",'Abierta')->where("sessioncaja_id","=", $cajaSessionid->id)->first();
        $caja = Caja::find($Cajas->id);
        // return $caja->sucursal->id;

        $bancosCLientes = BancosCliente::where('pertenece','Cliente')->where('persona_id',$id)->get();
        $bancosEmpresas = BancosEmpresa::where('pertenece','Empresa')->where('sucursal_id',$caja->sucursal->id)->get();
        $clientes = Persona::where('nombre', '<>','Proveedor Comun')->where('nombre', '<>','Cliente Comun')->get();
            $bancos = Banco::get();

        // return $bancosCLientes;
        return view('pagos.oficina.show', compact('bancosEmpresas','bancosCLientes','clientes','bancos','historialExcedentes','caja','title','pagarporoficina','BancosClientes','tasaDolarHabitacion','tasaPesoHabitacion','tasaDolar','tasaPeso','tasaTransferenciaPunto','tasaMixto','tasaEfectivo','users','UserName'));
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
