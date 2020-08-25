<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\IngresoFormRequest;
use App\Ingreso;
use App\Articulo;
use App\DetalleIngreso;
use App\DetalleVenta;
use Illuminate\Database\MySqlConnection;
use DB;

use Carbon\Carbon;
//use Illuminate\Http\Response;
use Response;
use Illuminate\Support\Collection;

class IngresoController extends Controller
{
    public function __construct()
    {

    }
    public function index(Request $request)
    {
        if ($request) {
            $title='Ingresos';
            $query = trim($request->get('buscarTexto'));
            $ingresos = DB::table('ingreso as i')
                ->join('persona as p', 'i.idproveedor', '=', 'p.idpersona')
                ->join('detalle_ingreso as di', 'i.idingreso', '=', 'di.idingreso')
                ->select('i.idingreso', 'i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.serie_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado', DB::raw('sum(di.cantidad*precio_compra) as total'))
                ->where('i.num_comprobante', 'LIKE', '%'. $query  .'%')
                ->orderBy('i.idingreso', 'desc')
                ->groupBy('i.idingreso', 'i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.serie_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado')
                ->get();

            return view('compras.ingreso.index', ["title"=>$title,"ingresos" => $ingresos, "buscarTexto" => $query]);
        }
    }

    public function create()
    {
        $personas = DB::table('persona')->where('tipo_persona', '=', 'Proveedor')->get();
        $articulos = DB::table('articulo as art')
            ->select(DB::raw('CONCAT(art.codigo, " ", art.nombre) AS articulo'), 'art.idarticulo')
            ->where('art.estado', '=', 'Activo')
            ->get();

        return view('compras.ingreso.create', ["personas" => $personas, "articulos" => $articulos]);
    }

    public function store(IngresoFormRequest $request)
    {
        // return $request->all

        try{
            DB::beginTransaction();
            $ingreso = new Ingreso; //(*) al guardar genera un idingreso automatimanente que luego se usa en la tabla detalle
            $ingreso->idproveedor = $request->get('idproveedor');
            $ingreso->tipo_comprobante = $request->get('tipo_comprobante');
            $ingreso->serie_comprobante = $request->get('serie_comprobante');
            $ingreso->num_comprobante = $request->get('num_comprobante');


            $myTime = Carbon::now('America/Caracas');
            $ingreso->fecha_hora = $myTime->toDateTimeString();
            $ingreso->impuesto = '12';
            $ingreso->estado = 'A';
            $ingreso->save();

            //cargamos los datos del detalle del ingreso en unas variables que reciven
            //un array

            $idarticulo = $request->get('idarticulo');
            $cantidad = $request->get('cantidad');
            $precio_compra = $request->get('precio_compra');
            $precio_venta = $request->get('precio_venta');

            //creamos un contador
            $cont = 0;

            //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
            while ($cont < count($idarticulo)) {

                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->idingreso;//este idingreso se autogenera cuando se crea el objeto en la parte superior (*)
                $detalle->idarticulo = $idarticulo[$cont];
                $detalle->cantidad = $cantidad[$cont];
                $detalle->precio_compra = $precio_compra[$cont];
                $detalle->precio_venta = $precio_venta[$cont];
                $detalle->save();

                $cont = $cont+1;
            }

            DB::commit();

        }catch(\Exception $e)
        {

            DB::rollback();
            // dd($e);
        }

        return Redirect::to('compras/ingreso')->with('success', 'El Ingreso fué registrado exitosamente');
    }

    public function show($id)
    {
        $ingreso = DB::table('ingreso as i')
            ->join('persona as p', 'i.idproveedor', '=', 'p.idpersona')
            ->join('detalle_ingreso as di', 'i.idingreso', '=', 'di.idingreso')
            ->select('i.idingreso', 'i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.serie_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado', DB::raw('sum(di.cantidad*precio_compra) as total'))
            ->where('i.idingreso', '=', $id)
            ->groupBy('i.idingreso', 'i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.serie_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado')

            ->first();

        //traemos los datos de la tabla detalle_articulos
        $detalles = DB::table('detalle_ingreso as d')
            ->join('articulo as a', 'd.idarticulo', '=', 'a.idarticulo')
            ->select('a.nombre as articulo', 'd.cantidad', 'd.precio_compra', 'd.precio_venta')
            ->where('d.idingreso', '=', $id)->get();

        return view("compras.ingreso.show", ["ingreso" => $ingreso, "detalles"=> $detalles]);
    }

    public function destroy($id)
    {
        try{
            DB::beginTransaction();
        $ingreso = Ingreso::findOrFail($id);
        $ingreso->estado = 'C';
        $ingreso->update();

        $detalleIngreso = DetalleIngreso::where('idingreso','=',$id)->get();

         //creamos un contador
         $cont = 0;

         //ahora creamos un bucle while para ir recorriendo los arrays que estamo enviando
         while ($cont < count($detalleIngreso)) {
            $idarticulo = $detalleIngreso[$cont]->idarticulo;
            $articulo = Articulo::findOrFail($idarticulo);
            $dingre = DetalleIngreso::where('idarticulo','=',$idarticulo)->orderBy('idingreso', 'desc')->first();
            $idingreso= $dingre->iddetalle_ingreso - 1;
            $dingreP = DetalleIngreso::findOrFail($idingreso);

            $articulo->precio_venta = $articulo->precio_venta - $dingre->precio_venta;
            // return $articulo->precio_venta;
            $articulo->stock = $articulo->stock-$detalleIngreso[$cont]->cantidad;
            $articulo->update();

            $cont = $cont+1;

         };
         DB::commit();

        }catch(\Exception $e)
        {

            DB::rollback();
            dd($e);
        }

        return Redirect::to('compras/ingreso');
    }
}
