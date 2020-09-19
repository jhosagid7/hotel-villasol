<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venta;
use App\Articulo;
use App\Articulo_venta;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
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
