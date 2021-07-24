<?php

namespace App\Amounts;

interface AmountInterface
{
    public function setAmount(float $value);

    public function setCurrency(string $currency);

    public function getAmount();

    public function getFormatedAmount();

    public function __toString();
}
