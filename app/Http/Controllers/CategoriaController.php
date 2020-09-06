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
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
            // return 'index categoria';

        if($request){
            // $query=trim($request->get("buscarTexto"));
            $categorias=Categoria::where("condicion","=","Activa")
            ->orderBy("id","desc")
            ->get();
            return view("almacen.categoria.index",["categorias"=>$categorias]);
        }

    }
    public function create()
    {
        return view('almacen.categoria.create');
    }
    public function store(CategoriaFormRequest $request)
    {
        // return 'store categoria';
        //creamos un objeto del modelo categoria
        $categoria = new Categoria;
        $categoria->nombre = $request->get('nombre');
        $categoria->descripcion = $request->get('descripcion');
        $categoria->condicion = 'Activa';
        $categoria->save();

        return Redirect::to('almacen/categoria')->with('status_success', 'Categoria registrada exitosamente');
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

        return redirect()
        ->route('categoria.index')
        ->with('status_success', 'Categoria actualizada exitosamente');


    }
    public function destroy($id)
    {
        // dd('categoria');
        $categoria = Categoria::findOrFail($id);
        $categoria->condicion = 'Eliminada';
        $categoria->update();
        return redirect()
        ->route('categoria.index')
        ->with('status_success', 'Categoria eliminada exitosamente');

    }

}
