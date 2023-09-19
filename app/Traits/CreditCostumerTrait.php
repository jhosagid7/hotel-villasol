<?php

namespace App\Traits;

use App\Credito;
use App\Detalle_credito;
use Carbon\Carbon;

trait CreditCostumerTrait
{
    public function checkDateCredit()
    {
        $date   = Carbon::now('America/Caracas');

        $creditos_clientes = Credito::get();
        if ($creditos_clientes) {

            foreach ($creditos_clientes as $fecha_limite) {

                $now = Carbon::parse($date);
                $second = Carbon::parse($fecha_limite->fecha_limite_pago);

                if ($second->gte($now)) {
                    $credito_id = $fecha_limite->id;
                    $upCredito = Credito::findOrFail($credito_id);
                    if ($upCredito->total_deuda > 0) {
                        $upCredito->estado_credito = 'Moroso';
                        $upCredito->update();
                    } else {
                        $upCredito->estado_credito = 'Activo';
                        $upCredito->update();
                    }

                    $upCredito->estado_credito = 'Activo';
                    $upCredito->update();
                } else {
                    $credito_id = $fecha_limite->id;
                    $upCredito = Credito::findOrFail($credito_id);

                    $upCredito->estado_credito = 'Moroso';
                    $upCredito->update();


                    if ($upCredito->total_deuda > 0) {
                        $upCredito->estado_credito = 'Moroso';
                        $upCredito->update();
                    } else {
                        $upCredito->estado_credito = 'Activo';
                        $upCredito->update();
                    }
                }
            }
        }

        $detalle_creditos = Detalle_credito::get();

        foreach ($detalle_creditos as $detalle_credito) {
            if ($date >= $detalle_credito->fecha_vencimiento) {
                $detalle_credito_id = $detalle_credito->id;
                $upDetalleCredito = Detalle_credito::findOrFail($detalle_credito_id);
                if ($upDetalleCredito->estado_pago == 'Pendiente') {
                    $upDetalleCredito->estado_credito = 'Vencido';
                    $upDetalleCredito->update();
                }
            }
        }
    }

    // public function checkDateCredit()
    // {
    //     $date   = Carbon::now('America/Caracas');
    //     $creditos = Credito::whereBetween('fecha_limite_pago', [$date->subDay(), $date])->pluck('id');

    //     foreach ($creditos as $credito) {
    //         $detalle_credito = Detalle_credito::where('credito_id', $credito->id)->where('estado_pago', 'Pendiente')->first();

    //         if ($detalle_credito) {
    //             $credito->estado_credito = 'Vencido';
    //             $credito->update();
    //         }
    //     }
    // }
}
