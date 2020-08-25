<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\VentaFormRequest;
use App\venta;
use App\DetalleVenta;
use App\DetallePago;
use App\Articulo;
use App\Tasa;
use Illuminate\Database\MySqlConnection;
use DB;

use Carbon\Carbon;
//use Illuminate\Http\Response;
use Response;
use Illuminate\Support\Collection;


class VentaController extends Controller
{
    public function __construct()
    {

    }
    public function index(Request $request)
    {
        if ($request) {
            $title='Ventas';
            $query = trim($request->get('buscarTexto'));
            $ventas = DB::table('venta as v')
                ->join('persona as p', 'v.idcliente', '=', 'p.idpersona')
                ->join('detalle_venta as dv', 'v.idventa', '=', 'dv.idventa')
                ->select('v.idventa', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.total_venta', 'v.estado')
                ->where('v.num_comprobante', 'LIKE', '%'. $query  .'%')
                ->orderBy('v.idventa', 'desc')
                ->groupBy('v.idventa', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.total_venta', 'v.estado')
                ->paginate(7);

            return view('ventas.venta.index', ["title" => $title,"ventas" => $ventas, "buscarTexto" => $query]);
        }
    }

    public function create()
    {
        $title='Nueva venta';
        $personas = DB::table('persona')->where('tipo_persona', '=', 'Cliente')->get();
        $tasaDolar = DB::table('tasa')->where('estado', '=', 'Activo')->where('nombre', '=', 'Dolar')->first();
        $tasaPeso = DB::table('tasa')->where('estado', '=', 'Activo')->where('nombre', '=', 'Peso')->first();
        $tasaTransferenciaPunto = DB::table('tasa')->where('estado', '=', 'Activo')->where('nombre', '=', 'Transferencia_Punto')->first();
        $tasaMixto = DB::table('tasa')->where('estado', '=', 'Activo')->where('nombre', '=', 'Mixto')->first();
        $tasaEfectivo = DB::table('tasa')->where('estado', '=', 'Activo')->where('nombre', '=', 'Efectivo')->first();
        $articulos = DB::table('articulo as art')
            ->select(DB::raw('CONCAT(art.codigo, " - ", art.nombre) AS articulo'), 'art.idarticulo', 'precio_compra', 'stock', 'art.nombre')
            ->where('art.estado', '=', 'Activo')
            ->where('art.stock', '>', '0')
            ->get();
            // dd($tasaTransferenciaPunto);
            // return $personas;

        return view('ventas.venta.create', compact('title','personas','tasaDolar','tasaPeso','tasaTransferenciaPunto','tasaMixto','tasaEfectivo','articulos'));
    }

    public function store(VentaFormRequest $request)
    {
       return  $request->all();
    //     dd('hola');
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

        return Redirect::to('ventas/venta')->with('success', 'La venta fué registrada exitosamente');
    }

    public function show($id)
    {
        // dd($id);
        $venta = DB::table('venta as v')
            ->join('persona as p', 'v.idcliente', '=', 'p.idpersona')
            ->join('detalle_venta as dv', 'v.idventa', '=', 'dv.idventa')
            ->select('v.idventa', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.total_venta', 'v.estado')
            ->where('v.idventa', '=', $id)
            ->first();

        //traemos los datos de la tabla detalle_venta
        $detalles = DB::table('detalle_venta as d')
            ->join('articulo as a', 'd.idarticulo', '=', 'a.idarticulo')
            ->select('a.nombre as articulo', 'd.cantidad', 'd.descuento','d.precio_venta')
            ->where('d.idventa', '=', $id)->get();

        return view("ventas.venta.show", ["venta" => $venta, "detalles"=> $detalles]);
    }

    public function destroy($id)
    {
        try{
            DB::beginTransaction();
        $venta = venta::findOrFail($id);
        $venta->estado = 'C';
        $venta->update();

        $detalleVenta = DetalleVenta::where('idventa','=',$id)->get();

         //creamos un contador
         $cont = 0;

         //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
         while ($cont < count($detalleVenta)) {
            $idarticulo = $detalleVenta[$cont]->idarticulo;
            $articulo = Articulo::findOrFail($idarticulo);
            $articulo->stock = $articulo->stock+$detalleVenta[$cont]->cantidad;
            $articulo->update();

            $cont = $cont+1;

         };
         DB::commit();

        }catch(\Exception $e)
        {

            DB::rollback();
            // dd($e);
        }

        return Redirect::to('ventas/venta');
    }
}

// DELIMITER //
// CREATE TRIGGER tr_updStockVenta AFTER INSERT ON detalle_venta
// FOR EACH ROW BEGIN
// 	UPDATE articulo SET stock = stock - NEW.cantidad
//     WHERE articulo.idarticulo = NEW.idarticulo;
// END
// //
// DELIMITER ;
