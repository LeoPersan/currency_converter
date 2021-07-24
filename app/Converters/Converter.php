<?php

namespace App\Converters;

use App\Quotations\QuotationInterface;
use InvalidArgumentException;

class Converter implements ConverterInterface
{
    private $quotation_parser;
    private $multipliers;

    public function loadQuotations(QuotationInterface $quotation_parser)
    {
        $this->quotation_parser = $quotation_parser;
        foreach ($this->quotation_parser->getQuotations() as $quotation) {
            $this->multipliers[$quotation->from . '_' . $quotation->to] = $quotation->quotation;
            $this->multipliers[$quotation->to . '_' . $quotation->from] = 1 / $quotation->quotation;
        }
    }

    public function run(string $from, string $to, float $amount)
    {
        if (!isset($this->multipliers[$from . '_' . $to]))
            throw new InvalidArgumentException("Conversion \"$from\" => \"$to\" cannot be done");
        return $amount * $this->multipliers[$from . '_' . $to];
    }
}
