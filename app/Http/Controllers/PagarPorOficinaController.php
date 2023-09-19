<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Tasa;
use App\User;
use App\Banco;
use App\Credito;
use App\Persona;
use Carbon\Carbon;
use App\Sessioncaja;
use App\BancosEmpresa;
use App\Detalle_credito;
use App\DetallePagoOficina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Traits\CreditCostumerTrait;

class PagarPorOficinaController extends Controller
{

    use CreditCostumerTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Historial Pagos por oficina';
        $pagarporoficinas = DetallePagoOficina::with('cliente', 'operador')->get();

        // Todo este codigo maneja las fechas de los creditos vencidos
        $this->checkDateCredit();

        return view('pagos.pendientes.index', compact('title', 'pagarporoficinas'));
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


        // $historialExcedentes = HistorialExcedente::where('detalle_pago_oficina_id',$id)->where('tipo_registro','Pago_por_oficina')->where('status','Pagado')->where('tipo_operacion','Egreso')->get();

        $historialExcedentes = DetallePagoOficina::where('id', $id)->get();

        // return $historialExcedentes;


        // return $historialExcedentes;
        $tasaDolarHabitacion = Tasa::where('nombre', '=', 'DolarHabitacion')->first();
        // return $tasaDolarHabitacion->tasa;
        $tasaPesoHabitacion = Tasa::where('nombre', '=', 'PesoHabitacion')->first();
        $tasaDolar = DB::table('tasas')->where('nombre', '=', 'Dolar')->first();
        $tasaPeso = DB::table('tasas')->where('nombre', '=', 'Peso')->first();
        $tasaTransferenciaPunto = DB::table('tasas')->where('nombre', '=', 'Transferencia_Punto')->first();
        $tasaMixto = DB::table('tasas')->where('nombre', '=', 'Mixto')->first();
        $tasaEfectivo = DB::table('tasas')->where('nombre', '=', 'Efectivo')->first();
        $users = User::with('roles')->orderBy('id', 'Desc')->get();
        $UserName = Auth::user()->name;
        $cajaSessionid =  Sessioncaja::where('estado', 'Abierta')->orderBy('id', 'desc')->first();
        $Cajas = Caja::where("estado", "=", 'Abierta')->where("sessioncaja_id", "=", $cajaSessionid->id)->first();
        $caja = Caja::find($Cajas->id);
        // return $caja->sucursal->id;{{$pagarporoficina->nombre_banco_cliente ?? ''}}

        // $bancosCLientes = BancosCliente::where('pertenece','Cliente')->where('persona_id',$historialExcedentes->persona_id)->get();
        // $pagarporoficina = Excedente::where('persona_id',$historialExcedentes->persona_id)->where('tipo','Pagar_por_oficina')->first();
        $bancosEmpresas = BancosEmpresa::where('pertenece', 'Empresa')->where('sucursal_id', $caja->sucursal->id)->get();
        $detalle_pagado_oficina = DetallePagoOficina::findOrFail($id);
        $cliente = Persona::findOrFail($detalle_pagado_oficina->persona_id);
        // return $detalle_pagado_oficina->telefono_pago_movil_cliente;
        // $excedente = Excedente::
        // return $cliente;
        $bancos = Banco::get();

        // return $detalle_creditos;
        return view('pagos.pendientes.show', compact('bancosEmpresas', 'cliente', 'bancos', 'historialExcedentes', 'caja', 'title', 'detalle_pagado_oficina', 'tasaDolarHabitacion', 'tasaPesoHabitacion', 'tasaDolar', 'tasaPeso', 'tasaTransferenciaPunto', 'tasaMixto', 'tasaEfectivo', 'users', 'UserName'));
    }
}
