<?php

namespace App\Http\Controllers;

use DB;

use App\Tasa;
use Response;
use App\venta;
use App\Articulo;
use Carbon\Carbon;
use App\DetallePago;
use App\Sessioncaja;
use App\DetalleVenta;
use App\Http\Requests;
use Illuminate\Http\Request;

use Illuminate\Support\Collection;
//use Illuminate\Http\Response;
use App\Http\Requests\VentaFormRequest;
use Illuminate\Database\MySqlConnection;
use Illuminate\Support\Facades\Redirect;
use App\Caja;


class VentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request) {
            $title='Ventas';
            $query = trim($request->get('buscarTexto'));
            $ventas = DB::table('venta as v')
                ->join('personas as p', 'v.idcliente', '=', 'p.id')
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
        $cajaSessionid =  Sessioncaja::where('estado', 'Abierta')->orderBy('id', 'desc')->first();
        // dd($cajaSessionid);
        $Caja = Caja::where("estado","=",'Abierta')->where("sessioncaja_id","=", $cajaSessionid->id)->first();
        // dd($Caja);

        $tasa = Tasa::find(1);
        $tasa->updated_at;
        $fechaActual = Carbon::now();

        if ($tasa->updated_at->diffInHours($fechaActual) >= 6 ) {
            return redirect()
            ->route('tasa.index')
            ->with('status_danger', '¡Debes Actualizar el margen de gananacia para poder acceder!');
        }else{

            if ($Caja) {
                $title='Nueva venta';
            $personas = DB::table('personas')->where('tipo_persona', '=', 'Cliente')->get();
            $tasaDolar = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Dolar')->first();
            $tasaPeso = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Peso')->first();
            $tasaTransferenciaPunto = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Transferencia_Punto')->first();
            $tasaMixto = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Mixto')->first();
            $tasaEfectivo = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Efectivo')->first();
            $articulos = DB::table('articulos as art')
                ->select(DB::raw('CONCAT(art.codigo, " - ", art.nombre) AS articulo'), 'art.id', 'precio_costo', 'stock', 'art.nombre')
                ->where('art.estado', '=', 'Activo')
                ->where('art.stock', '>', '0')
                ->get();

                $caja = Caja::where("estado","=",'Abierta')->where("sessioncaja_id","=", $cajaSessionid->id)->first();
            $ventas = DB::table('venta as v')
            ->join('personas as p', 'v.idcliente', '=', 'p.id')
            ->join('detalle_venta as dv', 'v.idventa', '=', 'dv.idventa')
            ->select('v.idventa', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.total_venta', 'v.estado')
            ->orderBy('v.idventa', 'desc')
            ->groupBy('v.idventa', 'v.fecha_hora', 'p.nombre', 'v.tipo_comprobante', 'v.serie_comprobante', 'v.num_comprobante', 'v.total_venta', 'v.estado')
            ->paginate(7);
                // dd($tasaTransferenciaPunto);
                // return $personas;

            return view('ventas.venta.create', compact('caja', 'ventas','title','personas','tasaDolar','tasaPeso','tasaTransferenciaPunto','tasaMixto','tasaEfectivo','articulos'));
            }
            return redirect()
            ->route('caja.index')
            ->with('status_danger', '¡Debes crear una caja para poder acceder!');
        }

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
