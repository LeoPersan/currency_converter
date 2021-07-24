<?php

namespace App\Amounts;

use InvalidArgumentException;

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
        if (!preg_match('/^[A-Z]{3}$/i', $currency))
            throw new InvalidArgumentException("\"$currency\" is a invalid currency");
        $this->currency = strtoupper($currency);
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
