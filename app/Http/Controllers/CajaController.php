<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Sessioncaja;
use App\Denominacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CajaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        Sessioncaja::crearsession();
        $title='Nueva venta';
        $personas = DB::table('persona')->where('tipo_persona', '=', 'Cliente')->get();
        $tasaDolar = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Dolar')->first();
        $tasaPeso = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Peso')->first();
        $tasaTransferenciaPunto = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Transferencia_Punto')->first();
        $tasaMixto = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Mixto')->first();
        $tasaEfectivo = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Efectivo')->first();
        $articulos = DB::table('articulo as art')
            ->select(DB::raw('CONCAT(art.codigo, " - ", art.nombre) AS articulo'), 'art.idarticulo', 'precio_compra', 'stock', 'art.nombre')
            ->where('art.estado', '=', 'Activo')
            ->where('art.stock', '>', '0')
            ->get();
        $ventas = DB::table('venta as v')
        ->join('persona as p', 'v.idcliente', '=', 'p.idpersona')
        ->join('detalle_venta as dv', 'v.idventa', '=', 'dv.idventa')
        ->select('v.idventa', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.total_venta', 'v.estado')
        ->orderBy('v.idventa', 'desc')
        ->groupBy('v.idventa', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.total_venta', 'v.estado')
        ->paginate(7);



        return view('ventas.caja.index', compact('caja','ventas','title','personas','tasaDolar','tasaPeso','tasaTransferenciaPunto','tasaMixto','tasaEfectivo','articulos'));
    }

    public function listarcaja(){
        $id = Auth::id();

        $caja = Caja::where('estado', 'Abierta')
        ->where('user_id','=' ,$id)
        ->orderBy('id', 'desc')
        ->first();

        if (isset($caja)) {
            // $caja = 'si hay registros';
            return view('ventas.caja.listarcaja')->with('caja', $caja);
        }else {
        //    return 'no hay registros';
            $caja = 'no hay registros';
            return view('ventas.caja.listarcaja')->with('caja', $caja);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $denominacion_dolar = Denominacion::get();
        $caja =  Sessioncaja::where('estado', 'Abierta')->orderBy('id', 'desc')->first();
        $denominacion_dolar = Denominacion::where('moneda', 'Dolar')->orderBy('id', 'desc')->get();
        $denominacion_peso = Denominacion::where('moneda', 'Pesos')->get();
        $denominacion_bolivar = Denominacion::where('moneda', 'Bolivares')->orderBy('id', 'desc')->get();





        $title='Crear caja';

        return view('ventas.caja.create', compact('title','denominacion_dolar', 'denominacion_peso', 'denominacion_bolivar','caja'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $request;
        try{
            DB::beginTransaction();
            $venta = new Venta;
            $venta->idcliente = $request->get('idcliente');
            $venta->tipo_comprobante = $request->get('tipo_comprobante');
            $venta->serie_comprobante = $request->get('serie_comprobante');
            $venta->num_comprobante = $request->get('num_comprobante');
            $venta->total_venta = $request->get('total_venta');


            $myTime = Carbon::now('America/Caracas');
            $venta->fecha_hora = $myTime->toDateTimeString();
            $venta->impuesto = '18';
            $venta->estado = 'A';
            $venta->save();

            //cargamos los datos del detalle del ingreso en unas variables que reciven
            //un array

            $idarticulo = $request->get('idarticulo');
            $cantidad = $request->get('cantidad');
            $precio_venta = $request->get('precio_venta');
            $descuento = $request->get('descuento');

            //creamos un contador
            $cont = 0;

            //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
            while ($cont < count($idarticulo)) {

                $detalle = new DetalleVenta();
                $detalle->idventa = $venta->idventa;//este idingreso se autogenera cuando se crea el objeto en la parte superior (*)
                $detalle->idarticulo = $idarticulo[$cont];
                $detalle->cantidad = $cantidad[$cont];
                $detalle->precio_venta = $precio_venta[$cont];
                $detalle->descuento = $descuento[$cont];
                $detalle->save();

                $cont = $cont+1;
            }

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

                $detallePago = new DetallePago();
                $detallePago->idventa = $venta->idventa;//este idingreso se autogenera cuando se crea el objeto en la parte superior (*)
                $detallePago->Divisa = $divisa[$cont];
                $detallePago->MontoDivisa = $MontoDivisa[$cont];
                $detallePago->TasaTiket = $TasaTiket[$cont];
                $detallePago->MontoDolar = $MontoDolar[$cont];
                $detallePago->Vueltos = $Vueltos[$cont];
                $detallePago->save();

                $cont = $cont+1;
            }

            DB::commit();

        }catch(\Exception $e)
        {

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
