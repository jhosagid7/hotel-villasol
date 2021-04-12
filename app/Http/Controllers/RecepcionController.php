<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Tasa;
use App\User;
use App\Level;
use App\Venta;
use App\Precio;
use App\Horario;
use App\Persona;
use App\Habitacione;
use App\Sessioncaja;
use App\Denominacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RecepcionController extends Controller
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
    public function index()
    {
        $title = 'Recepción';

        $levels = Level::orderBy('id','desc')->get();
        $horarios = Horario::get();
        $habitaciones = Habitacione::get();
        $tasaDolarHabitacion = Tasa::where('nombre','=','DolarHabitacion')->first();
        // return $tasaDolarHabitacion->tasa;
        $tasaPesoHabitacion = Tasa::where('nombre','=','PesoHabitacion')->first();
        $users = User::with('roles')->orderBy('id','Desc')->get();
// return $users->roles[0]->name;



        return view('servicios.recepcion.index', compact('title','levels','habitaciones','horarios', 'tasaDolarHabitacion', 'tasaPesoHabitacion', 'users'));
    }

    public function getPrecio(Request $request){
        // return $request;
        if ($request->ajax()) {
            $precio = Precio::where('cat_id', $request->cat_id)
                ->where('horario_id', $request->horario_id)
                ->get();


                // return $origenesArtArray;
                return response()->json($precio);
        }
    }
    public function getCliente(Request $request)
{
    $term = $request->term;
    // return $term;
    if ($request->ajax()) {
    $clientes = Persona::where('nombre', 'LIKE','%'.$request->term.'%')
    ->where('horario_id', $request->horario_id)
    ->get();

    // foreach ($queries as $query)
    // {
    //     $results[] = ['id' => $query->id, 'value' => $query->nombre]; //you can take custom values as you want
    // }
    // dd( $results);
return response()->json($clientes);
}
}


    public function proceso(Request $request)
    {

        // return $request;
        $title = 'PROCESAR HABITACIÓN';
        $denominacion_dolar = Denominacion::where('moneda', 'Dolar')->orderBy('id', 'desc')->get();
        $levels = Level::orderBy('id','desc')->get();$request->get('tipo_documento');
        $horario = Horario::where('id',$request->get('horario_id'))->first();
        // return $horario;
        $clientes = Persona::get();
        $precio = Precio::where('id',$request->get('precio_id'))->first();
        // return $horario;
        $habitacion = Habitacione::where('id',$request->get('habitacion_id'))->first();
        // return $habitacion;
        $tasaDolarHabitacion = Tasa::where('nombre','=','DolarHabitacion')->first();
        // return $tasaDolarHabitacion->tasa;
        $tasaPesoHabitacion = Tasa::where('nombre','=','PesoHabitacion')->first();
        $tasaDolar = DB::table('tasas')->where('nombre', '=', 'Dolar')->first();
        $tasaPeso = DB::table('tasas')->where('nombre', '=', 'Peso')->first();
        $tasaTransferenciaPunto = DB::table('tasas')->where('nombre', '=', 'Transferencia_Punto')->first();
        $tasaMixto = DB::table('tasas')->where('nombre', '=', 'Mixto')->first();
        $tasaEfectivo = DB::table('tasas')->where('nombre', '=', 'Efectivo')->first();
        $users = User::with('roles')->orderBy('id','Desc')->get();

        $articulos = DB::table('articulos as art')
                        ->select(DB::raw('CONCAT(art.codigo, " - ", art.nombre) AS articulo'), 'art.imagen', 'art.vender_al', 'art.nombre','art.id', 'precio_costo', 'porEspecial', 'isDolar', 'isPeso', 'isTransPunto', 'isMixto', 'isEfectivo', 'isKilo', 'stock', 'art.nombre')
                        ->where('art.estado', '=', 'Activo')
                        ->where('art.stock', '>', '0')
                        ->where('art.precio_costo', '>', '0')
                        ->get();

        $UserId = Auth::id();
        $cajaSessionid =  Sessioncaja::where('estado', 'Abierta')->orderBy('id', 'desc')->first();
        $caja = Caja::where("estado","=",'Abierta')->where("sessioncaja_id","=", $cajaSessionid->id)->first();
        $ventaNum = Venta::latest('id')->first();
        if (is_null($ventaNum)) {

            $num_comprobante = Sessioncaja::numCodigo('C', $UserId, 1);
            $serie_comprobante = Sessioncaja::numCodigo('N', $UserId, 1);
            // dd($serie_comprobante);
        }else{
            $num_comprobante = Sessioncaja::numCodigo('C', $UserId, $ventaNum->id+1);
            $serie_comprobante = Sessioncaja::numCodigo('N', $UserId, $ventaNum->id+1);
        }
// return $users->roles[0]->name;
// return $habitacion;

        // return redirect()->route('proceso', array('title' => $title,'levels' => $levels,'habitacion' => $habitacion,'horarios' => $horarios, 'tasaDolarHabitacion' => $tasaDolarHabitacion, 'tasaPesoHabitacion' => $tasaPesoHabitacion, 'users' => $users));

        return view('servicios.procesos.index', compact('articulos','num_comprobante','serie_comprobante','UserId','caja','ventaNum','tasaDolar','tasaPeso','tasaTransferenciaPunto','tasaMixto','tasaEfectivo','denominacion_dolar','title','levels','habitacion','horario', 'tasaDolarHabitacion', 'tasaPesoHabitacion', 'users', 'clientes','precio'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Recepcion  $resepcion
     * @return \Illuminate\Http\Response
     */
    public function show(Recepcion $resepcion)
    {
        //
    }

    public function registrarHabitacion(Recepcion $resepcion){
        return $request;
    }


}
