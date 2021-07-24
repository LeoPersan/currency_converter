<?php

namespace App\Converters;

use App\Quotations\QuotationInterface;
use InvalidArgumentException;

class Converter implements ConverterInterface
{
    private $quotation_parser;
    private $skip = [];

    public function loadQuotations(QuotationInterface $quotation_parser)
    {
        $this->quotation_parser = $quotation_parser;
    }

    public function run(string $from, string $to, float $amount)
    {
        foreach ($this->quotation_parser->getQuotations() as $key => $quotation) {
            if (in_array($key, $this->skip))
                continue;
            $this->skip[] = $key;
            if ($quotation->from == $from) {
                $new_amount = $amount * $quotation->quotation;
                if ($quotation->to == $to)
                    return $new_amount;
                if (($new_amount = $this->run($quotation->to, $to, $new_amount)) !== false)
                    return $new_amount;
            } else if ($quotation->to == $from) {
                $new_amount = $amount * (1 / $quotation->quotation);
                if ($quotation->from == $to)
                    return $new_amount;
                if (($new_amount = $this->run($quotation->from, $to, $new_amount)) !== false)
                    return $new_amount;
            }
            $this->skip = array_diff($this->skip, [$key]);
        }

        return false;
    }
}
