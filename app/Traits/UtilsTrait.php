<?php

namespace App\Traits;

use App\Tasa;

trait UtilsTrait
{
    public function amountGreaterThanPay($pay = 0, $amount = 0)
    {
        return $amount > $pay;
    }

    /**
     * Get the rate by name from the Tasa table.
     *
     * @param string $name The name of the rate.
     * @return float|null The rate value if found, otherwise null.
     */
    public function getRateByName($name)
    {
        if($name == 'Transferencia' || $name == 'Punto'){
            $name = 'Transferencia_Punto';
        }
        if($name == 'Bolivar'){
            $name = 'Efectivo';
        }
        $record = Tasa::where('nombre', $name)->first();
        if ($record) {

            return $record->tasa;
        }
        return null;
    }
}
