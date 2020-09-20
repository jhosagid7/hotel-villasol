<?php

namespace App\Http\Controllers;

use App\Venta;
use App\Articulo;
use App\Articulo_venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReporteController extends Controller
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
    public function index(Request $request)
    {
        // return $request->all();
        // $name = $request->get('name');
        $tipo = $request->get('tipo');
        // $description = $request->get('descripcion');
        $fecha = $request->get('fecha');

        // if($request){

        //     $categorias=Categoria::orderBy('id', 'DESC')
        //     ->name($name)
        //     ->condition($condition)
        //     ->description($description)
        //     ->fecha($fecha)
        //     ->get();
        //     return view("almacen.categoria.index",["categorias"=>$categorias]);
        // }

        $title = 'Reporte de Productos Vendidos';
        $articulos = Articulo_venta::join('articulos', 'articulo_ventas.articulo_id', '=', 'articulos.id')
        // ->name($name)
        ->tipo($tipo)
        // ->description($description)
        ->select('articulo_ventas.id','articulos.codigo','articulos.vender_al','articulos.nombre','articulo_ventas.cantidad','articulo_ventas.precio_costo_unidad','articulo_ventas.precio_venta_unidad','articulo_ventas.descuento','articulo_ventas.created_at',DB::raw('sum(articulo_ventas.cantidad*articulo_ventas.precio_costo_unidad) as precio_costo_total'),DB::raw('sum(articulo_ventas.cantidad*articulo_ventas.precio_venta_unidad) as precio_venta_total'))
        ->fecha($fecha)
        ->groupBy('articulo_ventas.id','articulos.codigo','articulos.vender_al','articulos.nombre','articulo_ventas.cantidad','articulo_ventas.precio_costo_unidad','articulo_ventas.precio_venta_unidad','articulo_ventas.descuento','articulo_ventas.created_at')
        ->get();


        // return $articulos;

        return view('reportes.ventas.index', ["title" => $title,"articulos" => $articulos]);
    }

    public function listadoInventario(){
        $title = 'Planilla de Inventario';
        $tasaDolar = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Dolar')->first();
            $tasaPeso = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Peso')->first();
            $tasaTransferenciaPunto = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Transferencia_Punto')->first();
            $tasaMixto = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Mixto')->first();
            $tasaEfectivo = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Efectivo')->first();
            $articulos = DB::table('articulos as a')
            ->join('categorias as c', 'a.categoria_id', '=', 'c.id')

            ->select('a.id', 'a.codigo', 'a.nombre', 'a.stock', 'a.precio_costo', 'a.unidades', 'a.descripcion', 'a.imagen', 'a.estado', 'c.nombre as categoria')
            ->orderBy('id', 'desc')
            ->get();
        // return $articulos;
        return view('reportes.inventario.listaInventario', compact('articulos','tasaDolar','tasaPeso','tasaTransferenciaPunto','tasaMixto','tasaEfectivo'));
    }

    public function listadoPrecio(){
        $title = 'Listado General de Precios';
        $tasaDolar = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Dolar')->first();
            $tasaPeso = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Peso')->first();
            $tasaTransferenciaPunto = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Transferencia_Punto')->first();
            $tasaMixto = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Mixto')->first();
            $tasaEfectivo = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Efectivo')->first();
            $articulos = DB::table('articulos as a')
            ->join('categorias as c', 'a.categoria_id', '=', 'c.id')

            ->select('a.id', 'a.codigo', 'a.nombre', 'a.stock', 'a.precio_costo', 'a.unidades', 'a.descripcion', 'a.imagen', 'a.estado', 'c.nombre as categoria')
            ->orderBy('id', 'desc')
            ->get();
        // return $articulos;
        return view('reportes.inventario.listaPrecio', compact('title','articulos','tasaDolar','tasaPeso','tasaTransferenciaPunto','tasaMixto','tasaEfectivo'));
    }

    public function reporteGeneral(){
        $title = 'Reporte General';
        $totalInversion = Articulo::where("estado","=",'Activo')
        ->select(DB::raw('sum(precio_costo*stock) as precio_costo_total'))
        ->get();

        $mayor = Articulo::where("estado","=",'Activo')
        ->where("vender_al","=",'Mayor')
        ->select(DB::raw('sum(precio_costo*stock) as totalMayor'),DB::raw('sum(unidades*stock) as totalUnidadesMayor'),DB::raw('sum(stock) as totalStockMayor'))
        ->get();

        $detal = Articulo::where("estado","=",'Activo')
        ->where("vender_al","=",'Detal')
        ->select(DB::raw('sum(precio_costo*stock) as totalDetal'),DB::raw('sum(unidades*stock) as totalUnidadesDetal'),DB::raw('sum(stock) as totalStockDetal'))
        ->get();
            $user = Auth::user();
        // return $totalInversion.$mayor.$detal;
        return view('reportes.inventario.general', compact('title', 'user','totalInversion','mayor','detal'));
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
