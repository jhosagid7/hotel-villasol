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

    }


    public function index(Request $request)
    {
        if ($request) {
            $query = trim($request->get('buscarTexto'));
            $articulos = DB::table('articulo as a')
            ->join('categoria as c', 'a.idcategoria', '=', 'c.idcategoria')
            ->where('a.nombre', 'LIKE', '%' . $query . '%')
            ->orwhere('a.codigo', 'LIKE', '%' . $query . '%')
            ->orwhere('a.estado', 'LIKE', '%' . $query . '%')
            ->orwhere('c.nombre', 'LIKE', '%' . $query . '%')
            ->select('a.idarticulo', 'a.codigo', 'a.nombre', 'a.stock', 'a.descripcion', 'a.imagen', 'a.estado', 'c.nombre as categoria')
            ->orderBy('idarticulo', 'desc')
            ->get();

            return view('almacen.articulo.index', ["articulos" => $articulos, "buscarTexto" => $query]);
        }
    }
    public function create()
    {
        $categorias = DB::table('categoria')->where('condicion', '=', '1')->get();
        return view('almacen.articulo.create', ['categorias'=>$categorias]);
    }
    public function store(ArticuloFormRequest $request)
    {
        // return $request->all();
        //creamos un objeto del modelo categoria
        $articulo = new Articulo;
        $articulo->idcategoria  = $request->get('idcategoria');
        $articulo->codigo       = $request->get('codigo');
        $articulo->nombre       = $request->get('nombre');
        $articulo->stock         = $request->get('stock');
        $articulo->descripcion  = $request->get('descripcion');

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $file->move(public_path(). '/imagenes/articulos/', $file->getClientOriginalName('imagen'));
            $articulo->imagen   = $file->getClientOriginalName('imagen');
        }

        $articulo->estado = 'Activo';

        $articulo->save();

        return Redirect::to('almacen/articulo')->with('success', 'Articulo registrado exitosamente');
    }
    public function show($id)
    {
        return view("almacen.articulo.show", ["articulo" => Articulo::findOrFail($id)]);
    }
    public function edit($id)
    {
        $articulo = Articulo::findOrFail($id);
        $categorias = DB::table('categoria')->where('condicion', '=', '1')->get();
        return view("almacen.articulo.edit", ["articulo" => $articulo, 'categorias'=> $categorias]);
    }
    public function update(ArticuloFormRequest $request, $id)
    {
        $articulo = Articulo::findOrFail($id);
        $articulo->idcategoria  = $request->get('idcategoria');
        $articulo->codigo       = $request->get('codigo');
        $articulo->nombre       = $request->get('nombre');
        $articulo->stock         = $request->get('stock');
        $articulo->descripcion  = $request->get('descripcion');

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $file->move(public_path() . '/imagenes/articulos', $file->getClientOriginalName('imagen'));
            $articulo->imagen   = $file->getClientOriginalName('imagen');
        }

        $articulo->update();

        return Redirect::to('almacen/articulo')->with('success', 'El Artículo fue actualizado exitosamente');
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
