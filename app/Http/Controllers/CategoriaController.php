<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\CategoriaFormRequest;
use DB;


class CategoriaController extends Controller
{
    public function __construct()
    {

    }
    public function index(Request $request)
    {

        if($request){
            // $query=trim($request->get("buscarTexto"));
            $categorias=Categoria::where("condicion","=","1")
            ->orderBy("idcategoria","desc")
            ->get();
            return view("almacen.categoria.index",["categorias"=>$categorias]);
        }
        // if ($request) {
        //     $query = trim($request->get('buscarTexto'));
        //     $categorias = DB::table('categoria')->where('nombre', 'LIKE', '%'. $query .'%')
        //     ->where('condicion', '=', '1')
        //     ->orderBy('idcategoria', 'desc')
        //     ->paginate(7);

        //     return view('almacen.categoria.index', ["categorias" => $categorias, "buscarTexto" => $query]);
        // }
    }
    public function create()
    {
        return view('almacen.categoria.create');
    }
    public function store(CategoriaFormRequest $request)
    {
        //creamos un objeto del modelo categoria
        $categoria = new Categoria;
        $categoria->nombre = $request->get('nombre');
        $categoria->descripcion = $request->get('descripcion');
        $categoria->condicion = '1';
        $categoria->save();

        return Redirect::to('almacen/categoria')->with('success', 'Categoria registrada exitosamente');
    }
    public function show($id)
    {
        return view("almacen.categoria.show", ["categoria" => Categoria::findOrFail($id)]);
    }
    public function edit($id)
    {
        return view("almacen.categoria.edit", ["categoria" => Categoria::findOrFail($id)]);
    }
    public function update(CategoriaFormRequest $request,$id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->nombre = $request->get('nombre');
        $categoria->descripcion = $request->get('descripcion');
        $categoria->update();

        return Redirect::to('almacen/categoria')->with('success', 'Categoria actualizada exitosamente');
    }
    public function destroy($id)
    {
        // dd('categoria');
        $categoria = Categoria::findOrFail($id);
        $categoria->condicion = '0';
        $categoria->update();

        return Redirect::to('almacen/categoria');
    }

}
