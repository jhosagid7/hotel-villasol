<?php

namespace App\Traits;

trait SaleTrait
{
    /**
     * Set the amount of the transaction.
     *
     * @param float $amount The amount of the transaction. Default is 0.
     * @return void
     */
    public function setTotalAmount(float $amount = 0): void
    {
        $this->amount = number_format($amount, 9);
    }

    /**
     * Get the amount formatted with 9 decimal places.
     *
     * @return float The formatted amount.
     */
    public function getTotalAmount(): float
    {
        return number_format($this->amount, 9);
    }

    /**
     * Set the cash amount.
     *
     * @param float $cash The cash amount to set. Default is 0.
     * @return void
     */
    public function setCashPayment(float $cash = 0): void
    {
        $this->amount = number_format($cash, 9);
    }

    /**
     * Get the cash amount.
     *
     * @return float The cash amount, formatted with 9 decimal places.
     */
    public function getCashPayment(): float
    {
        return number_format($this->cash, 9);
    }
}
