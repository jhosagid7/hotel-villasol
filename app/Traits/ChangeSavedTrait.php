<?php

namespace App\Traits;

use App\Venta;
use App\Persona;
use App\Servicio;
use App\Excedente;
use App\Horas_extra;
use App\Reintegro;
use Carbon\Carbon;
use App\PreExcedente;
use App\DetallePagoOficina;
use App\HistorialExcedente;
use Illuminate\Support\Facades\Auth;

trait ChangeSavedTrait
{
    public $amountToPay;
    public $surplusSaved;
    public $undeliveredChange;
    public $amountInCash;
    public $amount;

    /**
     * This function pays the amount with the change saved.
     *
     * @param float $change_saved_paid The amount of change saved paid.
     * @param float $amount The amount to pay.
     * @param int $costumer_id The customer ID.
     * @return float The amount paid, or 0 if the amount is less than the change saved.
     *
     * @author Jhonny Pirela (Jhosagid)
     * @date 2023-03-08
     *
     * This function first gets the amount of change saved for the customer with the specified ID. Then, it checks if the amount to pay is greater than the amount of change saved. If it is, the function returns the amount to pay minus the amount of change saved. If the amount to pay is less than the amount of change saved, the function returns the amount to pay. Otherwise, the function returns 0.
     */
    public function payWithChangeSaved(
        float $amount,
        string $serie_comprobante,
        string $tipo,
        int $caja_id,
        int $servicio_id,
        int $costumer_id,
        int $sale_id = 0,
        int $horasExtrasId = 0
    ): void {
        $surplusSaved = $this->getExcedentByCostumerId($costumer_id);
        // Check if there is a surplus saved for the customer
        if ($surplusSaved) {
            // If the amount is greater than or equal to the surplus saved
            if ($amount >= $surplusSaved->excedente) {
                $monto_pagado = $surplusSaved->excedente;
                // Update DetallePagoOficina table
                $DetallePagoOficina = new DetallePagoOficina([
                    'tipo_pago' => 'Efectivo',
                    'num_transaccion' => 'PAGOVENTAS',
                    'deuda' => $monto_pagado,
                    'saldo_pagado' => $monto_pagado,
                    'fecha_pago' => Carbon::now(),
                    'persona_id' => $costumer_id,
                    'caja_id' => $caja_id,
                    'user_id' => auth()->id(),
                ]);
                $DetallePagoOficina->save();
                // Update HistorialExcedente table
                $historialExcedentes = HistorialExcedente::where('persona_id', $costumer_id)
                    ->where('tipo_registro', 'Pago_por_oficina')
                    ->where('status', 'Pendiente')
                    ->get();
                if ($historialExcedentes) {
                    foreach ($historialExcedentes as $historialExcedente) {
                        $HistorialExcedente = HistorialExcedente::findOrFail($historialExcedente->id);
                        $HistorialExcedente->status = 'Pagado';
                        $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                        $HistorialExcedente->update();
                    }
                }
                // Remove the surplus record from the Excedente table
                $eliminarRegistroExcedente = Excedente::where('persona_id', $costumer_id)
                    ->where('tipo', 'Pagar_por_oficina')
                    ->first();

                if ($eliminarRegistroExcedente) {
                    Excedente::destroy($eliminarRegistroExcedente->id);
                }

                if($tipo == "Consumo"){
                    // Update Ventas table
                    $UpdateModel = Venta::findOrFail($sale_id);
                }

                if($tipo == "Servicio"){
                    // dd('$tipo == "Servicio"', $servicio_id);
                    // Update Ventas table
                    $servicio_id = Servicio::latest('created_at')->first();
                    $UpdateModel = Servicio::findOrFail($servicio_id->id);
                }

                if($tipo == "Horas_Extras"){
                    // Update Ventas table
                    $UpdateModel = Horas_extra::findOrFail($horasExtrasId);
                }

                $this->UpdatePagoConExcedente($UpdateModel, $monto_pagado);

                // if ($UpdateVenta) {
                //     $UpdateVenta->pago_con_excedente = $monto_pagado;
                //     $UpdateVenta->update();
                // }
                $this->setTotalAmount($amount - $surplusSaved->excedente);
            }
            // If the amount is less than the surplus saved
            elseif ($amount < $surplusSaved->excedente) {
                $DetallePagoOficina = new DetallePagoOficina([
                    'tipo_pago' => 'Efectivo',
                    'num_transaccion' => $serie_comprobante,
                    'deuda' => $surplusSaved->excedente,
                    'saldo_pagado' => $amount,
                    'fecha_pago' => Carbon::now(),
                    'persona_id' => $costumer_id,
                    'caja_id' => $caja_id,
                    'user_id' => auth()->id(),
                ]);
                $DetallePagoOficina->save();
                $historialExcedentes = HistorialExcedente::where('persona_id', $costumer_id)
                    ->where('tipo_registro', 'Pago_por_oficina')
                    ->where('status', 'Pendiente')
                    ->get();

                    // dd($historialExcedentes->count());

                    $saldo_anterior = 0;
                if ($historialExcedentes->count()) {
                    $saldo_disponible = 0;
                    $motivo = '';
                    $banco_id = '';
                    $servicio_id = '';
                    foreach ($historialExcedentes as $historialExcedente) {
                        $saldo_disponible = $historialExcedente->saldo_disponible;
                        $motivo = $historialExcedente->motivo;
                        $banco_id = $historialExcedente->banco_id;
                        $servicio_id = $historialExcedente->servicio_id;
                        $HistorialExcedente = HistorialExcedente::findOrFail($historialExcedente->id);
                        $HistorialExcedente->status = 'Pagado';
                        $HistorialExcedente->detalle_pago_oficina_id = $DetallePagoOficina->id;
                        $HistorialExcedente->update();
                    }

                    if ($saldo_disponible > 0) {
                        $saldo_anterior = $saldo_disponible;
                        $saldo_disponible = $saldo_disponible - $amount;
                    }
                    // dd($saldo_disponible);
                    $HistorialExcedente = new HistorialExcedente([
                        'tipo_registro' => 'Pago_por_oficina',
                        'status' => 'Pendiente',
                        'tipo_operacion' => 'Egreso',
                        'modo_pago' => 'Por caja',
                        'num_servicio' => $servicio_id,
                        'motivo' => $motivo,
                        'saldo_anterior' => $saldo_anterior,
                        'saldo_operacion' => $amount,
                        'saldo_disponible' => $saldo_disponible,
                        'operador' => auth()->user()->name,
                        'banco_id' => $banco_id,
                        'detalle_pago_oficina_id' => $DetallePagoOficina->id,
                        'persona_id' => $costumer_id,
                        'servicio_id' => $servicio_id,
                        'caja_id' => $caja_id,
                        'user_id' => auth()->id(),
                    ]);
                    $HistorialExcedente->save();
                }

                $UpdateExcedente = Excedente::where('persona_id', $costumer_id)->first();
                $UpdateExcedente->excedente -= $amount;
                $UpdateExcedente->update();

                if ($tipo == "Consumo") {
                    // Update Ventas table
                    $UpdateModel = Venta::findOrFail($sale_id);
                }

                if ($tipo == "Servicio") {
                    // Update Ventas table
                    $servicio_id = Servicio::latest('created_at')->first();
                    // dd('$tipo == "Servicio"', $servicio_id);
                    $UpdateModel = Servicio::findOrFail($servicio_id->id);
                }

                if ($tipo == "Horas_Extras") {
                    // Update Ventas table
                    $UpdateModel = Horas_extra::findOrFail($horasExtrasId);
                }

                $this->UpdatePagoConExcedente($UpdateModel, $amount);

                // if ($UpdateVenta) {
                //     $UpdateVenta->pago_con_excedente = $amount;
                //     $UpdateVenta->update();
                // }

                $this->setTotalAmount();
            }
        }
        // If there is no surplus saved for the customer
        else {
            $this->setTotalAmount($amount);
        }
    }

    public function UpdatePagoConExcedente(object $object, float $amount)
    {
        // dd('UpdatePagoConExcedente()', $amount);
        if ($object) {
            $object->pago_con_excedente = $amount;
            $object->update();
        }
    }


    /**
     * Get the customer's surplus with the specified ID.
     *
     * @param int $costumer_id The customer's ID.
     * @return object The excess amount for the customer, or an empty object if no records are found.
     * @throws \Exception If the customer is not found.
     *
     * @author Jhonny Pirela (Jhosagid)
     * @date 2023-03-08
     */

    public function getExcedentByCostumerId(int $costumer_id = 0): ?object
    {
        try {
            $costumer = Persona::find($costumer_id);
            if ($costumer) {
                return $costumer->excedente;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            // Manejo del error
            return null;
        }
    }


    function splitPayment($amountToPay, $amountSaved, $amountInBox, $amountInCash)
    {
        $amountFromSaved = $amountToPay - $amountInBox - $amountInCash;
        $amountFromBox = $amountInBox - $amountFromSaved;
        $amountInCash = $amountInCash;

        return array(
            "amountFromSaved" => $amountFromSaved,
            "amountFromBox" => $amountFromBox,
            "amountInCash" => $amountInCash
        );
    }

    function getTotalAmountInCash($amountToPay, $amountSaved, $amountInBox)
    {
        $amountInCash = $amountToPay - $amountSaved - $amountInBox;
        return $amountInCash;
    }
}
