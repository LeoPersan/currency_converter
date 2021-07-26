<?php

namespace App\Quotations;

use App\Exceptions\FileNotFoundException;
use TypeError;

class JsonFile implements QuotationInterface
{
    protected $quotations;
    protected $currencies;

    public function loadQuotations($quotations)
    {
        if (!is_string($quotations))
            throw new TypeError("Argument \$quotations in ".__CLASS__."::".__METHOD__."() must be of the type string, ".gettype($quotations)." given");

        if (!is_file($quotations))
            throw new FileNotFoundException($quotations);

        $this->quotations = $this->parseQuotations($quotations);
        $this->currencies = [];
        foreach ($this->quotations as &$quotation) {
            $quotation = $this->parseQuotation($quotation);
            $this->currencies[] = $quotation->from;
            $this->currencies[] = $quotation->to;
        }
        $this->currencies = array_unique($this->currencies);
        return $this;
    }

    protected function parseQuotations($quotations)
    {
        return json_decode(file_get_contents($quotations));
    }

    protected function parseQuotation($quotation)
    {
        return $quotation;
    }

    public function getQuotations()
    {
        return $this->quotations;
    }

    public function getValidCurrencies()
    {
        return $this->currencies;
    }
}