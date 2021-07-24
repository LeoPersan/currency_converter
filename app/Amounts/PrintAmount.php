<?php

namespace App\Amounts;

class PrintAmount implements AmountInterface
{
    private $amount;
    private $currency;

    public function setAmount(float $amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
        return $this;
    }

    public function getAmount()
    {
        $precision = 2;
        while (number_format($this->amount, $precision) == 0 && $precision < 10)
            $precision++;
        return number_format($this->amount, $precision);
    }

    public function getFormatedAmount()
    {
        return $this->getAmount() . ' ' . $this->currency;
    }

    public function __toString()
    {
        return $this->getFormatedAmount();
    }
}
