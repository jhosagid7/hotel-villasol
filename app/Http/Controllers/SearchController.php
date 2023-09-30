<?php

namespace App\Http\Controllers;

use App\Tasa;
use App\Persona;
use App\Articulo;
use Carbon\Carbon;
use App\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    // public function get_add_to_message_search(){
    // $term = Input::get( 'term' );
    // $users = array();
    // $search = DB::query("
    //     select *
    //     from users
    //     where id!='" . Auth::user()->id . "'
    //     and status=1
    //     and type='user'
    //     and match(name, email, username)
    //     against('+{$term}*' IN BOOLEAN MODE)
    //     ");

    //     foreach( $search as $results => $user )
    //     {
    //         $users[] = array(
    //             'id' => $user->id,
    //             'value' => $user->name,
    //         );
    //     }
    //     return json_encode( $users );
    // }

    // coment...
    public function personas(Request $request)
    {
        $term = $request->get('term');

        // $querys = Persona::WhereFulltext(['nombre'], $term)->get();

        $querys = Persona::query()
            ->when($term ?? false, function ($query, $term) {
                $query
                ->with('creditos', 'excedentes')
                    ->whereFullText(['nombre'], $term)
                    ->orWhereFullText(['num_documento'], $term)
                    ->orderBy('nombre', 'Asc');
            })->get();

        $data = [];

        foreach ($querys as $query) {
            $creditos = $query->creditos; // Obtener la colección de objetos de crédito relacionados con la persona
            $excedentes = $query->excedentes; // Obtener la colección de objetos de crédito relacionados con la persona
            $total_deuda = 0; // Variable para almacenar la suma de las deudas de los créditos
            $estado_credito = ''; // Variable para almacenar la suma de las deudas de los créditos
            $dispExcedente = 0; // Variable para almacenar la suma de las deudas de los créditos

            foreach ($creditos as $credito) {
                $total_deuda += $credito->total_deuda; // Sumar la deuda de cada crédito
                $estado_credito = $credito->estado_credito; // Sumar la deuda de cada crédito
            }
            foreach ($excedentes as $excedente) {
                $dispExcedente = $excedente->excedente; // Sumar la deuda de cada crédito
            }

            $data[] = [
                // 'label' => $query->nombre . ' - ' . $query->num_documento,
                'label' => $query->nombre. ' - ' . $total_deuda. ' - ' . $estado_credito. ' - ' . $dispExcedente,
                'id' => $query->id,
                'tipo_persona' => $query->tipo_persona,
                'nombre' => $query->nombre,
                'num_documento' => $query->num_documento,
                'direccion' => $query->direccion,
                'telefono' => $query->telefono,
                'email' => $query->email,
                'isCortesia' => $query->isCortesia,
                'isCredito' => $query->isCredito,
                'limite_fecha' => $query->limite_fecha,
                'limite_monto' => $query->limite_monto,
                'total_deuda' => $total_deuda,
                'estado_credito' => $estado_credito,
                'dispExcedente' => $dispExcedente
            ];
        };

        return $data;
    }


    public function articulos(Request $request)
    {
        $term = $request->get('term');
        // $term = 'TRIDENT chicle';
        $term = 'TRIDENT chicle';

        // $querys = Articulo::whereFullText(['codigo'], 'TRIDENT chicle')->get();
        // $querys = Articulo::whereFullText(['nombre'], $term)->orWhereFullText(['codigo'], $term)->get();
        // $querys = DB::table('articulos')->whereFulltext(['codigo'], '7591016854648', ['expanded' => true])->get();
        $querys = Articulo::query()
            ->with('categoria')
            ->when($term ?? false, function ($query, $term) {
                $query->where('estado', '=', 'Activo')
                    ->where('vender_al', 'Detal')
                    ->whereFullText(['nombre'], $term)
                    ->orWhereFullText(['codigo'], $term)
                    ->orderBy('created_at', 'Desc');
            })->get();

        $data = [];

        foreach ($querys as $query) {
            $data[] = [
                'label' => $query->nombre . ' - ' . $query->codigo . ' - ' . $query->stock . ' - ' . $query->vender_al,
                'codigo' => $query->codigo,
                'id' => $query->id,
                'stock' => $query->stock,
                'precio_costo' => number_format((float)round($query->precio_costo, PHP_ROUND_HALF_DOWN), 3, '.', ','),
                'nombre' => $query->nombre,
                'porEspecial' => $query->porEspecial,
                'isDolar' => $query->isDolar,
                'isPeso' => $query->isPeso,
                'isTransPunto' => $query->isTransPunto,
                'isMixto' => $query->isMixto,
                'isEfectivo' => $query->isEfectivo,
                'isKilo' => $query->isKilo
            ];
        };

        return $data;
    }



    public function articulosVentas(Request $request)
    {
        $term = $request->get('term');

        $querys = Articulo::query()
            ->when($term ?? false, function ($query, $term) {
                $query->where('estado', '=', 'Activo')
                    ->where('vender_al', 'Detal')
                    ->where('stock', '>', '0')
                    ->where('precio_costo', '>', '0')
                    ->whereFullText(['nombre'], $term)
                    ->orWhereFullText(['codigo'], $term)
                    ->orderBy('id', 'Desc');
            })->get();



        $tasaDolar = Tasa::where('estado', '=', 'Activo')->where('nombre', '=', 'Dolar')->first();

        $data = [];

        foreach ($querys as $query) {
            $data[] = [
                'label' => $query->nombre . ' - stock: ' . $query->stock . ' - Precio: $.' . number_format(($this->redondeado($query->precio_costo, 3) * (1 + ($tasaDolar->porcentaje_ganancia / 100))), 3, '.', ','),
                'codigo' => $query->codigo,
                'id' => $query->id,
                'stock' => $query->stock,
                'precio_costo' => $this->redondeado($query->precio_costo, 3),
                // 'precio_costo' => number_format((float)round( $query->precio_costo, PHP_ROUND_HALF_DOWN),3,'.',','),
                'nombre' => $query->nombre,
                'porEspecial' => $query->porEspecial,
                'isDolar' => $query->isDolar,
                'isPeso' => $query->isPeso,
                'isTransPunto' => $query->isTransPunto,
                'isMixto' => $query->isMixto,
                'isEfectivo' => $query->isEfectivo,
                'isKilo' => $query->isKilo
            ];
        };

        return $data;
    }



    public function articulosCargos(Request $request)
    {

        $tasaDolar = Tasa::where('estado', '=', 'Activo')->where('nombre', '=', 'Dolar')->first();
        $term = $request->get('term');

        $querys = Articulo::query()
            ->with('categoria')
            ->when($term ?? false, function ($query, $term) {
                $query->where('estado', '=', 'Activo')
                    ->where('vender_al', 'Detal')
                    ->whereFullText(['nombre'], $term)
                    ->orWhereFullText(['codigo'], $term)
                    ->orderBy('created_at', 'Desc');
            })->get();




        $data = [];

        foreach ($querys as $query) {
            $data[] = [
                'label' => $query->nombre . ' - Precio: $.' . $query->precio_costo * (1 + ($tasaDolar->porcentaje_ganancia / 100)) . ' - ' . $query->stock . ' - ' . $query->vender_al,
                'codigo' => $query->codigo,
                'id' => $query->id,
                'stock' => $query->stock,
                // 'precio_costo' => number_format((float)round( $query->precio_costo, PHP_ROUND_HALF_DOWN),3,'.',','),
                'precio_costo' => $query->precio_costo * (1 + ($tasaDolar->porcentaje_ganancia / 100)),
                'nombre' => $query->nombre,
                'category' => $query->categoria->nombre,
                'vender_al' => $query->vender_al

            ];
        };

        return $data;
    }

    public function redondeado($numero, $decimales)
    {
        $factor = pow(10, $decimales);
        return (round($numero * $factor) / $factor);
    }

    public function showFiltered()
    {
        // Obtén la fecha actual
        $fechaActual = Carbon::now()->toDateString();
        // return  $fechaActual;

        // Realiza el filtrado de los registros según los criterios
        $registrosFiltrados = Reservation::whereDate('start', '=', $fechaActual)
                      ->where('status', 'Pendiente')
                      ->get();
        // return  $registrosFiltrados;

        // Devuelve los registros filtrados en formato JSON
        return response()->json($registrosFiltrados);
    }

    public function saveNumberService(Request $request)
    {
        // Obtener el número de servicio del request
        $numServicio = $request->input('numServicio');
        $reservationId = $request->input('reservationId');

        // Guardar el número de servicio en la tabla "Reservations"

        $upReservation = Reservation::findOrFail($reservationId);
        if ($upReservation) {
            $upReservation->numServicio = $numServicio;
            $upReservation->status = 'Procesado';
            $upReservation->color = '#118F00';
            $upReservation->update();
        }


        // Retornar una respuesta de éxito

        return response()->json(['msg' => 'Numero de servicio guardado con exito...', 'type' => 'success']);
        // return response()->json(['message' => 'Número de servicio guardado satisfactoriamente']);
    }

}
