<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\ArticuloFormRequest;
use App\Articulo;
use Illuminate\support;
use DB;

class ArticuloController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        if ($request) {
            $query = trim($request->get('buscarTexto'));
            $tasaDolar = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Dolar')->first();
            $tasaPeso = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Peso')->first();
            $tasaTransferenciaPunto = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Transferencia_Punto')->first();
            $tasaMixto = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Mixto')->first();
            $tasaEfectivo = DB::table('tasas')->where('estado', '=', 'Activo')->where('nombre', '=', 'Efectivo')->first();
            $articulos = DB::table('articulos as a')
            ->join('categorias as c', 'a.categoria_id', '=', 'c.id')
            ->where('a.nombre', 'LIKE', '%' . $query . '%')
            ->orwhere('a.codigo', 'LIKE', '%' . $query . '%')
            ->orwhere('a.estado', 'LIKE', '%' . $query . '%')
            ->orwhere('c.nombre', 'LIKE', '%' . $query . '%')
            ->select('a.id', 'a.codigo', 'a.nombre', 'a.stock', 'a.precio_costo', 'a.unidades', 'a.descripcion', 'a.imagen', 'a.estado', 'c.nombre as categoria')
            ->orderBy('id', 'desc')
            ->get();

            return view('almacen.articulo.index', ["tasaDolar" => $tasaDolar,"tasaPeso" => $tasaPeso,"tasaTransferenciaPunto" => $tasaTransferenciaPunto,"tasaMixto" => $tasaMixto,"tasaEfectivo" => $tasaEfectivo,"articulos" => $articulos, "buscarTexto" => $query]);
        }
    }
    public function create()
    {
        $categorias = DB::table('categorias')->where('condicion', '=', 'Activa')->get();
        return view('almacen.articulo.create', ['categorias'=>$categorias]);
    }
    public function store(Request $request)
    {
        // return $request->all();
        //creamos un objeto del modelo categoria
        $articulo = new Articulo;
        $articulo->categoria_id  = $request->get('categoria_id');
        $articulo->codigo       = $request->get('codigo');
        $articulo->nombre       = $request->get('nombre');
        $articulo->stock         = 0;
        $articulo->unidades         = $request->get('unidades');
        $articulo->vender_al         = $request->get('vender_al');
        $articulo->precio_costo         = 0;
        $articulo->descripcion  = $request->get('descripcion');

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $file->move(public_path(). '/imagenes/articulos/', $file->getClientOriginalName('imagen'));
            $articulo->imagen   = $file->getClientOriginalName('imagen');
        }

        $articulo->estado = 'Activo';

        $articulo->save();

        return Redirect::to('almacen/articulo')->with('status_success', 'Articulo registrado exitosamente');
    }


    public function show($id)
    {
        return view("almacen.articulo.show", ["articulo" => Articulo::findOrFail($id)]);
    }


    public function edit($id)
    {
        $articulo = Articulo::findOrFail($id);
        $categorias = DB::table('categorias')->where('condicion', '=', 'Activa')->get();
        return view("almacen.articulo.edit", ["articulo" => $articulo, 'categorias'=> $categorias]);
    }
    public function update(Request $request, $id)
    {
        $articulo = Articulo::findOrFail($id);
        $articulo->categoria_id  = $request->get('categoria_id');
        $articulo->codigo       = $request->get('codigo');
        $articulo->nombre       = $request->get('nombre');
        $articulo->precio_costo         = $request->get('precio_costo');
        $articulo->descripcion  = $request->get('descripcion');
        $articulo->unidades         = $request->get('unidades');
        $articulo->vender_al         = $request->get('vender_al');

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $file->move(public_path() . '/imagenes/articulos', $file->getClientOriginalName('imagen'));
            $articulo->imagen   = $file->getClientOriginalName('imagen');
        }

        $articulo->update();

        return Redirect::to('almacen/articulo')->with('status_success', 'El Artículo fue actualizado exitosamente');
    }
    public function destroy($id)
    {
        // dd('hola');
        $articulo = Articulo::findOrFail($id);
        $articulo->estado = 'Inactivo';
        $articulo->update();

        return redirect()
        ->route('articulo.index')
        ->with('status_success', 'El Artículo fue Eliminado exitosamente');

        // return Redirect::to('almacen/articulo')->with('El Artículo fue Eliminado exitosamente');
    }
}
