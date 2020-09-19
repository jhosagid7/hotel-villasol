<?php

namespace App\Http\Controllers;

use App\Venta;
use App\Articulo;
use App\Articulo_venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    //Reporte de productos vendisos por fecha con margen de utilidad
    public function getReportVentasIndex(){
        // $ventas=DB::table('ventas')
        //     ->join('articulo_ventas', 'ventas.id', '=', 'articulo_ventas.venta_id')
        //     ->join('articulos', 'articulo_ventas.articulo_id', '=', 'articulos.id')
            // ->where('despachos.id_cliente', '=', $id)
            //  ->whereBetween('despachos.fecha', array($fechain,$fechater))
            // ->select('ventas.tipo_comprobante','ventas.serie_comprobante','ventas.fecha_hora','ventas.tipo_pago',DB::raw('sum(articulo_ventas.cantidad*articulo_ventas.precio_costo_unidad) as precio'),DB::raw('sum(articulo_ventas.cantidad*articulo_ventas.precio_venta_unidad) as total'))
            // ->groupBy('ventas.id')
            // ->get();
        // return $ventas;
        $title = 'Reporte de Productos Vendidos';
        $articulos = Articulo_venta::join('articulos', 'articulo_ventas.articulo_id', '=', 'articulos.id')
        ->select('articulo_ventas.id','articulos.codigo','articulos.nombre','cantidad','articulo_ventas.precio_costo_unidad','articulo_ventas.precio_venta_unidad','articulo_ventas.descuento','articulo_ventas.created_at',DB::raw('sum(articulo_ventas.cantidad*articulo_ventas.precio_costo_unidad) as precio_costo_total'),DB::raw('sum(articulo_ventas.cantidad*articulo_ventas.precio_venta_unidad) as precio_venta_total'))
        ->groupBy('articulo_ventas.id','articulos.codigo','articulos.nombre','cantidad','articulo_ventas.precio_costo_unidad','articulo_ventas.precio_venta_unidad','articulo_ventas.descuento','articulo_ventas.created_at')
        ->get();


        // return $articulos;

        return view('reportes.ventas.index', ["title" => $title,"articulos" => $articulos]);
    }
}
// "id": 1,
// "cantidad": 1,
// "precio_costo_unidad": "11.00",
// "precio_venta_unidad": "13.20",
// "descuento": "0.00",
// "articulo_id": 1,
// "venta_id": 1,
// "created_at": "2020-09-16T07:27:00.000000Z",
// "updated_at": "2020-09-18T23:36:08.000000Z",
// $queries = DB::getQueryLog();
// $last_query = end($queries);
