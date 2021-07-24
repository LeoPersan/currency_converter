<?php

namespace App\Converters;

use App\Quotations\QuotationInterface;

interface ConverterInterface
{
    public function loadQuotations(QuotationInterface $quotation_parser);
    public function run(string $from, string $to, float $amount);
}