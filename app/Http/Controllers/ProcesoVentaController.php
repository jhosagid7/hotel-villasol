<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Tasa;
use App\User;
use App\Venta;
use App\Precio;
use App\Credito;
use App\Persona;
use App\Articulo;
use App\Cortesia;
use App\Servicio;
use App\Excedente;
use Carbon\Carbon;
use App\Pago_Venta;
use App\Sessioncaja;
use App\Articulo_venta;
use App\Detalle_credito;
use App\Servicios_Ventas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProcesoVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'Pre venta';
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



        try{
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
            DB::beginTransaction();
            $myTime = Carbon::now('America/Caracas');
            // number_format($número, 2, '.', '');




            $total_venta = $request->get('total_venta');
            $serie_comprobante = $request->get('serie_comprobante');
            $operador = $request->get('operador');



            $servicio_id = $request->get('servicio_id');

            $tipo_pago = $request->get('tipo_pago');

            if($tipo_pago == null){
                $tipo_pago = 'No pagado';
            }else{
                $tipo_pago = $request->get('tipo_pago');
            }
            $modo_pago = $request->get('modo_pago');
            $monto_dejado = $request->get('monto_dejado');
            $monto_dejado = number_format($monto_dejado,2,'.',',');
            // return $monto_dejado;
            $total_costo = number_format($request->get('total_costo'),2,'.',',');;
            $status = '';
            $estado_pago = '';





            if($modo_pago == 'cortesia'){
                $status = 'Exonerado';
                $estado = 'Aceptada';
                $estado_pago = 'Exonerado';
                $tipo_pago = 'Exonerado';


            }

            if($modo_pago == 'credito'){

                $status = 'Falta pagar';
                $estado = 'Aceptada';
                $estado_pago = 'Falta pagar';
                $tipo_pago = 'No pagado';
            }



            if($modo_pago == 'contado'){

                if($monto_dejado == $total_costo){
                    $status = 'Pagado';
                    $estado = 'Aceptada';
                    $estado_pago = 'Pagado';
                }

                if($monto_dejado > $total_costo){
                    $status = 'Pagado';
                    $estado = 'Aceptada';
                    $estado_pago = 'Pagado';
                    // dd($status);

                    $exced = $monto_dejado - $total_costo;

                    $ifCliente = Excedente::where('persona_id',$request->get('cliente_id'))->first();
                    // return $ifCliente;

                    if($ifCliente){
                        // return 'si';

                        $upExcedente = Excedente::findOrFail($ifCliente->id);
                        $upExcedente->excedente = $upExcedente->excedente + $exced;
                        $upExcedente->update();
                    }else{
                        // return 'no';
                        $excedente = new Excedente;
                        $excedente->nombre_cliente = $request->get('nombre');
                        $excedente->cedula_cliente = $request->get('num_documento');
                        $excedente->direccion_cliente = $request->get('direccion');
                        $excedente->telefono_cliente = $request->get('telefono');
                        $excedente->excedente = $exced;
                        $excedente->persona_id = $request->get('cliente_id');
                        $excedente->save();
                    }
                }

                if($monto_dejado < $total_costo){
                    $status = 'Falta pagar';
                    $estado = 'Pendiente';
                    $estado_pago = 'Falta pagar';
                }
            }

            $articulo_id = $request->get('idarticulo');
            $cantidad = $request->get('cantidad');
            $precio_costo_unidad = $request->get('precio_costo_unidad');

            //creamos un contador
            $cont = 0;
            $precio_costo_final = 0;
            //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
            while ($cont < count($articulo_id)) {
                $precio_costo_final += $cantidad[$cont] * $precio_costo_unidad[$cont];

                $cont = $cont+1;
            }

            // return $precio_costo_final;
            $utilidad = $request->get('total_venta') - $precio_costo_final;



            $margen_gananacia = $utilidad / $request->get('total_venta');
            // dd($status);

            $venta = new Venta;
            $venta->tipo_comprobante = $request->get('tipo_comprobante');
            $venta->serie_comprobante = $request->get('serie_comprobante');
            $venta->num_comprobante = $request->get('num_comprobante');
            $venta->fecha_hora = $myTime->toDateTimeString();
            $venta->modo_pago = $modo_pago;
            $venta->tipo_pago = $tipo_pago;
            $venta->status = $status;
            $venta->tasaDolar = $request->get('tasaDolars');
            $venta->porDolar = $request->get('jmarjen_ganancia_dolar');
            $venta->tasaPeso = $request->get('tasaPesos');
            $venta->porPeso = $request->get('jmarjen_ganancia_peso');
            $venta->tasaTransPunto = $request->get('tasaTransPunto');
            $venta->porTransPunto = $request->get('jmarjen_ganancia_trans_punto');
            $venta->tasaMixto = $request->get('tasaMixto');
            $venta->porMixto = $request->get('jmarjen_ganancia_mixto');
            $venta->tasaEfectivo = $request->get('tasaEfectivo');
            $venta->porEfectivo = $request->get('jmarjen_ganancia_Efectivo');
            $venta->num_Punto = $request->get('num_Punto');
            $venta->num_Trans = $request->get('num_Trans');
            $venta->precio_costo = $precio_costo_final;
            $venta->margen_ganancia = $margen_gananacia;
            $venta->total_venta = $request->get('total_venta');
            $venta->ganancia_neta = $utilidad;
            $venta->estado = 'Aceptada';
            $venta->persona_id = $request->get('idcliente');
            $venta->caja_id = $request->get('caja_id');
            $venta->save();



if($modo_pago == 'credito'){



    $fecha_vencimiento = Carbon::now();
    $fecha_vencimiento->addDays($request->get('limite_fecha'));
    $fecha_vencimiento->toDateString();


    $deuda_actual = Credito::where('persona_id',$request->get('cliente_id'))->first();

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
            $detalleCredito->numero_factura = $request->get('num_comprobante');
            $detalleCredito->tipo_operacion = 'Consumo';
            $detalleCredito->operacion_id = $venta->id;
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
            $detalleCredito->numero_factura = $request->get('num_comprobante');
            $detalleCredito->tipo_operacion = 'Consumo';
            $detalleCredito->operacion_id = $venta->id;
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
        $credito->nombre_cliente = $request->get('nombre_cliente');
        $credito->cedula_cliente = $request->get('cedula_cliente');
        $credito->direccion_cliente = $request->get('direccion_cliente');
        $credito->telefono_cliente = $request->get('telefono_cliente');
        $credito->total_factura = 1;
        $credito->total_deuda = $total_costo;
        $credito->fecha_limite_pago = $fecha_vencimiento_pago;
        $credito->estado_credito = 'Activo';
        $credito->persona_id = $request->get('cliente_id');
        $credito->user_id = Auth::user()->id;
        $credito->save();


        $detalleCredito = new Detalle_credito;
        $detalleCredito->numero_factura = $request->get('num_comprobante');
        $detalleCredito->tipo_operacion = 'Consumo';
        $detalleCredito->operacion_id = $venta->id;
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
                    $cortesia->servicio_id = $servicio_id;
                    $cortesia->save();
            }

            //cargamos los datos del detalle del venta en la tabla articulo_venta en unas variables que reciven
            //un array



            if ($tipo_pago == 'Dolar') {
                $precio_venta_unidad = $request->get('precio_venta');
            }elseif ($tipo_pago == 'Peso') {
                $precio_venta_unidad = $request->get('precio_venta_p');
            }elseif ($tipo_pago == 'Trans/Punto') {
                $precio_venta_unidad = $request->get('precio_venta_tp');
            }elseif ($tipo_pago == 'Mixto') {
                $precio_venta_unidad = $request->get('precio_venta_m');
            }elseif ($tipo_pago == 'Efectivo') {
                $precio_venta_unidad = $request->get('precio_venta_e');
            }else{
                $precio_venta_unidad = $request->get('precio_venta');
            }


            $cantidad = $request->get('cantidad');
            $precio_costo_unidad = $request->get('precio_costo_unidad');
            $porEspecial = $request->get('porEspecial');

            $descuento = $request->get('descuento');
            $articulo_id = $request->get('idarticulo');

            //creamos un contador
            $cont = 0;

            //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
            while ($cont < count($articulo_id)) {

                $isDivisa = Articulo::find($articulo_id[$cont]);

                if($isDivisa->isDolar){
                    $activoD = $isDivisa->isDolar;
                }else{
                    $activoD = null;
                }
                if($isDivisa->isPeso){
                    $activoP = $isDivisa->isPeso;
                }else{
                    $activoP = null;
                }
                if($isDivisa->isTransPunto){
                    $activoT = $isDivisa->isTransPunto;
                }else{
                    $activoT = null;
                }
                if($isDivisa->isMixto){
                    $activoM = $isDivisa->isMixto;
                }else{
                    $activoM = null;
                }
                if($isDivisa->isEfectivo){
                    $activoE = $isDivisa->isEfectivo;
                }else{
                    $activoE = null;
                }

                $Servicios_venta = new Servicios_ventas();
                $Servicios_venta->cantidad = $cantidad[$cont];
                $Servicios_venta->precio_costo_unidad = $precio_costo_unidad[$cont];
                $Servicios_venta->precio_venta_unidad = $precio_venta_unidad[$cont];
                $Servicios_venta->porEspecial = $porEspecial[$cont];
                $Servicios_venta->isDolar = $activoD;
                $Servicios_venta->isPeso = $activoP;
                $Servicios_venta->isTransPunto = $activoT;
                $Servicios_venta->isMixto = $activoM;
                $Servicios_venta->isEfectivo = $activoE;
                $Servicios_venta->descuento = $descuento[$cont];
                $Servicios_venta->estado_pago = $estado_pago;
                $Servicios_venta->tipo_pago = $tipo_pago;
                $Servicios_venta->articulo_id = $articulo_id[$cont];
                $Servicios_venta->servicio_id =  $servicio_id;//este idingreso se autogenera cuando se crea el objeto en la parte superior (*)
                $Servicios_venta->save();

                // dd($estado_pago);

                $Articulo_venta = new Articulo_venta();
                $Articulo_venta->cantidad = $cantidad[$cont];
                $Articulo_venta->precio_costo_unidad = $precio_costo_unidad[$cont];
                $Articulo_venta->precio_venta_unidad = $precio_venta_unidad[$cont];
                $Articulo_venta->porEspecial = $porEspecial[$cont];
                $Articulo_venta->isDolar = $activoD;
                $Articulo_venta->isPeso = $activoP;
                $Articulo_venta->isTransPunto = $activoT;
                $Articulo_venta->isMixto = $activoM;
                $Articulo_venta->isEfectivo = $activoE;
                $Articulo_venta->descuento = $descuento[$cont];
                $Articulo_venta->estado_pago = $estado_pago;
                $Articulo_venta->articulo_id = $articulo_id[$cont];
                $Articulo_venta->venta_id =  $venta->id;//este idingreso se autogenera cuando se crea el objeto en la parte superior (*)
                $Articulo_venta->save();


                $cont = $cont+1;
            }

            if($modo_pago == 'contado'){
            $MontoDivisaR = $request->get('MontoDivisa');
            $divisaR = $request->get('divisa');
            $TasaTikeR = $request->get('TasaTike');
            $MontoDolarR = $request->get('MontoDolar');
            $VeltosR = $request->get('Veltos');

            $MontoDivisaR = array_filter($MontoDivisaR);


            foreach($MontoDivisaR as $key => $val) {


                $divisa[]=$divisaR[$key];
                $MontoDivisa[]=$MontoDivisaR[$key];
                $TasaTiket[]=$TasaTikeR[$key];
                $MontoDolar[]=$MontoDolarR[$key];
                $Vueltos[]=$VeltosR[$key];

            }




            // dd($divisa, $MontoDivisa,$TasaTike,$MontoDolar,$Veltos);
            //creamos un contador
            $cont = 0;

            //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
            while ($cont < count($MontoDolar)) {


                $Pago_Venta = new Pago_Venta();
                $Pago_Venta->Divisa = $divisa[$cont];
                $Pago_Venta->MontoDivisa = $MontoDivisa[$cont];
                $Pago_Venta->TasaTiket = $TasaTiket[$cont];
                $Pago_Venta->MontoDolar = $MontoDolar[$cont];
                $Pago_Venta->Vueltos = $Vueltos[$cont];
                $Pago_Venta->venta_id = $venta->id;
                $Pago_Venta->save();

                $cont = $cont+1;
            }
        }
            DB::commit();

        }catch(\Exception $e)
        {

            DB::rollback();
            dd($e);
        }
        // return count($requestPrint->idarticulo);
        $printer = new PrinterController;

        $printer->ticketConsumo('Consumo', $articulo_id, $serie_comprobante, $precio_venta_unidad, $cantidad, $modo_pago, $tipo_pago, $total_venta, $operador);

        return Redirect::to('checkout')->with('success', 'La venta fué registrada exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $habitacion = Servicio::where('id','=',"$id")->get();
        $sevicioVenta = Servicios_ventas::where('servicio_id', $habitacion[0]->id)->get();
        // return $sevicioVenta;
        $service = Servicio::where('id', $habitacion[0]->id)->first();
        $cliente = Persona::where('id',$habitacion[0]->persona_id)->first();
        $credito = Credito::where('persona_id',$habitacion[0]->persona_id)->first();
        // return $cliente;
////////////////////////////////////////////////////////////////////////////////////////////////////////
        $User = Auth::user();

        Sessioncaja::crearsession();
        $tasa = Tasa::find(1);
        // return $tasa;
        $tasa->updated_at;
        $fechaActual = Carbon::now();

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
                    $title='Nueva venta';
                    $personas = DB::table('personas')->where('tipo_persona', '=', 'Cliente')->get();
                    $tasaDolar = DB::table('tasas')->where('nombre', '=', 'Dolar')->first();
                    $tasaPeso = DB::table('tasas')->where('nombre', '=', 'Peso')->first();
                    $tasaTransferenciaPunto = DB::table('tasas')->where('nombre', '=', 'Transferencia_Punto')->first();
                    $tasaMixto = DB::table('tasas')->where('nombre', '=', 'Mixto')->first();
                    $tasaEfectivo = DB::table('tasas')->where('nombre', '=', 'Efectivo')->first();
                    $articulos = DB::table('articulos as art')
                        ->select(DB::raw('CONCAT(art.codigo, " - ", art.nombre) AS articulo'), 'art.imagen', 'art.vender_al', 'art.nombre','art.id', 'precio_costo', 'porEspecial', 'isDolar', 'isPeso', 'isTransPunto', 'isMixto', 'isEfectivo', 'isKilo', 'stock', 'art.nombre')
                        ->where('art.estado', '=', 'Activo')
                        ->where('art.stock', '>', '0')
                        ->where('art.precio_costo', '>', '0')
                        ->get();


                    $UserId = Auth::id();
                    $caja = Caja::where("estado","=",'Abierta')->where("sessioncaja_id","=", $cajaSessionid->id)->first();
                    $ventaNum = Venta::latest('id')->first();

                    if (is_null($ventaNum)) {

                        $num_comprobante = Sessioncaja::numCodigo('C', $UserId, 1);
                        $serie_comprobante = Sessioncaja::numCodigo('N', $UserId, 1);

                    }else{
                        $num_comprobante = Sessioncaja::numCodigo('C', $UserId, $ventaNum->id+1);
                        $serie_comprobante = Sessioncaja::numCodigo('N', $UserId, $ventaNum->id+1);
                    }



                    $ventas = DB::table('ventas as v')
                        ->join('personas as p', 'v.persona_id', '=', 'p.id')
                        ->join('articulo_ventas as av', 'v.id', '=', 'av.venta_id')
                        ->join('cajas as c', 'v.caja_id', '=', 'c.sessioncaja_id')
                        ->join('users as u', 'u.id', '=', 'c.user_id')
                        ->select('u.name','c.user_id','v.id', 'v.fecha_hora','v.modo_pago','v.caja_id', 'c.sessioncaja_id', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.total_venta', 'v.estado')
                        ->where('c.user_id', '=', Auth::user()->id)
                        ->where('c.estado', '=', 'Abierta')
                        ->orderBy('v.id', 'desc')
                        ->groupBy('u.name','c.user_id','v.id', 'v.fecha_hora','v.modo_pago', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.total_venta', 'v.estado')
                        ->get();

                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    $cajas = Caja::find($caja->id);
                    $cajas->user;
                    $cajas->ventas;
                    $cajas->pago_ventas;
                    $cajas->articulo_ventas;
                    $cajas->servicios;
                    // $cajas->creditos;
                    $cajas->cortesias;
                    $cajas->pago_servicios;


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

                    foreach ($cajas->ventas as $vent ) {
                        if ($vent->estado == 'Aceptada' && $vent->status == 'Pagado') {
                        $cajas->SumaTotalVentas = $cajas->SumaTotalVentas + $vent->total_venta;

                        }

                        if ($vent->estado == 'Aceptada') {

                            $cajas->SumaTotalCantidadVentas = $cajas->SumaTotalCantidadVentas + 1;
                            }
                    }

                    foreach ($cajas->articulo_ventas as $art_vent ) {
                        $cajas->SumaArticulosVendidos = $cajas->SumaArticulosVendidos + $art_vent->cantidad;
                        if($art_vent->estado_pago == 'Falta pagar'){
                            // $cajas->SumaTotalVentas = $cajas->SumaTotalVentas - ($art_vent->cantidad * $art_vent->precio_venta_unidad);
                            $cajas->SumaTotalVentasPorCobrar = $cajas->SumaTotalVentasPorCobrar + ($art_vent->cantidad * $art_vent->precio_venta_unidad);
                        }

                    }

                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    foreach ($cajas->pago_servicios as $pagoS ) {

                        if ($pagoS->Divisa == 'Dolar') {
                            if($pagoS->Vueltos > 0){
                                $cajas->SumaTotalDolarServ = $cajas->SumaTotalDolarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos);
                            }else{
                            $cajas->SumaTotalDolarServ = $cajas->SumaTotalDolarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1);
                            }
                        }elseif ($pagoS->Divisa == 'Peso') {
                            $cajas->SumaTotalPesoServ = $cajas->SumaTotalPesoServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1);
                        }elseif ($pagoS->Divisa == 'Bolivar') {
                            $cajas->SumaTotalBolivarServ = $cajas->SumaTotalBolivarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1);
                        }elseif ($pagoS->Divisa == 'Punto') {
                            $cajas->SumaTotalPuntoServ = $cajas->SumaTotalPuntoServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1);
                        }elseif ($pagoS->Divisa == 'Transferencia') {
                            $cajas->SumaTotalTransferenciaServ = $cajas->SumaTotalTransferenciaServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1);
                        }

                    }

                    foreach ($cajas->servicios as $serv ) {
                        if ($serv->estado == 'Aceptada') {
                            if($serv->status == 'Pagado'){
                                $cajas->SumaTotalServicios = $cajas->SumaTotalServicios + $serv->total_venta;
                                $cajas->SumaTotalCantidadServicios = $cajas->SumaTotalCantidadServicios + 1;
                            }

                            if($serv->status == 'Falta pagar'){
                                $cajas->SumaTotalServiciosPorPagar = $cajas->SumaTotalServiciosPorPagar + $serv->total_venta;
                                $cajas->SumaTotalCantidadServiciosPorPagar = $cajas->SumaTotalCantidadServiciosPorPagar + 1;
                            }

                            if($serv->status == 'Exonerado'){
                                $cajas->SumaTotalServiciosCortesia = $cajas->SumaTotalServiciosCortesia + $serv->total_venta;
                                $cajas->SumaTotalCantidadServiciosCortesia = $cajas->SumaTotalCantidadServiciosCortesia + 1;
                            }

                        }
                    }


                    $tasaDolarHabitacion = Tasa::where('nombre','=','DolarHabitacion')->first();
                    $tasaPesoHabitacion = Tasa::where('nombre','=','PesoHabitacion')->first();
                    $UserName = Auth::user()->name;
                    $UserId = Auth::user()->id;


                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    //  return $sevicioVenta;
                    //dd($ventas);

                    return view('preventa.create', compact('credito','cliente','UserName','UserId','tasaDolarHabitacion','tasaPesoHabitacion','cajas','habitacion','num_comprobante','serie_comprobante','caja', 'ventas','title','personas','tasaDolar','tasaPeso','tasaTransferenciaPunto','tasaMixto','tasaEfectivo','articulos'));
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
