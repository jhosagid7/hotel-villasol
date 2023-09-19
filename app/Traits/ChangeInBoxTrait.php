<?php

namespace App\Traits;

use App\Caja;
use App\Servicio;
use App\Pago_Vuelto;
use App\Pago_Venta;
use App\Pago_Extra;
use App\Pago_Servicio;
use App\Temp_Pago_Vuelto;
use Illuminate\Http\Request;
use App\Excedentes_Recibidos_Caja_Actual;

use App\Traits\SaleTrait;
use App\Traits\UtilsTrait;


trait ChangeInBoxTrait
{

    public $amount;

    use UtilsTrait;
    use SaleTrait;

    public function payWithChangeInBox(int $service_id, float $amount = 0,$box_id, $tipo,  int $sale_id = 0, int $horasExtrasId = 0)
    {

        //* This function pays with a change (Esta función paga con un vuelto)
        if ($service_id) {
            $array = $this->getTotalUndeliveredChangeByCurrencyTypeForServiceId3($service_id);

            $this->subtractMoney($array, $amount, $service_id, $box_id, $tipo, $sale_id, $horasExtrasId);
        }

    }


    /**
     * Function that retrieves the total undelivered amount by currency type for a given service ID.
     *
     * @param int $service_id - The service ID.
     * @return array - Array containing the total undelivered amount by currency type.
     */
    public function getTotalUndeliveredChangeByCurrencyTypeForServiceId(int $service_id): array
    {
        $service = Servicio::findOrFail($service_id);
        $currency = $service->undeliveredChanges->groupBy('Divisa');
        $TotalUndeliveredAmountByCurrencyType = [];

        foreach ($currency as $kindCurrency => $undeliveredAmount) {
            $TotalUndeliveredAmountByCurrencyType[$kindCurrency] = $undeliveredAmount->sum('MontoDivisa');
        }

        return $TotalUndeliveredAmountByCurrencyType;
    }


    /**
     * Get the total undelivered change in dollars for a given service ID.
     *
     * @param int $service_id The ID of the service.
     * @return array The total undelivered amount by currency type.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getTotalUndeliveredChangeInDollarsForServiceId($service_id): array
    {
        $service = Servicio::findOrFail($service_id);
        $currency = $service->undeliveredChanges->groupBy('Divisa');
        $TotalUndeliveredAmountByCurrencyType = [];

        foreach ($currency as $kindCurrency => $undeliveredAmount) {
            $TotalUndeliveredAmountByCurrencyType[$kindCurrency] = $undeliveredAmount->sum('MontoDolar');
        }

        return $TotalUndeliveredAmountByCurrencyType;
    }


    /**
     * This function calculates the total undelivered change by currency type.
     * It retrieves all services and groups the undelivered changes by currency.
     * Then, it sums the undelivered amounts by currency and stores them in an array.
     * Finally, it returns an array with the total undelivered change by currency type.
     *
     * @param  int  $box_id
     * @return array Total undelivered amount by currency type.
     */
    public function getTotalUndeliveredChangeByCurrencyType(int $box_id): array
    {
        $services = Servicio::where('caja_id', $box_id)->get();
        $totalUndeliveredChangeByCurrencyType = [];
        foreach ($services as $service) {
            $currency = $service->undeliveredChanges->groupBy('Divisa');
            foreach ($currency as $kindCurrency => $undeliveredAmount) {
                if (!isset($totalUndeliveredAmountByCurrencyType[$kindCurrency])) {
                    $totalUndeliveredChangeByCurrencyType[$kindCurrency] = 0;
                }
                $totalUndeliveredChangeByCurrencyType[$kindCurrency] += $undeliveredAmount->sum('MontoDivisa');
            }
        }
        return $totalUndeliveredChangeByCurrencyType;
    }


    /**
     * This function calculates the total undelivered change in dollars for a given box.
     *
     * @param int $box_id The ID of the box.
     * @return array An array with the total undelivered change in dollars for each currency.
     */
    public function getTotalUndeliveredChangeInDollars(int $box_id): array
    {
        $services = Servicio::where('caja_id', $box_id)->get();
        $totalUndeliveredChangeInDollars = [];
        foreach ($services as $service) {
            $currency = $service->undeliveredChanges->groupBy('Divisa');
            foreach ($currency as $kindCurrency => $undeliveredAmount) {
                if (!isset($totalUndeliveredAmountInDollars[$kindCurrency])) {
                    $totalUndeliveredChangeInDollars[$kindCurrency] = 0;
                }
                $totalUndeliveredChangeInDollars[$kindCurrency] += $undeliveredAmount->sum('MontoDolar');
            }
        }
        return $totalUndeliveredChangeInDollars;
    }


    /**
     * Subtract money from an array of coins in the specified order of priority.
     *
     * @param array $coins The array of coins to subtract from.
     * @param float $amount The amount to subtract.
     * @return array The resulting array after subtracting the amount from coins.
     */
    public function subtractMoney(array $coins, float $amount, int $service_id, int $box_id,string $tipo, int $sale_id = 0,int $horasExtrasId = 0): void
    {
        $coins = $this->sortCoins($coins);
        // dd($coins);
        $result = [];
        // Order of priority: Bolivar, Peso, Dollar
        foreach ($coins as $coin) {
            $currency = $coin['Divisa'];
            $quantity = $coin['MontoDolar'];
            $currency_id = $coin['id'];
            // dd($quantity);
            switch ($currency) {
                case "Bolivar":
                    if ($quantity >= $amount) {
                        $result[] = [
                            'Divisa' => 'Bolivar',
                            'MontoDolar' => $quantity - $amount
                        ];
                        $this->subtractExcess($currency_id, $amount, $currency, $quantity, $box_id, $service_id, $tipo, $sale_id, $horasExtrasId);
                        $amount = 0;
                    } else {
                        $result[] = [
                            'Divisa' => 'Bolivar',
                            'MontoDolar' => 0
                        ];
                        $this->subtractExcess($currency_id, $amount, $currency, $quantity, $box_id, $service_id, $tipo, $sale_id, $horasExtrasId);
                        $amount -= $quantity;
                    }
                    break;
                case "Peso":
                    if ($quantity >= $amount) {
                        $result[] = [
                            'Divisa' => 'Peso',
                            'MontoDolar' => $quantity - $amount
                        ];
                        $this->subtractExcess($currency_id, $amount, $currency, $quantity, $box_id, $service_id, $tipo, $sale_id, $horasExtrasId);
                        $amount = 0;
                    } else {
                        $result[] = [
                            'Divisa' => 'Peso',
                            'MontoDolar' => 0
                        ];
                        $this->subtractExcess($currency_id, $amount, $currency, $quantity, $box_id, $service_id, $tipo, $sale_id, $horasExtrasId);
                        $amount -= $quantity;
                    }
                    break;
                case "Dolar":

                    if ($quantity >= $amount) {
                        $result[] = [
                            'Divisa' => 'Dolar',
                            'MontoDolar' => $quantity - $amount
                        ];
                        $this->subtractExcess($currency_id, $amount, $currency, $quantity, $box_id, $service_id, $tipo, $sale_id, $horasExtrasId);
                        $amount = 0;
                    } else {

                        $result[] = [
                            'Divisa' => 'Dolar',
                            'MontoDolar' => 0
                        ];
                        $this->subtractExcess($currency_id, $amount, $currency, $quantity, $box_id, $service_id, $tipo, $sale_id, $horasExtrasId);
                        $amount -= $quantity;

                    }
                    break;
            }
        }
        // If there is remaining amount, return an empty array
        $this->setTotalAmount($amount);


        // If there is remaining amount, return an empty array
        // if ($amount > 0) {
        //     return [];
        // } else {
        //     return $result;
        // }
    }


    /**
     * Subtract excess amounts.
     *
     * @param int $currencyId
     * @param float $amount
     * @param string $currency
     * @param int $quantity
     * @param int $serviceId
     * @param int $saleId
     * @return void
     */
    public function subtractExcess($currencyId, $amount, $currency, $quantity, $box_id, $serviceId, $tipo, $saleId = 0, $horasExtrasId = 0): void
    {
        // Obtain the excess
        if ($amount >= $quantity) {
            // Update the excedentes_actuals table with returned status
            $excess = Excedentes_Recibidos_Caja_Actual::findOrFail($currencyId);
            // dd($excess);

            if ($excess) {
                $excess->Estado = 'Devueltos';
                $excess->update();
            }

            $paymentChange = new Pago_Vuelto();
            $paymentChange->Tipo = $excess->Tipo;
            $paymentChange->tipo_vuelto = 'Vueltos_Excedentes';
            $paymentChange->Divisa = $excess->Divisa;
            $paymentChange->MontoDivisa = floatval($quantity * $this->getRateByName($currency));
            $paymentChange->TasaTiket = $this->getRateByName($currency);
            $paymentChange->MontoDolar = floatval($quantity);
            $paymentChange->servicio_id = $serviceId;
            $paymentChange->venta_id = $saleId;
            $paymentChange->horas_extra_id = $horasExtrasId;
            $paymentChange->detalle__creditos__pagado_id = 0;
            $paymentChange->caja_id = $box_id;
            $paymentChange->save();

            if($tipo == 'Consumo'){
                $paymentSale = new Pago_Venta();
                $paymentSale->Divisa = $currency;
                $paymentSale->MontoDivisa = floatval($quantity * $this->getRateByName($currency));
                $paymentSale->TasaTiket = $this->getRateByName($currency);
                $paymentSale->MontoDolar = floatval($quantity);
                $paymentSale->MontoDolarConsumo = floatval($quantity);
                $paymentSale->Excedente = 0;
                $paymentSale->Vueltos = 0;
                $paymentSale->servicio_id = $serviceId;
                $paymentSale->caja_id = $box_id;
                $paymentSale->venta_id = $saleId;
                $paymentSale->save();
            }

            if ($tipo == 'Servicio') {
                $paymentSale = new Pago_Servicio();
                $paymentSale->Divisa = $currency;
                $paymentSale->MontoDivisa = floatval($quantity * $this->getRateByName($currency));
                $paymentSale->TasaTiket = $this->getRateByName($currency);
                $paymentSale->MontoDolar = floatval($quantity);
                $paymentSale->MontoDolarServicio = floatval($quantity);
                $paymentSale->Excedente = 0;
                $paymentSale->Vueltos = 0;
                $paymentSale->servicio_id = $serviceId;
                $paymentSale->caja_id = $box_id;
                $paymentSale->save();
            }

            if ($tipo == 'Horas_Extras') {
                $paymentSale = new Pago_Extra();
                $paymentSale->Divisa = $currency;
                $paymentSale->MontoDivisa = floatval($quantity * $this->getRateByName($currency));
                $paymentSale->TasaTiket = $this->getRateByName($currency);
                $paymentSale->MontoDolar = floatval($quantity);
                $paymentSale->MontoDolarHoraExctra = floatval($quantity);
                $paymentSale->Excedente = 0;
                $paymentSale->Vueltos = 0;
                $paymentSale->horas_extra_id = $horasExtrasId;
                $paymentSale->servicio_id = $serviceId;
                $paymentSale->caja_id = $box_id;
                $paymentSale->save();
            }

        } else {
            $excess = Excedentes_Recibidos_Caja_Actual::findOrFail($currencyId);

            // If the excess exists
            if ($excess) {
                // Subtract the amount from the excess
                $excess->MontoDivisa = $excess->MontoDivisa - ($amount * $this->getRateByName($currency));
                $excess->MontoDolar = $excess->MontoDolar - $amount;
                $excess->update();
            }

            // Create a new excess
            $newExcess = new Excedentes_Recibidos_Caja_Actual();
            $newExcess->Tipo = $excess->Tipo;
            $newExcess->Estado = 'Devueltos';
            $newExcess->Divisa = $excess->Divisa;
            $newExcess->MontoDivisa = floatval($amount * $this->getRateByName($currency));
            $newExcess->TasaTiket = $this->getRateByName($currency);
            $newExcess->MontoDolar = floatval($amount);
            $newExcess->servicio_id = $serviceId;
            $newExcess->venta_id = $saleId;
            $newExcess->horas_extra_id = $horasExtrasId;
            $newExcess->caja_id = $box_id;
            $newExcess->save();

            $paymentChange = new Pago_Vuelto();
            $paymentChange->Tipo = $excess->Tipo;
            $paymentChange->tipo_vuelto = 'Vueltos_Excedentes';
            $paymentChange->Divisa = $excess->Divisa;
            $paymentChange->MontoDivisa = floatval($amount * $this->getRateByName($currency));
            $paymentChange->TasaTiket = $this->getRateByName($currency);
            $paymentChange->MontoDolar = floatval($amount);
            $paymentChange->servicio_id = $serviceId;
            $paymentChange->venta_id = $saleId;
            $paymentChange->horas_extra_id = $horasExtrasId;
            $paymentChange->detalle__creditos__pagado_id = 0;
            $paymentChange->caja_id = $box_id;
            $paymentChange->save();

            if ($tipo == 'Consumo') {
                $paymentSale = new Pago_Venta();
                $paymentSale->Divisa = $currency;
                $paymentSale->MontoDivisa = floatval($amount * $this->getRateByName($currency));
                $paymentSale->TasaTiket = $this->getRateByName($currency);
                $paymentSale->MontoDolar = floatval($amount);
                $paymentSale->MontoDolarConsumo = floatval($amount);
                $paymentSale->Excedente = 0;
                $paymentSale->Vueltos = 0;
                $paymentSale->servicio_id = $serviceId;
                $paymentSale->caja_id = $box_id;
                $paymentSale->venta_id = $saleId;
                $paymentSale->save();
            }

            if ($tipo == 'Servicio') {
                $paymentServicio = new Pago_Servicio();
                $paymentServicio->Divisa = $currency;
                $paymentServicio->MontoDivisa = floatval($amount * $this->getRateByName($currency));
                $paymentServicio->TasaTiket = $this->getRateByName($currency);
                $paymentServicio->MontoDolar = floatval($amount);
                $paymentServicio->MontoDolarServicio = floatval($amount);
                $paymentServicio->Excedente = 0;
                $paymentServicio->Vueltos = 0;
                $paymentServicio->servicio_id = $serviceId;
                $paymentServicio->caja_id = $box_id;
                $paymentServicio->save();
            }

            if ($tipo == 'Horas_Extras') {
                $paymentServicio = new Pago_Extra();
                $paymentServicio->Divisa = $currency;
                $paymentServicio->MontoDivisa = floatval($amount * $this->getRateByName($currency));
                $paymentServicio->TasaTiket = $this->getRateByName($currency);
                $paymentServicio->MontoDolar = floatval($amount);
                $paymentServicio->MontoDolarHoraExctra = floatval($amount);
                $paymentServicio->Excedente = 0;
                $paymentServicio->Vueltos = 0;
                $paymentServicio->horas_extra_id = $horasExtrasId;
                $paymentServicio->servicio_id = $serviceId;
                $paymentServicio->caja_id = $box_id;
                $paymentServicio->save();
            }
        }
    }

    public function updateExcedentesRecibidosCajaActual(int $service_id, float $quantity, float $amount): void
    {
        $restarExcedentesPtes = Excedentes_Recibidos_Caja_Actual::findOrFail($service_id);
    }

    public function getTotalUndeliveredChangeByCurrencyTypeForServiceId2($service_id): array
    {
        $service = Servicio::findOrFail($service_id);
        $currency = $service->undeliveredChanges->groupBy('Divisa');
        $TotalUndeliveredAmountByCurrencyType = [];
        foreach ($currency as $kindCurrency => $undeliveredAmount) {
            $undeliveredAmountSum = $undeliveredAmount->sum('MontoDivisa');
            $TotalUndeliveredAmountByCurrencyType[] = [
                $kindCurrency => [
                    'id' => $undeliveredAmount->first()->id,
                    'undeliveredAmount' => $undeliveredAmountSum
                ]
            ];
        }
        return $TotalUndeliveredAmountByCurrencyType;
    }

    public function getTotalUndeliveredChangeInDollarsForServiceId2($service_id): array
    {
        $service = Servicio::findOrFail($service_id);
        $currency = $service->undeliveredChanges->groupBy('Divisa');
        $TotalUndeliveredAmountByCurrencyType = [];
        foreach ($currency as $kindCurrency => $undeliveredAmount) {
            $undeliveredAmountSum = $undeliveredAmount->sum('MontoDolar');
            $TotalUndeliveredAmountByCurrencyType[] = [
                $kindCurrency => [
                    'id' => $undeliveredAmount->first()->id,
                    'undeliveredAmount' => $undeliveredAmountSum
                ]
            ];
        }
        return $TotalUndeliveredAmountByCurrencyType;
    }


    public function getTotalUndeliveredChangeByCurrencyTypeForServiceId3($service_id): array
    {
        $service = Servicio::findOrFail($service_id);
        $undeliveredChanges = $service->undeliveredChanges()->where('Estado', 'Pendiente')->get(['id', 'Divisa', 'MontoDivisa', 'MontoDolar'])->toArray();
        return $undeliveredChanges;
    }

    function sortCoins(array $array): array
    {
        $order = ['Dolar', 'Transferencia', 'Bolivar', 'Peso', 'Punto'];
        $result = [];
        foreach ($order as $currency) {
            foreach ($array as $item) {
                if ($item['Divisa'] === $currency) {
                    $result[] = $item;
                }
            }
        }
        // dd($result);
        return $result;
    }

    function sortCoinsByAmount(array $array, bool $orderByQty = false, string $orderBy = 'ASC'): array
    {
        usort($array, function ($a, $b) use ($orderByQty, $orderBy) {
            if ($orderByQty) {
                if ($orderBy === 'ASC') {
                    return $a['MontoDolar'] - $b['MontoDolar'];
                } else {
                    return $b['MontoDolar'] - $a['MontoDolar'];
                }
            } else {
                return 0;
            }
        });

        return $array;
    }

    public function getTotalUndeliveredChangeInDolar($service_id)
    {
        $total = Excedentes_Recibidos_Caja_Actual::where('servicio_id', $service_id)
            ->where('Estado', 'Pendiente')
            ->sum('MontoDolar');

        return $total;
    }
}
