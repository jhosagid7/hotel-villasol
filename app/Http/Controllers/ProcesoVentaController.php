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
use App\Reintegro;
use Carbon\Carbon;
use App\Pago_Venta;
use App\Pago_Vuelto;
use App\Sessioncaja;
use App\Pago_Credito;
use App\PreExcedente;
use App\Articulo_venta;
use App\Detalle_credito;
use App\Servicios_Ventas;
use App\DetallePagoOficina;
use App\HistorialExcedente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Excedentes_Recibidos_Caja_Actual;

use App\Traits\CreditCostumerTrait;
use App\Traits\ChangeInBoxTrait;
use App\Traits\CashTrait;
use App\Traits\ChangeSavedTrait;
use App\Traits\SaleTrait;

class ProcesoVentaController extends Controller
{

    use CreditCostumerTrait;
    use ChangeInBoxTrait;
    use CashTrait;
    use ChangeSavedTrait;
    use SaleTrait;


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
        try {

            // Todo este codigo maneja las fechas de los creditos vencidos
            $this->checkDateCredit();

            DB::beginTransaction();

            $total_venta = $request->get('total_venta');
            $pagoConExcedente = $request->get('VueltospagoConExcedente');
            $cliente_id = $request->get('cliente_id');
            $servicio_id = $request->get('servicio_id');
            $serie_comprobante = $request->get('serie_comprobante');
            $pnombre_cliente = $request->get('nombre_cliente');
            $tasa_dolar = $request->get('tasaDolar');
            $tasa_peso = $request->get('tasaPeso');
            $tasa_bolivar = $request->get('tasaEfectivo');
            $tasa_trans = $request->get('tasaTransPunto');
            $monto_dejado = $request->get('monto_dejado');
            $myTime = Carbon::now('America/Caracas');
            $tipo_pago = $request->get('tipo_pago');
            $total_costo = $request->get('total_costo');
            $status = '';
            $operador = $request->get('operador');
            $modo_pago = $request->get('modo_pago');




            $this->setTotalAmount($total_venta);



            $nuevo_excedente = 0;

            $monto_reintegro = 0;
            $pagoConVueltosCaja = 0;

            $isVueltos = $request->get('isVueltos');

            $VueltosdispExcedente = $request->get('VueltosdispExcedente');



            if ($tipo_pago == null) {
                $tipo_pago = 'No pagado';
            }
            // $monto_dejado = $monto_dejado;
            // return $cliente_id;
            $estado_pago = '';

            $is_vueltos_caja = PreExcedente::where('cliente_id', $cliente_id)->first();



            if ($pagoConExcedente > 0) {
                $modo_pago = "Contado-Excedente";
            } else {
                $modo_pago = $request->get('modo_pago');
            }


            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // TODO metodo para pagar con vueltos pendientes
            // TODO Method to pay with pending returns



            if ($modo_pago == 'cambio') {
                $status = 'Exonerado';
                $estado = 'Aceptada';
                $tipo_pago = 'Exonerado';
            }

            if ($modo_pago == 'cortesia') {

                $status = 'Exonerado';
                $estado = 'Aceptada';
                $estado_pago = 'Exonerado';
                $tipo_pago = 'Exonerado';
            }

            if ($modo_pago == 'credito') {
                $status = 'Falta pagar';
                $estado_pago = 'Falta pagar';
                $tipo_pago = 'No pagado';
            }





            if ($modo_pago == 'contado') {

                if ($monto_dejado == $total_costo) {
                    $status = 'Pagado';
                    $estado_pago = 'Pagado';
                }

                if ($monto_dejado > $total_costo) {
                    $status = 'Pagado';
                    $estado_pago = 'Pagado';
                    $nuevo_excedente = $monto_dejado - $total_costo;
                }


                if ($monto_dejado < $total_costo) {
                    $status = 'Falta pagar';
                    $estado_pago = 'Pendiente';
                }
            }

            if ($modo_pago == 'Contado-Excedente') {
                $status = 'Pagado';
                $estado_pago = 'Pagado';
            }

            if ($modo_pago == 'Excedente') {
                $status = 'Pagado';
                $estado_pago = 'Pagado';
            }


            // return $modo_pago;



            /**************************************************************************************************************** */

            $articulo_id = $request->get('idarticulo');
            $cantidad = $request->get('cantidad');
            $precio_costo_unidad = $request->get('precio_costo_unidad');

            //creamos un contador
            $cont = 0;
            $precio_costo_final = 0;
            //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
            while ($cont < count($articulo_id)) {
                $precio_costo_final += $cantidad[$cont] * $precio_costo_unidad[$cont];

                $cont = $cont + 1;
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
            $venta->servicio_id = $request->get('servicio_id');
            $venta->caja_id = $request->get('caja_id');
            $venta->save();

            $sale_id = $venta->id;



            if ($modo_pago == 'credito') {



                $fecha_vencimiento = Carbon::now();
                $fecha_vencimiento->addDays($request->get('limite_fecha'));
                $fecha_vencimiento->toDateString();


                $deuda_actual = Credito::where('persona_id', $request->get('cliente_id'))->first();

                if ($deuda_actual) {




                    if ($deuda_actual->total_deuda == 0) {
                        // return 'La deuda es menor a 0 '.$deuda_actual->total_deuda.'';

                        $fecha_limite_pago = Detalle_credito::where('persona_id', $request->get('cliente_id'))->where('estado_pago', 'Pendiente')->first();
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
                        $detalleCredito->tipo_pago = 'Credito';
                        $detalleCredito->fecha_emision = $myTime->toDateString();
                        $detalleCredito->fecha_vencimiento = $fecha_vencimiento;
                        $detalleCredito->fecha_pago = null;
                        $detalleCredito->persona_id = $request->get('cliente_id');
                        $detalleCredito->credito_id = $upCredito->id;
                        $detalleCredito->caja_id = $request->get('caja_id');
                        $detalleCredito->save();
                    } else {
                        // return 'La deuda es mayor a 0 '.$deuda_actual->total_deuda.'';

                        $fecha_limite_pago = Detalle_credito::where('persona_id', $request->get('cliente_id'))->where('estado_pago', 'Pendiente')->first();
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
                        $detalleCredito->tipo_pago = 'Credito';
                        $detalleCredito->fecha_emision = $myTime->toDateString();
                        $detalleCredito->fecha_vencimiento = $fecha_vencimiento;
                        $detalleCredito->fecha_pago = null;
                        $detalleCredito->persona_id = $request->get('cliente_id');
                        $detalleCredito->credito_id = $upCredito->id;
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
                    $detalleCredito->credito_id = $credito->id;
                    $detalleCredito->caja_id = $request->get('caja_id');
                    $detalleCredito->save();
                }
            }


            if ($modo_pago == 'cortesia') {

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
            } elseif ($tipo_pago == 'Peso') {
                $precio_venta_unidad = $request->get('precio_venta_p');
            } elseif ($tipo_pago == 'Trans/Punto') {
                $precio_venta_unidad = $request->get('precio_venta_tp');
            } elseif ($tipo_pago == 'Mixto') {
                $precio_venta_unidad = $request->get('precio_venta_m');
            } elseif ($tipo_pago == 'Efectivo') {
                $precio_venta_unidad = $request->get('precio_venta_e');
            } else {
                $precio_venta_unidad = $request->get('precio_venta');
            }


            $cantidad = $request->get('cantidad');
            $precio_costo_unidad = $request->get('precio_costo_unidad');
            $porEspecial = $request->get('porEspecial');

            $descuento = $request->get('descuento');
            $articulo_id = $request->get('idarticulo');

            //creamos un contador
            $cont = 0;

            while ($cont < count($articulo_id)) {

                $isDivisa = Articulo::find($articulo_id[$cont]);

                if ($isDivisa->isDolar) {
                    $activoD = $isDivisa->isDolar;
                } else {
                    $activoD = null;
                }
                if ($isDivisa->isPeso) {
                    $activoP = $isDivisa->isPeso;
                } else {
                    $activoP = null;
                }
                if ($isDivisa->isTransPunto) {
                    $activoT = $isDivisa->isTransPunto;
                } else {
                    $activoT = null;
                }
                if ($isDivisa->isMixto) {
                    $activoM = $isDivisa->isMixto;
                } else {
                    $activoM = null;
                }
                if ($isDivisa->isEfectivo) {
                    $activoE = $isDivisa->isEfectivo;
                } else {
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
                $Servicios_venta->servicio_id =  $servicio_id;
                $Servicios_venta->venta_id =  $venta->id;
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
                $Articulo_venta->venta_id =  $venta->id;
                $Articulo_venta->save();


                $cont = $cont + 1;
            }

            // Todo: Verificar si hay pagos on vueltos en la oficina o vueltos en caja, para realizar el pago.
            if ($this->getTotalAmount() > 0) {
                // Todo: Verificar si hay vueltos en oficina, para realizar el pago.
                if (is_numeric($pagoConExcedente) && $pagoConExcedente > 0) {
                    $this->payWithChangeSaved(
                        $this->getTotalAmount(),
                        $serie_comprobante,
                        'Consumo',
                        $request->get('caja_id'),
                        $servicio_id,
                        $cliente_id,
                        $sale_id
                    );

                    // Todo: Verificar si hay vueltos en caja, para realizar el pago.
                    $this->payWithChangeInBox($servicio_id, $this->getTotalAmount(), $request->get('caja_id'), 'Consumo', $sale_id);
                }

                // Todo: Verificar si pago con Transferencia, Punto, Dolar, Peso, Bolivar, para realizar el pago.

                $this->payWithCash2($this->getTotalAmount(), $servicio_id,$request, 'Consumo', $sale_id);
            }


            DB::commit();
            // return count($requestPrint->idarticulo);
            $printer = new PrinterController;

            $printer->ticketConsumo('Consumo', $articulo_id, $serie_comprobante, $precio_venta_unidad, $cantidad, $modo_pago, $tipo_pago, $total_venta, $operador);

            if ($printer->print_error === 1) {
                return Redirect::to('checkout')->with('status_success', 'La venta fué registrada exitosamente');
            } else {
                return Redirect::to('checkout')->with('status_warning', 'El servicio fué registrado exitosamente. Sin embargo, no se pudo emitir el ticket con la impresora: ' . $printer->print_name);
            }

            return Redirect::to('checkout')->with('success', 'La venta fué registrada exitosamente');
        } catch (\Exception $e) {

            DB::rollback();
            dd($e);
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

        $habitacion = Servicio::where('id', '=', "$id")->get();
        $sevicioVenta = Servicios_Ventas::where('servicio_id', $habitacion[0]->id)->get();
        // return $sevicioVenta;
        $service = Servicio::where('id', $habitacion[0]->id)->first();
        // return $service;
        $cliente = Persona::where('id', $habitacion[0]->persona_id)->first();
        $credito = Credito::where('persona_id', $habitacion[0]->persona_id)->first();
        // return $cliente;
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        $User = Auth::user();

        Sessioncaja::crearsession();
        $tasa = Tasa::find(1);
        // return $tasa;
        $tasa->updated_at;
        $fechaActual = Carbon::now();

        if ($tasa->tasa <= 0 || $tasa->updated_at->diffInHours($fechaActual) >= 6) {
            return redirect()
                ->route('tasa.index')
                ->with('status_danger', '¡Debes Actualizar el margen de gananacia para poder acceder!');
        } else {

            $cajaSessionid =  Sessioncaja::where('estado', 'Abierta')->orderBy('id', 'desc')->first();
            // dd($cajaSessionid);
            $Caja = Caja::where("estado", "=", 'Abierta')->where("sessioncaja_id", "=", $cajaSessionid->id)->first();

            if ($Caja) {

                if ($Caja->user_id == Auth::id()) {
                    $title = 'Nueva venta';
                    $personas = DB::table('personas')->where('tipo_persona', '=', 'Cliente')->get();
                    $tasaDolar = DB::table('tasas')->where('nombre', '=', 'Dolar')->first();
                    $tasaPeso = DB::table('tasas')->where('nombre', '=', 'Peso')->first();
                    $tasaTransferenciaPunto = DB::table('tasas')->where('nombre', '=', 'Transferencia_Punto')->first();
                    $tasaMixto = DB::table('tasas')->where('nombre', '=', 'Mixto')->first();
                    $tasaEfectivo = DB::table('tasas')->where('nombre', '=', 'Efectivo')->first();
                    $articulos = DB::table('articulos as art')
                        ->select(DB::raw('CONCAT(art.codigo, " - ", art.nombre) AS articulo'), 'art.imagen', 'art.vender_al', 'art.nombre', 'art.id', 'precio_costo', 'porEspecial', 'isDolar', 'isPeso', 'isTransPunto', 'isMixto', 'isEfectivo', 'isKilo', 'stock', 'art.nombre')
                        ->where('art.estado', '=', 'Activo')
                        ->where('art.stock', '>', '0')
                        ->where('art.precio_costo', '>', '0')
                        ->get();


                    $UserId = Auth::id();
                    $caja = Caja::where("estado", "=", 'Abierta')->where("sessioncaja_id", "=", $cajaSessionid->id)->first();
                    $ventaNum = Venta::latest('id')->first();

                    if (is_null($ventaNum)) {

                        $num_comprobante = Sessioncaja::numCodigo('C', $UserId, 1);
                        $serie_comprobante = Sessioncaja::numCodigo('N', $UserId, 1);
                    } else {
                        $num_comprobante = Sessioncaja::numCodigo('C', $UserId, $ventaNum->id + 1);
                        $serie_comprobante = Sessioncaja::numCodigo('N', $UserId, $ventaNum->id + 1);
                    }



                    $ventas = DB::table('ventas as v')
                        ->join('personas as p', 'v.persona_id', '=', 'p.id')
                        ->join('articulo_ventas as av', 'v.id', '=', 'av.venta_id')
                        ->join('cajas as c', 'v.caja_id', '=', 'c.sessioncaja_id')
                        ->join('users as u', 'u.id', '=', 'c.user_id')
                        ->select('u.name', 'c.user_id', 'v.id', 'v.fecha_hora', 'v.modo_pago', 'v.caja_id', 'c.sessioncaja_id', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.total_venta', 'v.estado')
                        ->where('c.user_id', '=', Auth::user()->id)
                        ->where('c.estado', '=', 'Abierta')
                        ->orderBy('v.id', 'desc')
                        ->groupBy('u.name', 'c.user_id', 'v.id', 'v.fecha_hora', 'v.modo_pago', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.total_venta', 'v.estado')
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


                    // foreach ($cajas->pago_ventas as $pago ) {

                    //     if ($pago->Divisa == 'Dolar') {
                    //         $cajas->SumaTotalDolar = $cajas->SumaTotalDolar + ($pago->MontoDivisa - $pago->Vueltos * -1);
                    //     }elseif ($pago->Divisa == 'Peso') {
                    //         $cajas->SumaTotalPeso = $cajas->SumaTotalPeso + ($pago->MontoDivisa - $pago->Vueltos * -1);
                    //     }elseif ($pago->Divisa == 'Bolivar') {
                    //         $cajas->SumaTotalBolivar = $cajas->SumaTotalBolivar + ($pago->MontoDivisa - $pago->Vueltos * -1);
                    //     }elseif ($pago->Divisa == 'Punto') {
                    //         $cajas->SumaTotalPunto = $cajas->SumaTotalPunto + ($pago->MontoDivisa - $pago->Vueltos * -1);
                    //     }elseif ($pago->Divisa == 'Transferencia') {
                    //         $cajas->SumaTotalTransferencia = $cajas->SumaTotalTransferencia + ($pago->MontoDivisa - $pago->Vueltos * -1);
                    //     }

                    // }

                    foreach ($cajas->ventas as $vent) {
                        if ($vent->estado == 'Aceptada' && $vent->status == 'Pagado') {
                            $cajas->SumaTotalVentas = $cajas->SumaTotalVentas + $vent->total_venta;
                        }

                        if ($vent->estado == 'Aceptada') {

                            $cajas->SumaTotalCantidadVentas = $cajas->SumaTotalCantidadVentas + 1;
                        }
                    }

                    foreach ($cajas->articulo_ventas as $art_vent) {
                        $cajas->SumaArticulosVendidos = $cajas->SumaArticulosVendidos + $art_vent->cantidad;
                        if ($art_vent->estado_pago == 'Falta pagar') {
                            // $cajas->SumaTotalVentas = $cajas->SumaTotalVentas - ($art_vent->cantidad * $art_vent->precio_venta_unidad);
                            $cajas->SumaTotalVentasPorCobrar = $cajas->SumaTotalVentasPorCobrar + ($art_vent->cantidad * $art_vent->precio_venta_unidad);
                        }
                    }

                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    // foreach ($cajas->pago_servicios as $pagoS ) {

                    //     if ($pagoS->Divisa == 'Dolar') {
                    //         if($pagoS->Vueltos > 0){
                    //             $cajas->SumaTotalDolarServ = $cajas->SumaTotalDolarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos);
                    //         }else{
                    //         $cajas->SumaTotalDolarServ = $cajas->SumaTotalDolarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1);
                    //         }
                    //     }elseif ($pagoS->Divisa == 'Peso') {
                    //         $cajas->SumaTotalPesoServ = $cajas->SumaTotalPesoServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1);
                    //     }elseif ($pagoS->Divisa == 'Bolivar') {
                    //         $cajas->SumaTotalBolivarServ = $cajas->SumaTotalBolivarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1);
                    //     }elseif ($pagoS->Divisa == 'Punto') {
                    //         $cajas->SumaTotalPuntoServ = $cajas->SumaTotalPuntoServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1);
                    //     }elseif ($pagoS->Divisa == 'Transferencia') {
                    //         $cajas->SumaTotalTransferenciaServ = $cajas->SumaTotalTransferenciaServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1);
                    //     }

                    // }



                    foreach ($cajas->servicios as $serv) {
                        if ($serv->estado == 'Aceptada') {
                            if ($serv->status == 'Pagado') {
                                $cajas->SumaTotalServicios = $cajas->SumaTotalServicios + $serv->total_venta;
                                $cajas->SumaTotalCantidadServicios = $cajas->SumaTotalCantidadServicios + 1;
                            }

                            if ($serv->status == 'Falta pagar') {
                                $cajas->SumaTotalServiciosPorPagar = $cajas->SumaTotalServiciosPorPagar + $serv->total_venta;
                                $cajas->SumaTotalCantidadServiciosPorPagar = $cajas->SumaTotalCantidadServiciosPorPagar + 1;
                            }

                            if ($serv->status == 'Exonerado') {
                                $cajas->SumaTotalServiciosCortesia = $cajas->SumaTotalServiciosCortesia + $serv->total_venta;
                                $cajas->SumaTotalCantidadServiciosCortesia = $cajas->SumaTotalCantidadServiciosCortesia + 1;
                            }
                        }
                    }

                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    // TODO Verificar que tengamos liquidez en esa divisa para dar vueltos y se procesa
                    //con este codigo buscamos los saldos disponibles en dolar, peso y bolivar
                    // el resultado son 3 variables de nombre $dolarDisponible, $pesoDisponible y $bolivarDisponible.
                    //para luego con esto poder dar los vueltos si hay desponibilidad.


                    $tasaDolar = DB::table('tasas')->where('nombre', '=', 'Dolar')->first();
                    $tasaPeso = DB::table('tasas')->where('nombre', '=', 'Peso')->first();
                    $tasaTransferenciaPunto = DB::table('tasas')->where('nombre', '=', 'Transferencia_Punto')->first();
                    $tasaMixto = DB::table('tasas')->where('nombre', '=', 'Mixto')->first();
                    $tasaEfectivo = DB::table('tasas')->where('nombre', '=', 'Efectivo')->first();


                    $cajas->user;
                    $cajas->ventas;
                    $cajas->pago_ventas;
                    $cajas->articulo_ventas;
                    $cajas->servicios;
                    $cajas->detalle_creditos;
                    // $cajas->credito;
                    $cajas->cortesias;
                    $cajas->pago_servicios;
                    $cajas->pago_creditos;
                    $cajas->creditos_pagados;
                    $cajas->excedente_actual;



                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    foreach ($cajas->creditos_pagados as $credPagados) {

                        if ($credPagados->user_id == $cajas->user_id) {

                            $validarPagosCreditos = Pago_Credito::where('detalle__creditos__pagado_id', $credPagados->detalle__creditos__pagado_id)->get();
                            if (count($validarPagosCreditos)) {
                                foreach ($validarPagosCreditos as $credPagadosCaja) {
                                    if ($credPagadosCaja->Divisa == 'Dolar') {
                                        $cajas->SumaTotalDolarCred = $cajas->SumaTotalDolarCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);
                                    } elseif ($credPagadosCaja->Divisa == 'Peso') {
                                        $cajas->SumaTotalPesoCred = $cajas->SumaTotalPesoCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);
                                    } elseif ($credPagadosCaja->Divisa == 'Bolivar') {
                                        $cajas->SumaTotalBolivarCred = $cajas->SumaTotalBolivarCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);
                                    } elseif ($credPagadosCaja->Divisa == 'Punto') {
                                        $cajas->SumaTotalPuntoCred = $cajas->SumaTotalPuntoCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);
                                    } elseif ($credPagadosCaja->Divisa == 'Transferencia') {
                                        $cajas->SumaTotalTransferenciaCred = $cajas->SumaTotalTransferenciaCred + ($credPagadosCaja->MontoDivisa - $credPagadosCaja->Vueltos * -1);
                                    }
                                }
                            }

                            if ($credPagados->tipo_operacion == 'Consumo') {

                                $validarPagosCreditosConsumo = Pago_Credito::where('detalle__creditos__pagado_id', $credPagados->detalle__creditos__pagado_id)->get();
                                if (count($validarPagosCreditosConsumo)) {
                                    foreach ($validarPagosCreditosConsumo as $credPagadosCajaConsumo) {
                                        if ($credPagadosCajaConsumo->Divisa == 'Dolar') {
                                            $cajas->SumaTotalDolarCredConsumo = $cajas->SumaTotalDolarCredConsumo + ($credPagadosCajaConsumo->MontoDivisa - $credPagadosCajaConsumo->Vueltos * -1);
                                        } elseif ($credPagadosCajaConsumo->Divisa == 'Peso') {
                                            $cajas->SumaTotalPesoCredConsumo = $cajas->SumaTotalPesoCredConsumo + ($credPagadosCajaConsumo->MontoDivisa - $credPagadosCajaConsumo->Vueltos * -1);
                                        } elseif ($credPagadosCajaConsumo->Divisa == 'Bolivar') {
                                            $cajas->SumaTotalBolivarCredConsumo = $cajas->SumaTotalBolivarCredConsumo + ($credPagadosCajaConsumo->MontoDivisa - $credPagadosCajaConsumo->Vueltos * -1);
                                        } elseif ($credPagadosCajaConsumo->Divisa == 'Punto') {
                                            $cajas->SumaTotalPuntoCredConsumo = $cajas->SumaTotalPuntoCredConsumo + ($credPagadosCajaConsumo->MontoDivisa - $credPagadosCajaConsumo->Vueltos * -1);
                                        } elseif ($credPagadosCajaConsumo->Divisa == 'Transferencia') {
                                            $cajas->SumaTotalTransferenciaCredConsumo = $cajas->SumaTotalTransferenciaCredConsumo + ($credPagadosCajaConsumo->MontoDivisa - $credPagadosCajaConsumo->Vueltos * -1);
                                        }
                                    }
                                }

                                $cajas->SumaTotalCreditosPagadosConsumoPorCaja = $cajas->SumaTotalCreditosPagadosConsumoPorCaja + $credPagados->monto;
                            }

                            if ($credPagados->tipo_operacion == 'Servicio') {

                                $validarPagosCreditosServicio = Pago_Credito::where('detalle__creditos__pagado_id', $credPagados->detalle__creditos__pagado_id)->get();
                                if (count($validarPagosCreditosServicio)) {
                                    foreach ($validarPagosCreditosServicio as $credPagadosCajaServicio) {
                                        if ($credPagadosCajaServicio->Divisa == 'Dolar') {
                                            $cajas->SumaTotalDolarCredServicio = $cajas->SumaTotalDolarCredServicio + ($credPagadosCajaServicio->MontoDivisa - $credPagadosCajaServicio->Vueltos * -1);
                                        } elseif ($credPagadosCajaServicio->Divisa == 'Peso') {
                                            $cajas->SumaTotalPesoCredServicio = $cajas->SumaTotalPesoCredServicio + ($credPagadosCajaServicio->MontoDivisa - $credPagadosCajaServicio->Vueltos * -1);
                                        } elseif ($credPagadosCajaServicio->Divisa == 'Bolivar') {
                                            $cajas->SumaTotalBolivarCredServicio = $cajas->SumaTotalBolivarCredServicio + ($credPagadosCajaServicio->MontoDivisa - $credPagadosCajaServicio->Vueltos * -1);
                                        } elseif ($credPagadosCajaServicio->Divisa == 'Punto') {
                                            $cajas->SumaTotalPuntoCredServicio = $cajas->SumaTotalPuntoCredServicio + ($credPagadosCajaServicio->MontoDivisa - $credPagadosCajaServicio->Vueltos * -1);
                                        } elseif ($credPagadosCajaServicio->Divisa == 'Transferencia') {
                                            $cajas->SumaTotalTransferenciaCredServicio = $cajas->SumaTotalTransferenciaCredServicio + ($credPagadosCajaServicio->MontoDivisa - $credPagadosCajaServicio->Vueltos * -1);
                                        }
                                    }
                                }

                                $cajas->SumaTotalCreditosPagadosServicioPorCaja = $cajas->SumaTotalCreditosPagadosServicioPorCaja + $credPagados->monto;
                            }
                            $cajas->SumaTotalCreditosPagadosTotalesPorCaja = $cajas->SumaTotalCreditosPagadosTotalesPorCaja + $credPagados->monto;
                        } else {

                            $validarPagosCreditos = Pago_Credito::where('detalle__creditos__pagado_id', $credPagados->detalle__creditos__pagado_id)->get();
                            if (count($validarPagosCreditos)) {
                                foreach ($validarPagosCreditos as $credPagadosOficina) {
                                    if ($credPagadosOficina->Divisa == 'Dolar') {
                                        $cajas->SumaTotalDolarCredPorOficina = $cajas->SumaTotalDolarCredPorOficina + ($credPagadosOficina->MontoDivisa - $credPagadosOficina->Vueltos * -1);
                                    } elseif ($credPagadosOficina->Divisa == 'Peso') {
                                        $cajas->SumaTotalPesoCredPorOficina = $cajas->SumaTotalPesoCredPorOficina + ($credPagadosOficina->MontoDivisa - $credPagadosOficina->Vueltos * -1);
                                    } elseif ($credPagadosOficina->Divisa == 'Bolivar') {
                                        $cajas->SumaTotalBolivarCredPorOficina = $cajas->SumaTotalBolivarCredPorOficina + ($credPagadosOficina->MontoDivisa - $credPagadosOficina->Vueltos * -1);
                                    } elseif ($credPagadosOficina->Divisa == 'Punto') {
                                        $cajas->SumaTotalPuntoCredPorOficina = $cajas->SumaTotalPuntoCredPorOficina + ($credPagadosOficina->MontoDivisa - $credPagadosOficina->Vueltos * -1);
                                    } elseif ($credPagadosOficina->Divisa == 'Transferencia') {
                                        $cajas->SumaTotalTransferenciaCredPorOficina = $cajas->SumaTotalTransferenciaCredPorOficina + ($credPagadosOficina->MontoDivisa - $credPagadosOficina->Vueltos * -1);
                                    }
                                }
                            }

                            if ($credPagados->tipo_operacion == 'Consumo') {

                                $cajas->SumaTotalCreditosPagadosConsumoPorOficina = $cajas->SumaTotalCreditosPagadosConsumoPorOficina + $credPagados->monto;
                            }

                            if ($credPagados->tipo_operacion == 'Servicio') {

                                $cajas->SumaTotalCreditosPagadosServicioPorOficina = $cajas->SumaTotalCreditosPagadosServicioPorOficina + $credPagados->monto;
                            }

                            $cajas->SumaTotalCreditosPagadosTotalesPorOficina = $cajas->SumaTotalCreditosPagadosTotalesPorOficina + $credPagados->monto;
                        }


                        if ($credPagados->tipo_operacion == 'Servicio') {

                            // $cajas->SumaTotalCreditosPagadosServicio = $cajas->SumaTotalCreditosPagadosServicio + $credPagados->monto;
                            $cajas->SumaTotalCantidadCreditosPagadosServicio = $cajas->SumaTotalCantidadCreditosPagadosServicio + 1;
                        }

                        if ($credPagados->tipo_operacion == 'Consumo') {

                            // $cajas->SumaTotalCreditosPagadosConsumo = $cajas->SumaTotalCreditosPagadosConsumo + $credPagados->monto;
                            $cajas->SumaTotalCantidadCreditosPagadosConsumo =  $cajas->SumaTotalCantidadCreditosPagadosConsumo + 1;
                        }
                        // $cajas->SumaTotalCreditosPagadosTotales = $cajas->SumaTotalCreditosPagadosTotales + $credPagados->monto;
                        $cajas->SumaTotalCantidadCreditosPagadosTotales = $cajas->SumaTotalCantidadCreditosPagadosTotales + 1;
                    }
                    // return $cajas->SumaTotalCreditosPagados;
                    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    // TODO optenemos los datos de la tabla excedente actual

                    foreach ($cajas->excedente_actual as $excedenteActual) {

                        // TODO sacamos los totales devueltos recibidos por excedentes totales
                        if ($excedenteActual->Divisa == 'Dolar') {
                            $cajas->TotalSumaVueltosPendientesDolarDivisa = $cajas->TotalSumaVueltosPendientesDolarDivisa  + $excedenteActual->MontoDivisa;
                            $cajas->TotalSumaVueltosPendientesDolarDolar = $cajas->TotalSumaVueltosPendientesDolarDolar  + $excedenteActual->MontoDolar;
                        } elseif ($excedenteActual->Divisa == 'Peso') {
                            $cajas->TotalSumaVueltosPendientesPesoDivisa = $cajas->TotalSumaVueltosPendientesPesoDivisa  + $excedenteActual->MontoDivisa;
                            $cajas->TotalSumaVueltosPendientesPesoDolar = $cajas->TotalSumaVueltosPendientesPesoDolar  + $excedenteActual->MontoDolar;
                        } elseif ($excedenteActual->Divisa == 'Bolivar') {
                            $cajas->TotalSumaVueltosPendientesBolivarDivisa = $cajas->TotalSumaVueltosPendientesBolivarDivisa  + $excedenteActual->MontoDivisa;
                            $cajas->TotalSumaVueltosPendientesBolivarDolar = $cajas->TotalSumaVueltosPendientesBolivarDolar  + $excedenteActual->MontoDolar;
                        } elseif ($excedenteActual->Divisa == 'Punto') {
                            $cajas->TotalSumaVueltosPendientesPuntoDivisa = $cajas->TotalSumaVueltosPendientesPuntoDivisa  + $excedenteActual->MontoDivisa;
                            $cajas->TotalSumaVueltosPendientesPuntoDolar = $cajas->TotalSumaVueltosPendientesPuntoDolar  + $excedenteActual->MontoDolar;
                        } elseif ($excedenteActual->Divisa == 'Transferencia') {
                            $cajas->TotalSumaVueltosPendientesTransferenciaDivisa = $cajas->TotalSumaVueltosPendientesTransferenciaDivisa  + $excedenteActual->MontoDivisa;
                            $cajas->TotalSumaVueltosPendientesTransferenciaDolar = $cajas->TotalSumaVueltosPendientesTransferenciaDolar  + $excedenteActual->MontoDolar;
                        }


                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        // TODO hacemos subconsultas

                        if ($excedenteActual->Estado == 'Pendiente') {
                            if ($excedenteActual->Divisa == 'Dolar') {
                                $cajas->SumaVueltosPendientesDolarDivisa = $cajas->SumaVueltosPendientesDolarDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosPendientesDolarDolar = $cajas->SumaVueltosPendientesDolarDolar  + $excedenteActual->MontoDolar;
                            } elseif ($excedenteActual->Divisa == 'Peso') {
                                $cajas->SumaVueltosPendientesPesoDivisa = $cajas->SumaVueltosPendientesPesoDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosPendientesPesoDolar = $cajas->SumaVueltosPendientesPesoDolar  + $excedenteActual->MontoDolar;
                            } elseif ($excedenteActual->Divisa == 'Bolivar') {
                                $cajas->SumaVueltosPendientesBolivarDivisa = $cajas->SumaVueltosPendientesBolivarDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosPendientesBolivarDolar = $cajas->SumaVueltosPendientesBolivarDolar  + $excedenteActual->MontoDolar;
                            } elseif ($excedenteActual->Divisa == 'Punto') {
                                $cajas->SumaVueltosPendientesPuntoDivisa = $cajas->SumaVueltosPendientesPuntoDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosPendientesPuntoDolar = $cajas->SumaVueltosPendientesPuntoDolar  + $excedenteActual->MontoDolar;
                            } elseif ($excedenteActual->Divisa == 'Transferencia') {
                                $cajas->SumaVueltosPendientesTransferenciaDivisa = $cajas->SumaVueltosPendientesTransferenciaDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosPendientesTransferenciaDolar = $cajas->SumaVueltosPendientesTransferenciaDolar  + $excedenteActual->MontoDolar;
                            }
                        } elseif ($excedenteActual->Estado == 'Devueltos') {
                            if ($excedenteActual->Divisa == 'Dolar') {
                                $cajas->SumaVueltosDevueltosDolarDivisa = $cajas->SumaVueltosDevueltosDolarDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosDevueltosDolarDolar = $cajas->SumaVueltosDevueltosDolarDolar  + $excedenteActual->MontoDolar;
                            } elseif ($excedenteActual->Divisa == 'Peso') {
                                $cajas->SumaVueltosDevueltosPesoDivisa = $cajas->SumaVueltosDevueltosPesoDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosDevueltosPesoDolar = $cajas->SumaVueltosDevueltosPesoDolar  + $excedenteActual->MontoDolar;
                            } elseif ($excedenteActual->Divisa == 'Bolivar') {
                                $cajas->SumaVueltosDevueltosBolivarDivisa = $cajas->SumaVueltosDevueltosBolivarDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosDevueltosBolivarDolar = $cajas->SumaVueltosDevueltosBolivarDolar  + $excedenteActual->MontoDolar;
                            } elseif ($excedenteActual->Divisa == 'Punto') {
                                $cajas->SumaVueltosDevueltosPuntoDivisa = $cajas->SumaVueltosDevueltosPuntoDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosDevueltosPuntoDolar = $cajas->SumaVueltosDevueltosPuntoDolar  + $excedenteActual->MontoDolar;
                            } elseif ($excedenteActual->Divisa == 'Transferencia') {
                                $cajas->SumaVueltosDevueltosTransferenciaDivisa = $cajas->SumaVueltosDevueltosTransferenciaDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosDevueltosTransferenciaDolar = $cajas->SumaVueltosDevueltosTransferenciaDolar  + $excedenteActual->MontoDolar;
                            }
                        } elseif ($excedenteActual->Estado == 'PagarOficina') {
                            if ($excedenteActual->Divisa == 'Dolar') {
                                $cajas->SumaVueltosPagarOficinaDolarDivisa = $cajas->SumaVueltosPagarOficinaDolarDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosPagarOficinaDolarDolar = $cajas->SumaVueltosPagarOficinaDolarDolar  + $excedenteActual->MontoDolar;
                            } elseif ($excedenteActual->Divisa == 'Peso') {
                                $cajas->SumaVueltosPagarOficinaPesoDivisa = $cajas->SumaVueltosPagarOficinaPesoDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosPagarOficinaPesoDolar = $cajas->SumaVueltosPagarOficinaPesoDolar  + $excedenteActual->MontoDolar;
                            } elseif ($excedenteActual->Divisa == 'Bolivar') {
                                $cajas->SumaVueltosPagarOficinaBolivarDivisa = $cajas->SumaVueltosPagarOficinaBolivarDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosPagarOficinaBolivarDolar = $cajas->SumaVueltosPagarOficinaBolivarDolar  + $excedenteActual->MontoDolar;
                            } elseif ($excedenteActual->Divisa == 'Punto') {
                                $cajas->SumaVueltosPagarOficinaPuntoDivisa = $cajas->SumaVueltosPagarOficinaPuntoDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosPagarOficinaPuntoDolar = $cajas->SumaVueltosPagarOficinaPuntoDolar  + $excedenteActual->MontoDolar;
                            } elseif ($excedenteActual->Divisa == 'Transferencia') {
                                $cajas->SumaVueltosPagarOficinaTransferenciaDivisa = $cajas->SumaVueltosPagarOficinaTransferenciaDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosPagarOficinaTransferenciaDolar = $cajas->SumaVueltosPagarOficinaTransferenciaDolar  + $excedenteActual->MontoDolar;
                            }
                        } elseif ($excedenteActual->Estado == 'ExcedenteNuevo') {
                            if ($excedenteActual->Divisa == 'Dolar') {
                                $cajas->SumaVueltosExcedenteNuevoDolarDivisa = $cajas->SumaVueltosExcedenteNuevoDolarDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosExcedenteNuevoDolarDolar = $cajas->SumaVueltosExcedenteNuevoDolarDolar  + $excedenteActual->MontoDolar;
                            } elseif ($excedenteActual->Divisa == 'Peso') {
                                $cajas->SumaVueltosExcedenteNuevoPesoDivisa = $cajas->SumaVueltosExcedenteNuevoPesoDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosExcedenteNuevoPesoDolar = $cajas->SumaVueltosExcedenteNuevoPesoDolar  + $excedenteActual->MontoDolar;
                            } elseif ($excedenteActual->Divisa == 'Bolivar') {
                                $cajas->SumaVueltosExcedenteNuevoBolivarDivisa = $cajas->SumaVueltosExcedenteNuevoBolivarDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosExcedenteNuevoBolivarDolar = $cajas->SumaVueltosExcedenteNuevoBolivarDolar  + $excedenteActual->MontoDolar;
                            } elseif ($excedenteActual->Divisa == 'Punto') {
                                $cajas->SumaVueltosExcedenteNuevoPuntoDivisa = $cajas->SumaVueltosExcedenteNuevoPuntoDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosExcedenteNuevoPuntoDolar = $cajas->SumaVueltosExcedenteNuevoPuntoDolar  + $excedenteActual->MontoDolar;
                            } elseif ($excedenteActual->Divisa == 'Transferencia') {
                                $cajas->SumaVueltosExcedenteNuevoTransferenciaDivisa = $cajas->SumaVueltosExcedenteNuevoTransferenciaDivisa  + $excedenteActual->MontoDivisa;
                                $cajas->SumaVueltosExcedenteNuevoTransferenciaDolar = $cajas->SumaVueltosExcedenteNuevoTransferenciaDolar  + $excedenteActual->MontoDolar;
                            }

                            if ($excedenteActual->Tipo == 'Servicio') {
                                $cajas->TotalSumaVueltosExcedenteNuevoServicioDolarToDolar = $excedenteActual->MontoDolar;
                            }
                            if ($excedenteActual->Tipo == 'Consumo') {
                                $cajas->TotalSumaVueltosExcedenteNuevoConsumoDolarToDolar = $excedenteActual->MontoDolar;
                            }

                            if ($excedenteActual->Tipo == 'Otros') {
                                $cajas->TotalSumaVueltosExcedenteNuevoOtrosDolarToDolar = $excedenteActual->MontoDolar;
                            }
                            $cajas->TotalSumaVueltosExcedenteNuevoDolarToDolar = $excedenteActual->MontoDolar;
                        }
                    }

                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    foreach ($cajas->pago_servicios as $pagoS) {

                        $validarPagosServicios = Servicio::where('id', $pagoS->servicio_id)->first();
                        if ($validarPagosServicios) {
                            if ($pagoS->Divisa == 'Dolar') {
                                if ($pagoS->Vueltos > 0) {
                                    $cajas->SumaTotalDolarServ = $cajas->SumaTotalDolarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - $validarPagosServicios->excedente_nuevo;
                                } else {
                                    $cajas->SumaTotalDolarServDflotante = $cajas->SumaTotalDolarServDflotante + ($pagoS->Vueltos * -1);
                                    $cajas->SumaTotalDolarServ = $cajas->SumaTotalDolarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - $validarPagosServicios->excedente_nuevo;
                                }
                            } elseif ($pagoS->Divisa == 'Peso') {
                                if ($pagoS->Vueltos > 0) {
                                    $cajas->SumaTotalPesoServ = $cajas->SumaTotalPesoServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaPeso->tasa);
                                } else {
                                    $cajas->SumaTotalPesoServDflotante = $cajas->SumaTotalPesoServDflotante + ($pagoS->Vueltos * -1);
                                    $cajas->SumaTotalPesoServ = $cajas->SumaTotalPesoServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaPeso->tasa);
                                }
                            } elseif ($pagoS->Divisa == 'Bolivar') {
                                if ($pagoS->Vueltos > 0) {
                                    $cajas->SumaTotalBolivarServ = $cajas->SumaTotalBolivarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaEfectivo->tasa);
                                } else {
                                    $cajas->SumaTotalBolivarServDflotante = $cajas->SumaTotalBolivarServDflotante + ($pagoS->Vueltos * -1) * $tasaEfectivo->tasa;
                                    $cajas->SumaTotalBolivarServ = $cajas->SumaTotalBolivarServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaEfectivo->tasa);
                                }
                            } elseif ($pagoS->Divisa == 'Punto') {
                                if ($pagoS->Vueltos > 0) {
                                    $cajas->SumaTotalPuntoServ = $cajas->SumaTotalPuntoServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                                } else {
                                    $cajas->SumaTotalPuntoServDflotante = $cajas->SumaTotalPuntoServDflotante + ($pagoS->Vueltos * -1) * $tasaTransferenciaPunto->tasa;
                                    $cajas->SumaTotalPuntoServ = $cajas->SumaTotalPuntoServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                                }
                            } elseif ($pagoS->Divisa == 'Transferencia') {
                                if ($pagoS->Vueltos > 0) {
                                    $cajas->SumaTotalTransferenciaServ = $cajas->SumaTotalTransferenciaServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                                } else {
                                    $cajas->SumaTotalTransferenciaServDflotante = $cajas->SumaTotalTransferenciaServDflotante + ($pagoS->Vueltos * -1) * $tasaTransferenciaPunto->tasa;
                                    $cajas->SumaTotalTransferenciaServ = $cajas->SumaTotalTransferenciaServ + ($pagoS->MontoDivisa - $pagoS->Vueltos * -1) - ($validarPagosServicios->excedente_nuevo * $tasaTransferenciaPunto->tasa);
                                }
                            }
                        }
                    }

                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    foreach ($cajas->pago_ventas as $pago) {

                        if ($pago->Divisa == 'Dolar') {
                            $cajas->SumaTotalDolar = $cajas->SumaTotalDolar + ($pago->MontoDivisa - $pago->Vueltos * -1);
                        } elseif ($pago->Divisa == 'Peso') {
                            $cajas->SumaTotalPeso = $cajas->SumaTotalPeso + ($pago->MontoDivisa - $pago->Vueltos * -1);
                        } elseif ($pago->Divisa == 'Bolivar') {
                            $cajas->SumaTotalBolivar = $cajas->SumaTotalBolivar + ($pago->MontoDivisa - $pago->Vueltos * -1);
                        } elseif ($pago->Divisa == 'Punto') {
                            $cajas->SumaTotalPunto = $cajas->SumaTotalPunto + ($pago->MontoDivisa - $pago->Vueltos * -1);
                        } elseif ($pago->Divisa == 'Transferencia') {
                            $cajas->SumaTotalTransferencia = $cajas->SumaTotalTransferencia + ($pago->MontoDivisa - $pago->Vueltos * -1);
                        }
                    }
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    foreach ($cajas->pago_extras as $pagoext) {

                        if ($pagoext->Divisa == 'Dolar') {
                            $cajas->SumaTotalDolarPagoExtra = $cajas->SumaTotalDolarPagoExtra + ($pagoext->MontoDivisa - $pagoext->Vueltos * -1);
                        } elseif ($pagoext->Divisa == 'Peso') {
                            $cajas->SumaTotalPesoPagoExtra = $cajas->SumaTotalPesoPagoExtra + ($pagoext->MontoDivisa - $pagoext->Vueltos * -1);
                        } elseif ($pagoext->Divisa == 'Bolivar') {
                            $cajas->SumaTotalBolivarPagoExtra = $cajas->SumaTotalBolivarPagoExtra + ($pagoext->MontoDivisa - $pagoext->Vueltos * -1);
                        } elseif ($pagoext->Divisa == 'Punto') {
                            $cajas->SumaTotalPuntoPagoExtra = $cajas->SumaTotalPuntoPagoExtra + ($pagoext->MontoDivisa - $pagoext->Vueltos * -1);
                        } elseif ($pagoext->Divisa == 'Transferencia') {
                            $cajas->SumaTotalTransferenciaPagoExtra = $cajas->SumaTotalTransferenciaPagoExtra + ($pagoext->MontoDivisa - $pagoext->Vueltos * -1);
                        }
                    }
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    $excedentesPendientes = Excedentes_Recibidos_Caja_Actual::where('Estado', 'Pendiente')->get();
                    if ($excedentesPendientes) {


                        foreach ($excedentesPendientes as $excdPendientes) {
                            // return $excdPendientes->Divisa;
                            if ($excdPendientes->Divisa == 'Dolar') {
                                $cajas->SumaTotalDolarExcedentesPendientes = $cajas->SumaTotalDolarExcedentesPendientes + ($excdPendientes->MontoDivisa);
                                // return $cajas->SumaTotalDolarExcedentesPendientes;
                            } elseif ($excdPendientes->Divisa == 'Peso') {
                                $cajas->SumaTotalPesoExcedentesPendientes = $cajas->SumaTotalPesoExcedentesPendientes + ($excdPendientes->MontoDivisa);
                            } elseif ($excdPendientes->Divisa == 'Bolivar') {
                                $cajas->SumaTotalBolivarExcedentesPendientes = $cajas->SumaTotalBolivarExcedentesPendientes + ($excdPendientes->MontoDivisa);
                            } elseif ($excdPendientes->Divisa == 'Punto') {
                                $cajas->SumaTotalPuntoExcedentesPendientes = $cajas->SumaTotalPuntoExcedentesPendientes + ($excdPendientes->MontoDivisa);
                            } elseif ($excdPendientes->Divisa == 'Transferencia') {
                                $cajas->SumaTotalTransferenciaExcedentesPendientes = $cajas->SumaTotalTransferenciaExcedentesPendientes + ($excdPendientes->MontoDivisa);
                            }
                        }
                    }
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    foreach ($cajas->pago_vueltos as $pagoV) {

                        if ($pagoV->Divisa == 'Dolar') {

                            $cajas->SumaTotalDolarVueltos = $cajas->SumaTotalDolarVueltos + $pagoV->MontoDivisa;
                        } elseif ($pagoV->Divisa == 'Peso') {
                            $cajas->SumaTotalPesoVueltos = $cajas->SumaTotalPesoVueltos + $pagoV->MontoDivisa;
                        } elseif ($pagoV->Divisa == 'Bolivar') {
                            $cajas->SumaTotalBolivarVueltos = $cajas->SumaTotalBolivarVueltos + $pagoV->MontoDivisa;
                        } elseif ($pagoV->Divisa == 'Punto') {
                            $cajas->SumaTotalPuntoVueltos = $cajas->SumaTotalPuntoVueltos + $pagoV->MontoDivisa;
                        } elseif ($pagoV->Divisa == 'Transferencia') {
                            $cajas->SumaTotalTransferenciaVueltos = $cajas->SumaTotalTransferenciaVueltos + $pagoV->MontoDivisa;
                        }
                    }

                    // return ($cajas->SumaTotalDolarExcedentesPendientes + $cajas->SumaTotalDolarPagoExtra + $cajas->monto_dolar + ($cajas->SumaTotalDolarCredConsumo + $cajas->SumaTotalDolarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoDolarDivisa) + ($cajas->SumaTotalDolarServ + $cajas->SumaTotalDolar) + ($cajas->SumaTotalDolarServDflotante));


                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    // TODO Ahora le agregamos caja chica
                    // return $cajas->SumaTotalDolarVueltos;
                    // return 'algo';
                    $dolarDisponible = ($cajas->SumaTotalDolarExcedentesPendientes + $cajas->SumaTotalDolarPagoExtra + $cajas->monto_dolar + ($cajas->SumaTotalDolarCredConsumo + $cajas->SumaTotalDolarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoDolarDivisa) + ($cajas->SumaTotalDolarServ + $cajas->SumaTotalDolar) + ($cajas->SumaTotalDolarServDflotante) - $cajas->SumaTotalDolarVueltos);
                    // return $dolarDisponible;
                    $pesoDisponible = ($cajas->SumaTotalPesoExcedentesPendientes + $cajas->SumaTotalPesoPagoExtra + $cajas->monto_peso + ($cajas->SumaTotalPesoCredConsumo + $cajas->SumaTotalPesoCredServicio) + ($cajas->SumaVueltosExcedenteNuevoPesoDivisa) + ($cajas->SumaTotalPesoServ + $cajas->SumaTotalPeso) + ($cajas->SumaTotalPesoServDflotante) - $cajas->SumaTotalPesoVueltos);
                    // return $pesoDisponible;
                    $bolivarDisponible = ($cajas->SumaTotalBolivarExcedentesPendientes + $cajas->SumaTotalBolivarPagoExtra + $cajas->monto_bolivar + ($cajas->SumaTotalBolivarCredConsumo + $cajas->SumaTotalBolivarCredServicio) + ($cajas->SumaVueltosExcedenteNuevoBolivarDivisa) + ($cajas->SumaTotalBolivarServ + $cajas->SumaTotalBolivar) + ($cajas->SumaTotalBolivarServDflotante) - $cajas->SumaTotalBolivarVueltos);
                    // return $bolivarDisponible;
                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    // TODO Revisamos si el usuario tiene vueltos pendientes
                    // TODO pero tomando en cuenta que lo vamos a revisar por servicios no por la caja ?
                    $excedentesPendientesCajas = Excedentes_Recibidos_Caja_Actual::where('Estado', 'Pendiente')->get();
                    if ($excedentesPendientesCajas) {
                        foreach ($excedentesPendientesCajas as $exctePentesCajas) {
                            if ($exctePentesCajas->Estado == 'Pendiente' && $exctePentesCajas->servicio_id == $id) {
                                if ($exctePentesCajas->Divisa == 'Dolar') {
                                    $cajas->SumaVueltosPendientesDolarDivisa = $cajas->SumaVueltosPendientesDolarDivisa  + $exctePentesCajas->MontoDivisa;
                                    $cajas->SumaVueltosPendientesDolarDolar = $cajas->SumaVueltosPendientesDolarDolar  + $exctePentesCajas->MontoDolar;
                                } elseif ($exctePentesCajas->Divisa == 'Peso') {
                                    $cajas->SumaVueltosPendientesPesoDivisa = $cajas->SumaVueltosPendientesPesoDivisa  + $exctePentesCajas->MontoDivisa;
                                    $cajas->SumaVueltosPendientesPesoDolar = $cajas->SumaVueltosPendientesPesoDolar  + $exctePentesCajas->MontoDolar;
                                } elseif ($exctePentesCajas->Divisa == 'Bolivar') {
                                    $cajas->SumaVueltosPendientesBolivarDivisa = $cajas->SumaVueltosPendientesBolivarDivisa  + $exctePentesCajas->MontoDivisa;
                                    $cajas->SumaVueltosPendientesBolivarDolar = $cajas->SumaVueltosPendientesBolivarDolar  + $exctePentesCajas->MontoDolar;
                                } elseif ($exctePentesCajas->Divisa == 'Punto') {
                                    $cajas->SumaVueltosPendientesPuntoDivisa = $cajas->SumaVueltosPendientesPuntoDivisa  + $exctePentesCajas->MontoDivisa;
                                    $cajas->SumaVueltosPendientesPuntoDolar = $cajas->SumaVueltosPendientesPuntoDolar  + $exctePentesCajas->MontoDolar;
                                } elseif ($exctePentesCajas->Divisa == 'Transferencia') {
                                    $cajas->SumaVueltosPendientesTransferenciaDivisa = $cajas->SumaVueltosPendientesTransferenciaDivisa  + $exctePentesCajas->MontoDivisa;
                                    $cajas->SumaVueltosPendientesTransferenciaDolar = $cajas->SumaVueltosPendientesTransferenciaDolar  + $exctePentesCajas->MontoDolar;
                                }
                                $cajas->TotalSumaVueltosPendientesClienteDivisa = $cajas->TotalSumaVueltosPendientesClienteDivisa  + $exctePentesCajas->MontoDivisa;
                                $cajas->TotalSumaVueltosPendientesClienteDolar = $cajas->TotalSumaVueltosPendientesClienteDolar  + $exctePentesCajas->MontoDolar;
                            }
                        }
                    }


                    $tasaDolarHabitacion = Tasa::where('nombre', '=', 'DolarHabitacion')->first();
                    $tasaPesoHabitacion = Tasa::where('nombre', '=', 'PesoHabitacion')->first();
                    $UserName = Auth::user()->name;
                    $UserId = Auth::user()->id;
                    // return $service;



                    $vueltos_caja = $this->getTotalUndeliveredChangeInDolar($service->id);
                    $vueltos_oficina = $this->getTotalChangeBoxInDolar($service->persona_id);

                    $excedente_cliente = $vueltos_oficina + $vueltos_caja;

                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    //  return $cajas;
                    //dd($ventas);
                    // return $service->excedente_nuevo;

                    return view('preventa.create', compact('vueltos_caja', 'vueltos_oficina', 'excedente_cliente', 'service', 'cajas', 'dolarDisponible', 'pesoDisponible', 'bolivarDisponible', 'credito', 'cliente', 'UserName', 'UserId', 'tasaDolarHabitacion', 'tasaPesoHabitacion', 'cajas', 'habitacion', 'num_comprobante', 'serie_comprobante', 'caja', 'ventas', 'title', 'personas', 'tasaDolar', 'tasaPeso', 'tasaTransferenciaPunto', 'tasaMixto', 'tasaEfectivo', 'articulos'));
                } else {
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
}
