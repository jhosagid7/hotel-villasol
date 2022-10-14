<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Tasa;
use App\User;
use App\Level;
use App\Venta;
use App\Precio;
use App\Credito;
use App\Horario;
use App\Persona;
use App\Servicio;
use Carbon\Carbon;
use App\Habitacione;
use App\Sessioncaja;
use App\Denominacion;
use App\Detalle_credito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RecepcionController extends Controller
{
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
        $title = 'Recepción';

        $levels = Level::orderBy('id','desc')->get();
        $horarios = Horario::get();
        $habitaciones = Habitacione::get();
        $tasaDolarHabitacion = Tasa::where('nombre','=','DolarHabitacion')->first();
        // return $tasaDolarHabitacion->tasa;
        $tasaPesoHabitacion = Tasa::where('nombre','=','PesoHabitacion')->first();
        $users = User::with('roles')->orderBy('id','Desc')->get();
// return $users->roles[0]->name;

// return $horarios;

// return $horarios;

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
// return $habitaciones[0]->cat->id;
        return view('servicios.recepcion.index', compact('title','levels','habitaciones','horarios', 'tasaDolarHabitacion', 'tasaPesoHabitacion', 'users'));
    }

    public function getPrecio(Request $request){
        // return $request;
        if ($request->ajax()) {
            $precio = Precio::where('cat_id', $request->cat_id)
                ->where('horario_id', $request->horario_id)
                ->get();


                // return $origenesArtArray;
                return response()->json($precio);
        }
    }
    public function getCliente(Request $request)
{
    $term = $request->term;
    // return $term;
    if ($request->ajax()) {
    $clientes = Persona::where('nombre', 'LIKE','%'.$request->term.'%')
    ->where('horario_id', $request->horario_id)
    ->get();

    // foreach ($queries as $query)
    // {
    //     $results[] = ['id' => $query->id, 'value' => $query->nombre]; //you can take custom values as you want
    // }
    // dd( $results);
return response()->json($clientes);
}
}


    public function proceso(Request $request)
    {
        // return $request;
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

        Sessioncaja::crearsession();
        $tasa = Tasa::find(1);
        $tasa->updated_at;
        $fechaActual = Carbon::now();
        // dd($tasa->updated_at->diffInHours($fechaActual));
        if ($tasa->tasa <= 0 || $tasa->updated_at->diffInHours($fechaActual) >= 6 ) {
            return redirect()
            ->route('tasa.index')
            ->with('status_danger', '¡Debes Actualizar el margen de gananacia para poder acceder!');
        }else{
            $cajaSessionid =  Sessioncaja::where('estado', 'Abierta')->orderBy('id', 'desc')->first();
            // dd($cajaSessionid);
            $Caja = Caja::where("estado","=",'Abierta')->where("sessioncaja_id","=", $cajaSessionid->id)->first();

            if ($Caja) {

                if($Caja->user_id == Auth::id()){
        // return $request;
        $title = 'PROCESAR HABITACIÓN';
        $denominacion_dolar = Denominacion::where('moneda', 'Dolar')->orderBy('id', 'desc')->get();
        $levels = Level::orderBy('id','desc')->get();$request->get('tipo_documento');
        $horario = Horario::where('id',$request->get('horario_id'))->first();
        // return $horario;
        $clientes = Persona::where('tipo_persona', '<>', 'Inactivo')->get();
        // $clientes[3]->creditos;
        // return $clientes;
        $precio = Precio::where('id',$request->get('precio_id'))->first();
        // return $horario;
        $habitacion = Habitacione::where('id',$request->get('habitacion_id'))->first();
        // return $habitacion;
        $tasaDolarHabitacion = Tasa::where('nombre','=','DolarHabitacion')->first();
        // return $tasaDolarHabitacion->tasa;
        $tasaPesoHabitacion = Tasa::where('nombre','=','PesoHabitacion')->first();
        $tasaDolar = DB::table('tasas')->where('nombre', '=', 'Dolar')->first();
        $tasaPeso = DB::table('tasas')->where('nombre', '=', 'Peso')->first();
        $tasaTransferenciaPunto = DB::table('tasas')->where('nombre', '=', 'Transferencia_Punto')->first();
        $tasaMixto = DB::table('tasas')->where('nombre', '=', 'Mixto')->first();
        $tasaEfectivo = DB::table('tasas')->where('nombre', '=', 'Efectivo')->first();
        $users = User::with('roles')->orderBy('id','Desc')->get();

        $articulos = DB::table('articulos as art')
                        ->select(DB::raw('CONCAT(art.codigo, " - ", art.nombre) AS articulo'), 'art.imagen', 'art.vender_al', 'art.nombre','art.id', 'precio_costo', 'porEspecial', 'isDolar', 'isPeso', 'isTransPunto', 'isMixto', 'isEfectivo', 'isKilo', 'stock', 'art.nombre')
                        ->where('art.estado', '=', 'Activo')
                        ->where('art.stock', '>', '0')
                        ->where('art.precio_costo', '>', '0')
                        ->get();



        $UserId = Auth::user()->id;
        $UserName = Auth::user()->name;
        $cajaSessionid =  Sessioncaja::where('estado', 'Abierta')->orderBy('id', 'desc')->first();
        $caja = Caja::where("estado","=",'Abierta')->where("sessioncaja_id","=", $cajaSessionid->id)->first();
        $servicioNum = Servicio::latest('id')->first();
        if (is_null($servicioNum)) {

            $num_servicio = Sessioncaja::numCodigo('S', $UserId, 1);

            // dd($serie_comprobante);
        }else{
            $num_servicio = Sessioncaja::numCodigo('CS', $UserId, $servicioNum->id+1);

        }

        $cajas = Caja::find($caja->id);
                    $cajas->user;
                    $cajas->ventas;
                    $cajas->pago_ventas;
                    $cajas->articulo_ventas;


                    // $cont = 0;
                    // while ($cont < count($cajas->pago_ventas)) {
                    //     $v1[] = $cajas->pago_ventas[$cont]->Divisa;
                    //     $cont = $cont+1;
                    // }

                    foreach ($cajas->pago_ventas as $pago ) {

                        if ($pago->Divisa == 'Dolar') {
                            $cajas->SumaTotalDolar = $cajas->SumaTotalDolar + $pago->MontoDivisa;
                        }elseif ($pago->Divisa == 'Peso') {
                            $cajas->SumaTotalPeso = $cajas->SumaTotalPeso + $pago->MontoDivisa;
                        }elseif ($pago->Divisa == 'Bolivar') {
                            $cajas->SumaTotalBolivar = $cajas->SumaTotalBolivar + $pago->MontoDivisa;
                        }elseif ($pago->Divisa == 'Punto') {
                            $cajas->SumaTotalPunto = $cajas->SumaTotalPunto + $pago->MontoDivisa;
                        }elseif ($pago->Divisa == 'Transferencia') {
                            $cajas->SumaTotalTransferencia = $cajas->SumaTotalTransferencia + $pago->MontoDivisa;
                        }

                    }

                    foreach ($cajas->ventas as $vent ) {
                        if ($vent->estado == 'Aceptada') {
                        $cajas->SumaTotalVentas = $cajas->SumaTotalVentas + $vent->total_venta;
                        $cajas->SumaTotalCantidadVentas = $cajas->SumaTotalCantidadVentas + 1;
                        }
                    }

                    foreach ($cajas->articulo_ventas as $art_vent ) {
                        $cajas->SumaArticulosVendidos = $cajas->SumaArticulosVendidos + $art_vent->cantidad;
                    }
// return $users->roles[0]->name;
// return $horario;

        // return redirect()->route('proceso', array('title' => $title,'levels' => $levels,'habitacion' => $habitacion,'horarios' => $horarios, 'tasaDolarHabitacion' => $tasaDolarHabitacion, 'tasaPesoHabitacion' => $tasaPesoHabitacion, 'users' => $users));

             return view('servicios.procesos.index', compact('cajas','articulos','num_servicio','UserId','UserName','caja','tasaDolar','tasaPeso','tasaTransferenciaPunto','tasaMixto','tasaEfectivo','denominacion_dolar','title','levels','habitacion','horario', 'tasaDolarHabitacion', 'tasaPesoHabitacion', 'users', 'clientes','precio'));
            }else{
                return redirect()
                ->route('caja.index')
                ->with('status_danger', '¡Error de acceso! Solo se permite un usuario por caja para realizar ventas y ya se encuentra una caja abierta por otro usuario... ');
            }
        }
        return redirect()
        ->route('caja.index')
        ->with('status_danger', '¡Debes crear una caja para poder acceder!');
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Recepcion  $resepcion
     * @return \Illuminate\Http\Response
     */
    // public function show(Recepcion $resepcion)
    // {
    //     //
    // }

    // public function registrarHabitacion(Recepcion $resepcion){
    //     return $request;
    // }


}
