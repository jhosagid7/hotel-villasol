<?php

namespace App\Traits;


use App\Servicio;
use App\Pago_Venta;
use App\Pago_Servicio;
use App\Pago_Extra;
use App\Pago_Vuelto;
use App\Traits\SaleTrait;
use App\Traits\ChangeInBoxTrait;
use App\Excedentes_Recibidos_Caja_Actual;

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

    public function payWithCash2($amount = 0, $service_id,$request, $tipo, $sale_id = 0, $horas_extra_id = 0)
    {
        if($amount > 0){
            $array = $this->convertirArrays(
                $request->get('divisa'),
                $request->get('MontoDivisa'),
                $request->get('TasaTike'),
                $request->get('MontoDolar'),
                $request->get('Veltos')
            );
            // dd($array);
            $this->subtractCachPaymment($array, $this->getTotalAmount(), $request, $tipo, $service_id, $sale_id, $horas_extra_id);


        //* This function pays with a change (Esta función paga con un vuelto)
        // $this->setTotalAmount($amount - $cash);
        }

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

    public function savePaymment($divisa, $montoDivisa, $montoDolar, $montoDolarTipo, $getTotalExcedente, $getTotalVueltos, $service_id, $caja_id, $tipo, $sale_id = 0, $horas_extra_id = 0)
    {

        if ($tipo == 'Consumo') {
            $pagosVenta = new Pago_Venta();
            $pagosVenta->Divisa = $divisa;
            $pagosVenta->MontoDivisa = $montoDivisa;
            $pagosVenta->TasaTiket = $this->getRateByName($divisa);
            $pagosVenta->MontoDolar = number_format($montoDolar, 2);
            $pagosVenta->MontoDolarConsumo = number_format($montoDolarTipo, 2);
            $pagosVenta->Excedente = $getTotalExcedente;
            $pagosVenta->Vueltos = $getTotalVueltos;
            $pagosVenta->servicio_id = $service_id;
            $pagosVenta->caja_id = $caja_id;
            $pagosVenta->venta_id = $sale_id;

            // Guardar el registro en la base de datos
            $pagosVenta->save();
        }

        if ($tipo == 'Servicio') {
            $pagosServicio = new Pago_Servicio();
            $pagosServicio->Divisa = $divisa;
            $pagosServicio->MontoDivisa = $montoDivisa;
            $pagosServicio->TasaTiket = $this->getRateByName($divisa);
            $pagosServicio->MontoDolar = number_format($montoDolar, 2);
            $pagosServicio->MontoDolarServicio = number_format($montoDolarTipo, 2);
            $pagosServicio->Excedente = $getTotalExcedente;
            $pagosServicio->Vueltos = $getTotalVueltos;
            $pagosServicio->servicio_id = $service_id;
            $pagosServicio->caja_id = $caja_id;

            // Guardar el registro en la base de datos
            $pagosServicio->save();
        }

        if ($tipo == 'Horas_Extras') {
            $pagosHorasExtra = new Pago_Extra();
            $pagosHorasExtra->Divisa = $divisa;
            $pagosHorasExtra->MontoDivisa = $montoDivisa;
            $pagosHorasExtra->TasaTiket = $this->getRateByName($divisa);
            $pagosHorasExtra->MontoDolar = number_format($montoDolar, 2);
            $pagosHorasExtra->MontoDolarHoraExctra = number_format($montoDolarTipo, 2);
            $pagosHorasExtra->Excedente = $getTotalExcedente;
            $pagosHorasExtra->Vueltos = $getTotalVueltos;
            $pagosHorasExtra->horas_extra_id = $horas_extra_id;
            $pagosHorasExtra->servicio_id = $service_id;
            $pagosHorasExtra->caja_id = $caja_id;

            // Guardar el registro en la base de datos
            $pagosHorasExtra->save();
        }
    }
    public function setTotalVueltos($quantity, $amount, $isVueltos, $lastIteration = null, $iteration = null)
    {

        if ($iteration == $lastIteration) {
            $this->hasVueltos = $isVueltos;
        } else {
            $this->hasVueltos = 0;
        }
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

    public function saveVueltos($tipo, $isVueltos, $request, $service_id, $caja_id, $sale_id = 0, $horas_extra_id = 0, $detalle__creditos__pagado_id = 0)
    {

        if ($isVueltos > 0) {
            $MontoDivisaVueltos = $request->get('MontoDivisaV');
            $divisaVueltos = $request->get('divisaV');
            $TasaTikeVueltos = $request->get('TasaTikeV');
            $MontoDolarVueltos = $request->get('MontoDolarV');
            $MontoDivisaVueltos = array_filter($MontoDivisaVueltos);

            foreach ($MontoDivisaVueltos as $key => $val) {
                $Pago_Extras_Vueltos = new Pago_Vuelto();
                $Pago_Extras_Vueltos->Tipo = $tipo;
                $Pago_Extras_Vueltos->tipo_vuelto = 'Vueltos_Pago';
                $Pago_Extras_Vueltos->Divisa = $divisaVueltos[$key];
                $Pago_Extras_Vueltos->MontoDivisa = $MontoDivisaVueltos[$key];
                $Pago_Extras_Vueltos->TasaTiket = $TasaTikeVueltos[$key];
                $Pago_Extras_Vueltos->MontoDolar = floatval($MontoDolarVueltos[$key]);
                $Pago_Extras_Vueltos->servicio_id = $service_id;
                $Pago_Extras_Vueltos->venta_id = $sale_id;
                $Pago_Extras_Vueltos->horas_extra_id = $horas_extra_id;
                $Pago_Extras_Vueltos->detalle__creditos__pagado_id = $detalle__creditos__pagado_id;
                $Pago_Extras_Vueltos->caja_id = $caja_id;
                $Pago_Extras_Vueltos->save();
            }
        }
    }

    public function saveExcedente($tipo, $divisa, $excedentes, $service_id, $caja_id, $sale_id = 0, $horas_extra_id = 0)
    {
        if ($excedentes > 0) {
            $excdtsRecibidosCaja = new Excedentes_Recibidos_Caja_Actual();
            $excdtsRecibidosCaja->Tipo = $tipo;
            $excdtsRecibidosCaja->Estado = 'Pendiente';
            $excdtsRecibidosCaja->Divisa = $divisa;
            $excdtsRecibidosCaja->MontoDivisa = floatval($excedentes * $this->getRateByName($divisa));
            $excdtsRecibidosCaja->TasaTiket = $this->getRateByName($divisa);
            $excdtsRecibidosCaja->MontoDolar = $excedentes;
            $excdtsRecibidosCaja->servicio_id = $service_id;
            $excdtsRecibidosCaja->venta_id = $sale_id;
            $excdtsRecibidosCaja->horas_extra_id = $horas_extra_id;
            $excdtsRecibidosCaja->caja_id = $caja_id;
            $excdtsRecibidosCaja->save();

        }
    }

    public function subtractCachPaymment(array $coins, float $amount, $request, $tipo, int $service_id, int $sale_id = 0, int $horas_extra_id = 0)
    {
        $coins = $this->sortCoinsByAmount($coins, TRUE, 'ASC');

        $lastIteration = count($coins) - 1;
        $iteration = 0;

        $isVueltos = $request->get('isVueltos');
        $this->saveVueltos($tipo, $isVueltos, $request, $service_id, $request->get('caja_id'), $sale_id, $horas_extra_id);

        foreach ($coins as $coin) {

            $currency = $coin['Divisa'];
            $quantity = $coin['MontoDolar'];
            $quantityDivisa = $coin['MontoDivisa'];

            switch ($currency) {

                case "Bolivar":
                    if ($quantity >= $amount) {
                        $this->setTotalVueltos($quantity, $amount, $isVueltos, $lastIteration,  $iteration);
                        $this->setTotalExcedente($quantity, $amount);
                        $this->saveExcedente($tipo, $currency, $this->getTotalExcedente(), $service_id, $request->get('caja_id'), $sale_id, $horas_extra_id);

                        $this->savePaymment(
                            $currency,
                            $quantityDivisa,
                            $quantity,
                            $amount,
                            $this->getTotalExcedente(),
                            $this->getTotalVueltos(),
                            $service_id,
                            $request->get('caja_id'),
                            $tipo,
                            $sale_id,
                            $horas_extra_id

                        );

                        $amount = 0;
                    } else {

                        $this->savePaymment(
                            $currency,
                            $quantityDivisa,
                            $quantity,
                            $quantity,
                            $this->getTotalExcedente(),
                            $this->getTotalVueltos(),
                            $service_id,
                            $request->get('caja_id'),
                            $tipo,
                            $sale_id,
                            $horas_extra_id

                        );

                        $amount -= $quantity;
                    }
                    break;

                case "Peso":
                    if ($quantity >= $amount) {
                        $this->setTotalVueltos($quantity, $amount, $isVueltos, $lastIteration,  $iteration);
                        $this->setTotalExcedente($quantity, $amount);
                        $this->saveExcedente($tipo, $currency, $this->getTotalExcedente(), $service_id, $request->get('caja_id'), $sale_id, $horas_extra_id);

                        $this->savePaymment(
                            $currency,
                            $quantityDivisa,
                            $quantity,
                            $amount,
                            $this->getTotalExcedente(),
                            $this->getTotalVueltos(),
                            $service_id,
                            $request->get('caja_id'),
                            $tipo,
                            $sale_id,
                            $horas_extra_id

                        );

                        $amount = 0;
                    } else {

                        $this->savePaymment(
                            $currency,
                            $quantityDivisa,
                            $quantity,
                            $quantity,
                            $this->getTotalExcedente(),
                            $this->getTotalVueltos(),
                            $service_id,
                            $request->get('caja_id'),
                            $tipo,
                            $sale_id,
                            $horas_extra_id

                        );

                        $amount -= $quantity;
                    }
                    break;

                case "Dolar":
                    if ($quantity >= $amount) {
                        $this->setTotalVueltos($quantity, $amount, $isVueltos, $lastIteration,  $iteration);
                        $this->setTotalExcedente($quantity, $amount);
                        $this->saveExcedente($tipo, $currency, $this->getTotalExcedente(), $service_id, $request->get('caja_id'), $sale_id, $horas_extra_id);

                        $this->savePaymment(
                            $currency,
                            $quantityDivisa,
                            $quantity,
                            $amount,
                            $this->getTotalExcedente(),
                            $this->getTotalVueltos(),
                            $service_id,
                            $request->get('caja_id'),
                            $tipo,
                            $sale_id,
                            $horas_extra_id

                        );

                        $amount = 0;
                    } else {

                        $this->savePaymment(
                            $currency,
                            $quantityDivisa,
                            $quantity,
                            $quantity,
                            $this->getTotalExcedente(),
                            $this->getTotalVueltos(),
                            $service_id,
                            $request->get('caja_id'),
                            $tipo,
                            $sale_id,
                            $horas_extra_id

                        );

                        $amount -= $quantity;
                    }
                    break;

                case "Transferencia":
                    if ($quantity >= $amount) {
                        $this->setTotalVueltos($quantity, $amount, $isVueltos, $lastIteration,  $iteration);
                        $this->setTotalExcedente($quantity, $amount);
                        $this->saveExcedente($tipo, $currency, $this->getTotalExcedente(), $service_id, $request->get('caja_id'), $sale_id, $horas_extra_id);

                        $this->savePaymment(
                            $currency,
                            $quantityDivisa,
                            $quantity,
                            $amount,
                            $this->getTotalExcedente(),
                            $this->getTotalVueltos(),
                            $service_id,
                            $request->get('caja_id'),
                            $tipo,
                            $sale_id,
                            $horas_extra_id

                        );

                        $amount = 0;
                    } else {

                        $this->savePaymment(
                            $currency,
                            $quantityDivisa,
                            $quantity,
                            $quantity,
                            $this->getTotalExcedente(),
                            $this->getTotalVueltos(),
                            $service_id,
                            $request->get('caja_id'),
                            $tipo,
                            $sale_id,
                            $horas_extra_id

                        );

                        $amount -= $quantity;
                    }
                    break;

                case "Punto":
                    if ($quantity >= $amount) {
                        $this->setTotalVueltos($quantity, $amount, $isVueltos, $lastIteration,  $iteration);
                        $this->setTotalExcedente($quantity, $amount);
                        $this->saveExcedente($tipo, $currency, $this->getTotalExcedente(), $service_id, $request->get('caja_id'), $sale_id, $horas_extra_id);

                        $this->savePaymment(
                            $currency,
                            $quantityDivisa,
                            $quantity,
                            $amount,
                            $this->getTotalExcedente(),
                            $this->getTotalVueltos(),
                            $service_id,
                            $request->get('caja_id'),
                            $tipo,
                            $sale_id,
                            $horas_extra_id

                        );

                        $amount = 0;
                    } else {

                        $this->savePaymment(
                            $currency,
                            $quantityDivisa,
                            $quantity,
                            $quantity,
                            $this->getTotalExcedente(),
                            $this->getTotalVueltos(),
                            $service_id,
                            $request->get('caja_id'),
                            $tipo,
                            $sale_id,
                            $horas_extra_id

                        );

                        $amount -= $quantity;
                    }
                    break;
            }
            $iteration++;
        }
        // If there is remaining amount, return an empty array
        $this->setTotalAmount($amount);
    }
}
