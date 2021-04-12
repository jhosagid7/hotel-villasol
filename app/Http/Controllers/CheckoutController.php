<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Tasa;
use App\User;
use App\Level;
use App\Precio;
use App\Horario;
use App\Persona;
use App\Servicio;
use App\Excedente;
use Carbon\Carbon;
use App\Habitacione;
use App\Sessioncaja;
use App\Denominacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $title = 'Check Out';

        $levels = Level::orderBy('id','desc')->get();
        $horarios = Horario::get();
        $habitaciones = Habitacione::where('status','=','Ocupada')->get();
        $tasaDolarHabitacion = Tasa::where('nombre','=','DolarHabitacion')->first();
        // return $tasaDolarHabitacion->tasa;
        $tasaPesoHabitacion = Tasa::where('nombre','=','PesoHabitacion')->first();
        $users = User::with('roles')->orderBy('id','Desc')->get();
        $servicios = Servicio::where('status_servicio', 'Iniciado')->get();


// return $servicios;



        return view('checkout.index', compact('servicios','title','levels','habitaciones','horarios', 'tasaDolarHabitacion', 'tasaPesoHabitacion', 'users'));
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
        // return $id;
        $title = 'Salida';
        $servicio = Servicio::where('id', $id)->where('status_servicio', 'Iniciado')->first();
        // return $servicio;
        $cliente = Persona::where('id',$servicio->persona_id)->first();
        $servicio;
        $servicio->servicios_ventas;
        // $servicio->horario;
        // $servicio->servicios_ventas[0]->articulo;
// return $servicio;
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
        $title = 'PROCESAR SALIDA HABITACIÓN';

        $horarios_id = Horario::where('nombre',$servicio->horario)->first();
        // return $horarios_id;
        $habitacionese = Habitacione::where('status','Disponible')->get();
        // $habitacionese =[];

        //creamos un contador
        $cont = 0;

        //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
        while ($cont < count($habitacionese)) {
            $precio_habitacion_cambio[$cont] = Precio::where('cat_id',$habitacionese[$cont]->cat->id)->where('horario_id',$horarios_id->id)->first();
            // return $precio_habitacion_cambio->precio;
            $habitacionese[$cont]->habitacion_id = $habitacionese[$cont]->id;
            $habitacionese[$cont]->nombre = $habitacionese[$cont]->nombre;
            $habitacionese[$cont]->categoria_id = $habitacionese[$cont]->cat->id;
            $habitacionese[$cont]->categoria = $habitacionese[$cont]->cat->nombre;
            $habitacionese[$cont]->categoria_desc = $habitacionese[$cont]->cat->descripcion;
            $habitacionese[$cont]->precio_id = $precio_habitacion_cambio[$cont]->id;
            $habitacionese[$cont]->precio = $precio_habitacion_cambio[$cont]->precio;

            $cont = $cont+1;
        }
        // foreach ($habitacionese as $habitaciones) {

        //     $precio_habitacion_cambio = Precio::where('cat_id',$habitaciones->cat->id)->where('horario_id',$horarios_id->id)->first();
        //     // return $precio_habitacion_cambio->precio;

        //     $habitacionese[]->habitacion_id = $habitaciones->id;
        //     $habitacionese[]->nombre = $habitaciones->nombre;
        //     $habitacionese[]->categoria = $habitaciones->cat->nombre;
        //     $habitacionese[]->precio = $precio_habitacion_cambio->precio;


        // }
        // return $habitacionese;

        $users = User::with('roles')->orderBy('id','Desc')->get();
        // $denominacion_dolar = Denominacion::where('moneda', 'Dolar')->orderBy('id', 'desc')->get();
        // $levels = Level::orderBy('id','desc')->get();
        // $horario = Horario::where('id',$request->get('horario_id'))->first();
        // return $horario;
        // $clientes = Persona::get();
        // $precio = Precio::where('id',$request->get('precio_id'))->first();
        // return $horario;
        // $habitacion = Habitacione::where('id',$request->get('habitacion_id'))->first();
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

        // $articulos = DB::table('articulos as art')
        //                 ->select(DB::raw('CONCAT(art.codigo, " - ", art.nombre) AS articulo'), 'art.imagen', 'art.vender_al', 'art.nombre','art.id', 'precio_costo', 'porEspecial', 'isDolar', 'isPeso', 'isTransPunto', 'isMixto', 'isEfectivo', 'isKilo', 'stock', 'art.nombre')
        //                 ->where('art.estado', '=', 'Activo')
        //                 ->where('art.stock', '>', '0')
        //                 ->where('art.precio_costo', '>', '0')
        //                 ->get();



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
                    $cajas->excedente_actual;


                    $cont = 0;
                    while ($cont < count($cajas->pago_ventas)) {
                        $v1[] = $cajas->pago_ventas[$cont]->Divisa;
                        $cont = $cont+1;
                    }

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
                    // return $servicio->habitacion_id;

                    // TODO Revisamos si el usuario tiene vueltos pendientes


                    foreach ($cajas->excedente_actual as $excedenteActual) {
                        if ($excedenteActual->Estado == 'Pendiente' && $excedenteActual->servicio_id == $id) {
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
                            $cajas->TotalSumaVueltosPendientesClienteDivisa = $cajas->TotalSumaVueltosPendientesClienteDivisa  + $excedenteActual->MontoDivisa;
                            $cajas->TotalSumaVueltosPendientesClienteDolar = $cajas->TotalSumaVueltosPendientesClienteDolar  + $excedenteActual->MontoDolar;
                        }
                    }

                    $excedenteCliente = Excedente::where('persona_id',$cliente->id)->first();

                    // return $excedenteCliente;

                    if($excedenteCliente){
                        $cajas->excedenteCLiente = $excedenteCliente->excedente;
                    }

                // return redirect()->route('proceso', array('title' => $title,'levels' => $levels,'habitacion' => $habitacion,'horarios' => $horarios, 'tasaDolarHabitacion' => $tasaDolarHabitacion, 'tasaPesoHabitacion' => $tasaPesoHabitacion, 'users' => $users));

             return view('checkout.show', compact('cajas','articulos','servicio','serie_comprobante','UserId','UserName','caja','ventaNum','tasaDolar','tasaPeso','tasaTransferenciaPunto','tasaMixto','tasaEfectivo','denominacion_dolar','title','levels','habitacionese','horarios', 'tasaDolarHabitacion', 'tasaPesoHabitacion', 'users', 'cliente','precio','num_servicio'));
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
return $request;

        // $id = 2;
        $servicio_id = Servicio::where('habitacion_id',$id)->where('status_servicio', 'Iniciado')->first();
    //    return $servicio_id;
       if($servicio_id){
        // return 'todo bien';
        $servicio = Servicio::findOrFail($servicio_id->id);
        $servicio->status_servicio = 'Finalizado';
        $servicio->update();

        $habitacion = Habitacione::findOrFail($id);
        $habitacion->status = 'Limpieza';
        $habitacion->update();

        return redirect()
        ->route('checkout.index')
        ->with('status_success', 'La habitacion fue cerrada  exitosamente...');
       }else{
        // return 'no hay registros';
        return redirect()
        ->route('checkout.index')
        ->with('status_success', 'La habitacion fue cerrada previamente de manera exitosa...');
       }


    }

    // public function getHabitacion(Request $request){
    //     // return $request;
    //     if ($request->ajax()) {
    //         $origenArticulos = Habitacione::g('vender_al', $request->accion)
    //             ->where('stock', '>', '0')
    //             ->get();

    //             // return $origenesArtArray;
    //             return response()->json($origenArticulos);
    //     }
    // }


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
