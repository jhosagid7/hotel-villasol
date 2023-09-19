<?php

namespace App\Traits;

use App\Traits\SaleTrait;
use App\Traits\ChangeInBoxTrait;

trait CashTrait
{
    public $amount, $excedente, $vista = [], $hasVueltos = 0;


    use SaleTrait;
    use ChangeInBoxTrait;

    public function payWithCash(float $cash = 0, float $amount = 0): float
    {
        //* This function pays with a change (Esta función paga con un vuelto)
        return $this->amount = $amount - $cash;
    }

    public function payWithCash2($cash = 0, $amount = 0,  $sale_id, $service_id, $request, $tipo)
    {

        $array = $this->convertirArrays(
            $request->get('divisa'),
            $request->get('MontoDivisa'),
            $request->get('TasaTike'),
            $request->get('MontoDolar'),
            $request->get('Veltos')
        );
        // dd($array);
        $this->subtractCachPaymment($array, $this->getTotalAmount(), $service_id, $sale_id, $request, $tipo);


        //* This function pays with a change (Esta función paga con un vuelto)
        // $this->setTotalAmount($amount - $cash);
    }

    public function convertirArrays($divisa, $montoDivisa, $tasaTike, $montoDolar, $vueltos): array
    {
        $array = [];
        for ($i = 0; $i < count($divisa); $i++) {
            if ($montoDivisa[$i] > 0) {
                $array[] = [
                    'Divisa' => $divisa[$i],
                    'MontoDivisa' => $montoDivisa[$i],
                    'TasaTiket' => $tasaTike[$i],
                    'MontoDolar' => $montoDolar[$i],
                    'Vueltos' => $vueltos[$i],
                ];
            }
        }
        return $this->sortCoins($array);
    }

    public function savePaymment($result, $paymmentQuantity, $amount, $currency, $quantity, $service_id, $sale_id)
    {
        //    return $this->getTotalAmount();
        // dd($result);
        $vista = [];
        $vista[] = $result;
        // dd($vista);
    }
    public function setTotalVueltos($quantity, $amount, $isVueltos, $lastIteration = null, $iteration = null)
    {

        if ($iteration == $lastIteration) {
            $this->hasVueltos = $isVueltos;
        } else {
            $this->hasVueltos = 0;
        }


        // if ($quantity > $amount) {
        //     if ($isVueltos > 0) {
        //         $this->hasVueltos = $isVueltos;
        //     } else {
        //         $this->hasVueltos = 0;
        //     }
        // } elseif ($quantity < $amount) {
        //     $this->hasVueltos = 0;
        // } else {
        //     $this->hasVueltos = 0;
        // }

    }
    public function getTotalVueltos()
    {
        return floatval(number_format($this->hasVueltos, 2));
    }

    public function setTotalExcedente($quantity, $amount)
    {
        if ($this->getTotalVueltos() > 0) {
            $quantity -= $this->getTotalVueltos();
        }
        $value = $quantity - $amount;

        if ($value > 0) {
            $this->excedente = floatval(number_format($value, 2));

            if ($quantity > $amount) {
                $this->excedente = $this->getTotalExcedente();
            } else {
                $this->excedente = 0;
            }
        } else {
            $this->excedente = 0;
        }
    }
    public function getTotalExcedente()
    {
        return floatval(number_format($this->excedente, 2));
    }
    public function getVista()
    {
        return $this->vista;
    }

    public function procesarExcedente($excedente, $isVueltos = 0)
    {
        $result[] = [
            'Tipo' => 'Consumo',
            'Estado' => 'Pendiente',
            'Tipo_vuelto' => 'Consumo',
            'Divisa' => '',
            'MontoDivisa' => '',
            'TasaTiket' => '',
            'MontoDolar' => '',
            'servicio_id' => '1',
            'venta_id' => '1',
            'horas_extra_id' => '1',
            'detalle__crditos__pagado_id' => '',
        ];
        $this->vista = $result;
        if (!empty($isVueltos)) {
            //guardamos el vuelto
            $vuelto = $isVueltos;
        }

        return $this->excedente += number_format($this->excedente, 2);

        return abs($excedente);
    }

    public function subtractCachPaymment(array $coins, float $amount, int $service_id, int $sale_id, $request, $tipo)
    {
        $coins = $this->sortCoinsByAmount($coins, TRUE, 'ASC');

        $result = [];

        $lastIteration = count($coins) - 1;
        $iteration = 0;
        foreach ($coins as $coin) {

            $currency = $coin['Divisa'];
            $quantity = $coin['MontoDolar'];
            $quantityDivisa = $coin['MontoDivisa'];
            $isVueltos = $request->get('isVueltos');
            switch ($currency) {
                case "Bolivar":
                    if ($quantity >= $amount) {
                        $this->setTotalVueltos($quantity, $amount, $isVueltos, $lastIteration,  $iteration);
                        $this->setTotalExcedente($quantity, $amount);

                        $result[] = [
                            'Divisa' => $currency,
                            'MontoDivisa' => $quantityDivisa,
                            'TasaTiket' => floatval(number_format($quantity - $amount, 2)),
                            'MontoDolar' => floatval(number_format($quantity, 2)),
                            'MontoDolarConsumo' => floatval(number_format($amount, 2)),
                            'Excedente' => $this->getTotalExcedente(),
                            'Vueltos' => $this->getTotalVueltos(),
                            'servicio_id' => $service_id,
                            'caja_id' => $request->get('caja_id'),
                            'Tipo' => $tipo,
                            'venta_id' => $sale_id,
                            'Estado' => 'Pendiente',
                            'horas_extra_id' => '1'
                        ];
                        $paymmentQuantity = $amount;

                        $this->vista = $result;
                        $this->savePaymment($result, $paymmentQuantity, $amount, $currency, $quantity, $service_id, $sale_id);
                        $amount = 0;
                    } else {
                        $result[] = [
                            'Divisa' => $currency,
                            'MontoDivisa' => $quantityDivisa,
                            'TasaTiket' => floatval(number_format($amount - $quantity, 2)),
                            'MontoDolar' => floatval(number_format($quantity, 2)),
                            'MontoDolarConsumo' => floatval(number_format($quantity, 2)),
                            'Excedente' => $this->getTotalExcedente(),
                            'Vueltos' => $this->getTotalVueltos(),
                            'servicio_id' => $service_id,
                            'caja_id' => $request->get('caja_id'),
                            'venta_id' => $sale_id,
                            'Tipo' => $tipo,
                            'Estado' => 'Pendiente',
                            'horas_extra_id' => '2'
                        ];
                        $paymmentQuantity = $quantity;
                        $this->vista = $result;
                        $this->savePaymment($result, $paymmentQuantity, $amount, $currency, $quantity, $service_id, $sale_id);
                        $amount -= $quantity;
                    }
                    break;
                case "Peso":
                    if ($quantity >= $amount) {
                        $this->setTotalVueltos($quantity, $amount, $isVueltos, $lastIteration, $iteration);
                        $this->setTotalExcedente($quantity, $amount);

                        $result[] = [
                            'Divisa' => $currency,
                            'MontoDivisa' => $quantityDivisa,
                            'TasaTiket' => floatval(number_format($quantity - $amount, 2)),
                            'MontoDolar' => floatval(number_format($quantity, 2)),
                            'MontoDolarConsumo' => floatval(number_format($amount, 2)),
                            'Excedente' => $this->getTotalExcedente(),
                            'Vueltos' => $this->getTotalVueltos(),
                            'servicio_id' => $service_id,
                            'caja_id' => $request->get('caja_id'),
                            'Tipo' => $tipo,
                            'venta_id' => $sale_id,
                            'Estado' => 'Pendiente',
                            'horas_extra_id' => '1'
                        ];
                        $paymmentQuantity = $amount;

                        $this->vista = $result;
                        $this->savePaymment($result, $paymmentQuantity, $amount, $currency, $quantity, $service_id, $sale_id);
                        $amount = 0;
                    } else {
                        $result[] = [
                            'Divisa' => $currency,
                            'MontoDivisa' => $quantityDivisa,
                            'TasaTiket' => floatval(number_format($amount - $quantity, 2)),
                            'MontoDolar' => floatval(number_format($quantity, 2)),
                            'MontoDolarConsumo' => floatval(number_format($quantity, 2)),
                            'Excedente' => $this->getTotalExcedente(),
                            'Vueltos' => $this->getTotalVueltos(),
                            'servicio_id' => $service_id,
                            'caja_id' => $request->get('caja_id'),
                            'venta_id' => $sale_id,
                            'Tipo' => $tipo,
                            'Estado' => 'Pendiente',
                            'horas_extra_id' => '2'
                        ];
                        $paymmentQuantity = $quantity;
                        $this->vista = $result;
                        $this->savePaymment($result, $paymmentQuantity, $amount, $currency, $quantity, $service_id, $sale_id);
                        $amount -= $quantity;
                    }
                    break;
                case "Dolar":

                    if ($quantity >= $amount) {
                        $this->setTotalVueltos($quantity, $amount, $isVueltos, $lastIteration, $iteration);
                        $this->setTotalExcedente($quantity, $amount);

                        $result[] = [
                            'Divisa' => $currency,
                            'MontoDivisa' => $quantityDivisa,
                            'TasaTiket' => floatval(number_format($quantity - $amount, 2)),
                            'MontoDolar' => floatval(number_format($quantity, 2)),
                            'MontoDolarConsumo' => floatval(number_format($amount, 2)),
                            'Excedente' => $this->getTotalExcedente(),
                            'Vueltos' => $this->getTotalVueltos(),
                            'servicio_id' => $service_id,
                            'caja_id' => $request->get('caja_id'),
                            'Tipo' => $tipo,
                            'venta_id' => $sale_id,
                            'Estado' => 'Pendiente',
                            'horas_extra_id' => '1'
                        ];
                        $paymmentQuantity = $amount;

                        $this->vista = $result;
                        $this->savePaymment($result, $paymmentQuantity, $amount, $currency, $quantity, $service_id, $sale_id);
                        $amount = 0;
                    } else {

                        $result[] = [
                            'Divisa' => $currency,
                            'MontoDivisa' => $quantityDivisa,
                            'TasaTiket' => floatval(number_format($amount - $quantity, 2)),
                            'MontoDolar' => floatval(number_format($quantity, 2)),
                            'MontoDolarConsumo' => floatval(number_format($quantity, 2)),
                            'Excedente' => $this->getTotalExcedente(),
                            'Vueltos' => $this->getTotalVueltos(),
                            'servicio_id' => $service_id,
                            'caja_id' => $request->get('caja_id'),
                            'venta_id' => $sale_id,
                            'Tipo' => $tipo,
                            'Estado' => 'Pendiente',
                            'horas_extra_id' => '2'
                        ];
                        $paymmentQuantity = $quantity;
                        $this->vista = $result;
                        $this->savePaymment($result, $paymmentQuantity, $amount, $currency, $quantity, $service_id, $sale_id);
                        $amount -= $quantity;
                    }
                    break;
                case "Transferencia":

                    if ($quantity >= $amount) {
                        $this->setTotalVueltos($quantity, $amount, $isVueltos, $lastIteration, $iteration);
                        $this->setTotalExcedente($quantity, $amount);

                        $result[] = [
                            'Divisa' => $currency,
                            'MontoDivisa' => $quantityDivisa,
                            'TasaTiket' => floatval(number_format($quantity - $amount, 2)),
                            'MontoDolar' => floatval(number_format($quantity, 2)),
                            'MontoDolarConsumo' => floatval(number_format($amount, 2)),
                            'Excedente' => $this->getTotalExcedente(),
                            'Vueltos' => $this->getTotalVueltos(),
                            'servicio_id' => $service_id,
                            'caja_id' => $request->get('caja_id'),
                            'Tipo' => $tipo,
                            'venta_id' => $sale_id,
                            'Estado' => 'Pendiente',
                            'horas_extra_id' => '1'
                        ];
                        $paymmentQuantity = $amount;

                        $this->vista = $result;
                        $this->savePaymment($result, $paymmentQuantity, $amount, $currency, $quantity, $service_id, $sale_id);
                        $amount = 0;
                    } else {

                        $result[] = [
                            'Divisa' => $currency,
                            'MontoDivisa' => $quantityDivisa,
                            'TasaTiket' => floatval(number_format($amount - $quantity, 2)),
                            'MontoDolar' => floatval(number_format($quantity, 2)),
                            'MontoDolarConsumo' => floatval(number_format($quantity, 2)),
                            'Excedente' => $this->getTotalExcedente(),
                            'Vueltos' => $this->getTotalVueltos(),
                            'servicio_id' => $service_id,
                            'caja_id' => $request->get('caja_id'),
                            'venta_id' => $sale_id,
                            'Tipo' => $tipo,
                            'Estado' => 'Pendiente',
                            'horas_extra_id' => '2'
                        ];
                        $paymmentQuantity = $quantity;
                        $this->vista = $result;
                        $this->savePaymment($result, $paymmentQuantity, $amount, $currency, $quantity, $service_id, $sale_id);
                        $amount -= $quantity;
                    }
                    break;
                case "Punto":

                    if ($quantity >= $amount) {
                        $this->setTotalVueltos($quantity, $amount, $isVueltos, $lastIteration, $iteration);
                        $this->setTotalExcedente($quantity, $amount);

                        $result[] = [
                            'Divisa' => $currency,
                            'MontoDivisa' => $quantityDivisa,
                            'TasaTiket' => floatval(number_format($quantity - $amount, 2)),
                            'MontoDolar' => floatval(number_format($quantity, 2)),
                            'MontoDolarConsumo' => floatval(number_format($amount, 2)),
                            'Excedente' => $this->getTotalExcedente(),
                            'Vueltos' => $this->getTotalVueltos(),
                            'servicio_id' => $service_id,
                            'caja_id' => $request->get('caja_id'),
                            'Tipo' => $tipo,
                            'venta_id' => $sale_id,
                            'Estado' => 'Pendiente',
                            'horas_extra_id' => '1'
                        ];
                        $paymmentQuantity = $amount;

                        $this->vista = $result;
                        $this->savePaymment($result, $paymmentQuantity, $amount, $currency, $quantity, $service_id, $sale_id);
                        $amount = 0;
                    } else {

                        $result[] = [
                            'Divisa' => $currency,
                            'MontoDivisa' => $quantityDivisa,
                            'TasaTiket' => floatval(number_format($amount - $quantity, 2)),
                            'MontoDolar' => floatval(number_format($quantity, 2)),
                            'MontoDolarConsumo' => floatval(number_format($quantity, 2)),
                            'Excedente' => $this->getTotalExcedente(),
                            'Vueltos' => $this->getTotalVueltos(),
                            'servicio_id' => $service_id,
                            'caja_id' => $request->get('caja_id'),
                            'venta_id' => $sale_id,
                            'Tipo' => $tipo,
                            'Estado' => 'Pendiente',
                            'horas_extra_id' => '2'
                        ];
                        $paymmentQuantity = $quantity;
                        $this->vista = $result;
                        $this->savePaymment($result, $paymmentQuantity, $amount, $currency, $quantity, $service_id, $sale_id);
                        $amount -= $quantity;
                    }
                    break;
            }
            $iteration++;
        }
        // If there is remaining amount, return an empty array
        $this->setTotalAmount($amount);
        return $result;

        // If there is remaining amount, return an empty array
        if ($amount > 0) {
            return [];
        } else {
            return $result;
        }
    }
}
