<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Tasa;
use App\User;
use App\Banco;
use App\Credito;
use App\Persona;
use App\Excedente;
use Carbon\Carbon;
use App\Sessioncaja;
use App\BancosCliente;
use App\BancosEmpresa;
use App\Detalle_credito;
use App\DetallePagoOficina;
use App\HistorialExcedente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PagarPorOficinaController extends Controller
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
            $title='Historial Pagos por oficina';
            $pagarporoficinas = DetallePagoOficina::get();

            // return $pagarporoficinas->cliente;








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




            return view('pagos.pendientes.index', compact('title','pagarporoficinas'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = 'Facturas Pagadas';
        // $BancosClientes = BancosCliente::where('persona_id',$id)->get();
        // return $cliente_id;


        $historialExcedentes = HistorialExcedente::where('detalle_pago_oficina_id',$id)->where('tipo_registro','Pago_por_oficina')->where('status','Pagado')->get();


// return $historialExcedentes;
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
        // return $caja->sucursal->id;{{$pagarporoficina->nombre_banco_cliente ?? ''}}

        // $bancosCLientes = BancosCliente::where('pertenece','Cliente')->where('persona_id',$historialExcedentes->persona_id)->get();
        // $pagarporoficina = Excedente::where('persona_id',$historialExcedentes->persona_id)->where('tipo','Pagar_por_oficina')->first();
        $bancosEmpresas = BancosEmpresa::where('pertenece','Empresa')->where('sucursal_id',$caja->sucursal->id)->get();
        $detalle_pagado_oficina = DetallePagoOficina::findOrFail($id);
        $cliente = Persona::findOrFail($detalle_pagado_oficina->id);
        // $excedente = Excedente::
        // return $cliente;
            $bancos = Banco::get();

        // return $detalle_creditos;
        return view('pagos.pendientes.show', compact('bancosEmpresas','bancosCLientes','cliente','bancos','historialExcedentes','caja','title','detalle_pagado_oficina','BancosClientes','tasaDolarHabitacion','tasaPesoHabitacion','tasaDolar','tasaPeso','tasaTransferenciaPunto','tasaMixto','tasaEfectivo','users','UserName'));
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
